<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class BackOfficeController extends AppController {

    public $name = 'BackOffice';
    public $uses = array();

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('dashboard', 'call_integrate_portal');
    }

    /**
     * Default action for the controller. This will render the dashboard view.
     * It will check the system health and if unhealthy, it will render the
     * system error layout instead of the default layout.
     * @return void
     */
    public function index() {
        $this->layout = 'main';
        //if($this->getSystemSetting('SYSTEM_HEALTH') != 'H'){
        //    $this->layout = 'system_error';
        //}
		$health = $this->getSystemSetting('SYSTEM_HEALTH');
        if($health == 'U'){ // unhealthy state
            $this->layout = 'system_error';
        }
        $this->set('has_error_reporting', $this->getSystemSetting('ERROR_REPORTING'));
    }

    public function dashboard() {
        $this->layout = 'devoo';
    }

    function call_integrate_portal() {
        $this->layout = 'ajax';
        $s = $this->getSystemSetting('PORTAL_INTEGRATION_RUNNING');
        if($s == 1) {
            $this->set('msg', "PORTAL INTEGRATION RUNNING OR Dead-lock is created!\n");
            $message = "PORTAL INTEGRATION RUNNING OR Dead lock is created!. " . date('Y-m-d+H:i:s');
            $this->sendSMS('251930328163', $message);
        } else {
            $this->setSystemSetting('PORTAL_INTEGRATION_RUNNING', 1);
            
            $msg = $this->integrate_portal();
            
            $this->setSystemSetting('PORTAL_INTEGRATION_RUNNING', 0);
            $this->set('msg', $msg);
        }
    }

    public function integrate_portal() {
        // Take and hold the integration datetime
        $old_time = $this->getSystemSetting('LAST_INTEGRATION_TIME');
        $new_time = time();
        $this->setSystemSetting('LAST_INTEGRATION_TIME', $new_time);

        // Read the specific tables like edu_parents, edu_parent_details
        $sql_data = '';
        
		$sql_data .= $this->get_edu_parent($old_time, $new_time);
		$sql_data .= $this->get_edu_parent_detail($old_time, $new_time);
		$sql_data .= $this->get_edu_student($old_time, $new_time);
		$sql_data .= $this->get_edu_academic_year($old_time, $new_time);
		$sql_data .= $this->get_edu_quarter($old_time, $new_time);
		$sql_data .= $this->get_edu_registration($old_time, $new_time);
		$sql_data .= $this->get_edu_class($old_time, $new_time);
		$sql_data .= $this->get_edu_section($old_time, $new_time);
		$sql_data .= $this->get_edu_campus($old_time, $new_time);
		$sql_data .= $this->get_edu_registration_quarter($old_time, $new_time);
		$sql_data .= $this->get_edu_registration_result($old_time, $new_time);
		$sql_data .= $this->get_edu_registration_quarter_result($old_time, $new_time);
		$sql_data .= $this->get_edu_course($old_time, $new_time);


		// Create a folder and put the sql and image files to it
		$dir = date('Ymd');

		mkdir(IMAGES . 'portal_scripts' . DS . $dir , 0777);

		$handle_sql = fopen(IMAGES . 'portal_scripts' . DS . $dir . DS . 'script.sql', "w");
		fwrite($handle_sql, $sql_data);
		fclose($handle_sql);

		// collect new parents profile pictures to be uploaded to the portal
		$handle_parents = opendir(IMAGES . 'parents');
		while (($file = readdir($handle_parents)) !== false) {
			if (($file != '.') && ($file != '..')) {
				if (is_file(IMAGES . 'parents' . DS . $file) && $file > $old_time) {
					// get copy of the file to a temp directory
					$source = IMAGES . 'parents' . DS . $file;
					$destination = IMAGES . 'portal_scripts' . DS . $dir . DS . 'parents' . DS . $file;

					$data = file_get_contents($source);

					$handle2 = fopen($destination, "w");
					fwrite($handle2 , $data);
					fclose($handle2);
				}
			}
		}
		closedir($handle_parents);

		// collect new students profile pictures to be uploaded to the portal
		$handle_students = opendir(IMAGES . 'students');
		while (($file = readdir($handle_students)) !== false) {
			if (($file != '.') && ($file != '..')) {
				if (is_file(IMAGES . 'students' . DS . $file) && $file > $old_time) {
					// get copy of the file to a temp directory
					$source = IMAGES . 'students' . DS . $file;
					$destination = IMAGES . $dir . DS . 'students' . DS . $file;

					$data = file_get_contents($source);

					$handle2 = fopen($destination, "w");
					fwrite($handle2, $data);
					fclose($handle2);
				}
			}
		}
		closedir($handle_students);

        $ftp_server = '127.0.0.1';
        $ftp_username = 'redfox';
        $ftp_password = '123456';

        $conn_id = @ftp_connect($ftp_server);
        @ftp_login ($conn_id, $ftp_username, $ftp_password);
        $this->ftp_upload_directory($conn_id, IMAGES . 'portal_scripts' . DS . $dir . '/', 'integration/');
        @ftp_quit($conn_id);

        // remove the temporary directory
        $this->deleteDirectory(IMAGES . 'portal_scripts' . DS . $dir);

        // call a method (url) of the portal to execute the integration
        $result = 'OK'; //file_get_contents("http://portal.chaeduc.com/run_integration");
        $this->log('Result: ' . $result, 'portal');

        return $result;
    }

	function get_edu_parent($old_time, $new_time) {
		$sql_data = '';
        $this->loadModel('Edu.EduParent');
		$parent_conditions = array(
            'EduParent.modified >' => date('Y-m-d H:i:s', $old_time), 
            'EduParent.modified <=' => date('Y-m-d H:i:s', $new_time)
            );
		$parents = $this->EduParent->find('all', array('conditions' => $parent_conditions));

        foreach ($parents as $parent) {
            $p = $parent['EduParent'];
            if($p['created'] > date('Y-m-d H:i:s', $old_time)){
                $fields = "`id`, `authorized_person`, `marital_status`, `primary_parent`, `secret_code`, `sms_phone_number`, `created`, `modified`";
                $values = "". $p['id'] . ", '". $p['authorized_person'] . "', '". $p['marital_status'] . "', '". $p['primary_parent'] . "', ". $p['secret_code'] . ", '". $p['sms_phone_number'] . "', '". $p['created'] . "', '". $p['modified'] . "'";
                $sql_data .= "INSERT INTO `edu_parents` (" . $fields . ") VALUES(" . $values . "); \r\n";

            } else {
                $fv = "`authorized_person` = '". $p['authorized_person'] . "', `marital_status` = '". $p['marital_status'] . "', `primary_parent` = '". $p['primary_parent'] . "', `secret_code` = ". $p['secret_code'] . ", `sms_phone_number` = '". $p['sms_phone_number'] . "', `created` = '". $p['created'] . "', `modified` = '". $p['modified'] . "'";
                $sql_data .= "UPDATE `edu_parents` SET " . $fv . " WHERE `edu_parents`.`id` = " . $p['id'] . "; \r\n";
            }
        }
        return $sql_data;
	}

	function get_edu_parent_detail($old_time, $new_time) {
		$sql_data = '';
		$this->loadModel('Edu.EduParentDetail');

        $parent_detail_conditions = array(
            'EduParentDetail.modified >' => date('Y-m-d H:i:s', $old_time), 
            'EduParentDetail.modified <=' => date('Y-m-d H:i:s', $new_time)
            );
        $parent_details = $this->EduParentDetail->find('all', array('conditions' => $parent_detail_conditions));

		foreach ($parent_details as $pd) {
			$p = $pd['EduParentDetail'];
			if($p['created'] > date('Y-m-d H:i:s', $old_time)){
				$fields = "`id`, `first_name`, `middle_name`, `last_name`, `residence_address`, `nationality`, `relationship`, `occupation`, `academic_qualification`, `employment_status`, `employer`, `work_address`, `work_telephone`, `mobile`, `email`, `photo_file`, `family_type`, `edu_parent_id`, `created`, `modified`";
				$values = "". $p['id'] . ", '". $p['first_name'] . "', '". $p['middle_name'] . "', '". $p['last_name'] . "', '". $p['residence_address'] . "', '". $p['nationality'] . "', '". $p['relationship'] . "', '". $p['occupation'] . "', '". $p['academic_qualification'] . "', '". $p['employment_status'] . "', '". $p['employer'] . "', '". $p['work_address'] . "', '". $p['work_telephone'] . "', '". $p['mobile'] . "', '". $p['email'] . "', '". $p['photo_file'] . "', '". $p['family_type'] . "', ". $p['edu_parent_id'] . ", '". $p['created'] . "', '". $p['modified'] . "'";
				$sql_data .= "INSERT INTO `edu_parent_details` (" . $fields . ") VALUES(" . $values . "); \r\n";
			} else {
				$fv = "`first_name` = '". $p['first_name'] . "', `middle_name` = '". $p['middle_name'] . "', `last_name` = '". $p['last_name'] . "', `residence_address` = '". $p['residence_address'] . "', `nationality` = '". $p['nationality'] . "', `relationship` = '". $p['relationship'] . "', `occupation` = '". $p['occupation'] . "', `academic_qualification` = '". $p['academic_qualification'] . "', `employment_status` = '". $p['employment_status'] . "', `employer` = '". $p['employer'] . "', `work_address` = '". $p['work_address'] . "', `work_telephone` = '". $p['work_telephone'] . "', `mobile` = '". $p['mobile'] . "', `email` = '". $p['email'] . "', `photo_file` = '". $p['photo_file'] . "', `family_type` = '". $p['family_type'] . "', `edu_parent_id` = '". $p['edu_parent_id'] . "', `created` = '". $p['created'] . "', `modified` = '" . $p['modified'] . "'";
				$sql_data .= "UPDATE `edu_parent_details` SET " . $fv . " WHERE `edu_parent_details`.`id` = " . $p['id'] . "; \r\n";
			}
		}

		return $sql_data;
	}

	function get_edu_student($old_time, $new_time) {
		$sql_data = '';
		$this->loadModel('Edu.EduStudent');

        $student_conditions = array(
            'EduStudent.modified >' => date('Y-m-d H:i:s', $old_time), 
            'EduStudent.modified <=' => date('Y-m-d H:i:s', $new_time)
            );
        $students = $this->EduStudent->find('all', array('conditions' => $student_conditions));

		foreach ($students as $student) {
			$p = $student['EduStudent'];
			if($p['created'] > date('Y-m-d H:i:s', $old_time)){
				$fields = "`id`, `name`, `identity_number`, `birth_date`, `registration_date`, `gender`, `nationality`, `edu_parent_id`, `photo_file_name`, `maker_id`, `status`, `created`, `modified`";
				$values = "". $p['id'] . ", '". $p['name'] . "', '". $p['identity_number'] . "', '". $p['birth_date'] . "', '". $p['registration_date'] . "', '". $p['gender'] . "', '". $p['nationality'] . "', '". $p['edu_parent_id'] . "', '". $p['photo_file_name'] . "', '". $p['maker_id'] . "', '". $p['status'] . "', '". $p['created'] . "', '". $p['modified'] . "'";
				$sql_data .= "INSERT INTO `edu_students` (" . $fields . ") VALUES(" . $values . "); \r\n";
			} else {
				$fv = "`name` = '". $p['name'] . "', `identity_number` = '". $p['identity_number'] . "', `birth_date` = '". $p['birth_date'] . "', `registration_date` = '". $p['registration_date'] . "', `gender` = '". $p['gender'] . "', `nationality` = '". $p['nationality'] . "', `edu_parent_id` = '". $p['edu_parent_id'] . "', `photo_file_name` = '". $p['photo_file_name'] . "', `maker_id` = '". $p['maker_id'] . "', `status` = '". $p['status'] . "', `created` = '". $p['created'] . "', `modified` = '" . $p['modified'] . "'";
				$sql_data .= "UPDATE `edu_students` SET " . $fv . " WHERE `edu_students`.`id` = " . $p['id'] . "; \r\n";
			}
		}

		return $sql_data;
	}

	function get_edu_academic_year($old_time, $new_time) {
		$sql_data = '';
		$this->loadModel('Edu.EduAcademicYear');

		$academic_year_conditions = array(
			'EduAcademicYear.modified >' => date('Y-m-d H:i:s', $old_time), 
			'EduAcademicYear.modified <=' => date('Y-m-d H:i:s', $new_time)
		);
		$academic_years = $this->EduAcademicYear->find('all', array('conditions' => $academic_year_conditions));

		foreach ($academic_years as $academic_year) {
			$p = $academic_year['EduAcademicYear'];
			if($p['created'] > date('Y-m-d H:i:s', $old_time)){
				$fields = "`id`, `name`, `start_date`, `end_date`, `status_id`, `user_id`, `created`, `modified`";
				$values = "". $p['id'] . ", '". $p['name'] . "', '". $p['start_date'] . "', '". $p['end_date'] . "', '". $p['status_id'] . "', '". $p['user_id'] . "', '". $p['created'] . "', '". $p['modified'] . "'";
				$sql_data .= "INSERT INTO `edu_academic_years` (" . $fields . ") VALUES(" . $values . "); \r\n";
			} else {
				$fv = "`name` = '" . $p['name'] . "', `start_date` = '" . $p['start_date'] . "', `end_date` = '" . $p['end_date'] . "', `status_id` = '" . $p['status_id'] . "', `user_id` = '" . $p['user_id'] . "', `created` = '" . $p['created'] . "', `modified` = '" . $p['modified'] . "'";
				$sql_data .= "UPDATE `edu_academic_years` SET " . $fv . " WHERE `edu_academic_years`.`id` = " . $p['id'] . "; \r\n";
			}
		}

		return $sql_data;
	}

	function get_edu_quarter($old_time, $new_time) {
		$sql_data = '';
		$this->loadModel('Edu.EduQuarter');

		$quarter_conditions = array(
			'EduQuarter.modified >' => date('Y-m-d H:i:s', $old_time), 
			'EduQuarter.modified <=' => date('Y-m-d H:i:s', $new_time)
		);
		$quarters = $this->EduQuarter->find('all', array('conditions' => $quarter_conditions));

		foreach ($quarters as $quarter) {
			$p = $quarter['EduQuarter'];
			if($p['created'] > date('Y-m-d H:i:s', $old_time)){
				$fields = "`id`, `name`, `short_name`, `start_date`, `end_date`, `edu_academic_year_id`, `quarter_type`, `status_id`, `user_id`, `created`, `modified`";
				$values = "" . $p['id'] . ", '" . $p['name'] . "', '" . $p['short_name'] . "', '" . $p['start_date'] . "', '" . $p['end_date'] . "', '" . $p['edu_academic_year_id'] . "', '" . $p['quarter_type'] . "', '" . $p['status_id'] . "', '" . $p['user_id'] . "', '" . $p['created'] . "', '" . $p['modified'] . "'";
				$sql_data .= "INSERT INTO `edu_quarters` (" . $fields . ") VALUES(" . $values . "); \r\n";
			} else {
				$fv = "`name` = '" . $p['name'] . "', `short_name` = '" . $p['short_name'] . "', `start_date` = '" . $p['start_date'] . "', `end_date` = '". $p['end_date'] . "', `edu_academic_year_id` = '" . $p['edu_academic_year_id'] . "', `quarter_type` = '" . $p['quarter_type'] . "', `status_id` = '". $p['status_id'] . "', `user_id` = '". $p['user_id'] . "', `created` = '". $p['created'] . "', `modified` = '" . $p['modified'] . "'";
				$sql_data .= "UPDATE `edu_quarters` SET " . $fv . " WHERE `edu_quarters`.`id` = " . $p['id'] . "; \r\n";
			}
		}

		return $sql_data;
	}

	function get_edu_registration($old_time, $new_time) {
		$sql_data = '';
		$this->loadModel('Edu.EduRegistration');

		$registration_conditions = array(
			'EduRegistration.modified >' => date('Y-m-d H:i:s', $old_time), 
			'EduRegistration.modified <=' => date('Y-m-d H:i:s', $new_time)
		);
		$registrations = $this->EduRegistration->find('all', array('conditions' => $registration_conditions));

		foreach ($registrations as $registration) {
			$p = $registration['EduRegistration'];
			if($p['created'] > date('Y-m-d H:i:s', $old_time)){
				$fields = "`id`, `name`, `edu_student_id`, `edu_class_id`, `edu_section_id`, `edu_campus_id`, `grand_total_average`, `rank`, `class_rank`, `status`, `failure_count`, `allowed`, `disciplinary_failure`, `remark`, `created`, `modified`";
				$values = "" . $p['id'] . ", '" . $p['name'] . "', '" . $p['edu_student_id'] . "', '" . $p['edu_class_id'] . "', '" . $p['edu_section_id'] . "', '" . $p['edu_campus_id'] . "', '" . $p['grand_total_average'] . "', '" . $p['rank'] . "', '" . $p['class_rank'] . "', '" . $p['status'] . "', '" . $p['failure_count'] . "', '" . $p['allowed'] . "', '" . $p['disciplinary_failure'] . "', '" . $p['remark'] . "', '" . $p['created'] . "', '" . $p['modified'] . "'";
				$sql_data .= "INSERT INTO `edu_registrations` (" . $fields . ") VALUES(" . $values . "); \r\n";
			} else {
				$fv = "`name` = '" . $p['name'] . "', `edu_student_id` = '" . $p['edu_student_id'] . "', `edu_class_id` = '" . $p['edu_class_id'] . "', `edu_section_id` = '". $p['edu_section_id'] . "', `edu_campus_id` = '" . $p['edu_campus_id'] . "', `grand_total_average` = '" . $p['grand_total_average'] . "', `rank` = '". $p['rank'] . "', `class_rank` = '". $p['class_rank'] . "', `status` = '". $p['status'] . "', `failure_count` = '". $p['failure_count'] . "', `allowed` = '". $p['allowed'] . "', `disciplinary_failure` = '". $p['disciplinary_failure'] . "', `remark` = '". $p['remark'] . "', `created` = '". $p['created'] . "', `modified` = '" . $p['modified'] . "'";
				$sql_data .= "UPDATE `edu_registrations` SET " . $fv . " WHERE `edu_registrations`.`id` = " . $p['id'] . "; \r\n";
			}
		}

		return $sql_data;
	}

	function get_edu_class($old_time, $new_time) {
		$sql_data = '';
		$this->loadModel('Edu.EduClass');

		$class_conditions = array(
			'EduClass.modified >' => date('Y-m-d H:i:s', $old_time), 
			'EduClass.modified <=' => date('Y-m-d H:i:s', $new_time)
		);
		$classes = $this->EduClass->find('all', array('conditions' => $class_conditions));

		foreach ($classes as $class) {
			$p = $class['EduClass'];
			if($p['created'] > date('Y-m-d H:i:s', $old_time)){
				$fields = "`id`, `name`, `cvalue`, `min_for_promotion`, `max_failure_for_promotion`, `edu_class_level_id`, `uni_teacher`, `grading_type`, `enrollment_fee`, `registration_fee`, `rank_display`, `rank_display_upto`, `created`, `modified`";
				$values = "" . $p['id'] . ", '" . $p['name'] . "', '" . $p['cvalue'] . "', '" . $p['min_for_promotion'] . "', '" . $p['max_failure_for_promotion'] . "', '" . $p['edu_class_level_id'] . "', '" . $p['grand_total_average'] . "', '" . $p['uni_teacher'] . "', '" . $p['grading_type'] . "', '" . $p['enrollment_fee'] . "', '" . $p['registration_fee'] . "', '" . $p['rank_display'] . "', '" . $p['rank_display_upto'] . "', '" . $p['created'] . "', '" . $p['modified'] . "'";
				$sql_data .= "INSERT INTO `edu_classes` (" . $fields . ") VALUES(" . $values . "); \r\n";
			} else {
				$fv = "`name` = '" . $p['name'] . "', `cvalue` = '" . $p['cvalue'] . "', `min_for_promotion` = '" . $p['min_for_promotion'] . "', `max_failure_for_promotion` = '". $p['max_failure_for_promotion'] . "', `edu_class_level_id` = '" . $p['edu_class_level_id'] . "', `grand_total_average` = '" . $p['grand_total_average'] . "', `uni_teacher` = '". $p['uni_teacher'] . "', `grading_type` = '". $p['grading_type'] . "', `enrollment_fee` = '". $p['enrollment_fee'] . "', `registration_fee` = '". $p['registration_fee'] . "', `rank_display` = '". $p['rank_display'] . "', `rank_display_upto` = '". $p['rank_display_upto'] . "', `created` = '". $p['created'] . "', `modified` = '" . $p['modified'] . "'";
				$sql_data .= "UPDATE `edu_classes` SET " . $fv . " WHERE `edu_classes`.`id` = " . $p['id'] . "; \r\n";
			}
		}

		return $sql_data;
	}

	function get_edu_section($old_time, $new_time) {
		$sql_data = '';
		$this->loadModel('Edu.EduSection');

		$section_conditions = array(
			'EduSection.modified >' => date('Y-m-d H:i:s', $old_time), 
			'EduSection.modified <=' => date('Y-m-d H:i:s', $new_time)
		);
		$sections = $this->EduSection->find('all', array('conditions' => $section_conditions));

		foreach ($sections as $section) {
			$p = $section['EduSection'];
			if($p['created'] > date('Y-m-d H:i:s', $old_time)){
				$fields = "`id`, `name`, `edu_campus_id`, `edu_class_id`, `edu_academic_year_id`, `edu_teacher_id`, `created`, `modified`";
				$values = "" . $p['id'] . ", '" . $p['name'] . "', '" . $p['edu_campus_id'] . "', '" . $p['edu_class_id'] . "', '" . $p['edu_academic_year_id'] . "', '" . $p['edu_teacher_id'] . "', '" . $p['created'] . "', '" . $p['modified'] . "'";
				$sql_data .= "INSERT INTO `edu_sections` (" . $fields . ") VALUES(" . $values . "); \r\n";
			} else {
				$fv = "`name` = '" . $p['name'] . "', `edu_campus_id` = '" . $p['edu_campus_id'] . "', `edu_class_id` = '" . $p['edu_class_id'] . "', `edu_academic_year_id` = '". $p['edu_academic_year_id'] . "', `edu_teacher_id` = '" . $p['edu_teacher_id'] . "', `created` = '". $p['created'] . "', `modified` = '" . $p['modified'] . "'";
				$sql_data .= "UPDATE `edu_sections` SET " . $fv . " WHERE `edu_sections`.`id` = " . $p['id'] . "; \r\n";
			}
		}

		return $sql_data;
	}

	function get_edu_campus($old_time, $new_time) {
		$sql_data = '';
		$this->loadModel('Edu.EduCampus');

		$campus_conditions = array(
			'EduCampus.modified >' => date('Y-m-d H:i:s', $old_time), 
			'EduCampus.modified <=' => date('Y-m-d H:i:s', $new_time)
		);
		$campuses = $this->EduCampus->find('all', array('conditions' => $section_conditions));

		foreach ($campuses as $campus) {
			$p = $campus['EduCampus'];
			if($p['created'] > date('Y-m-d H:i:s', $old_time)){
				$fields = "`id`, `name`, `address`, `telephone`, `principal`, `created`, `modified`";
				$values = "" . $p['id'] . ", '" . $p['name'] . "', '" . $p['address'] . "', '" . $p['telephone'] . "', '" . $p['principal'] . "', '" . $p['created'] . "', '" . $p['modified'] . "'";
				$sql_data .= "INSERT INTO `edu_campuses` (" . $fields . ") VALUES(" . $values . "); \r\n";
			} else {
				$fv = "`name` = '" . $p['name'] . "', `address` = '" . $p['address'] . "', `telephone` = '" . $p['telephone'] . "', `principal` = '". $p['principal'] . "', `created` = '". $p['created'] . "', `modified` = '" . $p['modified'] . "'";
				$sql_data .= "UPDATE `edu_campuses` SET " . $fv . " WHERE `edu_campuses`.`id` = " . $p['id'] . "; \r\n";
			}
		}

		return $sql_data;
	}

	function get_edu_registration_quarter($old_time, $new_time) {
		$sql_data = '';
		$this->loadModel('Edu.EduRegistrationQuarter');

		$registration_quarter_conditions = array(
			'EduRegistrationQuarter.modified >' => date('Y-m-d H:i:s', $old_time), 
			'EduRegistrationQuarter.modified <=' => date('Y-m-d H:i:s', $new_time)
		);
		$registration_quarters = $this->EduRegistrationQuarter->find('all', array('conditions' => $section_conditions));

		foreach ($registration_quarters as $registration_quarter) {
			$p = $registration_quarter['EduRegistrationQuarter'];
			if($p['created'] > date('Y-m-d H:i:s', $old_time)){
				$fields = "`id`, `edu_registration_id`, `edu_quarter_id`, `quarter_average`, `quarter_rank`, `class_rank`, `absentees`, `parent_comment`, `homeroom_comment`, `created`, `modified`";
				$values = "" . $p['id'] . ", '" . $p['edu_registration_id'] . "', '" . $p['edu_quarter_id'] . "', '" . $p['quarter_average'] . "', '" . $p['quarter_rank'] . "', '" . $p['class_rank'] . "', '" . $p['absentees'] . "', '" . $p['parent_comment'] . "', '" . $p['homeroom_comment'] . "', '" . $p['created'] . "', '" . $p['modified'] . "'";
				$sql_data .= "INSERT INTO `edu_registration_quarters` (" . $fields . ") VALUES(" . $values . "); \r\n";
			} else {
				$fv = "`edu_registration_id` = '" . $p['edu_registration_id'] . "', `edu_quarter_id` = '" . $p['edu_quarter_id'] . "', `quarter_average` = '" . $p['quarter_average'] . "', `quarter_rank` = '". $p['quarter_rank'] . "', `class_rank` = '" . $p['class_rank'] . "', `absentees` = '". $p['absentees'] . "', `parent_comment` = '". $p['parent_comment'] . "', `homeroom_comment` = '". $p['homeroom_comment'] . "', `created` = '". $p['created'] . "', `modified` = '" . $p['modified'] . "'";
				$sql_data .= "UPDATE `edu_registration_quarters` SET " . $fv . " WHERE `edu_registration_quarters`.`id` = " . $p['id'] . "; \r\n";
			}
		}

		return $sql_data;
	}

	function get_edu_registration_quarter_result($old_time, $new_time) {
		$sql_data = '';
		$this->loadModel('Edu.EduRegistrationQuarterResult');

		$registration_quarter_result_conditions = array(
			'EduRegistrationQuarterResult.modified >' => date('Y-m-d H:i:s', $old_time), 
			'EduRegistrationQuarterResult.modified <=' => date('Y-m-d H:i:s', $new_time)
		);
		$registration_quarter_results = $this->EduRegistrationQuarterResult->find('all', array('conditions' => $section_conditions));

		foreach ($registration_quarter_results as $registration_quarter_result) {
			$p = $registration_quarter_result['EduRegistrationQuarterResult'];
			if($p['created'] > date('Y-m-d H:i:s', $old_time)){
				$fields = "`id`, `edu_registration_quarter_id`, `edu_course_id`, `course_result`, `course_rank`, `result_indicator`, `created`, `modified`";
				$values = "" . $p['id'] . ", '" . $p['edu_registration_quarter_id'] . "', '" . $p['edu_course_id'] . "', '" . $p['course_result'] . "', '" . $p['course_rank'] . "', '" . $p['result_indicator'] . "', '" . $p['created'] . "', '" . $p['modified'] . "'";
				$sql_data .= "INSERT INTO `edu_registration_quarter_results` (" . $fields . ") VALUES(" . $values . "); \r\n";
			} else {
				$fv = "`edu_registration_quarter_id` = '" . $p['edu_registration_quarter_id'] . "', `edu_course_id` = '" . $p['edu_course_id'] . "', `course_result` = '" . $p['course_result'] . "', `course_rank` = '". $p['course_rank'] . "', `result_indicator` = '" . $p['result_indicator'] . "', `created` = '". $p['created'] . "', `modified` = '" . $p['modified'] . "'";
				$sql_data .= "UPDATE `edu_registration_quarter_results` SET " . $fv . " WHERE `edu_registration_quarter_results`.`id` = " . $p['id'] . "; \r\n";
			}
		}

		return $sql_data;
	}

	function get_edu_registration_result($old_time, $new_time) {
		$sql_data = '';
		$this->loadModel('Edu.EduRegistrationResult');

		$registration_result_conditions = array(
			'EduRegistrationResult.modified >' => date('Y-m-d H:i:s', $old_time), 
			'EduRegistrationResult.modified <=' => date('Y-m-d H:i:s', $new_time)
		);
		$registration_results = $this->EduRegistrationResult->find('all', array('conditions' => $registration_result_conditions));

		foreach ($registration_results as $registration_result) {
			$p = $registration_result['EduRegistrationResult'];
			if($p['created'] > date('Y-m-d H:i:s', $old_time)){
				$fields = "`id`, `edu_registration_id`, `edu_course_id`, `average`, `status`, `created`, `modified`";
				$values = "" . $p['id'] . ", '" . $p['edu_registration_id'] . "', '" . $p['edu_course_id'] . "', '" . $p['average'] . "', '" . $p['status'] . "', '" . $p['created'] . "', '" . $p['modified'] . "'";
				$sql_data .= "INSERT INTO `edu_registration_results` (" . $fields . ") VALUES(" . $values . "); \r\n";
			} else {
				$fv = "`edu_registration_id` = '" . $p['edu_registration_id'] . "', `edu_course_id` = '" . $p['edu_course_id'] . "', `average` = '" . $p['average'] . "', `status` = '". $p['status'] . "', `created` = '". $p['created'] . "', `modified` = '" . $p['modified'] . "'";
				$sql_data .= "UPDATE `edu_registration_results` SET " . $fv . " WHERE `edu_registration_results`.`id` = " . $p['id'] . "; \r\n";
			}
		}

		return $sql_data;
	}

	function get_edu_course($old_time, $new_time) {
		$sql_data = '';
		$this->loadModel('Edu.EduCourse');

		$course_conditions = array(
			'EduCourse.modified >' => date('Y-m-d H:i:s', $old_time), 
			'EduCourse.modified <=' => date('Y-m-d H:i:s', $new_time)
		);
		$courses = $this->EduCourse->find('all', array('conditions' => $course_conditions));

		foreach ($courses as $course) {
			$p = $course['EduCourse'];
			if($p['created'] > date('Y-m-d H:i:s', $old_time)){
				$fields = "`id`, `edu_class_id`, `edu_subject_id`, `description`, `min_for_pass`, `is_mandatory`, `created`, `modified`";
				$values = "" . $p['id'] . ", '" . $p['edu_class_id'] . "', '" . $p['edu_subject_id'] . "', '" . $p['description'] . "', '" . $p['min_for_pass'] . "', '" . $p['is_mandatory'] . "', '" . $p['created'] . "', '" . $p['modified'] . "'";
				$sql_data .= "INSERT INTO `edu_courses` (" . $fields . ") VALUES(" . $values . "); \r\n";
			} else {
				$fv = "`edu_class_id` = '" . $p['edu_class_id'] . "', `edu_subject_id` = '" . $p['edu_subject_id'] . "', `description` = '" . $p['description'] . "', `min_for_pass` = '". $p['min_for_pass'] . "', `is_mandatory` = '" . $p['is_mandatory'] . "', `created` = '". $p['created'] . "', `modified` = '" . $p['modified'] . "'";
				$sql_data .= "UPDATE `edu_courses` SET " . $fv . " WHERE `edu_courses`.`id` = " . $p['id'] . "; \r\n";
			}
		}

		return $sql_data;
	}

    function create_zip($files = array(), $destination = '', $overwrite = false) {
        //if the zip file already exists and overwrite is false, return false
        if (file_exists($destination) && !$overwrite) {
            return false;
        }
        //vars
        $valid_files = array();
        //if files were passed in...
        if (is_array($files)) {
            //cycle through each file
            foreach ($files as $key => $file) {
                //make sure the file exists
                if (file_exists($file)) {
                    $valid_files[$key] = $file;
                }
            }
        }
        //if we have good files...
        if (count($valid_files)) {
            //create the archive
            $zip = new ZipArchive();
            if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                return false;
            }
            //add the files
            foreach ($valid_files as $key => $file) {
                $zip->addFile($file, $key);
            }
            //debug
            //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
            //close the zip -- done!
            $zip->close();

            //check to make sure the file exists
            return file_exists($destination);
        } else {
            return false;
        }
    }

    function ftp_upload_directory($conn_id, $local_dir, $remote_dir) {
        @ftp_mkdir($conn_id, $remote_dir);
        $handle = opendir($local_dir);
        $f = array();
        while (($file = readdir($handle)) !== false) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($local_dir.$file)) {
                    $this->ftp_upload_directory($conn_id, $local_dir.$file.'/', $remote_dir.$file.'/');
                } else {
                    $f[] = $file;
                }
            }
        }
        closedir($handle);
        if (count($f)) {
            sort($f);
            @ftp_chdir($conn_id, $remote_dir);
            foreach ($f as $files) {
                $from = @fopen("$local_dir$files", 'r');
                @ftp_fput($conn_id, $files, $from, FTP_BINARY);
            }
        }
    }

    function deleteDirectory($dir){
        $result = false;
        if ($handle = opendir("$dir")){
            $result = true;
            while ((($file=readdir($handle))!==false) && ($result)){
                if ($file!='.' && $file!='..'){
                    if (is_dir("$dir/$file")){
                        $result = $this->deleteDirectory("$dir/$file");
                    } else {
                        $result = unlink("$dir/$file");
                    }
                }
            }
            closedir($handle);
            if ($result){
                $result = rmdir($dir);
            }
        }
        return $result;
    }
}
