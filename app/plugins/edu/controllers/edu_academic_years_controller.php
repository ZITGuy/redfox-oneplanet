<?php

class EduAcademicYearsController extends EduAppController {

    var $name = 'EduAcademicYears';

    function index() {
        $aay = $this->EduAcademicYear->getActiveAcademicYear();
        $enable_copy = 0;
        if($aay === FALSE){
            $enable_copy = 1;
        }
        $this->set('enable_copy', $enable_copy);
    }

    function search() {
        
    }

    function list_data() {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $status_id = (isset($_REQUEST['status_id'])) ? $_REQUEST['status_id'] : '1 and 2';
        $conditions = isset($_REQUEST['conditions']) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");

        if($status_id == '1 and 2') {
            $conditions['EduAcademicYear.status_id'] = array(1, 2);
        } elseif ($status_id > 0) {
            $conditions['EduAcademicYear.status_id'] = $status_id;
        } else {
            unset($conditions['EduAcademicYear.status_id']);
        }
        
        //pr($conditions);

        $this->set('edu_academic_years', $this->EduAcademicYear->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduAcademicYear->find('count', array('conditions' => $conditions)));
    }

    function print_ay_summary() {
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        
        foreach($ay['EduQuarter'] as &$quarter){
            $q = $this->EduAcademicYear->EduQuarter->read(null, $quarter['id']);
            $quarter['EduCalendarEvent'] = $q['EduCalendarEvent'];
        }
        
        $this->loadModel('Edu.EduClass');
        $this->loadModel('Setting');
        $classes = $this->EduClass->find('all');
        
        $ay['EduClass'] = $classes;
        
        $settings = $this->Setting->find('all');

        $this->set('settings', $settings);
        $this->set('ay', $ay);
        
        $this->set('company_url', Configure::read('company_url'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_tin', Configure::read('company_tin'));
        $this->set('company_address', Configure::read('company_address'));
        $this->set('academic_year', $ay['EduAcademicYear']['name']);
        $this->set('payment_mode', $this->getSystemSetting('PAYMENT_SCHEDULE_METHOD'));
        
    }
    
    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu academic year', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduAcademicYear->recursive = 2;
        $this->set('edu_academic_year', $this->EduAcademicYear->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->EduAcademicYear->create();
            $ay = $this->EduAcademicYear->getActiveAcademicYear();
            $this->data['EduAcademicYear']['status_id'] = ($ay === FALSE)? 1: 2;
            $this->data['EduAcademicYear']['user_id'] = $this->Session->read('Auth.User.id');
            $this->autoRender = false;
            if ($this->EduAcademicYear->save($this->data)) {
                $this->Session->setFlash('Academic Year ' . $this->data['EduAcademicYear']['name'] . ' is created and opened successfully.', '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The Academic Year could not be created. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }
    
    function copy($id = null) {
        if (!empty($this->data)) {
            $old_id = $this->data['EduAcademicYear']['oldid'];
            $this->EduAcademicYear->recursive = 3;
            $old_ay = $this->EduAcademicYear->read(null, $old_id);
            
            $new_ay_data = array('EduAcademicYear' => array());
            $new_ay_data['EduAcademicYear']['name'] = $this->data['EduAcademicYear']['name'];
            //$new_ay_data['EduAcademicYear']['start_date'] = date('Y') . '-' . date('m-d', strtotime($old_ay['EduAcademicYear']['start_date']));
            $new_ay_data['EduAcademicYear']['start_date'] = (date('Y', strtotime($old_ay['EduAcademicYear']['start_date'])) + 1) . date('-m-d', strtotime($old_ay['EduAcademicYear']['start_date']));
            
            $ayday1 = new DateTime($old_ay['EduAcademicYear']['start_date']);
            $ayday2 = new DateTime($new_ay_data['EduAcademicYear']['start_date']);
            $aydiff_interval = $ayday1->diff($ayday2);
            $aydiff = $aydiff_interval->days;
			
			$status = 1;
			$ay = $this->EduAcademicYear->getActiveAcademicYear();
			if($ay){
				$status = 2;
			}
			
            $new_ay_data['EduAcademicYear']['end_date'] = date('Y-m-d', strtotime($old_ay['EduAcademicYear']['end_date'] . ' +' . $aydiff . ' days'));
            $new_ay_data['EduAcademicYear']['status_id'] = $status;
            $new_ay_data['EduAcademicYear']['user_id'] = $this->Session->read('Auth.User.id');
            
            $this->EduAcademicYear->create();
            $this->autoRender = false;
            $this->loadModel('Edu.EduCalendarEvent');
            if ($this->EduAcademicYear->save($new_ay_data)) {
                // copy the quarters if user selects the option to create quarters too.
                if(isset($this->data['EduAcademicYear']['copy_quarters'])) {
                    foreach($old_ay['EduQuarter'] as $quarter){
                        $q_data = array('EduQuarter' => $quarter);
                        unset($q_data['EduQuarter']['created']);
                        unset($q_data['EduQuarter']['modified']);
                        unset($q_data['EduQuarter']['id']);
                        
                        $q_data['EduQuarter']['edu_academic_year_id'] = $this->EduAcademicYear->id;
                        $q_data['EduQuarter']['user_id'] = $this->Session->read('Auth.User.id');
                        $q_data['EduQuarter']['status_id'] = 9;
                        $q_data['EduQuarter']['start_date'] = date('Y-m-d', strtotime($quarter['start_date'] . ' +' . $aydiff . ' days'));
                        $q_data['EduQuarter']['end_date'] = date('Y-m-d', strtotime($quarter['end_date'] . ' +' . $aydiff . ' days'));
                        
                        $this->EduAcademicYear->EduQuarter->create();
                        $this->EduAcademicYear->EduQuarter->save($q_data);
                        
                        foreach($quarter['EduCalendarEvent'] as $event) {
                            $e_data = array('EduCalendarEvent' => $event);
                            unset($e_data['EduCalendarEvent']['created']);
                            unset($e_data['EduCalendarEvent']['modified']);
                            unset($e_data['EduCalendarEvent']['id']);
                            
                            $e_data['EduCalendarEvent']['edu_quarter_id'] = $this->EduAcademicYear->EduQuarter->id;
                            $e_data['EduCalendarEvent']['start_date'] = date('Y-m-d', strtotime($event['start_date'] . ' +' . $aydiff . ' days'));
                            $e_data['EduCalendarEvent']['end_date'] = date('Y-m-d', strtotime($event['end_date'] . ' +' . $aydiff . ' days'));
                            
                            $this->EduCalendarEvent->create();
                            $this->EduCalendarEvent->save($e_data);
                        }
                    }
                }
                
                $this->Session->setFlash('Academic Year ' . $this->data['EduAcademicYear']['name'] . ' is created and opened successfully.', '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The Academic Year could not be created. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        
        if($id) {
            $edu_acdemic_year = $this->EduAcademicYear->read(null, $id);
            $this->set('edu_academic_year', $edu_acdemic_year);
        }
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid academic year', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduAcademicYear->save($this->data)) {
                $this->Session->setFlash(__('The academic year has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The academic year could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_academic_year', $this->EduAcademicYear->read(null, $id));
		$quarters =  $this->EduAcademicYear->EduQuarter->find('all', array('conditions' => array('EduQuarter.edu_academic_year_id' => $id), 'order' => 'EduQuarter.start_date'));
        
		$this->set('edu_quarters', $quarters);
    }

}
