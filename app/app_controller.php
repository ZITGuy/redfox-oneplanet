<?php

class AppController extends Controller {

    public $components = array('Auth', 'Session', 'RequestHandler');
    public $helpers = array('ExtForm', 'Html', 'Javascript', 'Session', 'Text');

    /**
     * beforeFilter
     *
     * Application hook which runs prior to each controller action
     *
     * @access public
     */
    public function beforeFilter() {
        //Create a global variable for views to use to send java script snippets to the browser
        $this->set('scripts_for_view', '');
        //Override default fields used by Auth component
        $this->Auth->fields = array('username' => 'username', 'password' => 'password');
        //Set application wide actions which do not require authentication
        $this->Auth->allow(
            'display', 'about', 'logout', 'change_campus', 'push_parent_data_to_portal',
		    'active_containers', 'active_tasks', 'edit_profile', 'print_cards_for_guideline_inner',
			'print_cards_for_numeric_inner', 'run_internal', 'build_attendance', 'view_detail_print'
        );
            //IMPORTANT for CakePHP 1.2 final release change this to $this->Auth->allow(array('display'));
        
        //Set the default redirect for users who logout
        $this->Auth->logoutRedirect = '/';
        //Set the default redirect for users who login
        $this->Auth->loginRedirect = '/';
        //Extend auth component to include authorization via isAuthorized action
        $this->Auth->authorize = 'controller';
        //Restrict access to only users with an active account
        $this->Auth->userScope = array('User.is_active = 1');
        //Pass auth component data over to view files
        $this->layout = 'login_2019';
		
		$health = $this->getSystemSetting('SYSTEM_HEALTH');
        if ($health == 'U') { // unhealthy state
            $this->layout = 'system_error';
			$this->autoRender = false;
			$this->Session->setFlash(__('Error has occurred. Contact System Admin', true), '');
			$this->render('/elements/failure2');
        }
		if ($health == 'B') { // busy state
            $this->layout = 'ajax';
			$this->autoRender = false;
			$this->Session->setFlash(__('System is busy now. Please try again later.', true), '');
			$this->render('/elements/failure2');
        }
        if (!defined('FILES_DIR')) {
            define('FILES_DIR', WWW_ROOT . 'files' . DS);
        }
		
		$this->Session->write('today', $this->getSystemSetting('TODAY'));

        Configure::write('AuthSession', $this->Session->read('Auth'));
        
        $this->set('term_name', $this->getSystemSetting('TERM_NAME'));
		
		$urlParts = $this->params['url'];
		$baseUrl = Configure::read('rf_base_url');
		foreach ($urlParts as $p) {
			$baseUrl .= '/' . $p;
		}
		
		$this->set('base_url', $baseUrl);
        $this->set('concat', '?');
    }

    /**
     * isAuthorized
     *
     * Called by Auth component for establishing whether the current authenticated
     * user has authorization to access the current controller:action
     *
     * @return true if authorised / false if not authorized
     * @access public
     */
    public function isAuthorized()
    {
        return $this->__permitted($this->name, $this->action);
    }

    public function isApplicable($actionName)
    {
        return true;
    }

    /**
     * __permitted
     *
     * Helper function returns true if the currently authenticated user has permission
     * to access the controller:action specified by $controllerName:$actionName
     * @return
     * @param $controllerName Object
     * @param $actionName Object
     */
    public function __permitted2($controllerName, $actionName)
    {
      return true;
    }

    public function __permitted($controllerName, $actionName)
    {
        if ($this->Session->read('Auth.User.username') == 'sa') {
            return true;
        }

        //Ensure checks are all made lower case
        $controllerName = strtolower(Inflector::underscore($controllerName));
        $actionName = strtolower($actionName);
        //...then build permissions array and cache it
        $permissions = array();
        //If permissions have not been cached to session...
        if (!$this->Session->check('Permissions')) {
            $thisGroups = array();
            //everyone gets permission to logout
            $permissions[] = 'users:logout';
            $permissions[] = 'users:welcome';
            $permissions[] = 'users:change_password';
            $permissions[] = 'back_office:*';
            $permissions[] = 'help_items:help_system';
            $permissions[] = 'help_items:help_menu';

            //Import the User Model so we can build up the permission cache
            App::import('Model', 'User');
            App::import('Model', 'Task');
            $thisUser = new User;
            $taskObj = new Task;
            //Now bring in the current users full record along with groups
			$thisUser->recursive = 1;
			$taskObj->recursive = 1;
            $thisUser->unbindModel(array('belongsTo' => array('Person', 'EduCampus')));
            $taskObj->unbindModel(array('hasAndBelongsToMany' => array('EduCalendarEventType', 'Group')));
            
			$thisGroups = $thisUser->find(array('User.id' => $this->Session->read('Auth.User.id')));

            $thisGroups = $thisGroups['Group'];

            foreach ($thisGroups as $thisGroup) {
				
                $thisTasks = $thisUser->Group->find(array('Group.id' => $thisGroup['id']));
                $thisTasks = $thisTasks['Task'];

                foreach ($thisTasks as $thisTask) {
					$task = $taskObj->read(null, $thisTask['id']);

                    foreach ($task['Permission'] as $thisPermission) {
                        $permissions[] = $thisPermission['name'];
                    }
                }
            }
            //write the permissions array to session
            $this->Session->write('Permissions', $permissions);
        } else {
            //...they have been cached already, so retrieve them
            $permissions = $this->Session->read('Permissions');
        }

        //Now iterate through permissions for a positive match
        foreach ($permissions as $permission) {
            $this->log('permission: '. $permission, 'pdebug');

            if ($permission == '*') {
                return true; //Super Admin Bypass Found
            }
            if (stripos($permission, $controllerName . ':*') !== false) {
                //if (strtolower($permission) == $controllerName . ':*') {
                return true; //Controller Wide Bypass Found
            }
            if (stripos($permission, $controllerName . ':' . $actionName) !== false) {
                return true; //Specific permission found
            }
        }
        return false;
    }
	
	public function today()
    {
		return date('Y-m-d', strtotime($this->getSystemSetting('TODAY')));
	}

    // TODO: Keep todays permitted tasks in a session variable and refer to that
    // whenever it is required.
    public function is_permitted_now($task_id)
    {
        $this->loadModel('EduCalendarEvent');
        $this->loadModel('EduCalendarEventType');
        $this->loadModel('Task');
        $this->EduCalendarEvent->recursive = 0;

		$this->Task->unbindModel(array('hasMany' => array('Permission')));
		$this->Task->unbindModel(array('hasAndBelongsToMany' => array('EduCalendarEventType', 'Group')));
		
        $t = $this->Task->read(null, $task_id);
		$today = $this->today();
		
		// check if the system is after EOD run state
		// if so, allow only the EoD Process and SoD Process
		$health = $this->getSystemSetting('SYSTEM_HEALTH');
		if ($health == 'E') {
			if ($t['Task']['when_active'] == 'AEOD' ||
			   $t['Task']['when_active'] == 'AAEOD' ||
			   $t['Task']['when_active'] == 'ALL') {
                return true;
            }
			return false;
		} else {
			$eodRunning = $this->getSystemSetting('EOD_RUNNING');
			if ($t['Task']['when_active'] == 'AEOD') {
				return false;
            }
            if ($eodRunning == 1) {
                if ($t['Task']['when_active'] == 'ALL' || $t['Task']['when_active'] == 'DEOD') {
                    return true;
                }
                return false; // during H 1
            }
		}
		
        if ($t['Task']['always_active']) {
            return true;
        }

        // deny for all if the date is holiday.
        if ($this->isHoliday($today)) {
            return false;
        }

        $this->loadModel('EduQuarter');
        $activeQuarter = $this->EduQuarter->getActiveQuarter();
		
        $conditions = array(
			'EduCalendarEvent.start_date <=' => $today,
			'EduCalendarEvent.end_date >=' => $today,
			'EduCalendarEvent.edu_quarter_id' => $activeQuarter['EduQuarter']['id']);

        $events = $this->EduCalendarEvent->find('all', array('conditions' => $conditions));
        
        $found = false;
		$this->EduCalendarEventType->recursive = 1;
		
        foreach ($events as $event) {
			$et = $this->EduCalendarEventType->read(null, $event['EduCalendarEvent']['edu_calendar_event_type_id']);
            $tasks = $et['Task'];
            
            foreach ($tasks as $task) {
                if ($task['id'] == $task_id){
                    $found = true;
                    break;
                }
            }
            if ($found) {
                break;
            }
        }
        return $found;
    }

    public function isHoliday($date) {
        $this->loadModel('Holiday');
        $holidays = $this->Holiday->find('all', array(
            'conditions' => array('Holiday.from_date <=' => $date, 'Holiday.to_date >=' => $date)));
        return count($holidays);
    }

    public function CleanData($str = '') {
        return str_replace("'", "\\'", $str);
    }
	
	public function clearTextForDB($str = '') {
		return str_replace("'", "&apos;", $str);
	}

    /**
     * Function to get a setting from the RedFox System Settings
     * @param string $setting_key the setting key to be searched
     * @return string the setting value
     */
    public function getSystemSetting($settingKey = '')
    {
        $this->loadModel('Setting');
        $setting = $this->Setting->find('first', array('conditions' =>
            array('Setting.setting_key' => $settingKey,
                'Setting.date_from <' => date('Y-m-d'),
                'Setting.date_to >' => date('Y-m-d')))
        );

        if (!$setting) {
            return false;
        }
        return $setting['Setting']['setting_value'];
    }
	
	/**
     * Function to set a setting to the System Settings
     * @param string $settingKey the setting key to be searched
     * @return string the setting value
     */
    public function setSystemSetting($settingKey = '', $settingValue = '') {
        $this->loadModel('Setting');
        $setting = $this->Setting->find('first', array('conditions' =>
            array('Setting.setting_key' => $settingKey)
        ));

        if (!$setting) {
            return false;
        } else {
            $this->Setting->read(null, $setting['Setting']['id']);
            $this->Setting->set('setting_value', $settingValue);
            $this->Setting->save();
        }
        return true;
    }

    public function getOrdinalForm($number) {
        $ords = array(0 => 'th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        $o = $ords[substr($number, strlen($number) - 1)];
        return $o;
    }

    public function queueSMSMessage($to = '', $msg = '') {
        $textMessage = array('TextMessage' => array(
                'receiver' => $to,
                'message' => $msg,
                'status' => 'N',
                'remark' => '-'
            ));
        $this->loadModel('TextMessage');
        $this->TextMessage->create();
        if ($this->TextMessage->save($textMessage)) {
            return true;
        }
        return false;
    }
}
