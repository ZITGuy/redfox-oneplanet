<?php
class EodProcessesController extends AppController {

	var $name = 'EodProcesses';
	
	function index() {
		$users = $this->EodProcess->User->find('all');
		
		$process_date = $this->today();
		$eods = $this->EodProcess->find('count', array('conditions' => array('EodProcess.process_date' => $process_date)));
		
		$disable_run = 'false';
		if($eods > 0) {
			$disable_run = 'true';
		}
		
		$this->loadModel('EduQuarter');
		$q = $this->EduQuarter->getActiveQuarter();
		$eoq_date = $q['EduQuarter']['end_date'];
		
		$this->set(compact('users', 'disable_run', 'process_date', 'eoq_date'));
	}
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}

	function search() {
	}
	
	function list_data() {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';
        eval("\$conditions = array( " . $conditions . " );");
		
		$this->set('eod_processes', $this->EodProcess->find('all', array(
			'conditions' => $conditions, 
			'limit' => $limit, 
			'offset' => $start, 
			'order' => 'EodProcess.name DESC')));
		$this->set('results', $this->EodProcess->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid eod process', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EodProcess->recursive = 2;
		$this->set('eod_process', $this->EodProcess->read(null, $id));
	}

	function run_eod_automatic($queued_job) {
		if($this->create_eod_process($queued_job['QueuedJob']['user_id']) == "NOK") {
			$this->Session->setFlash(__('EoD process could not be saved. Please, try again.', true), '');
			return false;
		} else {
			// return is OK
			$eod_process_id = $this->EodProcess->id;
			
			if($this->run_eod_tasks($eod_process_id)) {
				// return to the user
				$this->log('EOD process completed successfully.', 'queued_jobs_log');
				return true;
			} else {
				$this->log('EoD process could not be saved. Please, try again.', 'queued_jobs_log');
				return false;
			}
		}
	}
	
	function run_eod() {
		if (!empty($this->data)) {
			/*
			1. Setting EOD_RUNNING to 1
			2. Create EOD process db record
			3. 
			$this->EodProcess->create();
			$this->autoRender = false;
			if ($this->EodProcess->save($this->data)) {
				$this->Session->setFlash(__('The eod process has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The eod process could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}*/
			$user_id = $this->Session->read('Auth.User.id');

			if($this->create_eod_process($user_id) == "NOK") {
				$this->Session->setFlash(__('EoD process could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			} else {
				// return is OK
				$eod_process_id = $this->EodProcess->id;
				
				if($this->run_eod_tasks($eod_process_id)) {
					// return to the user
					$this->Session->setFlash(__('EOD process completed successfully.', true), '');
					$this->render('/elements/success');
				} else {
					$this->Session->setFlash(__('EoD process could not be saved. Please, try again.', true), '');
					$this->render('/elements/failure');
				}
			}
		}
		
		$process_date = $this->today();
		$name = 'EOD for ' . $process_date;
		
		$backup_type = 'F';
		$users = $this->EodProcess->User->find('list');
		$this->set(compact('users', 'backup_type', 'process_date', 'name'));
	}
	
	function run_internal() {
		if($this->create_eod_process() == "NOK") {
			$this->set('msg', 'Cannot create EOD Process Record');
		} else {
			// return is OK
			$eod_process_id = $this->EodProcess->id;
			
			if($this->run_eod_tasks($eod_process_id)) {
				// return to the user
				$this->set('msg', 'EOD Process run successfully');
			} else {
				$this->set('msg', 'Cannot run EOD Process tasks');
			}
		}
	}
	
	function create_eod_process($user_id) {
		// 1. 
		$this->setSystemSetting('EOD_RUNNING', 1);
		
		// 2. 
		$today = $this->today();
		$eod_process = array('EodProcess' => array(
			'name' => 'EoD Process for ' . $today,
			'process_date' => $today,
			'user_id' => $user_id,
			'task1_backup_taken' => 'P',
			'task2_portal_updated' => 'P',
			'task3_ftp_sent' => 'P',
			'backup_type' => 'I',
			'incremental_count' => 0,
			'backup_incremental_file' => 'NA',
			'backup_full_file' => 'NA'
		));
		
		$this->EodProcess->create();
		if(!$this->EodProcess->save($eod_process)) {
			$this->log($this->EodProcess->validationErrors, 'eod_process');
			return "NOK";
		}
		return "OK";
	}
	
	function run_eod_tasks($eod_process_id) {
		// 3. taking backup.
		
		$this->EodProcess->read(null, $eod_process_id);
		$this->EodProcess->set('task1_backup_taken', 'R');
		$this->EodProcess->save();
		
		// define variables
		$db = $this->EodProcess->query("SELECT DATABASE() AS DBNAME FROM DUAL");
		
		$db_name = $db[0][0]['DBNAME'];
		$tables = $this->EodProcess->query("SHOW TABLES");
		$content = "";
		
		// Take the backup
		foreach($tables as $table){
			$table_name = $table['TABLE_NAMES']['Tables_in_' . $db_name];
			$table_data = $this->EodProcess->query("SELECT * FROM $table_name");
			$field_def = $this->EodProcess->query("SHOW COLUMNS FROM $table_name");
			$field_defs = array();
			foreach($field_def as $fd){
				$field_defs[$fd['COLUMNS']['Field']] = $fd['COLUMNS'];
			}
			$content .= "\n-- Table: $table_name (" . (count($table_data) >= 1? count($table_data): "No") . " record" . (count($table_data) > 1? "s": "") . ") --\n";
			$count = 0;
			$temp_content = "";
			$temp_count = 0;
			foreach($table_data as $record) {
				$fields = array();
				$values = array();
				foreach($record[$table_name] as $field => $value) {
					$fields[] = $field;
					$values[] = (is_numeric($value)? $value: ($field_defs[$field]['Type'] == 'int(11)' && $value == ''? 'NULL': "'" . $value . "'"));
				}
				$count++;
				if($temp_content == '' && $count <= count($table_data)) {
					$temp_content .= "INSERT INTO $table_name (`" . join("`, `", $fields) . "`) VALUES\n (" . join(", ", $values) . "),\n";
				} else if((strlen($temp_content) + strlen(" (" . join(", ", $values) . "),\n")) < 50000) {
					$temp_content .= " (" . join(", ", $values) . "),\n";
				} else {
					if($temp_content == ''){
						$temp_content .= "INSERT INTO $table_name (`" . join("`, `", $fields) . "`) VALUES\n (" . join(", ", $values) . ");\n";
					} else {
						$temp_content .= " (" . join(", ", $values) . ");\n";
					}
					$count = 0;
					$content .= $temp_content;
					$temp_content = "";
					$temp_count++;
				}
			}
			if($temp_content <> ""){
				$content .= substr($temp_content, 0, strlen($temp_content)-2) . ";\n";
				$temp_content = "";
			}
		}

		$filename = date('Y-m-d-H-i-s') . '.sql';

		if (!file_exists(IMAGES . 'eod_backup')) {
			mkdir(IMAGES . 'eod_backup', 0777);
		}
		if (!file_exists(IMAGES . 'eod_backup' . DS . date('Y-m-d'))) {
			mkdir(IMAGES . 'eod_backup' . DS . date('Y-m-d'), 0777);
		}
		$handle = fopen(IMAGES . 'eod_backup' . DS . date('Y-m-d') . DS . $filename, 'a+');

		if (fwrite($handle, $content) === FALSE) {
			echo "Cannot write to file ($filename)";
			return false;
		}
		fclose($handle);
		
		$this->EodProcess->read(null, $eod_process_id);
		$this->EodProcess->set('task1_backup_taken', 'C');
		$this->EodProcess->set('backup_full_file', $filename);
		$this->EodProcess->save();
		
		// 4. Task 2: Portal Update
		// TODO: 
		
		
		// 5. Task 3: FTP upload
		// TODO: 
		
		// 6. Clean the restore points
		// TODO: 
		
		
		// 7. Close the EOD process
		$this->setSystemSetting('EOD_RUNNING', 0);
		$this->setSystemSetting('SYSTEM_HEALTH', 'E'); // after EOD State
		
		return true;
	}

	function run_sod_automatic() {
		$this->loadModel('EduQuarter');
		$quarter = $this->EduQuarter->getActiveQuarter();

		$process_date = $this->today();
		$name = 'EOD for ' . $process_date;
		$today_date = date('Y-m-d', strtotime($process_date . ' +1 day'));
		$i = $today_date;
		while($i != $quarter['EduQuarter']['end_date']) {
			if($this->isHoliday($i) > 0)
				$i = date('Y-m-d', strtotime($i . ' +1 day'));
			else {
				$today_date = $i;
				break;
			}
		}
		

		$yesterday = $this->getSystemSetting('TODAY');
		$this->setSystemSetting('TODAY', $today_date); //, strtotime($this->data['EodProcess']['todays_date'])));
		$this->setSystemSetting('SYSTEM_HEALTH', 'H');
		$yesterday = date('Y-m-d', strtotime($yesterday . ' +1 day'));
		
		// make adjustments for the edu_days record
		$this->loadModel('EduDay');
		$days = $this->EduDay->find('all', array(
			'conditions' => array(
				'EduDay.date >=' => $yesterday,
				'EduDay.date <' => $this->data['EodProcess']['todays_date']
			)
		));
		foreach($days as $edu_day) {
			$this->EduDay->read(null, $edu_day['EduDay']['id']);
			$this->EduDay->set('is_active', 0);
			$this->EduDay->save();
		}
		
		$this->log('SoD run successfully.', 'queued_jobs_log');
		return true;
	}
	
	function run_sod() {
		if (!empty($this->data)) {
			$yesterday = $this->getSystemSetting('TODAY');
			$this->setSystemSetting('TODAY', date('Y-m-d', strtotime($this->data['EodProcess']['todays_date'])));
			$this->setSystemSetting('SYSTEM_HEALTH', 'H');
			$yesterday = date('Y-m-d', strtotime($yesterday . ' +1 day'));
			
			// make adjustments for the edu_days record
			$this->loadModel('EduDay');
			$days = $this->EduDay->find('all', array(
				'conditions' => array(
					'EduDay.date >=' => $yesterday,
					'EduDay.date <' => $this->data['EodProcess']['todays_date']
				)
			));
			foreach($days as $edu_day) {
				$this->EduDay->read(null, $edu_day['EduDay']['id']);
				$this->EduDay->set('is_active', 0);
				$this->EduDay->save();
			}
			
			$this->Session->setFlash(__('SoD run successfully.', true), '');
			$this->render('/elements/success');
		}
		
		$this->loadModel('EduQuarter');
		$quarter = $this->EduQuarter->getActiveQuarter();
		
		
		// to build the form
		$process_date = $this->today();
		$name = 'EOD for ' . $process_date;
		$today_date = date('Y-m-d', strtotime($process_date . ' +1 day'));
		$i = $today_date;
		$holidays = "";
		while($i != $quarter['EduQuarter']['end_date']) {
			if($this->isHoliday($i) > 0)
				$holidays .= ($holidays == ""? "": ", ") . '"' . $i . '"';
			$i = date('Y-m-d', strtotime($i . ' +1 day'));
		}
		
		$this->set(compact('today_date', 'name', 'quarter', 'holidays'));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->EodProcess->create();
			$this->autoRender = false;
			if ($this->EodProcess->save($this->data)) {
				$this->Session->setFlash(__('The eod process has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The eod process could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$users = $this->EodProcess->User->find('list');
		$this->set(compact('users'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid eod process', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EodProcess->save($this->data)) {
				$this->Session->setFlash(__('The eod process has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The eod process could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$eod_process = $this->EodProcess->read(null, $id);
		$this->set('eod_process', $eod_process);
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
	
		$users = $this->EodProcess->User->find('list');
		$this->set(compact('users'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for eod process', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EodProcess->delete($i);
                }
				$this->Session->setFlash(__('Eod process deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Eod process was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EodProcess->delete($id)) {
				$this->Session->setFlash(__('Eod process deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Eod process was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>