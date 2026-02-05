<?php
class ReportsController extends AppController {
	public $name = 'Reports';
	public function index()
	{
		$reportCategories = $this->Report->ReportCategory->find('all');
		$this->set(compact('reportCategories'));
	}

	public function index2($id = null)
	{
		$this->set('parent_id', $id);
	}

	public function search()
	{
		// empty
	}
	
	public function list_data($id = null)
	{
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
		$reportCategoryId = (isset($_REQUEST['report_category_id'])) ? $_REQUEST['report_category_id'] : -1;
		if ($id) {
			$reportCategoryId = ($id) ? $id : -1;
		}
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';
        eval("\$conditions = array( " . $conditions . " );");
		if ($reportCategoryId != -1) {
            $conditions['Report.report_category_id'] = $reportCategoryId;
        }
		$reports = $this->Report->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start));
		$this->set('reports', $reports);
		$this->set('results', $this->Report->find('count', array('conditions' => $conditions)));
	}

	public function view($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid report', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Report->recursive = 2;
		$this->set('report', $this->Report->read(null, $id));
	}

	public function add($id = null)
	{
		if (!empty($this->data)) {
			$this->Report->create();
			$this->autoRender = false;
			if ($this->Report->save($this->data)) {
				$this->Session->setFlash(__('The report has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The report could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if ($id) {
			$this->set('parent_id', $id);
		}
		$reportCategories = $this->Report->ReportCategory->find('list');
		$groups = $this->Report->Group->find('list');
		$this->set('report_categories', $reportCategories);
		$this->set('groups', $groups);
	}

	public function edit($id = null, $parentId = null)
	{
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid report', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->Report->save($this->data)) {
				$this->Session->setFlash(__('The report has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The report could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$report = $this->Report->read(null, $id);
		$this->set('report', $report);
		if ($parentId) {
			$this->set('parent_id', $parentId);
		}
		$reportCategories = $this->Report->ReportCategory->find('list');
		$groups = $this->Report->Group->find('list');
		$this->set('report_categories', $reportCategories);
		$this->set('groups', $groups);
	}

	public function delete($id = null)
	{
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for report', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->Report->delete($i);
                }
				$this->Session->setFlash(__('Report deleted', true), '');
				$this->render('/elements/success');
            } catch (Exception $e) {
				$this->Session->setFlash(__('Report was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->Report->delete($id)) {
				$this->Session->setFlash(__('Report deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Report was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
	
	public function report_index()
	{
		$this->layout = 'reports_dashboard';
		$reportCategories = $this->Report->ReportCategory->find('all');
		$this->set('report_categories', $reportCategories);
	}

	public function rpt_active_teachers()
	{
		$this->layout = 'reports';
		$this->loadModel('Edu.EduSection');
		$this->loadModel('Edu.EduTeacher');
		$this->loadModel('User');
		$conditions = array();
		$this->EduTeacher->unbindModel(array('hasMany' => array('EduSection')));
		$this->EduTeacher->unbindModel(array('hasMany' => array('EduAssessment')));
		$this->EduTeacher->unbindModel(array('hasMany' => array('EduAssignment')));
		$this->EduTeacher->unbindModel(array('hasAndBelongsToMany' => array('EduSubject')));
		$this->EduTeacher->unbindModel(array('hasAndBelongsToMany' => array('EduClass')));
		$conditions['EduTeacher.deleted'] = false;
		$this->EduTeacher->recursive = -1;
		$teachers = $this->EduTeacher->find('all', array('conditions' => $conditions, 'limit' => '10'));
		foreach ($teachers as &$teacher) {
			$user = $this->User->read(null, $teacher['EduTeacher']['user_id']);
			$teacher['User'] = $user;
		}
		$this->set('teachers', $teachers);
	}
	
	public function rpt_all_students($cat = ' ')
	{
		$this->layout = 'reports';
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduAcademicYear');
		$conditions = array();
		if ($cat == ' ') {
			$conditions['EduStudent.name'] = '0';
		} elseif ($cat == 'ALL') {
			// empty
		} else {
			$conditions['EduStudent.name LIKE'] = $cat . '%';
		}
		$conditions['EduStudent.deleted'] = false;
		$this->EduStudent->recursive = 2;

		$students = $this->EduStudent->find('all', array('conditions' => $conditions));

		$this->set('students', $students);
	}
	
	public function rpt_active_students($classId = ' ')
	{
		$this->layout = 'reports';
		$this->loadModel('Edu.EduSection');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduAcademicYear');
		$ay = $this->EduAcademicYear->getActiveAcademicYear();
		$conditions = array();
		if ($classId == ' ') {
			$conditions['EduRegistration.edu_class_id'] = '0';
		} else {
			$conditions['EduRegistration.edu_class_id'] = $classId;
			$sections = array();
			$eduSections = $this->EduSection->find('all', array(
				'conditions' => array(
					'EduSection.edu_academic_year_id' => $ay['EduAcademicYear']['id'],
					'EduSection.edu_class_id' => $classId
				)
			));
			$sections[] = 0;
			foreach ($eduSections as $section) {
				$sections[] = $section['EduSection']['id'];
			}
			$conditions['EduRegistration.edu_section_id'] = $sections;
			$conditions['EduRegistration.status_id'] = 1;
		}
		$conditions['EduStudent.deleted'] = false;
		$this->EduRegistration->recursive = 2;
		$students = $this->EduRegistration->find('all', array('conditions' => $conditions));
		$this->set('students', $students);
	}
	
	public function rpt_promoted_students($ayId = 0, $classId = 0)
	{
		$this->layout = 'reports';
		if (isset($this->params['url']['format'])) {
			$this->layout = 'pdf_layout';
			$this->set('format', $this->params['url']['format']);
		}
		$this->loadModel('Edu.EduSection');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduAcademicYear');
		$ay = $this->EduAcademicYear->getActiveAcademicYear();
		if ($ayId > 0) {
			$ay = $this->EduAcademicYear->read(null, $ayId);
		} else {
			$ayId = $ay['EduAcademicYear']['id'];
		}
		$this->set('ay_id', $ayId);
		$this->set('selected_ay', $ay);
		$conditions = array();
		if ($classId == 0) {
			$conditions['EduRegistration.edu_class_id'] = '0';
		} else {
			$sections = array();
			$con = array('EduSection.edu_academic_year_id' => $ay['EduAcademicYear']['id']);
			if ($classId < 99) {
				$con['EduSection.edu_class_id'] = $classId;
				$conditions['EduRegistration.edu_class_id'] = $classId;
			}
			$this->EduSection->recursive = 0;
			$eduSections = $this->EduSection->find('all', array(
				'conditions' => $con
			));
			
			foreach ($eduSections as $section) {
				$sections[] = $section['EduSection']['id'];
			}
			
			$conditions['EduRegistration.edu_section_id'] = $sections;
			$conditions['EduRegistration.status_id'] = 13;
		}
		$conditions['EduStudent.deleted'] = false;
		$this->EduRegistration->recursive = 0;
		$students = $this->EduRegistration->find('all', array(
			'conditions' => $conditions, 'order' => 'EduSection.edu_class_id, EduSection.name'));
		$this->set('students', $students);
		$this->set('class_id', $class_id);
		$this->set('ays', $this->EduAcademicYear->find('all', array('limit' => 10, 'order' => 'start_date DESC')));
	}
	
	public function rpt_not_promoted_students($ayId = 0, $classId = 0)
	{
		$this->layout = 'reports';
		if (isset($this->params['url']['format'])) {
			$this->layout = 'pdf_layout';
			$this->set('format', $this->params['url']['format']);
		}
		$this->loadModel('Edu.EduSection');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduAcademicYear');
		$ay = $this->EduAcademicYear->getActiveAcademicYear();
		if ($ayId > 0) {
			$ay = $this->EduAcademicYear->read(null, $ayId);
		} else {
			$ayId = $ay['EduAcademicYear']['id'];
		}
		$this->set('ay_id', $ayId);
		$this->set('selected_ay', $ay);
		$conditions = array();
		if ($classId == 0) {
			$conditions['EduRegistration.edu_class_id'] = '0';
		} else {
			$sections = array();
			$con = array('EduSection.edu_academic_year_id' => $ay['EduAcademicYear']['id']);
			if ($classId < 99) {
				$con['EduSection.edu_class_id'] = $classId;
				$conditions['EduRegistration.edu_class_id'] = $classId;
			}
			$this->EduSection->recursive = 0;
			$eduSections = $this->EduSection->find('all', array(
				'conditions' => $con
			));
			foreach ($eduSections as $section) {
				$sections[] = $section['EduSection']['id'];
			}
			$conditions['EduRegistration.edu_section_id'] = $sections;
			$conditions['EduRegistration.status_id'] = 14;
		}
		$conditions['EduStudent.deleted'] = false;
		$this->EduRegistration->recursive = 0;
		$students = $this->EduRegistration->find('all', array(
			'conditions' => $conditions, 'order' => 'EduSection.edu_class_id, EduSection.name'));
		$this->set('students', $students);
		$this->set('class_id', $class_id);
		$this->set('ays', $this->EduAcademicYear->find('all', array('limit' => 10, 'order' => 'start_date DESC')));
	}
	
	public function rpt_not_registered_students($classId = 0)
	{
		$this->layout = 'reports';
		if (isset($this->params['url']['format'])) {
			$this->layout = 'pdf_layout';
			$this->set('format', $this->params['url']['format']);
		}
		$this->loadModel('Edu.EduSection');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduAcademicYear');
		$ay = $this->EduAcademicYear->getPreviousAcademicYear();
		$conditions = array();
		if ($classId == 0) {
			$conditions['EduRegistration.edu_class_id'] = '0';
		} else {
			$sections = array();
			$con = array('EduSection.edu_academic_year_id' => $ay['EduAcademicYear']['id']);
			if ($classId < 99) {
				$con['EduSection.edu_class_id'] = $classId;
				$conditions['EduRegistration.edu_class_id'] = $classId;
			}
			$this->EduSection->recursive = 0;
			$eduSections = $this->EduSection->find('all', array(
				'conditions' => $con
			));
			foreach ($eduSections as $section) {
				$sections[] = $section['EduSection']['id'];
			}
			$conditions['EduRegistration.edu_section_id'] = $sections;
			$conditions['EduRegistration.status_id'] = array(13, 14);
		}
		$conditions['EduStudent.deleted'] = false;
		$registereds = $this->EduRegistration->find('all', array('conditions' => array('edu_section_id' => 0)));
		$studIds = array();
		foreach ($registereds as $reg) {
			$studIds[] = $reg['EduRegistration']['edu_student_id'];
		}
		$conditions['NOT'] = array('EduRegistration.edu_student_id' => $studIds);
		$this->EduRegistration->recursive = 0;
		$students = $this->EduRegistration->find('all', array(
			'conditions' => $conditions, 'order' => 'EduSection.edu_class_id, EduSection.name'));
		$this->set('students', $students);
		$this->set('class_id', $class_id);
	}
	
	public function rpt_active_students_print($classId = ' ')
	{
		$this->layout = 'print_reports';
		$this->loadModel('Edu.EduSection');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduAcademicYear');
		$ay = $this->EduAcademicYear->getActiveAcademicYear();
		$conditions = array();
		if ($classId == ' ') {
			$conditions['EduRegistration.edu_class_id'] = '0';
		} else {
			$conditions['EduRegistration.edu_class_id'] = $classId;
			$sections = array();
			$eduSections = $this->EduSection->find('all', array(
				'conditions' => array(
					'EduSection.edu_academic_year_id' => $ay['EduAcademicYear']['id'],
					'EduSection.edu_class_id' => $classId
				)
			));
			foreach ($eduSections as $section) {
				$sections[] = $section['EduSection']['id'];
			}
			$conditions['EduRegistration.edu_section_id'] = $sections;
		}
		$conditions['EduStudent.deleted'] = false;
		$this->EduRegistration->recursive = 2;
		$students = $this->EduRegistration->find('all', array('conditions' => $conditions));
		$this->set('students', $students);
	}
	
	public function rpt_enrolled_students()
	{
		$this->layout = 'reports';
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduAcademicYear');
		$ay = array();
		if (isset($this->params['url']['input_ay'])) {
			$ay = $this->EduAcademicYear->read(null, $this->params['url']['input_ay']);
		} else {
			$ay = $this->EduAcademicYear->getActiveAcademicYear();
		}
		$this->set('ay', $ay);
		if (isset($this->params['url']['format'])) {
			$this->layout = 'ajax';
			$this->set('format', $this->params['url']['format']);
		} else {
			$this->layout = 'reports';
			$this->set('format', 'html');
		}
		$this->EduStudent->recursive = 2;
		$enrollments = $this->EduStudent->find('all', array(
			'conditions' => array(
				'EduStudent.edu_academic_year_id' => $ay['EduAcademicYear']['id']))
		);
		$academicYears = $this->EduAcademicYear->find('all');
		$this->set('company_url', Configure::read('company_url'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_tin', Configure::read('company_tin'));
        $this->set('company_address', Configure::read('company_address'));
		$this->set('enrollments', $enrollments);
		$this->set('academic_years', $academicYears);
		$this->set('selected_ay', $ay);
	}

	public function rpt_registered_students()
	{
		$this->layout = 'reports';
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduAcademicYear');
		$ay = array();
		if (isset($this->params['url']['input_ay'])) {
			$ay = $this->EduAcademicYear->read(null, $this->params['url']['input_ay']);
		} else {
			$ay = $this->EduAcademicYear->getActiveAcademicYear();
		}
		$startDate = date('Y-m-d');
		$endDate = date('Y-m-d');
		if (!empty($ay)) {
			$startDate = $ay['EduAcademicYear']['start_date'];
			$endDate = $ay['EduAcademicYear']['end_date'];
		}
		$registrations = $this->EduRegistration->find('all', array(
			'conditions' => array(
				'EduRegistration.created >=' => $startDate,
				'EduRegistration.created <=' => $endDate,
				'EduRegistration.status' => 'A')
			)
		);
		$academicYears = $this->EduAcademicYear->find('all');
		$this->set('registrations', $registrations);
		$this->set('academic_years', $academicYears);
		$this->set('selected_ay', $ay);
	}

	public function rpt_enrolled_unregistered_students()
	{
		$this->layout = 'reports';
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduAcademicYear');
		$ay = array();
		if (isset($this->params['url']['input_ay'])) {
			$ay = $this->EduAcademicYear->read(null, $this->params['url']['input_ay']);
		} else {
			$ay = $this->EduAcademicYear->getActiveAcademicYear();
		}
		$startDate = date('Y-m-d');
		$endDate = date('Y-m-d');
		if (!empty($ay)) {
			$startDate = $ay['EduAcademicYear']['start_date'];
			$endDate = $ay['EduAcademicYear']['end_date'];
		}
		$registrations = $this->EduRegistration->find('all', array(
			'conditions' => array(
				'EduRegistration.created >=' => $startDate,
				'EduRegistration.created <=' => $endDate,
				'EduRegistration.status' => 'U')
			)
		);
		$academicYears = $this->EduAcademicYear->find('all');
		$this->set('registrations', $registrations);
		$this->set('academic_years', $academicYears);
		$this->set('selected_ay', $ay);
	}

	public function rpt_former_unregistered_students()
	{
		$this->layout = 'reports';
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduAcademicYear');
		$ay = array();
		if (isset($this->params['url']['input_ay'])) {
			$ay = $this->EduAcademicYear->read(null, $this->params['url']['input_ay']);
		} else {
			$ay = $this->EduAcademicYear->getActiveAcademicYear();
		}
		$startDate = date('Y-m-d');
		$endDate = date('Y-m-d');
		if (!empty($ay)) {
			$startDate = $ay['EduAcademicYear']['start_date'];
			$endDate = $ay['EduAcademicYear']['end_date'];
		}
		$registrations = $this->EduRegistration->find('all', array(
			'conditions' => array(
				'EduRegistration.created >=' => $startDate,
				'EduRegistration.created <=' => $endDate,
				'EduRegistration.status !=' => 'U')
			)
		);
		$academicYears = $this->EduAcademicYear->find('all');
		$this->set('registrations', $registrations);
		$this->set('academic_years', $academicYears);
		$this->set('selected_ay', $ay);
	}

	public function student_detail($id = null)
	{
		$this->layout = 'reports';
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduAcademicYear');
		$this->loadModel('Status');
		$this->EduStudent->recursive = 2;
		$student = $this->EduStudent->read(null, $id);
		$academicYears = $this->EduAcademicYear->find('list');
		$this->set('status', $this->Status->find('list'));
		$this->set('academic_years', $academicYears);
		$this->set('student', $student);
	}
	
	public function class_detail($id = null)
	{
		$this->layout = 'reports';
		$this->loadModel('Edu.EduClass');
		$this->loadModel('Edu.EduAcademicYear');
		$this->EduClass->recursive = 3;
		$class = $this->EduClass->read(null, $id);
		$academicYears = $this->EduAcademicYear->find('list');
		$this->set('academic_years', $academicYears);
		$this->set('class', $class);
	}
	
	public function section_detail($id = null)
	{
		$this->layout = 'reports';
		$this->loadModel('Edu.EduSection');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduAcademicYear');
		$this->loadModel('Status');
		$this->EduClass->recursive = 2;
		$section = $this->EduSection->read(null, $id);
		$academicYears = $this->EduAcademicYear->find('list');
		$conditions = array('EduRegistration.edu_section_id' => $id, 'EduStudent.deleted' => false);
		$this->EduRegistration->recursive = 1;
		$students = $this->EduRegistration->find('all', array('conditions' => $conditions));
		$this->set('students', $students);
		$this->set('statuses', $this->Status->find('list'));
		$this->set('academic_years', $academicYears);
		$this->set('section', $section);
	}
	
	public function rpt_due_payment_students()
	{
		$this->layout = 'reports';
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduAcademicYear');
		$this->loadModel('Edu.EduClass');
		$this->loadModel('Edu.EduSection');
		$this->loadModel('Edu.EduPayment');
		$this->loadModel('Edu.EduPaymentSchedule');
		$classId = 0;
		if (isset($this->params['url']['input_class'])) {
			$classId = $this->params['url']['input_class'];
		}
		$ay = $this->EduAcademicYear->getActiveAcademicYear();
		$this->set('ay', $ay);
		if (isset($this->params['url']['format'])) {
			$this->layout = 'ajax';
			$this->set('format', $this->params['url']['format']);
		} else {
			$this->layout = 'reports';
			$this->set('format', 'html');
		}
		$sections = $this->EduSection->find('all', array(
			'conditions' => array(
				'EduSection.edu_academic_year_id' => $ay['EduAcademicYear']['id'],
				'EduSection.edu_class_id' => $classId
			)));
		$paymentSchedules = $this->EduPaymentSchedule->find('all', array(
			'conditions' => array(
				'EduPaymentSchedule.edu_academic_year_id' => $ay['EduAcademicYear']['id'],
				'EduPaymentSchedule.edu_class_id' => $classId,
				'EduPaymentSchedule.due_date <' => date('Y-m-d')
			)));
		$sectionIds = array();
		foreach ($sections as $section) {
			$sectionIds[] = $section['EduSection']['id'];
		}
		$psIds = array();
		foreach ($paymentSchedules as $paymentSchedule) {
			$psIds[] = $paymentSchedule['EduPaymentSchedule']['id'];
		}
		$this->EduStudent->EduRegistration->recursive = 2;
		$students = $this->EduStudent->EduRegistration->find('all', array(
			'conditions' => array(
				'EduRegistration.edu_section_id' => $sectionIds
			)));
		$duePayments = array();
		foreach ($students as $student) {
			$studPayments = $this->EduPayment->find('all', array(
				'conditions' => array(
					'EduPayment.edu_payment_schedule_id' => $psIds,
					'EduPayment.is_paid' => true,
					'EduPayment.edu_student_id' => $student['EduRegistration']['edu_student_id']
				)));
			if (count($studPayments) < count($psIds)) {
				$duePayment = array();
				$duePayment['name'] = $student['EduStudent']['name'];
				$duePayment['identity_number'] = $student['EduStudent']['identity_number'];
				$duePayment['section'] = $student['EduSection']['name'];
				$stuDuePayScheduleIds = array();
				$stuDuePaySchedules = array();
				foreach ($studPayments as $studPayments) {
					$stuDuePayScheduleIds[] = $studPayments['EduPayment']['edu_payment_schedule_id'];
				}
				$this->EduPaymentSchedule->recursive = 1;
				foreach ($psIds as $psId) {
					if (!in_array($psId, $stuDuePayScheduleIds)) {
						$ps = $this->EduPaymentSchedule->read(null, $psId);
						$stuDuePaySchedules[] = $ps['EduPaymentSchedule'];
					}
				}
				$duePayment['due_payments'] = $stuDuePaySchedules;
				$duePayments[] = $duePayment;
			}
		}
		$penaltySetting = $this->getSystemSetting('PENALTY_PER_DAY');
		$dodos = array();
		$dueDates = array();
		foreach ($duePayments as $duePayment) {
			foreach ($duePayment['due_payments'] as $dp) {
				$datetime1 = new DateTime($dp['due_date']);
				$datetime2 = new DateTime(date('Y-m-d'));
				$interval = $datetime1->diff($datetime2);
				$dodo = array(
					'name' => $duePayment['name'],
					'identity_number' => $duePayment['identity_number'],
					'section' => $duePayment['section'],
					'month' => $dp['month'],
					'amount' => $dp['amount'],
					'due_days' => $interval->format('%R%a'),
					'penalty' => ($interval->format('%R%a') > 0? $interval->format('%R%a') * $penaltySetting: 0)
				);
				if (!in_array($interval->format('%R%a'), $dueDates)) {
					$dueDates[] = $interval->format('%R%a');
				}
				$dodos[] = $dodo;
			}
		}
		$duePayments = array();
		rsort($dueDates);
		foreach ($dueDates as $dueDate) {
			foreach ($dodos as $dodo) {
				if ($dodo['due_days'] == $dueDate) {
					$duePayments[] = $dodo;
				}
			}
		}
		$eduClasses = $this->EduClass->find('all');
		$eduClass = $this->EduClass->read(null, $classId);
		$this->set('company_url', Configure::read('company_url'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_tin', Configure::read('company_tin'));
        $this->set('company_address', Configure::read('company_address'));
		$this->set('due_payments', $duePayments);
		$this->set('edu_classes', $eduClasses);
		$this->set('selected_class_id', $classId);
		$this->set('selected_class', $eduClass);
	}
}
