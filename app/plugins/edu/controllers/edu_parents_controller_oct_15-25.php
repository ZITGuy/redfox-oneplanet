<?php

class EduParentsController extends EduAppController {

    var $name = 'EduParents';
    var $api_url = 'http://92.205.160.107:8095/api'; // production server
    //var $api_url = 'http://127.0.0.1:8095/api'; // local testing

    function index() {
        
    }
    
	function index_m() {
        
    }
	
	function index_o() {
        
    }
	
    function index_v() {
        
    }
	
	function index_students($id = null) {
		$this->set('parent_id', $id);
		
		$this->EduParent->recursive = 2;
        $p = $this->EduParent->read(null, $id);
	}

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }

    function list_data() {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");

        $this->set('edu_parents', $this->EduParent->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduParent->find('count', array('conditions' => $conditions)));
    }
	
	function list_data2() {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';
		
        eval("\$conditions = array( " . $conditions . " );");
		$pdetails = $this->EduParent->EduParentDetail->find('all', array('conditions' => $conditions));
		$pids = array();
		foreach($pdetails as $pdetail) {
			$pids[] = $pdetail['EduParentDetail']['edu_parent_id'];
		}
		//pr($pids);
		
        $this->set('edu_parents', $this->EduParent->find('all', array('conditions' => array('id' => $pids), 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduParent->find('count', array('conditions' => array('id' => $pids))));
    }

    /**
     * Pushes parent data to the portal
     *
     * @return void
     */
    function push_parent_data_to_portal($id = null) {
        //$this->autoRender = false;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . '/auth/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        // JSON payload for POST request
        $postData = json_encode(array(
            "email" => "ophthysoft@gmail.com",
            "password" => "Pass@1234"
        ));

        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);

        $this->log('login response', 'debug_push');
        $this->log($response, 'debug_push');

        // response looks like
        /*
        (
            [statusCode] => 200
            [message] => Login successful
            [body] => Array
                (
                    [token] => eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJtYWJlYmUyNkBnbWFpbC5jb20iLCJpYXQiOjE3Mjg1NDg2NDEsImV4cCI6MTcyODYzNTA0MX0.QN-aCffdRCeD4JStkHNGGX1pUmDbgHvw6nOBWdZg4JA
                    [refreshToken] => eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJtYWJlYmUyNkBnbWFpbC5jb20iLCJpYXQiOjE3Mjg1NDg2NDEsImV4cCI6MTcyODYzNTA0MX0.QN-aCffdRCeD4JStkHNGGX1pUmDbgHvw6nOBWdZg4JA
                )
        )
        */
        $token = $response['body']['token'];
        $resfreshToken = $response['body']['refreshToken'];

        $this->setSystemSetting('ACCESS_TOKEN', $token);
        $this->setSystemSetting('REFRESH_TOKEN', $resfreshToken);

        $this->push_parent_to_portal($id, $token);

        $this->set('response', 'Parent Data pushed to portal Successfully');
    }

    /**
     * Reads the parent identified by the $id and adds the parent details to the portal.
     *
     * @param int $id The ID of the parent to be added.
     * @param string $accessToken The access token to be used to authenticate the request to the portal.
     * @return void
     */
    function push_parent_to_portal($id = null, $accessToken = null) {
        // read the parent identified by the $id
        $this->EduParent->recursive = 1;
        $p = $this->EduParent->read(null, $id);
		
		$this->setSystemSetting('PARENT_ID', '');
		// push parent data to portal
		$parent_id = $this->add_parent_data($accessToken, $p);
		
        $parent_details = $p['EduParentDetail'];
        $students = $p['EduStudent'];

        $this->log($p, 'debug_parent');
        $this->log('Returned ParentId: ' . $parent_id, 'debug_push');
		
		// save the id in local parent table
		$this->EduParent->read(null, $id);
		$this->EduParent->set('portal_record_id', $parent_id);
		if(!$this->EduParent->save()) {
			$this->log('cannot save portal_record_id', 'debug');
		}

        // add the parent details to the portal
        foreach($parent_details as $parent_detail) {
            $this->add_parent_detail_data($accessToken, $parent_detail, $parent_id, 
                $p['EduParent']['marital_status'] == 'S'? 'SINGLE': 'MARRIED', 
                $parent_detail['relationship'] == 'father'? 'MALE': 'FEMALE');
        }

        // add the students to the portal
        foreach($students as $student) {
            $this->add_student_data($accessToken, $student, $parent_id);
        }
    }
	
	/**
     * Adds a parent object to the portal
     *
     * @param string $accessToken The access token to be used to authenticate the request to the portal.
     * @param array $parent_detail The parent detail to be added to the portal.
     * @param string $maritalStatus The marital status of the parent. Defaults to 'MARRIED'.
     * @param string $gender The gender of the parent. Defaults to 'MALE'.
     *
     * @return void
     */
    function add_parent_data($accessToken, $parent) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . '/students/parents/add_a_parent',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $phoneNumber = $parent['EduParent']['sms_phone_number'];
		$maritalStatus = $parent['EduParent']['marital_status'] == 'S'? 'SINGLE': 'MARRIED';
		$portalRecordId = $parent['EduParent']['portal_record_id'] == ''? '00000000-0000-0000-0000-000000000000': $parent['EduParent']['portal_record_id'];
        
        // JSON payload for POST request
        $postData = json_encode(array(
            "phoneNumber" => $phoneNumber,	
            "maritalStatus" => $maritalStatus,
			"portalRecordId" => $portalRecordId
        ));

        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ));

        $response = curl_exec($curl);

        curl_close($curl);
		
		$response = json_decode($response, true);
		
		$this->log('postData', 'debug_push');
		$this->log($postData, 'debug_push');
        $this->log('response', 'debug_push');
        $this->log($response, 'debug_push');
		
		// response looks like
        /*
		(
			[statusCode] => 201
			[message] => Parent added successfully
			[body] => Array
				(
					[id] => 02f86f70-ca48-49ea-b85f-62e9f64a3b4f
					[phoneNumber] => +251 91 146 4381
					[maritalStatus] => MARRIED
				)
		)
        */
        $parentId = $response['body']['id'];
		$this->setSystemSetting('PARENT_ID', $parentId);

		$this->log('ParentId: ' . $parentId, 'debug_push');
		return $parentId;
    }

    /**
     * Adds a parent detail to the portal
     *
     * @param string $accessToken The access token to be used to authenticate the request to the portal.
     * @param array $parent_detail The parent detail to be added to the portal.
     * @param string $maritalStatus The marital status of the parent. Defaults to 'MARRIED'.
     * @param string $gender The gender of the parent. Defaults to 'MALE'.
     *
     * @return void
     */
    function add_parent_detail_data($accessToken, $parent_detail, $parent_id, $maritalStatus = 'MARRIED', $gender = 'MALE') {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . '/students/parents/add_a_parent_detail',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $firstName = $parent_detail['first_name'];
        $middleName = $parent_detail['middle_name'];
        $lastName = $parent_detail['last_name'];

        if($parent_detail['middle_name'] == 'NA') {
            $names = explode(" ", $firstName);
            if(count($names) > 1) {
                $firstName = $names[0];
                $middleName = $names[1];
            } 
            if(count($names) > 2) {
                $lastName = $names[2];
            }
        }

        // JSON payload for POST request
        $postData = json_encode(array(
            "firstName" => $firstName,	
            "middleName" => $middleName,
            "lastName" => $lastName,
            "email" => $parent_detail['email'],
            "phoneNumber" => $parent_detail['mobile'],
            "maritalStatus" => $maritalStatus,
            "gender" => $gender,
			"relationship" => strtoupper($parent_detail['relationship']),
			"parentId" => $parent_id
        ));

        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ));

        $response = curl_exec($curl);

        curl_close($curl);
		
		$response = json_decode($response, true);
		
		if($response['statusCode'] == 201) {
			$this->loadModel('EduParentDetail');
			$this->EduParentDetail->read(null, $parent_detail['id']);
			$this->EduParentDetail->set('portal_parent_detail_id', $response['body']['id']);
			$this->EduParentDetail->set('portal_user_id', $response['body']['authUserDTO']['id']);
			$this->EduParentDetail->save();
		}
		
		//$this->log($accessToken, 'debug_access_token_parent_detail_push');
		$this->log('Post Data', 'debug_push');
		$this->log($postData, 'debug_push');
		$this->log('Response', 'debug_push');
		$this->log($response, 'debug_push');
    }

    /**
     * Adds a parent detail to the portal
     *
     * @param string $accessToken The access token to be used to authenticate the request to the portal.
     * @param array $parent_detail The parent detail to be added to the portal.
     *
     * @return void
     */
    function add_student_data($accessToken, $student, $parentId) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . '/students/add_student',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $firstName = $student['name'];
        $middleName = 'NA';
        $lastName = 'NA';

        $names = explode(" ", $firstName);
        if(count($names) > 1) {
            $firstName = $names[0];
            $middleName = $names[1];
        } 
        if(count($names) > 2) {
            $lastName = $names[2];
        }

        $this->loadModel('Edu.EduRegistration');

        $reg = $this->EduRegistration->getLastRegistration($student['id']);
        $grade = '4';
        $section = 'A';
        $courses = array();
        $courses_ids = array();
        $registration = array();
        if($reg) {
            $grade = $reg['EduClass']['name'];
            $section = $reg['EduSection']['name'];
            $this->loadModel('Edu.EduAcademicYear');
            $this->loadModel('Status');
            
            $ay = $this->EduAcademicYear->getActiveAcademicYear();
            if($ay) {
                $registration['academicYear'] = $ay['EduAcademicYear']['name'];
            } else {
                $registration['academicYear'] = 'N/A';
            }
            $registration['recordId'] = $reg['EduRegistration']['id'];
            $registration['grade'] = $reg['EduClass']['name'];
            $registration['section'] = $reg['EduSection']['name'];
            $registration['grandTotalAverage'] = $reg['EduRegistration']['grand_total_average'];
            $registration['aCGPA'] = $reg['EduRegistration']['acgpa'];
            $status = $this->Status->read(null, $reg['EduRegistration']['status_id']);
            if(!$status) {
                $this->log('Status not found for registration: ' . $reg['EduRegistration']['id'], 'debug_push');
                $registration['status'] = 'N/A';
            } else {
                $this->log('Status found for registration: ' . $reg['EduRegistration']['id'], 'debug_push');
                $registration['status'] = $status['Status']['name'];
            }
            
            // collect the registration-quarter and the results, and set as $courses
            $this->loadModel('Edu.EduCourse');
            $this->loadModel('Edu.EduRegistrationQuarter');
            $this->loadModel('Edu.EduRegistrationQuarterResult');
            $this->loadModel('Edu.EduAssessmentRecord');
            $this->EduRegistrationQuarter->recursive = 2;

            $regQuarters = $this->EduRegistrationQuarter->find('all', array(
                'conditions' => array('EduRegistrationQuarter.edu_registration_id' => $reg['EduRegistration']['id'])
            ));
            $registrationQuarters = array();
            foreach ($regQuarters as $regQuarter) {
                $registrationQuarter = array(
                    'recordId' => $regQuarter['EduRegistrationQuarter']['id'],
                    'name' => $regQuarter['EduQuarter']['name'],
                    'average' => $regQuarter['EduRegistrationQuarter']['quarter_average'],
                    'total' => $regQuarter['EduRegistrationQuarter']['quarter_total'],
                    'cGPA' => $regQuarter['EduRegistrationQuarter']['cgpa'],
                    'absentees' => $regQuarter['EduRegistrationQuarter']['absentees'],
                    'parentComment' => $regQuarter['EduRegistrationQuarter']['parent_comment'],
                    'homeroomComment' => $regQuarter['EduRegistrationQuarter']['homeroom_comment']
                );

                $this->log('Processing registration quarter: ' . $regQuarter['EduQuarter']['name'], 'debug_push');

                $regQuarterResults = $regQuarter['EduRegistrationQuarterResult'];
                $registrationQuarterResults = array();
                foreach ($regQuarterResults as $rqr) {
                    $term_name = substr($regQuarter['EduQuarter']['short_name']);
                    $this->log('Processing course: ' . $rqr['EduCourse']['description'] . ' for quarter: ' . $term_name, 'debug_push');

                    $registrationQuarterResult = array();
                    $registrationQuarterResult['recordId'] = $rqr['id'];
                    $registrationQuarterResult['courseName'] = $rqr['EduCourse']['description'];
                    $registrationQuarterResult['courseResult'] = $rqr['course_result'];
                    $registrationQuarterResult['scaleResult'] = $rqr['scale_result'];
                    $registrationQuarterResult['courseRank'] = $rqr['course_rank'];
                    $registrationQuarterResult['resultIndicator'] = $rqr['result_indicator'];

                    $registrationQuarterResult['assessmentRecords'] = array();
                    /*$this->loadModel('Edu.EduAssessmentRecord');
                    $this->EduAssessmentRecord->recursive = 2;
                    $assessmentRecords = $this->EduAssessmentRecord->find('all', array(
                        'conditions' => array(
                            'EduAssessment.edu_course_id' => $rqr['EduCourse']['id'], 
                            'EduAssessmentRecord.edu_registration_id' => $reg['EduRegistration']['id'],
                            'EduAssessment.edu_quarter_id' => $regQuarter['EduQuarter']['id']
                        )
                    ));
                    foreach ($assessmentRecords as $ar) {
                        $registrationQuarterResult['assessmentRecords'][] = array(
                            'recordId' => $ar['EduAssessmentRecord']['id'],
                            'termName' => $term_name,
                            'name' => $ar['EduAssessment']['EduAssessmentType']['name'],
                            'mark' => $ar['EduAssessmentRecord']['mark'],
                            'maxValue' => $ar['EduAssessment']['max_value'],
                            'bonus' => $ar['EduAssessmentRecord']['bonus'],
                        );
                    }*/
                    $registrationQuarterResults[] = $registrationQuarterResult;
                }
                $registrationQuarter['registrationQuarterResults'] = $registrationQuarterResults;
                $registrationQuarters[] = $registrationQuarter;
            }
            $registration['registrationQuarters'] = $registrationQuarters;
            $this->log('Registration Quarters: ' . print_r($registrationQuarters, true), 'debug_push');
        }

        // Map registration quarters to their results

        // JSON payload for POST request
        $postData = json_encode(array(
            "recordId" => $student['id'],
            "idNumber" => $student['identity_number'],
            "firstName" => $firstName,	
            "middleName" => $middleName,
            "lastName" => $lastName, 
            "gender" => $student['gender'] == 'F'? 'FEMALE': 'MALE',
            "birthDate" => $student['birth_date'],
            "enrollmentDate" => $student['registration_date'],
            "currentGrade" => $grade,
            "currentSection" => $section,
            "registration" => $registration,
			"parentId" => $parentId
        ));

        $this->log('Final postData', 'debug_push');
        $this->log($postData, 'debug_push');

        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ));

        $response = curl_exec($curl);

        curl_close($curl);
		
		$response = json_decode($response, true);
		
		if($response['statusCode'] == 201) {
			$this->loadModel('EduStudent');
			$this->EduStudent->read(null, $student['id']);
			$this->EduStudent->set('portal_record_id', $response['body']['id']);
			$this->EduStudent->save();
            // registration
            $this->EduRegistration->read(null, $response['body']['registration']['recordId']);
            $this->EduRegistration->set('portal_record_id', $response['body']['registration']['id']);
            $this->EduRegistration->save();
            // registration quarters
            $rqs = $response['body']['registration']['registrationQuarters'];
            foreach($rqs as $rq) {
                $this->EduRegistrationQuarter->read(null, $rq['recordId']);
                $this->EduRegistrationQuarter->set('portal_record_id', $rq['id']);
                $this->EduRegistrationQuarter->save();
                // registration quarter results
                $rqrs = $rq['registrationQuarterResults'];
                foreach($rqrs as $rqr_item) {
                    $this->EduRegistrationQuarterResult->read(null, $rqr_item['recordId']);
                    $this->EduRegistrationQuarterResult->set('portal_record_id', $rqr_item['id']);
                    $this->EduRegistrationQuarterResult->save();
                    // assessment records
                    $ars = $rqr_item['assessmentRecords'];
                    foreach($ars as $ar) {
                        $this->EduAssessmentRecord->read(null, $ar['recordId']);
                        $this->EduAssessmentRecord->set('portal_record_id', $ar['id']);
                        $this->EduAssessmentRecord->save();
                    }
                }
            }
		}

        $this->log('Total response', 'debug_push');
        $this->log($response, 'debug_push');
    }

    /**
     * Pushes the parent student relation to the portal
     * ************* THIS CODE IS NTO WORKING FOR NOW *************
     *
     * @param string $studentIdNumber The id number of the student.
     * @param string $parentEmail The email address of the parent.
     * @param string $parentRelation The relation of the parent, either 'FATHER' or 'MOTHER'. Defaults to 'FATHER'.
     * @param string $accessToken The access token to be used to authenticate the request to the portal.
     *
     * @return void
     */
    function push_parent_student_relation($studentIdNumber = null, $parentEmail = null, $parentRelation = 'FATHER', $accessToken = null) {
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . '/students/add_parent_with_email',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        // JSON payload for POST request
        $postData = json_encode(array(
            "studentIdNumber" => $studentIdNumber,
            "parentEmail" => $parentEmail,	
            "parentRelation" => $parentRelation
        ));

        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $this->log('postData', 'debug_push');
        $this->log($postData, 'debug_push');
        $this->log('response', 'debug_push');
        $this->log($response, 'debug_push');
    }
    
    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Parent', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduParent->recursive = 2;
        $this->set('edu_parent', $this->EduParent->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->EduParent->create();
            $this->autoRender = false;
			$secret_code = rand(10000, 99999);
            $this->data['EduParent']['secret_code'] = $secret_code;
            $this->data['EduParent']['authorized_person'] = '-';
			
            if ($this->EduParent->save($this->data)) {
                $this->Session->setFlash(__('The Parent has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The Parent could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Parent', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduParent->save($this->data)) {
                $this->Session->setFlash(__('The Parent has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The Parent could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_parent', $this->EduParent->read(null, $id));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Parent', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduParent->delete($i);
                }
                $this->Session->setFlash(__('Parent deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Parent was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduParent->delete($id)) {
                $this->Session->setFlash(__('Edu parent deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Edu parent was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}
