<?php

class EduStudentsController extends EduAppController {

    public $name = 'EduStudents';
    public $dsEduParent;
    public $dsEduParentDetail;
    public $dsEduStudent;
    public $dsEduRegistration;
    public $dsEduPayment;
    public $dsEduRequiredDocument;
    public $dsEduPreviousSchool;
    public $dsEduEmergencyContact;
    public $dsEduSibling;
    public $dsEduStudentStatus;
	public $dsEduRegistrationQuarter;
	public $dsEduRegistrationQuarterResult;
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('get_enrollment_certificate', 'make_registration_corrections', 'students_per_section');
	}

	public function index() {
		$eduParents = $this->EduStudent->EduParent->find('all');
		$this->set('edu_parents', $eduParents);
	}

	public function index_v() {
		$this->loadModel('Status');
		$statuses = $this->Status->find('list', array('conditions' => array('Status.tables LIKE' => '%edu_students,%')));
		
		$this->set('statuses', $statuses);

	}

	public function index_o() {
		$eduParents = $this->EduStudent->EduParent->find('all');
		$this->set(compact('eduParents'));
	}

	public function index2($id = null) {
		$this->set('parent_id', $id);
	}
	
    public function search() {
        // this is a comment
    }
	
	public function students_per_section($id= null) {
		$conditions = array('EduRegistration.edu_section_id' => $id);
		$registrations = $this->EduStudent->EduRegistration->find('all', array(
            'conditions' => $conditions, 'order' => 'EduRegistration.name'));
		foreach ($registrations as $registration) {
			echo $registration['EduRegistration']['name'] . ',' . $registration['EduStudent']['identity_number'] . '<br/>,<br/>';
		}
	}

    public function list_data_section($id = null) {
        $conditions = array('EduRegistration.edu_section_id' => $id);

        $registrations = $this->EduStudent->EduRegistration->find('all', array(
            'conditions' => $conditions, 'order' => 'EduRegistration.name'));
		
        $this->set('results', count($registrations));
        $this->set('edu_registrations', $registrations);
    }

	public function student_profile_pdf($id) {
        $this->set('student_id', $id);
		
		$this->loadModel('Edu.EduQuarter');
        $this->loadModel('Edu.EduAcademicYear');
		
		$ays = $this->EduAcademicYear->find('list');

        $this->EduStudent->recursive = 2;
        $this->set('edu_student', $this->EduStudent->read(null, $id));
        $this->set('edu_quarters', $this->EduQuarter->find('list'));
        $this->set('ays', $ays);
		
        $this->set('company_url', Configure::read('company_url'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_tin', Configure::read('company_tin'));
        $this->set('company_address', Configure::read('company_address'));

        $baseUrl = 'http://' . Configure::read('domain') . Configure::read('localhost_string');
        $this->set('base_url', $baseUrl);
	}
	
    public function enrollment_certificate($id = null) {
        $eduStudentId = 0;
        if (!$id) {
            $eduStudentId = $this->Session->read('edu_student_id'); // at the time of receipt creation
            $this->Session->delete('edu_student_id');
        } else {
            $eduStudentId = $id; // at any time whenever print is needed
        }
        $this->set('student_id', $eduStudentId);

        $this->set('company_url', Configure::read('company_url'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_tin', Configure::read('company_tin'));
        $this->set('company_address', Configure::read('company_address'));

        $baseUrl = 'http://' . Configure::read('domain') . Configure::read('localhost_string');
        $this->set('base_url', $baseUrl);
    }

    public function get_enrollment_certificate($id = null) {
        $this->layout = 'ajax';

		$this->loadModel('EduEmergencyContact');
        $this->EduStudent->recursive = 3;
        $genders = array('M' => 'Male', 'F' => 'Female');
        $relationship = '';

        $student = $this->EduStudent->read(null, $id);
        $pp = $student['EduParent']['primary_parent'];
        $secretCode = $student['EduParent']['secret_code'];
        $primaryParent = '';
        $parents = array();
		
		$emergencyContacts = $this->EduEmergencyContact->find('all', array(
            'conditions' => array('EduEmergencyContact.edu_student_id' => $id)));

		$this->set('emergency_contacts', $emergencyContacts);
		
        foreach ($student['EduParent']['EduParentDetail'] as $p_detail) {
            if ($p_detail['family_type'] == $pp) {
                $primaryParent = $p_detail['first_name'] . ' ' . $p_detail['middle_name'];
                $relationship = $p_detail['relationship'];
            }
            $parents[$p_detail['family_type']] = array(
                'full_name' => $p_detail['first_name'] . ' ' . $p_detail['middle_name'],
                'address' => $p_detail['residence_address'],
                'telephone' => $p_detail['mobile']
            );
        }
        if (!isset($parents['M'])) {
            $parents['M'] = array('full_name' => ' - ', 'address' => ' - ', 'telephone' => ' - ');
        }
        if (!isset($parents['F'])) {
            $parents['F'] = array('full_name' => ' - ', 'address' => ' - ', 'telephone' => ' - ');
        }
        if (!isset($parents['G'])) {
            $parents['G'] = array('full_name' => ' - ', 'address' => ' - ', 'telephone' => ' - ');
        }

        $docs = array('vaccination' => 'NP', 'birth_certificate' => 'NP', 'report_card' => 'NP', 'clearance' => 'NP');
        foreach ($student['EduRegistration'][0]['EduRequiredDocument'] as $doc) {
            $docs[$doc['name']] = 'P';
        }

        $certificate = array();
        $certificate['Student'] = array(
            'enrollment_date' => date('F d, Y', strtotime($student['EduStudent']['registration_date'])),
            'last_modified' => date('F d, Y', strtotime($student['EduStudent']['modified'])),
			'status' => $student['Status']['name'],
            'primary_parent' => $primaryParent,
            'relationship' => $relationship,
            'authorized_person' => $student['EduParent']['authorized_person'],
            'name' => $student['EduStudent']['name'],
            'birth_date' => date('F d, Y', strtotime($student['EduStudent']['birth_date'])),
            'gender' => $genders[$student['EduStudent']['gender']],
            'identity_number' => $student['EduStudent']['identity_number'],
            'grade' => $student['EduRegistration'][0]['EduClass']['name'],
            'section' => ($student['EduRegistration'][0]['edu_section_id'] == 0 ? ' NA' :
                $student['EduRegistration'][0]['EduSection']['name']),
            'learning_condition' => $student['EduStudentCondition'][0]['learning_condition'],
            'health_condition' => $student['EduStudentCondition'][0]['health_condition'],
            'physical_condition' => $student['EduStudentCondition'][0]['physical_condition'],
            'docs' => $docs,
        );
        $certificate['Parents'] = $parents;
        $certificate['Parent']['secret_code'] = $secretCode;

        $this->set('certificate', $certificate);
        $this->set('company_url', Configure::read('company_url'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_tin', Configure::read('company_tin'));
        $this->set('company_address', Configure::read('company_address'));
    }

    public function search_students(){
        $students = $this->EduStudent->find('all', array());

        $this->set('results', count($students));
        $this->set('students', $students);
    }

    public function activate_student() {
        // this is a comment
    }
 
    public function transfer_student() {
        // this is a comment
    }

    public function withdraw_student() {
        // this is a comment
    }

    public function readmit_student(){
        // this is a comment
    }

    public function dismiss_student() {
        // this is a comment
    }

    public function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $eduParentId = (isset($_REQUEST['edu_parent_id'])) ? $_REQUEST['edu_parent_id'] : -1;
        if ($id) {
            $eduParentId = ($id) ? $id : -1;
        }
        $status = (isset($_REQUEST['status'])) ? $_REQUEST['status'] : -1;

        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';
		
        eval("\$conditions = array( " . $conditions . " );");
        if ($eduParentId != -1) {
            $conditions['EduStudent.edu_parent_id'] = $eduParentId;
        }
        if ($status != -1) {
            $conditions['EduStudent.status_id'] = $status;
        }
		$students = $this->EduStudent->find('all', array(
					'conditions' => $conditions,
					'limit' => $limit, 'offset' => $start,
					'order' => 'EduStudent.name ASC'
				));
		$this->loadModel('EduSection');
		
		foreach ($students as &$student) {
			$sid = $student['EduStudent']['id'];
			$registration = $this->EduStudent->EduRegistration->getLastRegistration($sid);
			if (!empty($registration)) {
				$this->EduStudent->read(null, $sid);
				$this->EduStudent->set('edu_class_id', $registration['EduRegistration']['edu_class_id']);
				$this->EduStudent->save();
				
				$student['EduStudent']['identity_number'] .= ' &gt; ' . $registration['EduRegistration']['edu_class_id'];
			}
		}
		
        $this->set('edu_students', $students);
        $this->set('results', $this->EduStudent->find('count', array('conditions' => $conditions)));
    }
	
	public function list_data_parent_students($id = null) {
        $eduParentId = (isset($_REQUEST['edu_parent_id'])) ? $_REQUEST['edu_parent_id'] : -1;
        if ($id) {
            $eduParentId = ($id) ? $id : -1;
        }
        
        $cond = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';
		eval("\$cond = array( " . $cond . " );");
        if ($eduParentId != -1) {
            $cond['EduStudent.edu_parent_id'] = $eduParentId;
        }
        $sts = $this->EduStudent->find('all', array('conditions' => $cond, 'order' => 'EduStudent.name ASC'));
        $this->set('edu_students', $sts);
        $this->set('results', count($sts));
    }
	
	public function list_data_students_per_ay($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $eduAcademicYearId = (isset($_REQUEST['edu_academic_year_id'])) ? $_REQUEST['edu_academic_year_id'] : -1;
        if ($id) {
            $eduAcademicYearId = ($id) ? $id : -1;
        }
        $status = (isset($_REQUEST['status'])) ? $_REQUEST['status'] : -1;

        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';
        eval("\$conditions = array( " . $conditions . " );");
        $conditions['EduStudent.edu_academic_year_id'] = $eduAcademicYearId;
        
        if ($status != -1) {
            $conditions['EduStudent.status_id'] = $status;
        }

        $student = $this->EduStudent->find('all', array(
            'conditions' => $conditions, 'limit' => $limit, 'offset' => $start,
            'order' => 'EduStudent.name ASC'
        ));
        $this->set('edu_students', $student);

        $this->set('results', $this->EduStudent->find('count', array('conditions' => $conditions)));
    }

    public function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid student', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->loadModel('Edu.EduQuarter');
        $this->loadModel('Status');
        $this->loadModel('Edu.EduAcademicYear');
		
		$ays = $this->EduAcademicYear->find('list');

        $this->EduStudent->recursive = 2;
        $this->set('edu_student', $this->EduStudent->read(null, $id));
        $this->set('edu_quarters', $this->EduQuarter->find('list'));
        $this->set('statuses', $this->Status->find('list'));
        $this->set('ays', $ays);
    }

    public function populate_parent_info($id = null) {
        $eduParent = $this->EduStudent->EduParent->read(null, $id);

        $this->set('edu_parent', $eduParent);
    }

    public function generate_id_number() {
        // read all records that have identity number pattern 'CSN/YY/%'
        $pattern = Configure::read('company_short_name') . date('y') . '%';
        $student = $this->EduStudent->find('first', array(
            'conditions' => array('EduStudent.identity_number LIKE' => $pattern),
            'order' => 'EduStudent.identity_number DESC'));
        $max = 0;
        if (!empty($student)) {
            //$parts = explode('/', $student['EduStudent']['identity_number']);
            // get the last 4 digits
            $max = substr(
                $student['EduStudent']['identity_number'],
                strlen($student['EduStudent']['identity_number']) - 4
            );
        }
        $max++;

        // to make the last compartment 4 digit number
        $next = '';
        if ($max < 1000) {
            $next .= '0';
        }
        if ($max < 100) {
            $next .= '0';
        }
        if ($max < 10) {
            $next .= '0';
        }
        $next .= $max;
        // construct the new IDNumber
        // $idNum = Configure::read('company_short_name') . date('y') . $next;

        return Configure::read('company_short_name') . date('y') . $next;
    }

    public function print_receipt() {
        $this->layout = 'ajax';

        $transaction = $this->Session->read('transaction');
        $studentId = $this->Session->read('edu_student_id');
        $student = $this->EduStudent->read(null, $studentId);

        $this->set('transaction', $transaction);
        $this->set('student', $student);
    }

    public function print_attachment($id = null) {
        $this->layout = 'print_layout';
        if (!$id) {
            $this->autoRender = false;
            $this->Session->setFlash(__('Invalid student ID', true), '');
            $this->render('/elements/failure');
        }
        $this->EduStudent->recursive = 2;
        $this->set('edu_student', $this->EduStudent->read(null, $id));
    }

    public function checkEverything() {
        $messages = '';
        /*try {
            $this->loadModel('Acct.AcctFiscalYear');

            $fy = $this->AcctFiscalYear->getActiveFiscalYear();
            if (empty($fy)) {
                $messages .= "There is no Active Fiscal Year!";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }*/
        return $messages;
    }

    public function loadRequiredModels() {
        $this->loadModel('Edu.EduPayment');
        $this->loadModel('Edu.EduReceipt');
        $this->loadModel('Edu.EduReceiptItem');
        $this->loadModel('Edu.EduRequiredDocument');
        $this->loadModel('Edu.EduParentDetail');
        $this->loadModel('Edu.EduPreviousSchool');
        $this->loadModel('Edu.EduEmergencyContact');
        $this->loadModel('Edu.EduSibling');
        $this->loadModel('Edu.EduStudentStatus');
		$this->loadModel('Edu.EduRegistrationQuarter');
        $this->loadModel('Edu.EduRegistrationQuarterResult');

        // This is not required if the accounting module is not applicable
        // for the respective school
        $this->loadModel('Acct.AcctTransaction');
        $this->loadModel('Acct.AcctJournal');
        $this->loadModel('Acct.AcctAccount');
    }

    public function instantiateModelObjects() {
        $this->dsEduParent = $this->EduStudent->EduParent->getDataSource();
        $this->dsEduParentDetail = $this->EduParentDetail->getDataSource();
        $this->dsEduStudent = $this->EduStudent->getDataSource();
        $this->dsEduRegistration = $this->EduStudent->EduRegistration->getDataSource();
        $this->dsEduPayment = $this->EduPayment->getDataSource();
        $this->dsEduRequiredDocument = $this->EduRequiredDocument->getDataSource();
        $this->dsEduPreviousSchool = $this->EduPreviousSchool->getDataSource();
        $this->dsEduEmergencyContact = $this->EduEmergencyContact->getDataSource();
        $this->dsEduSibling = $this->EduSibling->getDataSource();
        $this->dsEduStudentStatus = $this->EduStudentStatus->getDataSource();
        $this->dsEduRegistrationQuarter = $this->EduRegistrationQuarter->getDataSource();
        $this->dsEduRegistrationQuarterResult = $this->EduRegistrationQuarterResult->getDataSource();
		
        // This is not required if the accounting module is not applicable
        // for the respective school
        $this->dsAcctTransaction = $this->AcctTransaction->getDataSource();
        $this->dsAcctJournal = $this->AcctJournal->getDataSource();
        $this->dsAcctAccount = $this->AcctAccount->getDataSource();

        $this->dsEduReceipt = $this->EduReceipt->getDataSource();
        $this->dsEduReceiptItem = $this->EduReceiptItem->getDataSource();
    }

    public function beginTransactions() {
        $this->dsEduParent->begin($this->EduStudent->EduParent);
        $this->dsEduParentDetail->begin($this->EduParentDetail);
        $this->dsEduStudent->begin($this->EduStudent);
        $this->dsEduRegistration->begin($this->EduStudent->EduRegistration);
        $this->dsEduPayment->begin($this->EduPayment);
        $this->dsEduRequiredDocument->begin($this->EduRequiredDocument);
        $this->dsEduPreviousSchool->begin($this->EduPreviousSchool);
        $this->dsEduEmergencyContact->begin($this->EduEmergencyContact);
        $this->dsEduSibling->begin($this->EduSibling);
        $this->dsEduStudentStatus->begin($this->EduStudentStatus);
        $this->dsEduRegistrationQuarter->begin($this->EduRegistrationQuarter);
        $this->dsEduRegistrationQuarterResult->begin($this->EduRegistrationQuarterResult);

        $this->dsAcctTransaction->begin($this->AcctTransaction);
        $this->dsAcctJournal->begin($this->AcctJournal);
        $this->dsAcctAccount->begin($this->AcctAccount);

        $this->dsEduReceipt->begin($this->EduReceipt);
        $this->dsEduReceiptItem->begin($this->EduReceiptItem);
    }

    public function rollbackTransactions() {
        $this->dsEduParent->rollback($this->EduStudent->EduParent);
        $this->dsEduParentDetail->rollback($this->EduParentDetail);
        $this->dsEduStudent->rollback($this->EduStudent);
        $this->dsEduRegistration->rollback($this->EduStudent->EduRegistration);
        $this->dsEduPayment->rollback($this->EduPayment);
        $this->dsEduRequiredDocument->rollback($this->EduRequiredDocument);
        $this->dsEduPreviousSchool->rollback($this->EduPreviousSchool);
        $this->dsEduEmergencyContact->rollback($this->EduEmergencyContact);
        $this->dsEduSibling->rollback($this->EduSibling);
        $this->dsEduStudentStatus->rollback($this->EduStudentStatus);
        $this->dsEduRegistrationQuarter->rollback($this->EduRegistrationQuarter);
        $this->dsEduRegistrationQuarterResult->rollback($this->EduRegistrationQuarterResult);

        // for acct module
        $this->dsAcctTransaction->rollback($this->AcctTransaction);
        $this->dsAcctJournal->rollback($this->AcctJournal);
        $this->dsAcctAccount->rollback($this->AcctAccount);

        $this->dsEduReceipt->rollback($this->EduReceipt);
        $this->dsEduReceiptItem->rollback($this->EduReceiptItem);
    }

    public function commitTransactions() {
        $this->dsEduParent->commit($this->EduStudent->EduParent);
        $this->dsEduParentDetail->commit($this->EduParentDetail);
        $this->dsEduStudent->commit($this->EduStudent);
        $this->dsEduRegistration->commit($this->EduStudent->EduRegistration);
        $this->dsEduPayment->commit($this->EduPayment);
        $this->dsEduRequiredDocument->commit($this->EduRequiredDocument);
	    $this->dsEduPreviousSchool->commit($this->EduPreviousSchool);
        $this->dsEduEmergencyContact->commit($this->EduEmergencyContact);
        $this->dsEduSibling->commit($this->EduSibling);
        $this->dsEduStudentStatus->commit($this->EduStudentStatus);
        $this->dsEduRegistrationQuarter->commit($this->EduRegistrationQuarter);
        $this->dsEduRegistrationQuarterResult->commit($this->EduRegistrationQuarterResult);
		
        // for acct module
        $this->dsAcctTransaction->commit($this->AcctTransaction);
        $this->dsAcctJournal->commit($this->AcctJournal);
        $this->dsAcctAccount->commit($this->AcctAccount);

        $this->dsEduReceipt->commit($this->EduReceipt);
        $this->dsEduReceiptItem->commit($this->EduReceiptItem);
    }

    public function createNewParent() {
        $parentId = 0;
        try {
            
            $primary = $this->data['EduParent']['primary_parent'];
            $primaryParent = '';
            switch ($primary) {
                case 'M':
                    $primaryParent = 'mother';
                    break;
                case 'F':
                    $primaryParent = 'father';
                    break;
                case 'G':
                    $primaryParent = 'guardian';
                    break;
                default :
                    $primaryParent = 'mother';
            }

            $parent['EduParent'] = $this->data['EduParent'];
            $secretCode = rand(10000, 99999);
            $parent['EduParent']['secret_code'] = $secretCode;

            if (strpos($parent['EduParent']['sms_phone_number'], 'x') !== false) {
                $parent['EduParent']['sms_phone_number'] = 'NA';
            }

            $this->EduStudent->EduParent->create();
            if ($this->EduStudent->EduParent->save($parent)) {
                $parentId = $this->EduStudent->EduParent->id;
                // insert parent details
                $pTypes = array('mother', 'father', 'guardian');
                $fTypes = array('mother' => 'M', 'father' => 'F', 'guardian' => 'G');
                $pTypesIncluded = array();
				$this->log($this->data['EduParentDetail'], 'detail');
                foreach ($pTypes as $p_type) {
                    // prepare data
					if (isset($this->data['EduParentDetail'][$p_type . '_name1']) &&
                            $this->data['EduParentDetail'][$p_type . '_name1'] <> '' &&
                            $this->data['EduParentDetail'][$p_type . '_name1'] <> 'NA') {
						$pTypesIncluded[] = $p_type;
						$detail = array('EduParentDetail' => array());
						$detail['EduParentDetail']['first_name'] = $this->data['EduParentDetail'][$p_type . '_name1'];
						$detail['EduParentDetail']['middle_name'] = $this->data['EduParentDetail'][$p_type . '_name2'];
						$detail['EduParentDetail']['last_name'] = $this->data['EduParentDetail'][$p_type . '_name3'];
						$detail['EduParentDetail']['short_name'] = (isset($this->data['EduParentDetail'][$p_type . '_short_name']) &&
                            $this->data['EduParentDetail'][$p_type . '_short_name'] <> '' ?
                            $this->data['EduParentDetail'][$p_type . '_short_name'] : 'NA');

						$detail['EduParentDetail']['residence_address'] = $this->data['EduParentDetail'][$p_type . '_residence_address'];
						$detail['EduParentDetail']['country_of_birth'] = $this->data['EduParentDetail'][$p_type . '_country_of_birth'];
						$detail['EduParentDetail']['nationality'] = $this->data['EduParentDetail'][$p_type . '_nationality'];
						$detail['EduParentDetail']['occupation'] = $this->data['EduParentDetail'][$p_type . '_occupation'];
						$detail['EduParentDetail']['academic_qualification'] =
                            $this->data['EduParentDetail'][$p_type . '_academic_qualification'];
						$detail['EduParentDetail']['employment_status'] = $this->data['EduParentDetail'][$p_type . '_employment'];
						$detail['EduParentDetail']['employer'] = $this->data['EduParentDetail'][$p_type . '_employment_organization'];
						$detail['EduParentDetail']['mobile'] = $this->data['EduParentDetail'][$p_type . '_mobile'];
						$detail['EduParentDetail']['work_address'] =
                            ($this->data['EduParentDetail'][$p_type . '_work_address'] <> 'Work Address' ?
                            $this->data['EduParentDetail'][$p_type . '_work_address'] : 'NA');
						$detail['EduParentDetail']['work_telephone'] =
                            ($this->data['EduParentDetail'][$p_type . '_work_telephone'] <> 'Work Telephone' ?
                            $this->data['EduParentDetail'][$p_type . '_work_telephone'] : 'NA');
						$detail['EduParentDetail']['email'] =
                            ($this->data['EduParentDetail'][$p_type . '_email'] <> 'NA' ?
                            $this->data['EduParentDetail'][$p_type . '_email'] : 'NA');
						$detail['EduParentDetail']['family_type'] = $fTypes[$p_type];
						$detail['EduParentDetail']['relationship'] = ($p_type == 'guardian') ?
                            $this->data['EduParentDetail']['guardian_relationship'] : $p_type;
						$detail['EduParentDetail']['relationship_other'] =
                            ($p_type == 'guardian') ? $this->data['EduParentDetail']['guardian_relationship_other'] :
                            $p_type;
						$detail['EduParentDetail']['edu_parent_id'] = $parentId;

						$fileName = 'No file';
						if ($this->Session->check('photos')) {
							$photos = $this->Session->read('photos');
							
							foreach ($photos as $k => $photo) {
								if ($photo['relationship'] == $fTypes[$p_type]) {
									$fileName = $photo['photo_file'];
									
									// move the temporary photo file to the parents folder
									rename(IMAGES . "tmpphotos/" . $fileName, IMAGES . "parents/" . $fileName);
									
									break;
								}
							}
						}
						$detail['EduParentDetail']['photo_file'] = $fileName;

						$this->EduParentDetail->create();
						if (!$this->EduParentDetail->save($detail)) {
							$this->log('ERROR: ' . pr($this->EduParentDetail->validationErrors, true), 'debug');
							@unlink(IMAGES . 'parents' . DS . $fileName);
						}
                    }
                }

                if (!in_array($primaryParent, $pTypesIncluded)) {
                    $this->log(
                        'PP [' . $primaryParent . '] is not included in [' . join(', ', $pTypesIncluded) . '].',
                        'debug'
                    );
                    $this->Session->write('msg', 'Primary parent is not included in the parent details list.');
                    return false;
                }
            } else {
                $this->log('Code: ' . $this->EduParentDetail->validationErrors, 'debug');
            }

            return $parentId;
        } catch (Exception $ex) {
            $this->log('Code: ' . $ex->getCode() . ' and MSG: ' . $ex->getMessage(), 'debug');
            return false;
        }
    }

	public function savePreviousSchoolData($eduStudentId) {
		if ($this->Session->check('prev_schools')) {
			$prevSchools = $this->Session->read('prev_schools');
			
			foreach ($prevSchools as $k => $prev_school) {
				$ps = array('EduPreviousSchool' => $prev_school);
				$ps['EduPreviousSchool']['edu_student_id'] = $eduStudentId;
				
				$this->EduPreviousSchool->create();
				$this->EduPreviousSchool->save($ps);
			}
			
			$this->Session->write('prev_schools', array());
		}
	}
	
	public function saveEmergencyContactData($eduStudentId) {
		if ($this->Session->check('emergency_contacts')) {
			$emergencyContacts = $this->Session->read('emergency_contacts');
			
			foreach ($emergencyContacts as $k => $emergency_contact) {
				$ps = array('EduEmergencyContact' => $emergency_contact);
				$ps['EduEmergencyContact']['edu_student_id'] = $eduStudentId;
				
				$this->EduEmergencyContact->create();
				$this->EduEmergencyContact->save($ps);
			}
			
			$this->Session->write('emergency_contacts', array());
		}
	}
	
	public function saveSiblingData($eduStudentId) {
		if ($this->Session->check('siblings')) {
			$siblings = $this->Session->read('siblings');
			
			foreach ($siblings as $sibling) {
				$ps = array('EduSibling' => $sibling);
				$ps['EduSibling']['edu_student_id'] = $eduStudentId;
				
				$this->EduSibling->create();
				$this->EduSibling->save($ps);
			}
			
			$this->Session->write('siblings', array());
		}
	}
	
    public function enrollment() {
        if (!empty($this->data)) {
            $this->autoRender = false; $this->layout = 'ajax';

            $this->log($this->data, 'enrollment');
            // 1. if there is no active fiscal year
            $messages = $this->checkEverything();
            if ($messages != '') {
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'The student could not be enrolled. <br/>ERROR: ' . $messages . ' (ERR-101-02)',
                    'helpcode' => 'ERR-101-02'));
            }
            $success = false;
            $this->loadRequiredModels(); $this->instantiateModelObjects(); $this->beginTransactions();
            
            // Replace NA to all fields that are with the Place holder values.
            foreach ($this->data['EduStudent'] as &$recField) {
                if (!is_array($recField) && strpos($recField, '[') !== false) {
                    $recField = 'NA';
                }
				$recField = $this->clearTextForDB($recField);
            }
            if (isset($this->data['EduParent'])){
                foreach ($this->data['EduParent'] as &$recField2) {
					if(!is_array($recField2) && strpos($recField2, '[') !== false) {
                        $recField2 = 'NA';
                    }
					// replace any character that can create any problem if inserted into db
					$recField2 = $this->clearTextForDB($recField2);
                }
            }
            
            if (isset($this->data['EduParentDetail'])){
                foreach ($this->data['EduParentDetail'] as &$recField3) {
                    if (!is_array($recField3) && strpos($recField3, '[') !== false) {
                        $recField3 = 'NA';
                    }
                }
				// replace any character that can create any problem if inserted into db
				$recField3 = $this->clearTextForDB($recField);
            }

            $this->data['EduStudent']['name'] = strtoupper($this->data['EduStudent']['name']);
            $this->data['EduPayment']['crm_number'] =
                (isset($this->data['EduPayment']['crm_number']) && $this->data['EduPayment']['crm_number'] != '') ?
                $this->data['EduPayment']['crm_number'] :
                'AUTO-' . time();

            $parentId = 0;

            // if existing parent
            if ($this->data['EduStudent']['edu_parent_id'] <> '0' && $this->data['EduStudent']['edu_parent_id'] <> 'New Parent') {
                preg_match('/\((\d+)\)/', $this->data['EduStudent']['edu_parent_id'], $matches);
                $number = isset($matches[1]) ? $matches[1] : null;

                $this->data['EduStudent']['edu_parent_id'] = $number; // Outputs: 12
                $parentId = $number;

                //$this->log('selected parent id: ' . $number, 'enrollment');

                $p = $this->EduStudent->EduParent->find('first', array(
                    'conditions' => array(//'EduParent.id' => 1 // this is for one planet case
                        'OR' => array(
                            'LOWER(EduParent.authorized_person)' => strtolower($this->data['EduStudent']['edu_parent_id']),
                            'EduParent.id' => $this->data['EduStudent']['edu_parent_id'])
                    )
                ));
                //$this->log($p, 'enrollment');
                if(!empty($p)) {
                    $parentId = $p['EduParent']['id'];
                    $this->data['EduParent']['primary_parent'] = $p['EduParent']['primary_parent'];
                
                    $pp = $this->data['EduParent']['primary_parent'];
                    $primaryParent = '';
                    switch ($pp) {
                        case 'M':
                            $primaryParent = 'mother';
                            break;
                        case 'F':
                            $primaryParent = 'father';
                            break;
                        default :
                            $primaryParent = 'guardian';
                    }
                    if (!empty($p['EduParentDetail'])) {
                        foreach ($p['EduParentDetail'] as $pd) {
                            if ($pd['relationship'] == $primaryParent) {
                                $this->data['EduParentDetail'][$primaryParent . '_name1'] = $pd['first_name'];
                                $this->data['EduParentDetail'][$primaryParent . '_name2'] = $pd['middle_name'];
                                $this->data['EduParentDetail'][$primaryParent . '_name3'] = $pd['last_name'];
                                $this->data['EduParentDetail'][$primaryParent .
                                    '_residence_address'] = $pd['residence_address'];
                                $this->data['EduParentDetail'][$primaryParent . '_mobile'] = $pd['mobile'];

                                break;
                            }
                        }
                    }
                }
                $success = true;
            } else {
                $parentId = $this->createNewParent();
                if ($parentId === false) {
                    $this->rollbackTransactions();
                    $this->cakeError('cannotSaveRecord', array(
                        'message' => 'The parent could not be saved. Please, try again. (ERR-101-02)',
                        'helpcode' => 'ERR-101-02'));
                }
            }
            
            $classId = $this->data['EduStudent']['edu_class_id'];
            unset ($this->data['EduStudent']['edu_class_id']);
			
			// Included for OnePlanet to allow late migration
	        $eduAcademicYearId = $this->data['EduStudent']['edu_academic_year_id'];
            
            $student['EduStudent'] = $this->data['EduStudent'];
            $student['EduStudent']['registration_date'] = $this->today();
            $student['EduStudent']['identity_number'] = $this->generate_id_number();
            $student['EduStudent']['edu_parent_id'] = $parentId;
            $student['EduStudent']['maker_id'] = $this->Session->read('Auth.User.id');
            $student['EduStudent']['status_id'] = 19; // R - Registered
            
            $fileName = 'No file';
	        if ($this->Session->check('photos')) {
		        $photos = $this->Session->read('photos');
		
                foreach ($photos as $k => $photo) {
                    if ($photo['relationship'] == 'S') {
                        $fileName = $photo['photo_file'];
                        rename(IMAGES . "tmpphotos" . DS . $fileName, IMAGES . "students" . DS . $fileName);
                        break;
                    }
                }
	        }
            $student['EduStudent']['photo_file_name'] = $fileName;
            if (isset($this->data['EduStudent']['include_registration'])) {
                $student['EduStudent']['status_id'] = 19; // R - Enrolled and registered
            }
			$this->loadModel('EduQuarter');
			$currentQuarter = $this->EduQuarter->getActiveQuarter();
			
			$student['EduStudent']['status_id'] = 1; // Active
			
            $this->log($student, 'enrollment');

            // reached here
            $this->EduStudent->create();
            if ($this->EduStudent->save($student)) {
				// Save student status change history
				$status = array('EduStudentStatus' => array(
					'edu_student_id' => $this->EduStudent->id,
					'status_id' => $student['EduStudent']['status_id'],
					'user_id' => $this->Session->read('Auth.User.id'),
					'remark' => 'Enrollment and Registration'
				));

                //$this->log($status, 'enrollment');
				
				$this->EduStudentStatus->create();
				if(!$this->EduStudentStatus->save($status)) {
                    $this->log(__('EduStudentStatus could not be saved. Please, try again.' . $this->EduStudentStatus->validationErrors, true), 'enrollment_err');
                }
		
                // Now save the first registration record
                $registration = array('EduRegistration' => array());
                $registration['EduRegistration']['name'] = $this->data['EduStudent']['name'];
                $registration['EduRegistration']['edu_student_id'] = $this->EduStudent->id;
                $registration['EduRegistration']['edu_class_id'] = $classId;
                $registration['EduRegistration']['edu_section_id'] =
                     (isset($student['EduStudent']['edu_section_id']) &&
                     $student['EduStudent']['edu_section_id'] != '')?
                $student['EduStudent']['edu_section_id']: 0; // Not sectioned yet
                $registration['EduRegistration']['edu_campus_id'] = $this->Session->read('Auth.User.edu_campus_id');

                $registration['EduRegistration']['status_id'] = 1; // Active

                $this->log($registration, 'enrollment');

                $this->EduStudent->EduRegistration->create();
                if(!$this->EduStudent->EduRegistration->save($registration)) {
                    $this->log(__('EduStudent->EduRegistration could not be saved. Please, try again.' . $this->EduStudent->EduRegistration->validationErrors, true), 'enrollment_err');
                }
				
				// Save the Previous School Data if any existed in the session.
				$this->savePreviousSchoolData($this->EduStudent->id);
				
				// Save the Emergency Contact Data if any existed in the session.
				$this->saveEmergencyContactData($this->EduStudent->id);
				
				// Save the Emergency Contact Data if any existed in the session.
				$this->saveSiblingData($this->EduStudent->id);
				
                $regId = $this->EduStudent->EduRegistration->id;
                $this->saveRegistrationQuarters($regId);

                // Register Student Conditions
                $sc = array('EduStudentCondition' => array());
                $sc['EduStudentCondition']['edu_student_id'] = $this->EduStudent->id;
                $sc['EduStudentCondition']['learning_condition'] =
                    isset($this->data['EduStudentCondition']['normal_learing_condition']) ?
                    'Normal' : $this->data['EduStudentCondition']['special_learning_need'];
                $sc['EduStudentCondition']['reading'] =
                    isset($this->data['EduStudentCondition']['reading']) ? true : false;
                $sc['EduStudentCondition']['math'] = isset($this->data['EduStudentCondition']['math']) ? true : false;
                $sc['EduStudentCondition']['language'] =
                    isset($this->data['EduStudentCondition']['language']) ? true : false;
                $sc['EduStudentCondition']['behavioral'] =
                    isset($this->data['EduStudentCondition']['behavioral']) ? true : false;
                $sc['EduStudentCondition']['health_condition'] =
                    isset($this->data['EduStudentCondition']['normal_health_condition']) ? 'Normal' : 'Not Normal';
                $sc['EduStudentCondition']['treatment_type'] = $this->data['EduStudentCondition']['treatment_type'];
                $sc['EduStudentCondition']['health_care_institute'] =
                    $this->data['EduStudentCondition']['health_care_institute'];
                $sc['EduStudentCondition']['physician'] = $this->data['EduStudentCondition']['physician'];
                $sc['EduStudentCondition']['alergy'] = $this->data['EduStudentCondition']['alergy'];
                $sc['EduStudentCondition']['physical_condition'] =
                    isset($this->data['EduStudentCondition']['normal_physical_condition']) ?
                    'Normal' : $this->data['EduStudentCondition']['physically_disabled'];

                $this->log($sc, 'enrollment');

                $this->EduStudent->EduStudentCondition->create();
                if(!$this->EduStudent->EduStudentCondition->save($sc)) {
                    $this->log(__('EduStudent->EduStudentCondition could not be saved. Please, try again.' . $this->EduStudent->EduStudentCondition->validationErrors, true), 'enrollment_err');
                }

				// this part is not working
                /*if (isset($this->data['EduSibling'])){
                    $this->log('Add siblings', 'enrollment');

                    $siblings = $this->data['EduSibling'];
                    for ($i = 1; $i <= 6; $i++) {
                        if ($siblings['brother_name' . $i] <> ''){
                            $sibling = array('EduSibling' => array(
                                'name' => $siblings['brother_name' . $i],
                                'age' => $siblings['brother_age' . $i],
                                'grade' => $siblings['brother_grade' . $i],
                                'sex' => $siblings['brother_sex' . $i],
                                'edu_student_id' => $this->EduStudent->id
                            ));
                            
                            $this->EduStudent->EduSibling->create();
                            $this->EduStudent->EduSibling->save($sibling);
                        }
                    }
                }

                if (isset($this->data['EduDocument'])) {
                    foreach ($this->data['EduDocument'] as $k => $v) {
                        $this->EduRequiredDocument->create();
                        $requiredDoc = array('EduRequiredDocument' => array());
                        $requiredDoc['EduRequiredDocument']['name'] = $k;
                        $requiredDoc['EduRequiredDocument']['edu_registration_id'] = $regId;
                        $requiredDoc['EduRequiredDocument']['presented'] = 1;

                        if(!$this->EduRequiredDocument->save($requiredDoc)) {
                            $this->log('Error in saving required docs', 'enrollment_err');
                            $this->log($this->EduRequiredDocument->validationErrors, 'enrollment_err');
                        }
                    }
                }*/
				
				/*$this->loadModel('EduRegistrationPreference');
				$this->loadModel('EduExtraPaymentSetting');
				
				if (isset($this->data['EduExtraPaymentType'])) {
					$extraPaymentSettings = $this->EduExtraPaymentSetting->find('all', array(
						'conditions' => array(
							'edu_class_id' => $classId,
							'edu_academic_year_id' => $currentQuarter['EduAcademicYear']['id']
						)
					));
					if (!empty($extraPaymentSettings)) {
						$epts = array();
						foreach ($extraPaymentSettings as $eps) {
							$eptId = $eps['EduExtraPaymentSetting']['edu_extra_payment_type_id'];
							if (in_array($eptId, $epts)) continue;
							$epts[] = $eptId;
							$this->EduRegistrationPreference->create();
							$rp = array('EduRegistrationPreference' => array());
							$rp['EduRegistrationPreference']['edu_registration_id'] = $regId;
							$rp['EduRegistrationPreference']['edu_extra_payment_type_id'] = $eptId;
							$rp['EduRegistrationPreference']['is_applicable'] = (isset($this->data['EduExtraPaymentType'][$eptId])? 1: 0);
							
							$this->EduRegistrationPreference->save($rp);
						}
					}
                }*/

                $this->log('The student user has been saved', 'enrollment_err');
                $this->Session->setFlash(__('The student user has been saved', true));
                $success = true;
            } else {
                $this->log('EduStudent could not be saved. Please, try again.', 'enrollment_err');
                $this->log($this->EduStudent->validationErrors, 'enrollment_err');
                $this->Session->setFlash(__('The student could not be saved. Please, try again.', true), '');
                $success = false;
            }

            if ($success) {
                // save payment
                /*$paymentData = $this->data['EduPayment'];
                $transaction = array(
                    'dr_acct_code' => $this->getSystemSetting('CASH_GL_ACCOUNT'),
                    'cr_acct_code' => $this->getSystemSetting('TUITION_GL_ACCOUNT'),
                    'value' => 1000; //$paymentData['amount'] - $paymentData['discount'],
                    'description' => $this->clearTextForDB($paymentData['description'] .
                        ' of student ' . $this->data['EduStudent']['name']),
                    'cheque_number' => (isset($paymentData['cheque_number']) ? $paymentData['cheque_number'] : 'NA'),
                    'invoice_number' => $paymentData['crm_number'], // should be corrected in the ACCT plugin
                    'return' => ''
                );

                $this->Session->write('transaction', $transaction);

                // Request the accounting module to save the transaction
                $ret = $this->requestAction(
                    array('controller' => 'acct_transactions', 'action' => 'save_transaction', 'plugin' => 'acct'),
                    array('pass' => $transaction)
                );*/
                $ret = true;

                if ($ret) {
                    /*$transaction = $this->Session->read('transaction');

                    // Save the payment in the edu payment module (record).
                    $payment = array('EduPayment' => array(
                            'edu_payment_schedule_id' => -1,
                            'edu_student_id' => $this->EduStudent->id,
                            'is_paid' => 1,
                            'date_paid' => $this->today(),
                            'paid_amount' => $paymentData['amount'] - $paymentData['discount'],
                            'cheque_number' => (isset($paymentData['cheque_number']) ?
                                $paymentData['cheque_number'] : 'NA'),
                            'invoice' => $paymentData['crm_number'],
                            'transaction_ref' => $transaction['return']
                    ));

                    //////// Make the payment record two if the include_registration is selected
                    // otherwise make it only one record.

                    $this->EduPayment->create();
                    $this->EduPayment->save($payment);

                    // Save Receipt Info
                    $this->loadModel('Edu.EduReceipt');
                    $this->loadModel('Edu.EduAcademicYear');
                    // Restart Invoice Number for new Academic year
                    $ay = $this->EduAcademicYear->getActiveAcademicYear();
                    $cond = array('EduReceipt.invoice_date >=' => $ay['EduAcademicYear']['start_date'],
                        'EduReceipt.invoice_date <=' => $ay['EduAcademicYear']['end_date']);
                    $re = $this->EduReceipt->find('first', array(
                        'conditions' => $cond, 'order' => 'EduReceipt.reference_number DESC')
                    );
                    $referenceNumber = 1;
                    if (!empty($re)) {
                        $referenceNumber = $re['EduReceipt']['reference_number'] + 1;
                    }

                    $this->EduStudent->recursive = 3;
                    $student = $this->EduStudent->read(null, $this->EduStudent->id);

                    $primary = $this->data['EduParent']['primary_parent'];
                    $primaryParent = '';
                    switch ($primary) {
                        case 'M':
                            $primaryParent = 'mother';
                            break;
                        case 'F':
                            $primaryParent = 'father';
                            break;
                        default :
                            $primaryParent = 'guardian';
                    }

                    $parentName = '';
                    if($this->data['EduParentDetail'][$primaryParent . '_name1'] != 'NA'){
                        $parentName .= $this->data['EduParentDetail'][$primaryParent . '_name1'];
                    }
                    if($this->data['EduParentDetail'][$primaryParent . '_name2'] != 'NA'){
                         $parentName .= ' ' . $this->data['EduParentDetail'][$primaryParent . '_name2'];
                    }
                    if($this->data['EduParentDetail'][$primaryParent . '_name3'] != 'NA'){
                         $parentName .= ' ' . $this->data['EduParentDetail'][$primaryParent . '_name3'];
                    }

                    $parentName = str_replace('First Name', '', $parentName);
                    $parentName = str_replace('Middle Name', '', $parentName);
                    $parentName = str_replace('Last Name', '', $parentName);
                    $parentName = str_replace('  ', ' ', $parentName);
                    $parentName = trim($parentName);
                    $pam = $this->data['EduParentDetail'][$primaryParent . '_mobile'];

                    $receipt = array(
                        'EduReceipt' => array(
                            'reference_number' => $referenceNumber,
                            'invoice_date' => $this->today(),
                            'crm_number' => $paymentData['crm_number'],
                            'parent_name' => $parentName,
                            'parent_address' => $this->data['EduParentDetail'][$primaryParent .
                                '_residence_address'] . '<br>' . ($pam == 'NA'? '': $pam),
                            'edu_student_id' => $this->EduStudent->id,
                            'student_name' => $student['EduStudent']['name'],
                            'student_number' => $student['EduStudent']['identity_number'],
                            'student_class' => $student['EduRegistration'][0]['EduClass']['name'],
                            'student_section' => (isset($student['EduRegistration'][0]['EduSection']['name']) ?
                                $student['EduRegistration'][0]['EduSection']['name'] : 'Not Set'),
                            'student_academic_year' => $ay['EduAcademicYear']['name'],
                            'total_before_tax' => $paymentData['amount'] - $paymentData['discount'],
                            'total_after_tax' => $paymentData['amount'] - $paymentData['discount'],
                            'VAT' => 0,
                            'TOT' => 0
                        )
                    );

                    $this->EduReceipt->create();
                    if ($this->EduReceipt->save($receipt)) {
                        $item = array('name' => 'Enrollment and Registration Payment',
                            'amount' => $paymentData['amount'], 'edu_receipt_id' => $this->EduReceipt->id);
                        $this->EduReceipt->EduReceiptItem->create();
                        $this->EduReceipt->EduReceiptItem->save($item);
						
						$itemD = array('name' => '&nbsp;&nbsp;&nbsp;&nbsp;Discount',
                            'amount' => -1 * $paymentData['discount'], 'edu_receipt_id' => $this->EduReceipt->id);
                        $this->EduReceipt->EduReceiptItem->create();
                        $this->EduReceipt->EduReceiptItem->save($itemD);
						
                        $receiptId = $this->EduReceipt->id;
                        $this->Session->write('edu_receipt_id', $receiptId);
                    } else {
                        $this->log('Receipt Return: ' . pr($this->EduReceipt->validationErrors, true), 'debug');
                    }*/
                    $this->Session->write('edu_student_id', $this->EduStudent->id);

                    // Commit all transactions here
                    $this->commitTransactions();

                    /* SEND SMS and Email MESSAGE TO THE PARENT */
                    /*$pp = $this->EduStudent->EduParent->read(null, $parent_id);

                    $secretCode = $pp['EduParent']['secret_code'];

                    $primaryName = '';

                    foreach ($pp['EduParentDetail'] as $pd) {
                        if($primaryName == ''){
                            $primaryName = $pd['short_name'];
                        } else {
                            $primaryName .= ' and ' . $pd['short_name'];
                        }
                    }
                    $this->loadModel('MessageTemplate');

                    $msgTmpl = $this->MessageTemplate->read(null, 1);

                    $msg = $msgTmpl['MessageTemplate']['body'];
                    $msg = str_replace('{{1}}', $primaryName, $msg);

                    $to = $pp['EduParent']['sms_phone_number'] != 'NA'? $pp['EduParent']['sms_phone_number']: '';
                    if($to != '' && $to != 'NA'){
                        $this->queueSMSMessage($to, $msg);
                    }
                    foreach ($pp['EduParentDetail'] as $pd) {
                        if($pd['email'] != 'NA'){
                            $to = $pd['email'];
                            $this->queueSMSMessage($to, $msg);
                        }
                    }*/

                    $this->Session->setFlash(__('The student has been successfully enrolled', true), '');
                    $this->render('/elements/success');
                } else {
                    $transaction = $this->Session->read('transaction');
                    $err = $transaction['return'];
                    // rollback transactions here
                    $this->rollbackTransactions();
                    $this->cakeError('cannotSaveRecord', array(
                        'message' => 'The student could not be enrolled. <br>ERROR: ' . $err . ' (ERR-101-03)',
                        'helpcode' => 'ERR-101-03'));
                }
            } else {
                $this->rollbackTransactions();
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'The student could not be enrolled . Please, try again. (ERR-101-04)',
                    'helpcode' => 'ERR-101-04'));
            }
        }
        // to the form
        $eduPs = $this->EduStudent->EduParent->find('all');
        $eduParents = array();
        foreach ($eduPs as $pr) {
            $primary = $pr['EduParent']['primary_parent'];
            $primary = $primary == 'M'? 'mother': ($primary == 'F'? 'father': 'guardian');
            foreach ($pr['EduParentDetail'] as $pd) {
                if ($pd['relationship'] == $primary) {
                    $eduParents[$pr['EduParent']['id']] =
                        $pd['first_name'] . ' ' . $pd['middle_name'] . ' ' .
                        ($pd['last_name'] == 'NA'? '': $pd['last_name']) . ' (' .$pr['EduParent']['id'] . ')';
                }
            }
        }
        asort($eduParents);
        $this->loadModel('EduClass');
        $classes = $this->EduClass->find('all');
		
		if ($this->Session->check('prev_schools')) {
			$this->Session->write('prev_schools', array());
		}
		
		if ($this->Session->check('emergency_contacts')) {
			$this->Session->write('emergency_contacts', array());
		}
		if ($this->Session->check('photos')) {
			$this->Session->write('photos', array());
		}
		if ($this->Session->check('siblings')) {
			$this->Session->write('siblings', array());
		}
		
        $classPayments = array();
        $eduClasses = array();
        $this->loadModel('EduClassPayment');
        $this->loadModel('EduAcademicYear');
        $this->loadModel('EduSection');
		
		$classPayments = $this->EduClassPayment->find('all');
		
        $ay = $this->EduAcademicYear->getActiveAcademicYear();

		$academicYears = $this->EduAcademicYear->find('list', array(
            'conditions' => array('EduAcademicYear.status_id' => array(1, 8)),
            'order' => 'EduAcademicYear.start_date DESC'));
		
		$this->set('academic_years', $academicYears);
		$this->set('academic_year_id', $ay['EduAcademicYear']['id']);
		
		$sections = $this->EduSection->find('list', array('conditions' => array(
			'EduSection.edu_academic_year_id' => $ay['EduAcademicYear']['id'],
			'EduSection.edu_campus_id' => $this->Session->read('Auth.User.edu_campus_id'))));
		$this->set('sections', $sections);
		
        foreach ($classes as $class) {
            $found = true;
            /*foreach ($class['EduPaymentSchedule'] as $ps) {
                if ($ps['edu_academic_year_id'] == $ay['EduAcademicYear']['id']) {
                    $found = true;
                    break;
                }
            }*/
            if ($found) {
                $eduClasses[$class['EduClass']['id']] = $class['EduClass']['name'];
            } else {
                $eduClasses['abc' . $class['EduClass']['id']] =
                    '<font color=red style="text-decoration: line-through">' . $class['EduClass']['name'] . '</font>';
            }
        }
        
        $this->log('After class data prepared: ' . time(), 'timing');

        $this->loadModel('Country');

        $countries = $this->Country->find('all');
        $nationalities = array();
		$counts = array();
        foreach ($countries as $country) {
            $nationalities[$country['Country']['nationality']] = $country['Country']['nationality'];
            $counts[$country['Country']['name']] = $country['Country']['name'];
        }
		$countries = $counts;
        $this->loadModel('SubCity');

        $scities = $this->SubCity->find('all', array('order' => 'SubCity.name ASC'));
        $subCities = array();
        foreach ($scities as $scity) {
            $subCities[$scity['SubCity']['name']] = $scity['SubCity']['name'];
        }
		$isChequePaymentAllowed = $this->getSystemSetting('RECEIVE_PAYMENT_BY_CHEQUE');
		$this->loadModel('EduQuarter');
        $currentQuarter = $this->EduQuarter->getActiveQuarter();
		$this->set('current_quarter', $currentQuarter);
		
		$this->loadModel('EduExtraPaymentType');
		$eduExtaPaymentTypes = $this->EduExtraPaymentType->find('list');

        $this->set('edu_parents', $eduParents);
        $this->set('edu_classes', $eduClasses);
        $this->set('nationalities', $nationalities);
        $this->set('countries', $countries);
        $this->set('sub_cities', $subCities);
        $this->set('class_payments', $classPayments);
        $this->set('is_cheque_payment_allowed', $isChequePaymentAllowed);
        $this->set('edu_exta_payment_types', $eduExtaPaymentTypes);
    }

    function saveRegistrationQuarters($id) {
        //$this->log('inside id: ' . $id, 'debug');
        $reg = $this->EduStudent->EduRegistration->read(null, $id);
        $this->loadModel('Edu.EduQuarter');
        $this->loadModel('Edu.EduAcademicYear');
        $this->loadModel('Edu.EduRegistrationQuarter');
        $this->loadModel('Edu.EduRegistrationQuarterResult');
		
		$this->log('The method is called.', 'enrollment_2');

        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $ay_id = $ay['EduAcademicYear']['id'];
        $this->log('inside ay: ' . $ay_id, 'enrollment_2');

        $quarters = $this->EduQuarter->find('all', array('conditions' => array(
                'EduQuarter.edu_academic_year_id' => $ay_id,
                'EduQuarter.quarter_type' => 'E')));
        $this->log($quarters, 'enrollment_2');

        foreach ($quarters as $quarter) {
            $reg_q = array('EduRegistrationQuarter' => array(
                    'edu_registration_id' => $id,
                    'edu_quarter_id' => $quarter['EduQuarter']['id'],
                    'quarter_average' => 0,
                    'quarter_rank' => 0,
                    'class_rank' => 0
            ));
            $this->EduRegistrationQuarter->create();
            if ($this->EduRegistrationQuarter->save($reg_q)) {
                $class = $this->EduStudent->EduRegistration->EduClass->read(null, $reg['EduRegistration']['edu_class_id']);
                foreach ($class['EduCourse'] as $course) {
                    $rqr = array('EduRegistrationQuarterResult' => array(
                            'edu_registration_quarter_id' => $this->EduRegistrationQuarter->id,
                            'edu_course_id' => $course['id'],
                            'course_result' => 0,
                            'course_rank' => 0,
                            'result_indicator' => 'N'
                    ));

                    $this->EduRegistrationQuarterResult->create();
                    if (!$this->EduRegistrationQuarterResult->save($rqr)) {
                        $this->log('ERROR (EduRegistrationQuarterResult): ', 'enrollment_2');
                    } //pr($this->EduRegistrationQuarterResult->validationErrors, true)
                }
            } else {
                $this->log('ERROR (EduRegistrationQuarter): ', 'enrollment_2');
            } //  . pr($this->EduRegistrationQuarter->validationErrors, true)
        }
    }

    function make_registration_corrections($section_id) {
        $this->loadModel('Edu.EduRegistrationQuarter');
        $registrations = $this->EduStudent->EduRegistration->find('all', array(
            'conditions' => array('EduRegistration.edu_section_id' => $section_id)
        ));
        foreach($registrations as $reg) {
            if(count($reg['EduRegistrationQuarter']) == 0) {
                $this->saveRegistrationQuarters($reg['EduRegistration']['id']);
            }
        }
        
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->EduStudent->create();
            $this->autoRender = false;
            if ($this->EduStudent->save($this->data)) {
                $this->Session->setFlash(__('The edu student has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu student could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
        $edu_parents = $this->EduStudent->EduParent->find('list');
        $this->set(compact('edu_parents', 'users'));
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu student', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->layout = 'ajax';
            $this->autoRender = false;
			
            if ($this->EduStudent->save($this->data)) {
		$student_condition = $this->EduStudent->EduStudentCondition->find('first', array(
				'conditions' => array('edu_student_id' => $this->data['EduStudent']['id'])));
		$scid = 0;
		if(!isset($student_condition['EduStudentCondition'])){
			$student_condition['EduStudentCondition'] = $this->data['EduStudentCondition'];
		} else {
			$scid = $student_condition['EduStudentCondition']['id'];
		}
		$student_condition['EduStudentCondition'] = $this->data['EduStudentCondition'];
		$student_condition['EduStudentCondition']['edu_student_id'] = $this->data['EduStudent']['id'];
		if($scid > 0)
			$student_condition['EduStudentCondition']['id'] = $scid;
				
		$this->EduStudent->EduStudentCondition->save($student_condition);

		$this->EduStudent->EduRegistration->recursive = 0;
		$registrations = $this->EduStudent->EduRegistration->find('all', array('conditions' => array('edu_student_id' => $this->data['EduStudent']['id'])));
		foreach($registrations as $reg) {
			//$this->EduStudent->EduRegistration->read(null, $reg['EduRegistration']['id']);
			$this->EduStudent->EduRegistration->read(null, $reg['EduRegistration']['id']);
                        $this->EduStudent->EduRegistration->set('name', $this->data['EduStudent']['name']);
                        $this->EduStudent->EduRegistration->save();
		}
                
                /*
		$registrations = $this->EduStudent->EduRegistration->find('all', array('conditions' => array('edu_student_id' => $this->data['EduStudent']['id'])));
                
                if(count($registrations) > 0) {
                    if(count($registrations) == 1) {
                        $reg_data = $this->data['EduRegistration'];
                        $this->EduStudent->EduRegistration->read(null, $registrations[0]['EduRegistration']['id']);
                        $this->EduStudent->EduRegistration->set('edu_class_id', $reg_data['edu_class_id']);
                        $this->EduStudent->EduRegistration->set('edu_section_id', $reg_data['edu_section_id']);
                        $this->EduStudent->EduRegistration->save();
                    }
                }
		*/
                
                $this->Session->setFlash(__('The student profile has been updated', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The student profile could not be updated. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_student', $this->EduStudent->read(null, $id));
		
		$this->loadModel('SubCity');

        $scities = $this->SubCity->find('all', array('order' => 'SubCity.name ASC'));
        $sub_cities = array();
        foreach ($scities as $scity) {
            $sub_cities[$scity['SubCity']['id']] = $scity['SubCity']['name'];
        }

        $this->loadModel('Edu.EduClass');
        $edu_classes = $this->EduClass->find('list');

		$this->set('edu_classes', $edu_classes);
		$this->set('sub_cities', $sub_cities);
    }
	
	function change_student_status($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid student', true), '');
            $this->redirect(array('action' => 'index_o'));
        }
        if (!empty($this->data)) {
            $this->layout = 'ajax';
            $this->autoRender = false;
			
            if ($this->EduStudent->save($this->data)) {
				$this->loadModel('EduStudentStatus');
				
				$student_status = array('EduStudentStatus' => array(
					'edu_student_id' => $this->data['EduStudent']['id'],
					'status_id' => $this->data['EduStudent']['status_id'],
					'remark' => $this->data['EduStudent']['remark'],
					'user_id' => $this->Session->read('Auth.User.id')
				));
				
				$this->EduStudentStatus->create();
				$this->EduStudentStatus->save($student_status);
				
                $this->Session->setFlash(__('The student profile has been updated', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The student profile could not be updated. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
		$edu_student = $this->EduStudent->read(null, $id);
        $this->set('edu_student', $edu_student);
		$conditions = array();
		$this->loadModel('Status');
		if($edu_student['EduStudent']['status_id'] == 1) {
			$conditions['Status.id'] = array(2, 4, 8, 3, 16, 5);
		} else {
			$conditions['Status.id'] = array(1);
		}
		$statuses = $this->Status->find('list', array('conditions' => $conditions));
		
		$this->set('statuses', $statuses);
    }
	
	function change_student_parent($id = null) {
		if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid student', true), '');
            $this->redirect(array('action' => 'index_o'));
        }
        if (!empty($this->data)) {
            $this->layout = 'ajax';
            $this->autoRender = false;
			
			if ($this->EduStudent->save($this->data)) {
				$this->Session->setFlash(__('The student parent has been successfully changed', true), '');
                $this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The student parent could not be changed. Please, try again.', true), '');
                $this->render('/elements/failure');
			}
		}
		$edu_ps = $this->EduStudent->EduParent->find('all');
        $edu_parents = array();
        foreach ($edu_ps as $pr) {
            $primary = $pr['EduParent']['primary_parent'];
            $primary = $primary == 'M'? 'mother': ($primary == 'F'? 'father': 'guardian');
            foreach($pr['EduParentDetail'] as $pd) {
                if($pd['relationship'] == $primary) {
                    $edu_parents[$pr['EduParent']['id']] = $pd['first_name'] . ' ' . $pd['middle_name'] . ' ' . ($pd['last_name'] == 'NA'? '': $pd['last_name']);
                }
            }
            
        }
        asort($edu_parents);
		$this->set('parents', $edu_parents);
		
		$edu_student = $this->EduStudent->read(null, $id);
        $this->set('student', $edu_student);
	}
	
	function upload_photo($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid student', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->layout = 'ajax';
            $this->autoRender = false;
			
			$id = $this->data['EduStudent']['id'];
			$student = $this->EduStudent->read(null, $id);
			$id_number = $student['EduStudent']['identity_number'];
			
			// upload image
            $file = $this->data['EduStudent']['photo_file_name'];
            $file_name = basename($file['name']);
            $fext = substr($file_name, strrpos($file_name, "."));
            $fname = time(); // str_replace($fext, "", $file_name);
            $file_name = $id_number . '_' . $fname . $fext;

            if (!file_exists(IMAGES . 'students')) {
                mkdir(IMAGES . 'students', 0777);
            }

            if (!move_uploaded_file($file['tmp_name'], IMAGES . 'students' . DS . $file_name)) {
                unset($this->data['EduStudent']['photo_file_name']);
            } else {
                $this->data['EduStudent']['photo_file_name'] = $file_name;
            }
			
            if ($this->EduStudent->save($this->data)) {
				$registration = $this->EduStudent->EduRegistration->find('first', array(
					'conditions' => array(
						'EduRegistration.edu_student_id' => $this->data['EduStudent']['id'], 
						'EduRegistration.status' => 'A'
					)
				));
				if($registration){
					$registration['EduRegistration']['photo_file'] = $file_name;
					$this->EduStudent->EduRegistration->save($registration);
				}
				
                $this->Session->setFlash(__('The student profile has been updated', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The student profile could not be updated. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_student', $this->EduStudent->read(null, $id));
    }

    function delete($id = null, $reason) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for student', true), '');
            $this->render('/elements/failure');
        }

        $student = $this->EduStudent->read(null, $id);
        $this->EduStudent->set('deleted', true);
        $this->EduStudent->set('delete_reason', $reason);
        $this->EduStudent->set('deleted_by_id', $this->Session->read('Auth.User.id'));
        $this->EduStudent->save();

        $this->Session->setFlash(__('Student deleted successfully', true), '');
        $this->render('/elements/success');
    }

}
