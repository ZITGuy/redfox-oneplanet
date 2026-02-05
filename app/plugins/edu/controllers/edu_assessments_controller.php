<?php
App::import('Vendor', 'PHPExcel/PHPExcel/IOFactory', array('file' => 'IOFactory.php'));
App::import('Vendor', 'PHPExcel/PHPExcel', array('file' => 'PHPExcel.php'));
App::import('Vendor', 'PHPExcel/PHPExcel/PHPExcel_RichText', array('file' => 'RichText.php'));

class EduAssessmentsController extends EduAppController
{
    public $name = 'EduAssessments';

    /**
     * Index action for department managers to view all submitted assessments for their department's courses in the current quarter.
     * 
     * @return void
     */
	public function index_manager_o() {
		$this->loadModel('EduQuarter');
		$this->loadModel('EduDepartment');
        $this->loadModel('EduCourse');

		$qr = $this->EduQuarter->getActiveQuarter();
        $eduQuarterId = $qr['EduQuarter']['id'];
    
        // read department of the current user (as department manager)
        $department = $this->EduDepartment->find('first', array(
                'conditions' => array(
                    'EduDepartment.user_id' => $this->Session->read('Auth.User.id')
                )
        ));

        $courseIds = array();
        if (!empty($department)) {
            $subjectIds = array();
            $classIds = array();
            foreach ($department['EduClass'] as $cls) {
                $classIds[] = $cls['id'];
            }
    
            foreach ($department['EduSubject'] as $sub) {
                $subjectIds[] = $sub['id'];
            }
            // get courses from the associated subjects and classes
            $cond = array('EduCourse.edu_class_id' => $classIds, 'EduCourse.edu_subject_id' => $subjectIds);
            $courses = $this->EduCourse->find('all', array(
                'conditions' => $cond
            ));
            if (!empty($courses)) {
                foreach ($courses as $cc){
                    $courseIds[] = $cc['EduCourse']['id'];
                }
            }
        }

		$conditions = array();
        if (!empty($courseIds)) {
            $conditions['EduAssessment.edu_course_id'] = $courseIds;
        }
		$conditions['EduAssessment.edu_quarter_id'] = $eduQuarterId;
		$conditions['EduAssessment.status'] = 'SB'; // only SUBMITTED records are needed here
		
        $this->EduAssessment->recursive = 0;
        
        $assessments = $this->EduAssessment->find('all', array('conditions' => $conditions));
		$courseIds = array();
		foreach ($assessments as $assessment) {
			$courseIds[] = $assessment['EduAssessment']['edu_course_id'];
		}
		
		$eduCourses = array(); //commented $this->EduAssessment->EduCourse->find('all', array(
           // 'conditions' => array('EduCourse.id' => $courseIds)));
		
		$this->set('courses', $eduCourses);
	}

    /**
     * Index action for curriculum officers to view all submitted and checked assessments of the current quarter.
     * 
     * @return void
     */
    public function index_curriculum_o() {
		$this->loadModel('EduQuarter');
		$this->loadModel('EduDepartment');
        $this->loadModel('EduCourse');

		$qr = $this->EduQuarter->getActiveQuarter();
        $eduQuarterId = $qr['EduQuarter']['id'];

		$conditions = array();
		$conditions['EduAssessment.edu_quarter_id'] = $eduQuarterId;
		$conditions['EduAssessment.checked_by_id >'] = 0;
		$conditions['EduAssessment.status'] = 'SB'; // only SUBMITTED records are needed here
		
        $this->EduAssessment->recursive = 0;
        
        $assessments = $this->EduAssessment->find('all', array('conditions' => $conditions));
		$courseIds = array();
		foreach ($assessments as $assessment) {
			$courseIds[] = $assessment['EduAssessment']['edu_course_id'];
		}
		
		$eduCourses = array(); //commented $this->EduAssessment->EduCourse->find('all', array(
        //    'conditions' => array('EduCourse.id' => $courseIds)));
		
		$this->set('courses', $eduCourses);
	}
	
    /**
     * Called from the Assessment Management screen to get the list of already
     * maintained assessments for the specified course, section and also for the
     * current active quarter.
     *
     * params: $id int (course_id)
     * return: the list of assessments for the view.
     */
	public function list_data_for_manager() {
		$eduCourseId = (isset($_REQUEST['edu_course_id'])) ? $_REQUEST['edu_course_id'] : -1;
        
		$this->loadModel('EduQuarter');
        $this->loadModel('EduDepartment');
        $this->loadModel('EduCourse');
        $this->loadModel('EduTeacher');
        $this->loadModel('EduClass');

		$qr = $this->EduQuarter->getActiveQuarter();
        $eduQuarterId = $qr['EduQuarter']['id'];

		$conditions = array();
		$conditions['EduAssessment.edu_course_id'] = $eduCourseId;
		$conditions['EduAssessment.edu_quarter_id'] = $eduQuarterId;
		$conditions['EduAssessment.status'] = 'SB'; // only SUBMITTED records are needed here
		
		if ($eduCourseId == 991) { // Unsubmitted
			unset($conditions['EduAssessment.edu_course_id']);
            $conditions['EduAssessment.status'] = 'S';
            $conditions['EduAssessment.checked_by_id'] = 0;
            $conditions['EduAssessment.approved_by_id'] = 0;
		} elseif ($eduCourseId == 992) { // Submitted
            $conditions['EduAssessment.checked_by_id'] = 0;
            $conditions['EduAssessment.approved_by_id'] = 0;
            unset($conditions['EduAssessment.edu_course_id']);
        } elseif ($eduCourseId == 993 || $eduCourseId == 999) { // Checked or Unapproved
            unset($conditions['EduAssessment.edu_course_id']);
            $conditions['EduAssessment.checked_by_id >'] = 0;
            $conditions['EduAssessment.approved_by_id'] = 0;
        } elseif ($eduCourseId == 994) { // Approved
            unset($conditions['EduAssessment.edu_course_id']);
			$conditions['EduAssessment.checked_by_id >'] = 0;
            $conditions['EduAssessment.approved_by_id >'] = 0;
        }

        $department = $this->EduDepartment->find('first', array(
            'conditions' => array(
                'EduDepartment.user_id' => $this->Session->read('Auth.User.id')
            )
        ));

        $courseIds = array();
        if (!empty($department)) {
            $subjectIds = array();
            $classIds = array();
            foreach ($department['EduClass'] as $cls) {
                $classIds[] = $cls['id'];
            }

            foreach ($department['EduSubject'] as $sub) {
                $subjectIds[] = $sub['id'];
            }
            // get courses from the associated subjects and classes
            $cond = array('EduCourse.edu_class_id' => $classIds, 'EduCourse.edu_subject_id' => $subjectIds);
            $courses = $this->EduCourse->find('all', array(
                'conditions' => $cond
            ));
            if (!empty($courses)) {
                foreach ($courses as $cc){
                    $courseIds[] = $cc['EduCourse']['id'];
                }
            }
        }

        if (!empty($courseIds)) {
            $conditions['EduAssessment.edu_course_id'] = $courseIds;
        }
		
		$this->EduAssessment->recursive = 0;
        $assessments = $this->EduAssessment->find('all', array(
            'conditions' => $conditions));
        
        $courses = array();
        $included = array();
        $count = 1;
        foreach ($assessments as $assessment) {
            $i = $assessment['EduAssessment']['edu_course_id'] . '-' . $assessment['EduAssessment']['edu_section_id'];
            if (!in_array($i, $included)) {
                $status = $assessment['EduAssessment']['status'] == 'SB'? 'Submitted': 'Not Submitted';
                if ($assessment['EduAssessment']['checked_by_id'] > 0 && $status != 'Not Submitted') {
                    $status = 'Checked';
                }
                if ($assessment['EduAssessment']['approved_by_id'] > 0 && $status != 'Not Submitted') {
                    $status = 'Approved';
                }

                $this->EduTeacher->recursive = 0;
                $this->EduClass->recursive = 0;
                $teacher = $this->EduTeacher->read(null, $assessment['EduAssessment']['edu_teacher_id']); // TODO: 
                $class = $this->EduClass->read(null, $assessment['EduSection']['edu_class_id']);
                $courses[] = array (
                    'id' => $count,
                    'edu_course_id' => $assessment['EduAssessment']['edu_course_id'],
                    'edu_section_id' => $assessment['EduAssessment']['edu_section_id'],
                    'EduCourse' => $assessment['EduCourse'],
                    'EduClass' => $class['EduClass'],
                    'EduSection' => $assessment['EduSection'],
                    'EduQuarter' => $assessment['EduQuarter'],
                    'EduTeacher' => $teacher,
                    'status' => $status
                );
                $included[] = $i;
                $count++;
            }
        }
        
        $this->set('courses', $courses);
        $this->set('results', count($courses));
	}

    /**
     * Called from the Assessment Management screen to get the list of already
     * maintained assessments for the specified course, section and also for the
     * current active quarter.
     *
     * params: $id int (course_id)
     * return: the list of assessments for the view.
     */
    public function list_data_for_curriculum_manager() {
		$eduCourseId = (isset($_REQUEST['edu_course_id'])) ? $_REQUEST['edu_course_id'] : -1;
        	$this->loadModel('EduQuarter');
		
		$qr = $this->EduQuarter->getActiveQuarter();
          $eduQuarterId = $qr['EduQuarter']['id'];

		$conditions = array();
		$conditions['EduAssessment.edu_course_id'] = $eduCourseId;
		$conditions['EduAssessment.edu_quarter_id'] = $eduQuarterId;
		$conditions['EduAssessment.checked_by_id >'] = 0;
		$conditions['EduAssessment.status'] = 'SB'; // only SUBMITTED records are needed here
		
		if ($eduCourseId == 991) { // ['991', 'Unsubmitted'],
		  unset($conditions['EduAssessment.edu_course_id']);
            unset($conditions['EduAssessment.checked_by_id >']);
		  $conditions['EduAssessment.status'] = 'S';
            $conditions['EduAssessment.checked_by_id'] = 0;
            $conditions['EduAssessment.approved_by_id'] = 0;
		} elseif ($eduCourseId == 992) { // ['992', 'Submitted'],
            unset($conditions['EduAssessment.edu_course_id']);
            unset($conditions['EduAssessment.checked_by_id >']);
            $conditions['EduAssessment.checked_by_id'] = 0;
            $conditions['EduAssessment.approved_by_id'] = 0;
        } elseif ($eduCourseId == 993 || $eduCourseId == 999) { // ['993', 'Checked'], ['999', 'Unapproved']
            unset($conditions['EduAssessment.edu_course_id']);
            $conditions['EduAssessment.checked_by_id >'] = 0;
            $conditions['EduAssessment.approved_by_id'] = 0;
        } elseif ($eduCourseId == 994) { // ['994', 'Approved'],
            unset($conditions['EduAssessment.edu_course_id']);
		  $conditions['EduAssessment.checked_by_id >'] = 0;
            $conditions['EduAssessment.approved_by_id >'] = 0;
        }
		
	   $this->EduAssessment->recursive = 0;
        $assessments = $this->EduAssessment->find('all', array(
            'conditions' => $conditions));
        $this->loadModel('EduTeacher');
        $this->loadModel('EduClass');
        
        $courses = array();
        $included = array();
        $count = 0;
        foreach ($assessments as $assessment) {
            $i = $assessment['EduAssessment']['edu_course_id'] . '-' . $assessment['EduAssessment']['edu_section_id'];
            if (!in_array($i, $included)) {
                $status = $assessment['EduAssessment']['status'] == 'SB'? 'Submitted': 'Not Submitted';
                if ($assessment['EduAssessment']['checked_by_id'] > 0 && $status != 'Not Submitted') {
                    $status = 'Checked';
                }
                if ($assessment['EduAssessment']['approved_by_id'] > 0 && $status != 'Not Submitted') {
                    $status = 'Approved';
                }

                $this->EduTeacher->recursive = 0;
                $this->EduClass->recursive = 0;
                $teacher = $this->EduTeacher->read(null, $assessment['EduAssessment']['edu_teacher_id']);
                $class = $this->EduClass->read(null, $assessment['EduSection']['edu_class_id']);
                $courses[] = array(
                    'id' => $count,
                    'edu_course_id' => $assessment['EduAssessment']['edu_course_id'],
                    'edu_section_id' => $assessment['EduAssessment']['edu_section_id'],
                    'EduCourse' => $assessment['EduCourse'],
                    'EduClass' => $class['EduClass'],
                    'EduSection' => $assessment['EduSection'],
                    'EduQuarter' => $assessment['EduQuarter'],
                    'EduTeacher' => $teacher,
                    'status' => $status
                );
                $included[] = $i;
                $count++;
            }
        }
        
        $this->set('courses', $courses);
        $this->set('results', count($courses));
	}

    /**
     * Called from the Assessment Management screen to get the list of already
     * maintained assessment records for the specified course, section and also for the
     * current active quarter.
     *
     * params: $id int (course_id)
     * return: the list of assessment records for the view.
     */
    public function list_data_matrix_records() {
        $eduCourseId = (isset($_REQUEST['edu_course_id'])) ? $_REQUEST['edu_course_id'] : -1;
        $eduSectionId = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : -1;
        
        $this->loadModel('EduQuarter');
		
		$qr = $this->EduQuarter->getActiveQuarter();
        $eduQuarterId = $qr['EduQuarter']['id'];
        
		$conditions = array();
		$conditions['EduAssessment.edu_course_id'] = $eduCourseId;
		$conditions['EduAssessment.edu_quarter_id'] = $eduQuarterId;
		$conditions['EduAssessment.edu_section_id'] = $eduSectionId;

		//$conditions['EduAssessment.status'] = 'S'; // only SUBMITTED records are needed here
		
		if ($eduCourseId == 999 ){
			unset($conditions['EduAssessment.edu_course_id']);
			$conditions['EduAssessment.status'] = 'S';
		}
		
		$this->EduAssessment->recursive = 1;
        $assessments = $this->EduAssessment->find('all', array('conditions' => $conditions));

        $this->loadModel('Edu.EduRegistration');
        $this->loadModel('Edu.EduAssessmentRecord');
        $this->loadModel('Edu.EduExemption');
        $this->loadModel('Edu.EduAcademicYear');
        
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $eduAcademicYearId = $ay['EduAcademicYear']['id'];

        $assessmentRecords = array();
        $registrations = $this->EduRegistration->find('all', array(
            'conditions' => array(
                'EduRegistration.edu_section_id' => $eduSectionId,
                'EduRegistration.edu_academic_year_id' => $eduAcademicYearId,
                'EduStudent.deleted' => false
            ),
            'order' => 'EduRegistration.name'
        ));
            
        foreach ($registrations as $reg) {
            $exemptions = $this->EduExemption->find('all', array(
                'conditions' => array(
                    'EduExemption.edu_student_id' => $reg['EduRegistration']['edu_student_id'],
                    'EduExemption.edu_course_id' => $eduCourseId,
                    'EduExemption.edu_quarter_id' => array($eduQuarterId, 0)
                )
            ));
            if (!empty($exemptions)) {
                continue;
            }

            $arForStudent = array('EduAssessmentRecord' => array(
                'student' => $reg['EduRegistration']['name'],
                'identity_number' => $reg['EduStudent']['identity_number'],
                'remark' => ''
            ));

            foreach ($assessments as $assessment) {
                $ar = $this->EduAssessmentRecord->find('first', array('conditions' => array(
                        'edu_registration_id' => $reg['EduRegistration']['id'],
                        'edu_assessment_id' => $assessment['EduAssessment']['id']
                    )));
                if (empty($ar)) {
                    $ar = array('EduAssessmentRecord' => array(
                            'edu_registration_id' => $reg['EduRegistration']['id'],
                            'edu_assessment_id' => $assessment['EduAssessment']['id'],
                            'mark' => -1
                        ));
                    $this->EduAssessmentRecord->create();
                    $this->EduAssessmentRecord->save($ar);
                    $ar['EduAssessmentRecord']['id'] = $this->EduAssessmentRecord->id;
                }
                $ar = $this->EduAssessmentRecord->read(null, $ar['EduAssessmentRecord']['id']);

                $arForStudent['EduAssessmentRecord']['ar_id_' . $ar['EduAssessment']['id']] =
                    $ar['EduAssessmentRecord']['id'];
                $arForStudent['EduAssessmentRecord']['rvalue_' . $ar['EduAssessment']['id']] =
                    str_replace('.0', '', $ar['EduAssessmentRecord']['mark']);
                $arForStudent['EduAssessmentRecord']['max_value_' . $ar['EduAssessment']['id']] =
                    $ar['EduAssessment']['max_value'];
            }

            $assessmentRecords[] = $arForStudent;
        }

        $this->set('assessment_records', $assessmentRecords);
        $this->set('results', count($assessmentRecords));
    }
	
    /**
     * Prepares and sets data for the index view for secretaries.
     *
     * This function retrieves a list of education classes and sections, excluding classes with a grading type of 'G'.
     * It sets these lists, along with the parent ID and user ID, to be used in the view.
     *
     * @param mixed $id Optional parameter, not used in this function.
     */
    public function index_secretary_m($id = null) {
        $eduClasses = $this->EduAssessment->EduSection->EduClass->find('list', array(
            'conditions' => array('EduClass.grading_type <>' => 'G')));
        $eduSections = $this->EduAssessment->EduSection->find('list');
        
        $this->set('edu_classes', $eduClasses);
        $this->set('edu_sections', $eduSections);
        $this->set('parent_id', '');
        $this->set('user_id', $this->Session->read('Auth.User.id'));
        $this->set('username', $this->Session->read('Auth.User.username'));
    }

    /**
     * Prepares and sets data for the index view for teachers.
     *
     * This function retrieves a list of education classes and sections the teacher is associated with, and sets these lists, 
     * along with the parent ID and user ID, to be used in the view.
     *
     * @param mixed $id Optional parameter, not used in this function.
     */
    public function index_teacher_m($id = null) {
        $this->loadModel('Edu.EduPeriod');
        $this->loadModel('Edu.EduTeacher');
        $this->loadModel('Edu.EduCourseTeacherAssociation');
		$this->loadModel('Edu.EduAcademicYear');
		
		$ay = $this->EduAcademicYear->getActiveAcademicYear();
        $eduAcademicYearId = $ay['EduAcademicYear']['id'];
        
        $teacher = $this->EduTeacher->find('first', array(
                'conditions' => array('EduTeacher.user_id' => $this->Session->read('Auth.User.id'))
            ));
        
		$teacherId = $teacher['EduTeacher']['id'];
        
		$sections = $this->EduAssessment->EduSection->find('all', array('conditions' => array(
			'edu_teacher_id' => $teacherId,
			'EduClass.uni_teacher' => 1)));
		
        $classIds = array();
        foreach ($sections as $section) {
            $classIds[] = $section['EduSection']['edu_class_id'];
        }
		
		// if the teacher is associated with courses in the section
		$allSections = array();
		foreach ($ay['EduSection'] as $sec) {
			$allSections[] = $sec['id'];
		}
		
		$ctas = $this->EduCourseTeacherAssociation->find('all', array('conditions' => array(
			'EduCourseTeacherAssociation.edu_teacher_id' => $teacherId
		)));
		
		foreach ($ctas as $cta) {
            $classIds[] = $cta['EduSection']['edu_class_id'];
        }

        foreach ($teacher['EduClass'] as $cls) {
            $classIds[] = $cls['id'];
        }

        foreach ($teacher['EduSection'] as $sec) {
            $sectionIds[] = $sec['id'];
        }
		/**/
		$periods = $this->EduPeriod->find('all', array('conditions' => array(
			'EduPeriod.edu_teacher_id' => $teacherId,
			'EduPeriod.edu_section_id' => $allSections
		)));
		
		foreach ($periods as $period) {
            $classIds[] = $period['EduSection']['edu_class_id'];
        }

        $conditions = array('EduClass.id' => $classIds);

        $eduClasses = $this->EduAssessment->EduSection->EduClass->find('list', array(
                'conditions' => $conditions
            ));
        
        $eduSections = $this->EduAssessment->EduSection->find('list', array(
            'conditions' => array('id' => $sectionIds)));
        
        $this->set('edu_classes', $eduClasses);
        $this->set('edu_sections', $eduSections);
        $this->set('parent_id', '');
        $this->set('user_id', $this->Session->read('Auth.User.id'));
    }

    /**
     * This method is used to populate the list of classes and sections for a secretary to record marks.
     * It is used by the index_record_secretary_o view.
     */
    public function index_record_secretary_o() {
        $conditions = array('EduClass.grading_type <>' => 'G');
        $eduClasses = $this->EduAssessment->EduSection->EduClass->find('list', array('conditions' => $conditions));
        $eduSections = $this->EduAssessment->EduSection->find('list');

        $this->set('edu_classes', $eduClasses);
        $this->set('edu_sections', $eduSections);
        $this->set('parent_id', '');
    }

    /**
     * This method is used to populate the list of classes and sections for a secretary to record marks, when recording marks for all students in a class.
     * It is used by the index_record_all_secretary_o view.
     */
    public function index_record_all_secretary_o() {
        $conditions = array('EduClass.grading_type <>' => 'G');
        $eduClasses = $this->EduAssessment->EduSection->EduClass->find('list', array('conditions' => $conditions));
        
        $this->set('edu_classes', $eduClasses);
        $this->set('parent_id', '');
    }

    /**
     * This method is used to populate the list of classes and quarters for a secretary to record a new set of marks for an existing assessment.
     * It is used by the index_record_adjust_secretary_o view.
     */
    public function index_record_adjust_secretary_o() {
        $conditions = array('EduClass.grading_type <>' => 'G');
        $eduClasses = $this->EduAssessment->EduSection->EduClass->find('list', array('conditions' => $conditions));
        
        $this->loadModel('EduAcademicYear');
        $this->loadModel('EduQuarter');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $eduQuarters = $this->EduQuarter->find('list', array('conditions' => array(
            'EduQuarter.edu_academic_year_id' => $ay['EduAcademicYear']['id'],
            'EduQuarter.quarter_type' => 'E',
            'EduQuarter.start_date <=' => $this->today()
        )));

        $this->set('edu_quarters', $eduQuarters);
        $this->set('edu_classes', $eduClasses);
        $this->set('parent_id', '');
    }

    /**
     * Retrieves and sets the assessments for a given section and course.
     *
     * This method loads the active quarter and retrieves assessments 
     * based on the provided section and course IDs. If the course ID is 999, 
     * it retrieves all submitted assessments for the section.
     * The retrieved assessments, along with the section and course IDs, 
     * are set for the view.
     *
     * @param int $eduSectionId The ID of the education section.
     * @param int $eduCourseId The ID of the education course.
     */
    public function index_record_matrix_secretary_o($eduSectionId, $eduCourseId) {
        $this->loadModel('EduQuarter');
		
		$qr = $this->EduQuarter->getActiveQuarter();
        $eduQuarterId = $qr['EduQuarter']['id'];

		$conditions = array();
		$conditions['EduAssessment.edu_course_id'] = $eduCourseId;
		$conditions['EduAssessment.edu_quarter_id'] = $eduQuarterId;
		$conditions['EduAssessment.edu_section_id'] = $eduSectionId;

		//$conditions['EduAssessment.status'] = 'S'; // only SUBMITTED records are needed here
		
		if ($eduCourseId == 999 ){
			unset($conditions['EduAssessment.edu_course_id']);
			$conditions['EduAssessment.status'] = 'S';
		}
		
		$this->EduAssessment->recursive = 1;
        $assessments = $this->EduAssessment->find('all', array('conditions' => $conditions));

        $this->set('assessments', $assessments);
        $this->set('edu_section_id', $eduSectionId);
        $this->set('edu_course_id', $eduCourseId);
    }

    public function get_assessments($eduCourseId, $eduSectionId) {
        $this->loadModel('EduQuarter');
		
		$qr = $this->EduQuarter->getActiveQuarter();
        $eduQuarterId = $qr['EduQuarter']['id'];

		$conditions = array();
		$conditions['EduAssessment.edu_course_id'] = $eduCourseId;
		$conditions['EduAssessment.edu_quarter_id'] = $eduQuarterId;
		$conditions['EduAssessment.edu_section_id'] = $eduSectionId;

		//$conditions['EduAssessment.status'] = 'S'; // only SUBMITTED records are needed here
		
		if ($eduCourseId == 999 ){
			unset($conditions['EduAssessment.edu_course_id']);
			$conditions['EduAssessment.status'] = 'S';
		}
		$this->EduAssessment->recursive = 1;

        return $this->EduAssessment->find('all', array('conditions' => $conditions));
    }

    public function index_record_teacher_o() {
        $this->loadModel('Edu.EduPeriod');
        $this->loadModel('Edu.EduTeacher');
        $this->loadModel('Edu.EduCourseTeacherAssociation');
		$this->loadModel('Edu.EduAcademicYear');
		
		$ay = $this->EduAcademicYear->getActiveAcademicYear();
        
        $teacher = $this->EduTeacher->find('first', array(
                'conditions' => array('EduTeacher.user_id' => $this->Session->read('Auth.User.id'))
            ));
		$teacherId = $teacher['EduTeacher']['id'];
        // if the teacher is homeroom for self contained classes
		$sections = $this->EduAssessment->EduSection->find('all', array('conditions' => array(
			'edu_teacher_id' => $teacherId,
			'EduClass.uni_teacher' => 1)));
		
        $classIds = array();
        foreach ($sections as $section) {
            $classIds[] = $section['EduSection']['edu_class_id'];
        }
		
		// if the teacher is associated with courses in the section
		$allSections = array();
		foreach ($ay['EduSection'] as $sec) {
			$allSections[] = $sec['id'];
		}
		
		$ctas = $this->EduCourseTeacherAssociation->find('all', array('conditions' => array(
			'EduCourseTeacherAssociation.edu_section_id' => $allSections,
			'EduCourseTeacherAssociation.edu_teacher_id' => $teacherId
		)));

		foreach ($ctas as $cta) {
            $classIds[] = $cta['EduSection']['edu_class_id'];
        }
        
        foreach ($teacher['EduClass'] as $cls) {
            $classIds[] = $cls['id'];
        }
        
		/**/
		$periods = $this->EduPeriod->find('all', array('conditions' => array(
			'EduPeriod.edu_teacher_id' => $teacherId,
			'EduPeriod.edu_section_id' => $allSections
		)));
		
		foreach ($periods as $period) {
            $classIds[] = $period['EduSection']['edu_class_id'];
        }

        $conditions = array('EduClass.id' => $classIds);

        $eduClasses = $this->EduAssessment->EduSection->EduClass->find('list', array(
                'conditions' => $conditions
            ));
        $eduSections = $this->EduAssessment->EduSection->find('list');

        $this->set('edu_classes', $eduClasses);
        $this->set('edu_sections', $eduSections);
        $this->set('parent_id', '');
    }

    public function index_record_matrix_teacher_o() {
        // let it be identical
        $this->loadModel('Edu.EduPeriod');
        $this->loadModel('Edu.EduTeacher');
        $this->loadModel('Edu.EduCourseTeacherAssociation');
	   $this->loadModel('Edu.EduAcademicYear');
		
	   $ay = $this->EduAcademicYear->getActiveAcademicYear();
        
        $teacher = $this->EduTeacher->find('first', array(
                'conditions' => array('EduTeacher.user_id' => $this->Session->read('Auth.User.id'))
            ));
		$teacherId = $teacher['EduTeacher']['id'];
        // if the teacher is homeroom for self contained classes
		$sections = $this->EduAssessment->EduSection->find('all', array('conditions' => array(
			'edu_teacher_id' => $teacherId,
			'EduClass.uni_teacher' => 1)));
		
        $classIds = array();
        foreach ($sections as $section) {
            $classIds[] = $section['EduSection']['edu_class_id'];
        }
		
		// if the teacher is associated with courses in the section
		$allSections = array();
		foreach ($ay['EduSection'] as $sec) {
			$allSections[] = $sec['id'];
		}
		
		$ctas = $this->EduCourseTeacherAssociation->find('all', array('conditions' => array(
			'EduCourseTeacherAssociation.edu_section_id' => $allSections,
			'EduCourseTeacherAssociation.edu_teacher_id' => $teacherId
		)));

		foreach ($ctas as $cta) {
            $classIds[] = $cta['EduSection']['edu_class_id'];
        }
        
        foreach ($teacher['EduClass'] as $cls) {
            $classIds[] = $cls['id'];
        }
        
		/**/
		$periods = $this->EduPeriod->find('all', array('conditions' => array(
			'EduPeriod.edu_teacher_id' => $teacherId,
			'EduPeriod.edu_section_id' => $allSections
		)));
		
		foreach ($periods as $period) {
            $classIds[] = $period['EduSection']['edu_class_id'];
        }

        $conditions = array('EduClass.id' => $classIds);

        $eduClasses = $this->EduAssessment->EduSection->EduClass->find('list', array(
                'conditions' => $conditions
            ));
        $eduSections = $this->EduAssessment->EduSection->find('list');

        $this->set('edu_classes', $eduClasses);
        $this->set('edu_sections', $eduSections);
        $this->set('parent_id', '');
    }

    public function list_data_records() {
        $eduAssessmentId = (isset($_REQUEST['edu_assessment_id'])) ? $_REQUEST['edu_assessment_id'] : -1;
        $asIdparts = explode('-', $eduAssessmentId);
		if (count($asIdparts) > 1) {
			$eduAssessmentId = $asIdparts[0];
		}
        $assessment = $this->EduAssessment->read(null, $eduAssessmentId);

        $this->loadModel('Edu.EduQuarter');
        $this->loadModel('Edu.EduRegistration');
        $this->loadModel('Edu.EduAssessmentRecord');
        $this->loadModel('Edu.EduExemption');

        $activeQuarter = $this->EduQuarter->getActiveQuarter();
        $edu_quarter_id = $activeQuarter['EduQuarter']['id'];
        
        $this->loadModel('EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();

        $registrations = $this->EduRegistration->find('all', array(
                'conditions' => array(
				'edu_section_id' => (empty($assessment)? 0: $assessment['EduAssessment']['edu_section_id']),
				'EduRegistration.edu_academic_year_id' => $ay['EduAcademicYear']['id'],
				'EduRegistration.deleted' => false,
				'EduStudent.deleted' => false
			  ),
			  'order' => 'EduRegistration.name'
            ));
        
        $assessmentRecords = array();
        foreach ($registrations as $reg) {
            $exemptions = $this->EduExemption->find('all', array(
				'conditions' => array(
					'EduExemption.edu_student_id' => $reg['EduRegistration']['edu_student_id'],
					'EduExemption.edu_course_id' => $assessment['EduCourse']['id'],
                         'EduExemption.edu_quarter_id' => array($edu_quarter_id, 0)
				)
		  ));
		  if (!empty($exemptions))
				continue;
			
		  $ar = $this->EduAssessmentRecord->find('first', array('conditions' => array(
                    'edu_registration_id' => $reg['EduRegistration']['id'],
                    'edu_assessment_id' => $eduAssessmentId
            )));
            
            if (empty($ar)) {
                $ar = array('EduAssessmentRecord' => array(
                        'edu_registration_id' => $reg['EduRegistration']['id'],
                        'edu_assessment_id' => $eduAssessmentId,
                        'mark' => -1
                ));
                $this->EduAssessmentRecord->create();
                $this->EduAssessmentRecord->save($ar);
                $ar['EduAssessmentRecord']['id'] = $this->EduAssessmentRecord->id;
            }
            $ar = $this->EduAssessmentRecord->read(null, $ar['EduAssessmentRecord']['id']);

            $assessmentRecords[] = array('EduAssessmentRecord' => array(
                'id' =>  $ar['EduAssessmentRecord']['id'],
                'student' => $reg['EduRegistration']['name'],
                'identity_number' => $reg['EduStudent']['identity_number'],
                'rvalue' => str_replace('.0', '', $ar['EduAssessmentRecord']['mark']),
                'max_value' => $ar['EduAssessment']['max_value']
            ));
        }

        $this->set('assessment_records', $assessmentRecords);
        $this->set('results', count($assessmentRecords));
    }

    public function search() {
        // this is ....
    }

    public function submit_assessments() {
        $eduSectionId = $this->data['edu_section_id'];
        $eduCourseId = $this->data['edu_course_id'];

        try {
            $msg = 'OK'; // comment $this->check_assessments($eduCourseId, $eduSectionId);

            if ($msg == 'OK') {
				$activeQuarter = $this->EduAssessment->EduQuarter->getActiveQuarter();
				
                $conditions['EduAssessment.edu_section_id'] = $eduSectionId;
                $conditions['EduAssessment.edu_course_id'] = $eduCourseId;
                $conditions['EduAssessment.edu_quarter_id'] = $activeQuarter['EduQuarter']['id'];
                $conditions['EduAssessment.status'] = 'S';

                $assessments = $this->EduAssessment->find('all', array('conditions' => $conditions));

				foreach ($assessments as $assessment) {
					if (count($assessment['EduAssessmentRecord']) == 0) {
						throw new Exception('There is assessment not yet recorded.');
					}
				}
				
                foreach ($assessments as $assessment) {
                    $this->EduAssessment->read(null, $assessment['EduAssessment']['id']);
                    $this->EduAssessment->set('status', 'SB');
                    $this->EduAssessment->set('submitted_at', date('Y-m-d H:i:s'));
                    $this->EduAssessment->set('user_id', $this->Session->read('Auth.User.id'));
                    $this->EduAssessment->save();
                }
                $this->Session->setFlash(__('The Assessments are submitted successfully.', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'Please, revise records and try again. ' . $msg . ' <br/>(ERR-101-02)',
                    'helpcode' => 'ERR-101-02'));
            }
        } catch (Exception $ex) {
            $this->cakeError('cannotSaveRecord', array(
                'message' => 'Please, revise records and try again. (MSG: ' . $ex->getMessage() . '). (ERR-101-02)',
                'helpcode' => 'ERR-101-02'));
        }
    }

    public function make_assessments_checked($eduSectionId, $eduCourseId) {
        try {
            $msg = 'OK'; //connect $this->check_assessments($eduCourseId, $eduSectionId, 'SB');

            if ($msg == 'OK') {
				$activeQuarter = $this->EduAssessment->EduQuarter->getActiveQuarter();
				
                $conditions = array();
                $conditions['EduAssessment.edu_section_id'] = $eduSectionId;
                $conditions['EduAssessment.edu_course_id'] = $eduCourseId;
                $conditions['EduAssessment.edu_quarter_id'] = $activeQuarter['EduQuarter']['id'];
                $conditions['EduAssessment.status'] = 'SB';

                $assessments = $this->EduAssessment->find('all', array('conditions' => $conditions));

                if (count($assessments) == 0) {
					throw new Exception('There is no assessment selected.' . pr($conditions, true));
				}

				foreach ($assessments as $assessment) {
					if (count($assessment['EduAssessmentRecord']) == 0) {
						throw new Exception('There is assessment not yet recorded.');
					}
				}
				
                foreach ($assessments as $assessment) {
                    $this->EduAssessment->read(null, $assessment['EduAssessment']['id']);
                    $this->EduAssessment->set('checked_by_id', $this->Session->read('Auth.User.id'));
                    $this->EduAssessment->set('checked_at', date('Y-m-d H:i:s'));
                    $this->EduAssessment->save();
                }
                $this->Session->setFlash(__('The Assessments are checked successfully.', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'Please, revise records and try again. ' . $msg . ' <br/>(ERR-101-02)',
                    'helpcode' => 'ERR-101-02'));
            }
        } catch (Exception $ex) {
            $this->cakeError('cannotSaveRecord', array(
                'message' => 'Please, revise records and try again. (MSG: ' . $ex->getMessage() . '). (ERR-101-02)',
                'helpcode' => 'ERR-101-02'));
        }
    }

    public function make_assessments_approved($eduSectionId, $eduCourseId) {
        try {
            $msg = 'OK'; // comment $this->check_assessments($eduCourseId, $eduSectionId, 'SB');

            if ($msg == 'OK') {
				$activeQuarter = $this->EduAssessment->EduQuarter->getActiveQuarter();
				
                $conditions['EduAssessment.edu_section_id'] = $eduSectionId;
                $conditions['EduAssessment.edu_course_id'] = $eduCourseId;
                $conditions['EduAssessment.checked_by_id >'] = 0;
                $conditions['EduAssessment.edu_quarter_id'] = $activeQuarter['EduQuarter']['id'];
                $conditions['EduAssessment.status'] = 'SB';

                $assessments = $this->EduAssessment->find('all', array('conditions' => $conditions));

				foreach ($assessments as $assessment) {
					if (count($assessment['EduAssessmentRecord']) == 0) {
						throw new IoException('There is assessment not yet recorded.');
					}
				}
				
                foreach ($assessments as $assessment) {
                    $this->EduAssessment->read(null, $assessment['EduAssessment']['id']);
                    $this->EduAssessment->set('approved_by_id', $this->Session->read('Auth.User.id'));
                    $this->EduAssessment->set('approved_at', date('Y-m-d H:i:s'));
                    $this->EduAssessment->save();
                }
                $this->Session->setFlash(__('The Assessments are submitted successfully.', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'Please, revise records and try again. ' . $msg . ' <br/>(ERR-101-02)',
                    'helpcode' => 'ERR-101-02'));
            }
        } catch (Exception $ex){
            $this->cakeError('cannotSaveRecord', array(
                'message' => 'Please, revise records and try again. (MSG: ' . $ex->getMessage() . '). (ERR-101-02)',
                'helpcode' => 'ERR-101-02'));
        }
    }
    
	public function return_assessment($eduSectionId, $eduCourseId) {
        $this->autoRender = false;
		
		try {
            $msg = 'OK'; //connect $this->check_assessments($eduCourseId, $eduSectionId, 'SB');

            if ($msg == 'OK') {
				$activeQuarter = $this->EduAssessment->EduQuarter->getActiveQuarter();
				
                $conditions = array();
                $conditions['EduAssessment.edu_section_id'] = $eduSectionId;
                $conditions['EduAssessment.edu_course_id'] = $eduCourseId;
                $conditions['EduAssessment.edu_quarter_id'] = $activeQuarter['EduQuarter']['id'];
                $conditions['EduAssessment.status'] = 'SB';

                $assessments = $this->EduAssessment->find('all', array('conditions' => $conditions));

                if (count($assessments) == 0) {
					throw new Exception('There is no assessment selected.' . pr($conditions, true));
				}

                foreach ($assessments as $assessment) {
                    $ass = $this->EduAssessment->read(null, $assessment['EduAssessment']['id']);
                    $this->EduAssessment->set('status', 'S');
                    $this->EduAssessment->set('return_count', ++$ass['EduAssessment']['return_count']);
                    $this->EduAssessment->set('checked_by_id', 0);
                    $this->EduAssessment->set('checked_at', '0000-00-00 00:00:00');
                    $this->EduAssessment->save();
                }
                $this->Session->setFlash(__('The Assessments are returned successfully.', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'Please, revise records and try again. ' . $msg . ' <br/>(ERR-101-02)',
                    'helpcode' => 'ERR-101-02'));
            }
        } catch (Exception $ex) {
            $this->cakeError('cannotSaveRecord', array(
                'message' => 'Please, revise records and try again. (MSG: ' . $ex->getMessage() . '). (ERR-101-02)',
                'helpcode' => 'ERR-101-02'));
        }
    }

    public function return_assessment_curriculum($eduSectionId, $eduCourseId) {
        $this->autoRender = false;
		
		try {
            $msg = 'OK'; //connect $this->check_assessments($eduCourseId, $eduSectionId, 'SB');

            if ($msg == 'OK') {
				$activeQuarter = $this->EduAssessment->EduQuarter->getActiveQuarter();
				
                $conditions = array();
                $conditions['EduAssessment.edu_section_id'] = $eduSectionId;
                $conditions['EduAssessment.edu_course_id'] = $eduCourseId;
                $conditions['EduAssessment.edu_quarter_id'] = $activeQuarter['EduQuarter']['id'];
                $conditions['EduAssessment.status'] = 'SB';

                $assessments = $this->EduAssessment->find('all', array('conditions' => $conditions));

                if (count($assessments) == 0) {
					throw new Exception('There is no assessment selected.' . pr($conditions, true));
				}

                foreach ($assessments as $assessment) {
                    $ass = $this->EduAssessment->read(null, $assessment['EduAssessment']['id']);
                    $this->EduAssessment->set('status', 'SB');
                    $this->EduAssessment->set('return_count_curr', ++$ass['EduAssessment']['return_count_curr']);
                    $this->EduAssessment->set('checked_by_id', 0);
                    $this->EduAssessment->set('checked_at', '0000-00-00 00:00:00');
                    $this->EduAssessment->set('approved_by_id', 0);
                    $this->EduAssessment->set('approved_at', '0000-00-00 00:00:00');
                    $this->EduAssessment->save();
                }
                $this->Session->setFlash(__('The Assessments are returned successfully.', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'Please, check records and try again. ' . $msg . ' <br/>(ERR-101-02)',
                    'helpcode' => 'ERR-101-02'));
            }
        } catch (Exception $ex) {
            $this->cakeError('cannotSaveRecord', array(
                'message' => 'Please, check records and try again. (MSG: ' . $ex->getMessage() . '). (ERR-101-02)',
                'helpcode' => 'ERR-101-02'));
        }
    }

    public function save_assessment_records_matrix() {
        $this->autoRender = false;
        $this->loadModel('EduAssessmentRecord');
        try {
            $assessments = array();
            foreach ($this->data as $record) {
                if (count($assessments) == 0) {
                    $eduCourseId = str_replace('"', '', $record['edu_course_id']);
                    $eduSectionId = str_replace('"', '', $record['edu_section_id']);
                    $assessments = $this->get_assessments($eduCourseId, $eduSectionId);
                }
                foreach ($assessments as $assessment) {
                    $assessmentRecordId = str_replace('"', '', $record['ar_id_' . $assessment['EduAssessment']['id']]);
                    $mark = str_replace('"', '', $record['mark_' . $assessment['EduAssessment']['id']]);
                    $ar = $this->EduAssessmentRecord->read(null, $assessmentRecordId);
                    if (!empty($ar) && $ar != null) {
                        $this->EduAssessmentRecord->set('mark', $mark);
                        $this->EduAssessmentRecord->save();
                    }
                }
            }
            $this->Session->setFlash(__('The assessment records are saved successfully', true), '');
            $this->render('/elements/success');
        } catch (Exception $ex) {
            $this->cakeError('cannotSaveRecord', array(
                'message' => 'Not saved successfully. Please, try again. (MSG:' . $ex->getMessage() . '). (ERR-101-02)',
                'helpcode' => 'ERR-101-02'));
        }
    }
	
    public function save_assessment_records() {
        $this->autoRender = false;
        
        $this->loadModel('EduAssessmentRecord');
        try {
            foreach ($this->data as $record) {
                $id = str_replace('"', '', $record['id']);
                $mark = str_replace('"', '', $record['mark']);
                
                $ar = $this->EduAssessmentRecord->read(null, $id);

                if (!empty($ar) && $ar != null) {
                    $this->EduAssessmentRecord->set('mark', $mark);
                    $this->EduAssessmentRecord->save();
                }
            }
            $this->Session->setFlash(__('The assessment records are saved successfully', true), '');
            $this->render('/elements/success');
        } catch (Exception $ex) {
            
            $this->cakeError('cannotSaveRecord', array(
                'message' => 'Not saved successfully. Please, try again. (MSG:' . $ex->getMessage() . '). (ERR-101-02)',
                'helpcode' => 'ERR-101-02'));
        }
    }

    public function check_assessments($eduCourseId, $eduSectionId, $status = 'S') {
        $params = $this->params['pass'];
        if (is_array($params) && isset($params['edu_section_id']) && isset($params['edu_course_id'])) {
            $eduSectionId = $params['edu_section_id'];
            $eduCourseId = $params['edu_course_id'];
        }
        $conditions['EduAssessment.edu_section_id'] = $eduSectionId;
        $conditions['EduAssessment.edu_course_id'] = $eduCourseId;
        $conditions['EduAssessment.status'] = $status;

        $assessments = $this->EduAssessment->find('all', array('conditions' => $conditions));

        $msg = "";
        $total = 0;
        $nonFilledAssessments = array();
        $msg .= "<ol style='list-style: circle outside'>";
        foreach ($assessments as $a) {
            $total += $a['EduAssessment']['max_value'];
            $found = false;
            foreach ($a['EduAssessmentRecord'] as $rec) {
                if ($rec['mark'] > -1) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $nonFilledAssessments[] = $a['EduAssessment']['id'];
                $msg .= "<li style='margin-left: 20px'>Results have not recorded for the assessment " .
                    $a['EduAssessmentType']['name'] . ". </li>";
            }
        }
        if ($msg != "<ol style='list-style: circle outside'>") {
            $msg .= "</ol>";
        } else {
            $msg = "";
        }

        $msg2 = "<ol style='list-style: circle outside'>";
        $this->loadModel('EduRegistration');
        $students = $this->EduRegistration->find('all', array(
            'conditions' => array('EduRegistration.edu_section_id' => $eduSectionId)));
        
        foreach ($students as $student) {
            foreach ($assessments as $a) {
                if (!in_array($a['EduAssessment']['id'], $nonFilledAssessments)) {
                    $marks = $this->EduAssessment->EduAssessmentRecord->find('all', array(
                            'conditions' => array(
                                'EduAssessmentRecord.edu_registration_id' => $student['EduRegistration']['id'],
                                'EduAssessmentRecord.edu_assessment_id' => $a['EduAssessment']['id'],
                                'EduAssessmentRecord.mark >=' => 0
                            )
                        )
                    );
                    if (count($marks) == 0) {
                        //$this->log($marks, 'redfox');
                        $msg2 .= "<li style='margin-left: 20px'>Student " .
                            $student['EduStudent']['name'] . " " . $student['EduStudent']['id'] .
                            " has non-recorded assessment. </li>";
                    }
                }
            }
        }
        if ($msg2 == "<ol style='list-style: circle outside'>") {
            $msg2 = "";
        } else {
            $msg2 .= "</ol>";
        }
        $msg .= $msg2;

        if ($msg == "") {
            $msg = "OK";
        }
        return $msg;
    }

    /**
     * Called from the Assessment Management screen to get the list of already
     * maintained assessments for the specified course, section and also for the
     * current active quarter.
     *
     * params: $id int (course_id)
     * return: the list of assessments for the view.
     */
    public function list_data($id = null) {
        $eduCourseId = (isset($_REQUEST['edu_course_id'])) ? $_REQUEST['edu_course_id'] : -1;
        $eduSectionId = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : -1;

        $conditions = array();
        if ($eduSectionId > 0) {
            $conditions['EduAssessment.edu_section_id'] = $eduSectionId;
        }
        if ($eduCourseId != -1) {
            $conditions['EduAssessment.edu_course_id'] = $eduCourseId;
        }
        $this->loadModel('EduQuarter');
        $activeQuarter = $this->EduQuarter->getActiveQuarter();

        $conditions['EduAssessment.edu_quarter_id'] = $activeQuarter['EduQuarter']['id'];

        $assessments = $this->EduAssessment->find('all', array('conditions' => $conditions));
        $this->set('assessments', $assessments);
        $this->set('results', count($assessments));
    }
	
	public function list_data_for_teacher($id = null) {
        $conditions = array();
        if (!$id) {
            $conditions['EduAssessment.edu_teacher_id'] = $id;
        }
        $this->loadModel('EduQuarter');
        $activeQuarter = $this->EduQuarter->getActiveQuarter();

        $conditions['EduAssessment.edu_quarter_id'] = $activeQuarter['EduQuarter']['id'];

        $assessments = $this->EduAssessment->find('all', array('conditions' => $conditions));
        $this->set('assessments', $assessments);
        $this->set('results', count($assessments));
    }

    public function list_data2($id = null, $all = null) {
        $eduSectionId = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : -1;
        $eduCourseId = (isset($_REQUEST['edu_course_id'])) ? $_REQUEST['edu_course_id'] : -1;
        $include_all = (isset($_REQUEST['include_all'])) ? $_REQUEST['include_all'] : false;
        $eduQuarterId = (isset($_REQUEST['edu_quarter_id'])) ? $_REQUEST['edu_quarter_id'] : -1;
		
        if ($eduSectionId != -1) {
            $conditions['EduAssessment.edu_section_id'] = $eduSectionId;
        }
        if ($eduCourseId != -1) {
            $conditions['EduAssessment.edu_course_id'] = $eduCourseId;
        }
        if ($all != null) {
            $include_all = $all;
        }

        if(!$include_all) {
            $activeQuarter = $this->EduAssessment->EduQuarter->getActiveQuarter();
            $conditions['EduAssessment.edu_quarter_id'] = $activeQuarter['EduQuarter']['id'];
            $conditions['EduAssessment.status'] = 'S';
        } else if($eduQuarterId != -1) {
            $conditions['EduAssessment.edu_quarter_id'] = $eduQuarterId;
        }

        $assessments = $this->EduAssessment->find('all', array('conditions' => $conditions));
        $this->set('assessments', $assessments);
        $this->set('results', count($assessments));
    }

    public function list_data_combo($id = null, $all = null) {
        $eduSectionId = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : -1;
        $eduCourseId = (isset($_REQUEST['edu_course_id'])) ? $_REQUEST['edu_course_id'] : -1;
        $include_all = (isset($_REQUEST['include_all'])) ? $_REQUEST['include_all'] : false;
        $eduQuarterId = (isset($_REQUEST['edu_quarter_id'])) ? $_REQUEST['edu_quarter_id'] : -1;
		
        if ($eduSectionId != -1) {
            $conditions['EduAssessment.edu_section_id'] = $eduSectionId;
        }
        if ($eduCourseId != -1) {
            $conditions['EduAssessment.edu_course_id'] = $eduCourseId;
        }
        if ($all != null) {
            $include_all = $all;
        }

        if(!$include_all) {
            $activeQuarter = $this->EduAssessment->EduQuarter->getActiveQuarter();
            $conditions['EduAssessment.edu_quarter_id'] = $activeQuarter['EduQuarter']['id'];
            $conditions['EduAssessment.status'] = 'S';
        } else if($eduQuarterId != -1) {
            $conditions['EduAssessment.edu_quarter_id'] = $eduQuarterId;
        }

        $assessments = $this->EduAssessment->find('all', array('conditions' => $conditions));
        $this->set('assessments', $assessments);
        $this->set('results', count($assessments));
    }

    public function list_data_records_detail($eduSectionId = null, $eduCourseId = null) {
        $this->loadModel('EduQuarter');
        $activeQuarter = $this->EduQuarter->getActiveQuarter();

        $eduQuarterId = $activeQuarter['EduQuarter']['id'];

        $assessments = $this->EduAssessment->find('all', array(
            'conditions' => array(
                    'edu_section_id' => $eduSectionId,
                    'edu_course_id' => $eduCourseId,
                    'edu_quarter_id' => $eduQuarterId
                )
        ));

        //$this->log($assessments, 'assessments');

        $this->loadModel('Edu.EduRegistration');
        $this->loadModel('Edu.EduAssessmentRecord');
        $this->loadModel('EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
	   $edu_academic_year_id = $ay['EduAcademicYear']['id'];

	   $this->EduRegistration->recursive = 0;
        $students = $this->EduRegistration->find('all', array(
                'conditions' => array(
					'EduRegistration.edu_section_id' => $eduSectionId,
					'EduRegistration.edu_academic_year_id' => $edu_academic_year_id,
					'EduStudent.deleted' => false
				),
			'order' => 'EduRegistration.name'
            ));
         $records = array();
		//$this->log(count($students), 'ar2');
	    $count = 1;
	    $scaleIt = true;
         foreach ($students as $student) {
            $record = array(
                'id' => $student['EduRegistration']['id'],
                'student_name' => $student['EduRegistration']['name']);
            $totalGot = 0;
            $totalExp = 0;
            $dontFill = false;
			$asCount = 1;
            $scaleIt = true;
            $this->EduAssessmentRecord->recursive = -1;
            foreach ($assessments as $assessment) {
                
                $ar = $this->EduAssessmentRecord->find('first', array(
                        'conditions' => array(
                                'edu_registration_id' => $student['EduRegistration']['id'],
                                'edu_assessment_id' => $assessment['EduAssessment']['id']
                            )
                    ));
				
                if (!empty($ar)) {
                    $m = $ar['EduAssessmentRecord']['mark'] > 0? $ar['EduAssessmentRecord']['mark']: 0;
                    $record[$assessment['EduAssessment']['max_value'] . '-' .
                        $assessment['EduAssessment']['id']] = $m;
                    $record[$assessment['EduAssessment']['max_value'] . '-' .
                        $assessment['EduAssessment']['id']] = str_replace('.0', '', $m);
                    $totalGot += ($ar['EduAssessmentRecord']['mark'] > 0? $ar['EduAssessmentRecord']['mark']: 0);
                } else {
					$cond = array(
                                'edu_registration_id' => $student['EduRegistration']['id'],
                                'edu_assessment_id' => $assessment['EduAssessment']['id']
                            );
                    $record[$assessment['EduAssessment']['max_value'] . '-' . $assessment['EduAssessment']['id']] = '-';
					$scaleIt = false;
                }
                $totalExp += $assessment['EduAssessment']['max_value'];
            }
			if ($totalExp == 0) {
				$totalExp = 0.0001;
            }
            $record['Out of ' . $totalExp] = ($dontFill)? '-': number_format($totalGot, 1);
            $record['Out of ' . $totalExp] = str_replace('.0', '', $record['Out of ' . $totalExp]);
            $record['100%'] = ($dontFill)? '-': number_format((($totalGot / $totalExp) * 100), 1);
            $record['100%'] = str_replace('.0', '', $record['100%']);
			$record['Scale'] = ($scaleIt)? $this->getScale($record['100%']): '-';

            $records[] = $record;
        }
		
        $this->set('records', $records);
        $this->set('results', count($records));
    }

    public function get_list_data_records_detail($eduSectionId, $eduCourseId) {
        $this->loadModel('EduQuarter');
        $this->loadModel('Edu.EduRegistration');
        $this->loadModel('Edu.EduAssessmentRecord');
        
        $activeQuarter = $this->EduQuarter->getActiveQuarter();
        $eduQuarterId  = $activeQuarter['EduQuarter']['id'];
        
        $this->loadModel('Edu.EduAcademicYear');
        $active_ay = $this->EduAcademicYear->getActiveAcademicYear();

        $assessments = $this->EduAssessment->find('all', array(
            'conditions' => array(
                'edu_section_id' => $eduSectionId, 'edu_course_id' => $eduCourseId, 'edu_quarter_id' => $eduQuarterId
            )
        ));
        $students = $this->EduRegistration->find('all', array(
                'conditions' => array(
                	'EduRegistration.edu_section_id' => $eduSectionId,
                	'EduRegistration.edu_academic_year_id' => $active_ay['EduAcademicYear']['id'],
                	'EduStudent.deleted' => false
                ),
			'order' => 'EduRegistration.name'
            ));
        $records = array();
        
	   //$scaleIt = true;
        foreach ($students as $student) {
            $record = array(
                'id' => $student['EduRegistration']['id'],
                'student_name' => $student['EduRegistration']['name']);
            $totalGot = 0;
            $totalExp = 0;
            $dontFill = false;
            $scaleIt = true;
            
            foreach ($assessments as $assessment) {
                $ar = $this->EduAssessmentRecord->find('first', array(
                        'conditions' => array(
                                'edu_registration_id' => $student['EduRegistration']['id'],
                                'edu_assessment_id' => $assessment['EduAssessment']['id']
                            )
                    ));
                if (!empty($ar)) {
                    $m = $ar['EduAssessmentRecord']['mark'];
                    $record[$assessment['EduAssessment']['max_value'] . '-' .
                        $assessment['EduAssessment']['id'] . '-' .
                        $assessment['EduAssessmentType']['short_name']] = str_replace('.0', '', ($m > 0? $m: 0));
                    $totalGot += ($m > 0? $m: 0);
                } else {
                    $record[$assessment['EduAssessment']['max_value'] . '-' . $assessment['EduAssessment']['id'] .
                        '-' . $assessment['EduAssessmentType']['short_name']] = '-';
					$scaleIt = false;
                }
                $totalExp += $assessment['EduAssessment']['max_value'];
            }
			if ($totalExp == 0) {
				$totalExp = 0.0001;
            }
            $record['Out of ' . $totalExp] = ($dontFill)? '-': number_format($totalGot, 1);
            $record['Out of ' . $totalExp] = str_replace('.0', '', $record['Out of ' . $totalExp]);
            $record['100%'] = ($dontFill)? '-': number_format((($totalGot / $totalExp) * 100), 1);
            $record['100%'] = str_replace('.0', '', $record['100%']);
		  $record['Scale'] = ($scaleIt)? $this->getScale($record['100%']): '-';
		  
		  // TODO
		  if($record['Scale'] == 'NG') {
		  	continue;
		  }

            $records[] = $record;
        }
		
        return $records;
    }
	
	public $scales = array();
	
	public function getScale($mark = 0) {
		if (count($this->scales) == 0) {
			// load the scales
			$this->loadModel('EduScale');
			$scs = $this->EduScale->find('all');
			
			foreach ($scs as $sc) {
				$this->scales[$sc['EduScale']['scale']] = array('min' => $sc['EduScale']['min'], 'max' => $sc['EduScale']['max']);
			}
		}
		$scale = '-';
		foreach ($this->scales as $v => $s) {
			if ($s['max'] > $mark && $s['min'] <= $mark) {
				$scale = $v;
			}
		}
		return $scale;
	}

    public function view_detail($eduSectionId = null, $eduCourseId = null) {
        $this->loadModel('EduQuarter');
        $activeQuarter = $this->EduQuarter->getActiveQuarter();

        $eduQuarterId = $activeQuarter['EduQuarter']['id'];

        $assessments = $this->EduAssessment->find('all', array(
                'conditions' => array(
                        'edu_section_id' => $eduSectionId,
                        'edu_course_id' => $eduCourseId,
                        'edu_quarter_id' => $eduQuarterId
                    )
            ));
        $fields = array();
        $total = 0;
        foreach ($assessments as $assessment) {
            $fields[] = $assessment['EduAssessment']['max_value'] . '-' .
                $assessment['EduAssessment']['id'];
            $total += $assessment['EduAssessment']['max_value'];
        }

        $fields[] = 'Out of ' . $total;
        $fields[] = '100%';
		$fields[] = 'Scale';

        $this->set('fields', $fields);
        $this->set('edu_section_id', $eduSectionId);
        $this->set('edu_course_id', $eduCourseId);
    }

    public function view_detail_print($eduSectionId = null, $eduCourseId = null) {
        $this->loadModel('EduQuarter');
        $this->loadModel('EduSection');
        $this->loadModel('EduCourse');
        $this->loadModel('User');

        $activeQuarter = $this->EduQuarter->getActiveQuarter();

        $eduQuarterId = $activeQuarter['EduQuarter']['id'];

        $section = $this->EduSection->read(null, $eduSectionId);
        $course = $this->EduCourse->read(null, $eduCourseId);

        $assessments = $this->EduAssessment->find('all', array(
                'conditions' => array(
                        'edu_section_id' => $eduSectionId,
                        'edu_course_id' => $eduCourseId,
                        'edu_quarter_id' => $eduQuarterId
                    )
            ));
        $fields = array();
        $total = 0;
        $a = array();
        foreach ($assessments as $assessment) {
            $fields[] = $assessment['EduAssessment']['max_value'] . '-' . $assessment['EduAssessment']['id'] .
                '-' . $assessment['EduAssessmentType']['short_name'];
            $total += $assessment['EduAssessment']['max_value'];
            $a = $assessment;
        }
        if (!empty($a)) {
            $checker = $this->User->read(null, $a['EduAssessment']['checked_by_id']);
            $a['Checker'] = $checker;

            $approver = $this->User->read(null, $a['EduAssessment']['approved_by_id']);
            $a['Approver'] = $approver;
        }
        $this->set('assessment', $a);

        $fields[] = 'Out of ' . $total;
        $fields[] = '100%';
		$fields[] = 'Scale';

        $records = $this->get_list_data_records_detail($eduSectionId, $eduCourseId);
        $this->set('fields', $fields);
        $this->set('records', $records);
        $this->set('section', $section);
        $this->set('course', $course);
        $this->set('quarter', $activeQuarter);
        $this->set('edu_section_id', $eduSectionId);
        $this->set('edu_course_id', $eduCourseId);
    }

    public function add($id = null) {
        if (!empty($this->data)) {
            $this->autoRender = false;
            
			$sectionIds = array();
			// ie. if the record is submitted by the secretary of teachers
			if ($this->data['EduAssessment']['edu_section_id'] == 0) {
				$this->loadModel('EduAcademicYear');
				$this->loadModel('EduCourse');
				$this->loadModel('EduSection');
				
				$ay = $this->EduAcademicYear->getActiveAcademicYear();
				$eduAcademicYearId = $ay['EduAcademicYear']['id'];
				
				$course = $this->EduCourse->read(null, $this->data['EduAssessment']['edu_course_id']);
				$classId = 0;
				if (!empty($course)) {
					$classId = $course['EduCourse']['edu_class_id'];
                }
				$sections = $this->EduSection->find('all', array('conditions' => array(
					'edu_class_id' => $classId,
					'edu_academic_year_id' => $eduAcademicYearId
				)));
				
				foreach ($sections as $section) {
					$sectionIds[] = $section['EduSection']['id'];
				}
			} else {
				$sectionIds[] = $this->data['EduAssessment']['edu_section_id'];
			}
			
			$activeQuarter = $this->EduAssessment->EduQuarter->getActiveQuarter();
			$quarterId = 0;
			if (isset($activeQuarter['EduQuarter']['id'])) {
				$quarterId = $activeQuarter['EduQuarter']['id'];
			}
			// then for each section create the assessment
			foreach ($sectionIds as $sectionId) {
				$this->EduAssessment->create();
				$assessment = $this->data;
				$assessment['EduAssessment']['edu_quarter_id'] = $quarterId;
				$assessment['EduAssessment']['edu_section_id'] = $sectionId;
				$assessment['EduAssessment']['user_id'] = $this->Session->read('Auth.User.id');
                $assessment['EduAssessment']['submitted_at'] = date('Y-m-d H:i:s');
                $assessment['EduAssessment']['checked_at'] = date('Y-m-d H:i:s');
                $assessment['EduAssessment']['approved_at'] = date('Y-m-d H:i:s');
                $assessment['EduAssessment']['checked_by_id'] = 0;
                $assessment['EduAssessment']['approved_by_id'] = 0;
				
				if (!$this->EduAssessment->save($assessment)) {
					$this->Session->setFlash(__('The assessment could not be saved. Please, try again.', true), '');
					$this->render('/elements/failure');
					return;
				}
			}
			$this->Session->setFlash(__('The assessment has been saved', true), '');
			$this->render('/elements/success');
        }
		if (isset($_GET["edu_course_id"]) && isset($_GET["edu_section_id"])) {
			$this->set('edu_section_id', $_GET["edu_section_id"]);
			$this->set('edu_course_id', $_GET["edu_course_id"]);
		}
		$this->loadModel('Edu.EduTeacher');
		$teacher = $this->EduTeacher->find('first', array(
                'conditions' => array('EduTeacher.user_id' => $this->Session->read('Auth.User.id'))
            ));
		$teacherId = 0;
		$teacherName = '';
		if (!empty($teacher)) {
			$teacherId = $teacher['EduTeacher']['id'];
			$teacherName = $teacher['EduTeacher']['identity_number'];
		}
		$this->set('edu_teacher_id', $teacherId);
		$this->set('teacher_name', $teacherName);
		
        $assessmentTypes = $this->EduAssessment->EduAssessmentType->find('list');
		$this->set('assessment_types', $assessmentTypes);
		
		$activeQuarter = $this->EduAssessment->EduQuarter->getActiveQuarter();
        $this->set('edu_quarter', $activeQuarter);
		
        /* this is a commented part
        if (isset($_GET["edu_course_id"])) {
            if ($_GET["edu_course_id"] && $_GET["edu_section_id"]) {
                $this->set('edu_section_id', $_GET["edu_section_id"]);
                $this->set('edu_course_id', $_GET["edu_course_id"]);
                //$this->loadModel('Edu.EduPeriod');
                $active_quarter = $this->EduAssessment->EduQuarter->getActiveQuarter();
                $this->set('edu_quarter', $active_quarter);
                
                $conditions['EduPeriod.edu_course_id'] = $_GET["edu_course_id"];
                $conditions['EduPeriod.created >='] = $active_quarter['EduAcademicYear']['start_date'];
				
                $period = $this->EduPeriod->find('first', array('conditions' => $conditions));

                if (!empty($period)) {
                    $this->set('edu_teacher_id', $period['EduPeriod']['edu_teacher_id']);
                    $this->set('teacher_name', $period['EduTeacher']['identity_number']);
                    $assessment_types = $this->EduAssessment->EduAssessmentType->find('list');
                    $this->set(compact('assessment_types'));
                } else {
                    $this->autoRender = false;
                    echo 'alert("The course is not scheduled for the selected section.");';
                }
            }
        }*/
    }

    public function add_copy_from_previous($id = null) {

        if (!empty($this->data)) {
            $this->autoRender = false;

            $this->loadModel('EduAcademicYear');
            $this->loadModel('EduCourse');
            $this->loadModel('EduSection');
            
			$sectionIds = array();
            $edu_class_id = $this->data['EduAssessment']['edu_class_id'];

            $prev_ay = $this->EduAcademicYear->getPreviousAcademicYear(); 
            $ay_id = 0;
            if($prev_ay){
                $ay_id = $prev_ay['EduAcademicYear']['id'];
            }

            $prev_section = $this->EduSection->find('first', array('conditions' => array(
                'EduSection.edu_academic_year_id' => $ay_id,
                'EduSection.edu_class_id' => $edu_class_id
            )));

            // get all the assessments from previous year for the class
            $this->EduAssessment->recursive = -1;
            $prev_assessments = $this->EduAssessment->find('all', array(
                'conditions' => array(
                    'EduAssessment.edu_section_id' => $prev_section['EduSection']['id'],
                    'EduAssessment.edu_quarter_id' => 36 // 3rd term of AY 2022/23
                )
            ));

            // copy them for all current sections of the class, for the current quarter

			$ay = $this->EduAcademicYear->getActiveAcademicYear();
			$eduAcademicYearId = $ay['EduAcademicYear']['id'];
			
			$activeQuarter = $this->EduAssessment->EduQuarter->getActiveQuarter();
            $quarterLastDate = date('Y-m-d');
			$quarterId = 0;
			if (isset($activeQuarter['EduQuarter']['id'])) {
				$quarterId = $activeQuarter['EduQuarter']['id'];
                $quarterLastDate = $activeQuarter['EduQuarter']['end_date'];
			}

            $curr_sections = $this->EduSection->find('all', array('conditions' => array(
                'EduSection.edu_class_id' => $edu_class_id,
                'EduSection.edu_academic_year_id' => $eduAcademicYearId
            )));

			// then for each section create the assessment
			foreach ($prev_assessments as $prev_assessment) {
                foreach ($curr_sections as $section) {
                    $this->EduAssessment->create();
                    $assessment['EduAssessment'] = $prev_assessment['EduAssessment'];

                    unset($assessment['EduAssessment']['id']);
                    unset($assessment['EduAssessment']['deleted']);
                    unset($assessment['EduAssessment']['created']);
                    unset($assessment['EduAssessment']['modified']);
                    $assessment['EduAssessment']['edu_quarter_id'] = $quarterId;
                    $assessment['EduAssessment']['edu_section_id'] = $section['EduSection']['id'];
                    $assessment['EduAssessment']['user_id'] = $this->Session->read('Auth.User.id');
                    $assessment['EduAssessment']['date'] = $quarterLastDate;
                    $assessment['EduAssessment']['status'] = 'S';
                    $assessment['EduAssessment']['detail'] = '-';
                    $assessment['EduAssessment']['return_count'] = 0;
                    $assessment['EduAssessment']['return_count_curr'] = 0;
                    $assessment['EduAssessment']['submitted_at'] = date('Y-m-d H:i:s');
                    $assessment['EduAssessment']['checked_at'] = date('Y-m-d H:i:s');
                    $assessment['EduAssessment']['approved_at'] = date('Y-m-d H:i:s');
                    $assessment['EduAssessment']['checked_by_id'] = 0;
                    $assessment['EduAssessment']['approved_by_id'] = 0;
                    
                    if (!$this->EduAssessment->save($assessment)) {
                        $this->log($assessment, 'cannot_save_this_assessment');
                    }
                }
			}
			$this->Session->setFlash(__('The assessment has been saved', true), '');
			$this->render('/elements/success');
        }

        $edu_class_id = 0;
		if (isset($_GET["edu_class_id"])) {
            $edu_class_id = $_GET["edu_class_id"];
		}
        $this->set('edu_class_id', $edu_class_id);
    }


    /**
     * Copy all the assessments from the previous year for the specified class
     * to all the current sections of the class, for the current quarter.
     *
     * @param int $id the id of the class
     *
     * @return void
     */
    public function add_copy_from_previous_term($id = null) {

        if (!empty($this->data)) {
            $this->autoRender = false;

            $this->loadModel('EduAcademicYear');
            $this->loadModel('EduCourse');
            $this->loadModel('EduSection');
            
			$sectionIds = array();
            $edu_class_id = $this->data['EduAssessment']['edu_class_id'];

            $curr_ay = $this->EduAcademicYear->getActiveAcademicYear(); 
            //$prev_ay = $this->EduAcademicYear->getPreviousAcademicYear(); 
            $ay_id = 0;
            if($curr_ay){
                $ay_id = $curr_ay['EduAcademicYear']['id'];
            }

            $curr_section = $this->EduSection->find('first', array('conditions' => array(
                'EduSection.edu_academic_year_id' => $ay_id,
                'EduSection.edu_class_id' => $edu_class_id
            )));
            
            $prev_section_A = $this->EduSection->find('first', array('conditions' => array(
                'EduSection.edu_academic_year_id' => 19,   // TODO
                'EduSection.edu_class_id' => $edu_class_id,
                'EduSection.name' => 'A'
            )));

            // get all the assessments from previous year for the class
            $this->EduAssessment->recursive = -1;
            $prev_assessments = $this->EduAssessment->find('all', array(
                'conditions' => array(
                    'EduAssessment.edu_section_id' => $prev_section_A['EduSection']['id'],
                    'EduAssessment.edu_quarter_id' => 47 // 1st term of AY 2024/25
                )
            ));

            // copy them for all current sections of the class, for the current quarter

			$ay = $this->EduAcademicYear->getActiveAcademicYear();
			$eduAcademicYearId = $ay['EduAcademicYear']['id'];
			
			$activeQuarter = $this->EduAssessment->EduQuarter->getActiveQuarter();
               $quarterLastDate = date('Y-m-d');
			$quarterId = 0;
			if (isset($activeQuarter['EduQuarter']['id'])) {
				$quarterId = $activeQuarter['EduQuarter']['id'];
                    $quarterLastDate = $activeQuarter['EduQuarter']['end_date'];
			}

               $curr_sections = $this->EduSection->find('all', array('conditions' => array(
                   'EduSection.edu_class_id' => $edu_class_id,
                   'EduSection.edu_academic_year_id' => $eduAcademicYearId
               )));
               
               $aborted = false;

			// then for each section create the assessment
			foreach ($prev_assessments as $prev_assessment) {
                foreach ($curr_sections as $section) {
                    $this->EduAssessment->create();
                    $assessment['EduAssessment'] = $prev_assessment['EduAssessment'];

                    unset($assessment['EduAssessment']['id']);
                    unset($assessment['EduAssessment']['deleted']);
                    unset($assessment['EduAssessment']['created']);
                    unset($assessment['EduAssessment']['modified']);
                    $assessment['EduAssessment']['edu_quarter_id'] = $quarterId;
                    $assessment['EduAssessment']['edu_section_id'] = $section['EduSection']['id'];
                    $assessment['EduAssessment']['user_id'] = $this->Session->read('Auth.User.id');
                    $assessment['EduAssessment']['date'] = $quarterLastDate;
                    $assessment['EduAssessment']['status'] = 'S';
                    $assessment['EduAssessment']['detail'] = '-';
                    $assessment['EduAssessment']['return_count'] = 0;
                    $assessment['EduAssessment']['return_count_curr'] = 0;
                    $assessment['EduAssessment']['submitted_at'] = date('Y-m-d H:i:s');
                    $assessment['EduAssessment']['checked_at'] = date('Y-m-d H:i:s');
                    $assessment['EduAssessment']['approved_at'] = date('Y-m-d H:i:s');
                    $assessment['EduAssessment']['checked_by_id'] = 0;
                    $assessment['EduAssessment']['approved_by_id'] = 0;
                    
                    $existing = $this->EduAssessment->find('count', array('conditions' => array(
                    	'EduAssessment.edu_quarter_id' => $quarterId,
                    	'EduAssessment.edu_section_id' => $section['EduSection']['id'],
                    	'EduAssessment.edu_course_id' => $assessment['EduAssessment']['edu_course_id']
                    )));
                    
                    if($existing > 0) {
                    	$aborted = true;
                    	continue;
                    }
                    
                    
                    if (!$this->EduAssessment->save($assessment)) {
                        $this->log($assessment, 'cannot_save_this_assessment');
                    }
                }
			}
			
			if($aborted) {
				$this->Session->setFlash(__('Assessments might end up duplicated. So not created', true), '');
				$this->render('/elements/failure');
			} else {
				$this->Session->setFlash(__('The assessment has been saved', true), '');
				$this->render('/elements/success');
			}
        }

        $edu_class_id = 0;
		if (isset($_GET["edu_class_id"])) {
            $edu_class_id = $_GET["edu_class_id"];
		}
        $this->set('edu_class_id', $edu_class_id);
    }
	
    public function edit($id = null, $parentId = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid assessment', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            $oldAssessment = $this->EduAssessment->read(null, $this->data['EduAssessment']['id']);
            $this->loadModel('EduAssessmentRecord');

            if ($this->EduAssessment->save($this->data)) {
                $multiplier = $this->data['EduAssessment']['max_value'] / $oldAssessment['EduAssessment']['max_value'];

                foreach ($oldAssessment['EduAssessmentRecord'] as $ar) {
                    $this->EduAssessmentRecord->read(null, $ar['id']);
                    $this->EduAssessmentRecord->set('mark', $ar['mark'] * $multiplier);
                    $this->EduAssessmentRecord->save();
                }
                $this->Session->setFlash(__('The assessment has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The assessment could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_assessment', $this->EduAssessment->read(null, $id));

        if ($parentId) {
            $this->set('parent_id', $parentId);
        }

        $eduAssessmentTypes = $this->EduAssessment->EduAssessmentType->find('list');
        $this->set('edu_assessment_types', $eduAssessmentTypes);
    }
	
    public function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for assessment', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduAssessment->read(null, $i);
        	          $this->EduAssessment->set('deleted', true);
                    $this->EduAssessment->save();
                }
                $this->Session->setFlash(__('Assessment deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Assessment was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
        	  $this->EduAssessment->read(null, $id);
        	  $this->EduAssessment->set('deleted', true);
        	  
            if ($this->EduAssessment->save()) {
                $this->Session->setFlash(__('Assessment deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Assessment was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }
	
	// for migration purposes
	public function build_assessments() {
		$this->loadModel('Edu.EduSection');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduCourse');
		$this->loadModel('Edu.EduAssessment');
		$this->loadModel('Edu.EduAssessmentRecord');
		$quarterId = 14;
		$sections = $this->EduSection->find('all', array(
            'conditions' => array('EduSection.id' => 88)));
		$allMarks = array();
		// read all the sheets in the excel file (having all the assessments)
		// $all_marks ==> array -> [student id] -> array([subject] => mark])
		// 1. open the file to read
		$filDir = IMAGES . 'assessments' . DS . 'assessment.xlsx';
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel = $objReader->load($filDir);
		// 2. open the sheet for reading
		$subjects = array();
		$studentMarks = array();
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			if ($worksheet->getTitle() <> 'G.1B Assis') { continue; }
			
			foreach ($worksheet->getRowIterator() as $row) {
				if ($row->getRowIndex() < 3) {
					continue;
				} elseif ($row->getRowIndex() == 3) {
					// 3. on the row #3 find all the subjects (array([#column] => subject name) starting from D3
					//    where A3 = #, B3 = Student Name, C3 = Student ID Number, D3 = the first subject
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(false);
					foreach ($cellIterator as $cell) {
						if (!is_null($cell)) {
							$co = $cell->getCoordinate();
							if ($co < 'E3') {
								continue;
							} else {
								$cv = $cell->getCalculatedValue();
								// Strip out carriage returns
								$cv = ereg_replace("\r", '', $cv);
								$cv = ereg_replace("\n\n", '', $cv);
								$cv = ereg_replace("\n", '', $cv);
								$cv = ereg_replace("  ", ' ', $cv);
								$cv = trim($cv);
								$co = substr($co, 0, -1);
								$subjects[$co] = $cv;
							}
						}
					}
				} else {
					if ($row->getRowIndex() % 2 == 1) {
						continue;
					}
					// 4. Starting A4 row, build an array of subject -> mark for the student
					//    then add this array to the $all_marks array indexed by student id number.
					//    the resulting array will be used in the following code at the end of the function.
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(false);
					$stMarks = array();
					foreach ($cellIterator as $cell) {
						if (!is_null($cell)) {
							$co = $cell->getCoordinate();
							$coC = substr($co, 0, 1); // column
							$cv = $cell->getCalculatedValue();
							$cv = ereg_replace("\r", '', $cv);
							$cv = ereg_replace("\n\n", '', $cv);
							$cv = ereg_replace("\n", '', $cv);
							$cv = ereg_replace("  ", '', $cv);
							if ($coC == 'C') {
								$stMarks['identity_number'] = $cv;
							} elseif ($coC > 'D') {
								$stMarks[$subjects[$coC]] = $cv;
							}
						}
					}
					$studentMarks[$stMarks['identity_number']] = $stMarks;
				}
			}
		}
		
		// Now use $student_marks for the following code
		foreach ($sections as $section) {
			$courses = $this->EduCourse->find('all', array(
                'conditions' => array('EduCourse.edu_class_id' => $section['EduSection']['edu_class_id'])));
			$students = $this->EduRegistration->find('all', array('conditions' => array(
					'EduRegistration.edu_section_id' => $section['EduSection']['id'],
					'EduStudent.deleted' => false
				)));
			foreach ($courses as $course) {
				$assessment = array('EduAssessment' => array(
						'edu_assessment_type_id' => 11, // Final Exam
						'edu_teacher_id' => 0,
						'edu_section_id' => $section['EduSection']['id'],
						'max_value' => 100,
						'date' => date('Y-m-d'),
						'status' => 'S', // submitted
						'detail' => 'Final Exam',
						'edu_course_id' => $course['EduCourse']['id'],
						'edu_quarter_id' => $quarterId,
						'deleted' => 0
					));
				$this->EduAssessment->create();
				$this->EduAssessment->save($assessment);
				$assessmentId = $this->EduAssessment->id;
				$subjectName = $course['EduSubject']['name'];
				
				foreach ($students as $student) {
					$mark = $studentMarks[$student['EduStudent']['identity_number']][$subjectName];
					$ar = array('EduAssessmentRecord' => array(
							'edu_registration_id' => $student['EduRegistration']['id'],
							'edu_assessment_id' => $assessmentId,
							'mark' => $mark,
							'bonus' => 0,
							'deleted' => 0
						));
					$this->EduAssessmentRecord->create();
					$this->EduAssessmentRecord->save($ar);
				}
			}
		}
	}
	
	// for migration purposes
	public function build_evaluations() {
		$this->loadModel('Edu.EduSection');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduEvaluation');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		$quarterId = 4;
		$evaluationValues = array(1 => 5, 2 => 6, 3 => 12);
		
		$sections = $this->EduSection->find('all', array(
            'conditions' => array('edu_academic_year_id' => 14, 'edu_class_id' => array(1,2,3))));
		foreach ($sections as $section) {
			$evaluations = $this->EduEvaluation->find('all', array(
                'conditions' => array('EduEvaluation.edu_class_id' => $section['EduSection']['edu_class_id'])));
			$students = $this->EduRegistration->find('all', array('conditions' => array(
					'EduRegistration.edu_section_id' => $section['EduSection']['id'],
					'EduStudent.deleted' => false
				)));
			
			foreach ($evaluations as $evaluation) {
				$evaluationValueId = isset($evaluationValues[$evaluation['EduEvaluationArea']['evaluation_value_group']])?
                    $evaluationValues[$evaluation['EduEvaluationArea']['evaluation_value_group']]: 0;
				foreach ($students as $student) {
					$regEv = array('EduRegistrationEvaluation' => array(
							'edu_registration_id' => $student['EduRegistration']['id'],
							'edu_evaluation_id' => $evaluation['EduEvaluation']['id'],
							'edu_quarter_id' => $quarterId,
							'edu_evaluation_value_id' => $evaluationValueId,
							'deleted' => 0
						));
					$this->EduRegistrationEvaluation->create();
					$this->EduRegistrationEvaluation->save($regEv);
				}
			}
		}
	}

    public function generate_excel($edu_section_id, $edu_course_id) {
        $this->autoRender = false;
        $this->layout = 'ajax';

        $this->loadModel('EduQuarter');
        $this->loadModel('EduRegistration');
        $this->loadModel('EduSection');
        $this->loadModel('EduCourse');

        // 1. get active quarter
        $quarter = $this->EduQuarter->getActiveQuarter();
        // 2. find all assessments of section, course and quarter
        $assessments = $this->EduAssessment->find('all', array('conditions' => array(
            'EduAssessment.edu_section_id' => $edu_section_id,
            'EduAssessment.edu_course_id' => $edu_course_id,
            'EduAssessment.edu_quarter_id' => $quarter['EduQuarter']['id']
        )));
        
        $this->loadModel('Edu.EduAcademicYear');
        
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $eduAcademicYearId = $ay['EduAcademicYear']['id'];

        // 3. find all active students in the section that are not exempted for the selected course
        $students = $this->EduRegistration->find('all', array('conditions' => array(
            'EduRegistration.edu_section_id' => $edu_section_id,
            'EduRegistration.edu_academic_year_id' => $eduAcademicYearId
        )));

        $section = $this->EduSection->read(null, $edu_section_id);
        $course = $this->EduCourse->read(null, $edu_course_id);

        // 4. Create an excel document
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("Assessment Sheet");
        $objPHPExcel->getProperties()->setCreator("Redfox System");
        $objPHPExcel->getProperties()->setDescription("Assessment Sheet generated from Redfox School Management System");

        // Get the active sheet
        $sheet = $objPHPExcel->getActiveSheet();

        $letters = array(1 => 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O');
        // develop header elements/cells
        // Merge cells A1 to B2
        $sheet->mergeCells('A1:' . $letters[count($assessments) + 3] . '1');

        // Set the value of the merged cell
        $sheet->setCellValue('A1', 'Assessment Records - Section ' . $section['EduClass']['name'] . $section['EduSection']['name'] . ', Course ' . $course['EduSubject']['name']);

        // Get the style object for the merged cell
        $style = $sheet->getStyle('A1');

        // Get the alignment object for the style
        $alignment = $style->getAlignment();

        // Set the horizontal alignment to center
        $alignment->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // Set the vertical alignment to center
        $alignment->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        // list all the assessments in a row, display numbers, set the title as comment for the cell
        $sheet->setCellValue('A2', 'No.');
        $sheet->setCellValue('B2', 'ID');
        $sheet->setCellValue('C2', 'Name');
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $fill = $sheet->getCell('A1')->getStyle()->getFill();
        $fill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $fill->getStartColor()->setRGB('EEDCDC');

        //$comm = $sheet->getComment('B2');
        //$richText = new PHPExcel_RichText();
        //$richText->createTextRun('Student ID Number');
        //$richText->createText($textRun);
        //$comm->setText($richText);
        //$comm->setAuthor('Invictus Software');
        //$comm->setVisible(true);

        $cell_number = 4;
        foreach ($assessments as $assessment) {
            $sheet->setCellValue($letters[$cell_number] . '2', $assessment['EduAssessment']['max_value'] . ' (ID=' . $assessment['EduAssessment']['id']  .  ')');
            
            // Get the comment object for cell A1
            $comment = $sheet->getComment($letters[$cell_number] . '2');
            $richText = new PHPExcel_RichText();
            $richText->createTextRun($assessment['EduAssessment']['id']  .  '-'  . $assessment['EduAssessmentType']['name']);
            $comment->setText($richText);
            $comment->setAuthor('Redfox System');
            $comment->setVisible(false);

            $cell_number++;
        }
        // for each of the students, list them down names in column C, 
        //  while column A have order number, and column B for their ID
        $roll_number = 1;
        foreach ($students as $registration) {
            $sheet->setCellValue('A' . ($roll_number+2), $roll_number);
            $sheet->setCellValue('B' . ($roll_number+2), $registration['EduStudent']['identity_number']);
            $sheet->setCellValue('C' . ($roll_number+2), $registration['EduRegistration']['name']);
            $roll_number++;
        }
        // save the file and render it as binary file.
        // Save the Excel document or output it to the browser
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        // Output the Excel document to the browser's output stream
        header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=Assessment_Sheet_" . date('YmdHis') . ".xlsx");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        //ob_start();
        $objWriter->save('php://output');
        //$excelData = ob_get_clean();

        //$this->set('data', $excelData);
    }

    public function upload_excel() {
        if (!empty($this->data)) {
            $this->autoRender = false;
            $this->layout = 'ajax';

            // load models
			$this->loadModel('EduQuarter');
            $this->loadModel('EduRegistration');
            
			// upload image
            $file = $this->data['EduAssessment']['excel_file_name'];
            $file_name = basename($file['name']);
            $fext = substr($file_name, strrpos($file_name, "."));
            $fname = time(); // str_replace($fext, "", $file_name);
            $file_name = 'sa' . $fname . $fext;

            if (!file_exists(IMAGES . 'assessment_uploads')) {
                mkdir(IMAGES . 'assessment_uploads', 0777);
            }

            if (move_uploaded_file($file['tmp_name'], IMAGES . 'assessment_uploads' . DS . $file_name)) {
                // 1. get active quarter
                $quarter = $this->EduQuarter->getActiveQuarter();

                // get the uploaded file and treat as Excel
                $file_name = IMAGES . 'assessment_uploads' . DS . $file_name;
                $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                $objPHPExcel = $objReader->load($file_name);
                $results = '';
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    //$this->log($worksheet->getTitle(), 'upload_assessments');
                    if($worksheet->getTitle() == 'Worksheet') {
                        $this->importAssessments($worksheet);
                        break;
                    }
                }
                $this->Session->setFlash(__('Assessment uploaded successfully', true));
                $this->render('/elements/success');
            } else {
                $this->log('Cannot upload the file', 'upload_assessments');
                $this->Session->setFlash(__('Cannot upload the file. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    private function importAssessments($worksheet) {
        $assessments_identified = array();
        $indexer = 2;

        $this->loadModel('EduAssessmentRecord');
        $this->loadModel('EduStudent');
        $this->loadModel('EduRegistration');


        foreach ($worksheet->getRowIterator() as $row) {
			if($row->getRowIndex() <= 1) {
				continue;
			}

            // header row (in my case: 1 and 2)
			if($row->getRowIndex() == 2) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = str_replace("  ", ' ',$cv);
						$cv = str_replace("+", '', $cv);
						$cv = str_replace("AA", 'A',$cv);
						$cv = strtoupper($cv);
						
						$co = $cell->getCoordinate();
						//$this->log('CV: ' . $cv . ' (' . $co . ')', 'upload_assessments');
                        if(!in_array($co, array('A2', 'B2', 'C2'))){
                            // cv = 10 (ID = 6)
                            $startPosition = strpos($cv, "=") + 1;
                            $endPosition = strrpos($cv, ")");
                            $value = substr($cv, $startPosition, $endPosition - $startPosition);

                            //$this->log('Assess ID:' . $value, 'upload_assessments'); // Output: 6
                            $assessments_identified[substr($co, 0, 1)] = $value;
                        }

                    }
                }
            } else {
                $cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);

                $indexer++;
                $student_identity = '';
                $reg_id = 0;

                foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = str_replace("  ", ' ',$cv);
						$cv = str_replace("+", '', $cv);
						$cv = str_replace("AA", 'A',$cv);
						$cv = strtoupper($cv);
						
						$co = $cell->getCoordinate();
						//$this->log('CV: ' . $cv . ' (' . $co . ')', 'upload_assessments');
                        if($co == 'B' . $indexer) {
                            $student_identity = $cv;
                            $student = $this->EduStudent->find('first', array('conditions' => array(
                                'EduStudent.identity_number' => $student_identity
                            )));
                            $student_id = $student['EduStudent']['id'];
                            $registration = $this->EduRegistration->getLastRegistration($student_id);
                            $reg_id = $registration['EduRegistration']['id'];
                        }
                        if(!in_array($co, array('A' . $indexer, 'B' . $indexer, 'C' . $indexer))) {
                            // cv = 9
                            $value = $cv;
                            $ass_id = $assessments_identified[substr($co, 0, 1)];

                            $assessment_record = array('EduAssessmentRecord' => array(
                                'edu_registration_id' => $reg_id,
                                'edu_assessment_id' => $ass_id,
                                'mark' => $value == ''? -1: $value,
                                'bonus' => 0
                            ));

                            $ar = $this->EduAssessmentRecord->find('first', array('conditions' => array(
                                'EduAssessmentRecord.edu_registration_id' => $reg_id,
                                'EduAssessmentRecord.edu_assessment_id' => $ass_id,
                            )));

                            if(empty($ar)) {
                                $this->EduAssessmentRecord->create();
                            } else {
                                $assessment_record['EduAssessmentRecord']['id'] = $ar['EduAssessmentRecord']['id'];
                            }

                            if(!$this->EduAssessmentRecord->save($assessment_record)) {
                                $this->log('Cannot save: Error - ' . print_r($this->EduAssessmentRecord->validationErrors, true), 'upload_assessments');
                            }

                            //$this->log('Assess Record:' . print_r($assessment_record, true), 'upload_assessments'); // Output: 9
                        }
                    }
                }                
            }
        }
    }
}
