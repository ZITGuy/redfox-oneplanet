<?php

class EduRegistrationsController extends EduAppController {

    var $name = 'EduRegistrations';

    #region Index functions

    /**
     * Index action
     *
     * @return void
     */
    public function index() {
        $edu_students = $this->EduRegistration->EduStudent->find('all');
        $this->set(compact('edu_students'));
    }
    
    /**
     * View action for a list of registrations by class
     *
     * @return void
     */
    public function index_v() {
        $edu_classes = $this->EduRegistration->EduClass->find('all');
        $this->set(compact('edu_classes'));
    }
	
    /**
     * View action for a list of academic years by section
     *
     * Retrieves and sets all academic years associated with sections 
     * for display in the view.
     *
     * @return void
     */
	public function index_v_per_ay() {
        $edu_academic_years = $this->EduRegistration->EduSection->EduAcademicYear->find('all');
        $this->set(compact('edu_academic_years'));
    }
	
    /**
     * View action for a list of academic years with enrollments
     *
     * Retrieves and sets all academic years associated with 
     * sections for display in the view, focusing on student enrollments.
     *
     * @return void
     */
	public function index_v_per_ay_enrollment() {
        $edu_academic_years = $this->EduRegistration->EduSection->EduAcademicYear->find('all');
        $this->set(compact('edu_academic_years'));
    }

    /**
     * Index action for card printing
     *
     * Loads and manages section data for card printing view. 
     * Clears the session section ID if set. Retrieves the active 
     * academic year and sections associated with the current 
     * campus ID, ordering them by class value. Sets the sections 
     * for use in the view.
     *
     * @return void
     */
    public function index_card_print() {
        $this->loadModel('EduSection');
		if($this->Session->check('edu_section_id')) {
			$this->Session->delete('edu_section_id');
		}

        $this->loadModel('EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
		$edu_academic_year_id = $ay['EduAcademicYear']['id'];

        $sections = $this->EduSection->find('all', array(
            'conditions' => array(
                'EduSection.edu_campus_id' => $this->Session->read('Auth.User.edu_campus_id'),
                //'EduSection.edu_academic_year_id' => $edu_academic_year_id
            ),
            'order' => 'EduClass.cvalue ASC'
        ));

        $this->set(compact('sections'));
    }

    /**
     * Index action for students per class
     *
     * Loads and manages class data for the view. Clears the session 
     * class ID if set. Retrieves all classes with a class value greater 
     * than or equal to zero, ordering them by class value. Sets the 
     * classes for use in the view.
     *
     * @return void
     */
    public function index_students_per_class() {
        $this->loadModel('EduClass');
        if($this->Session->check('edu_class_id')) {
            $this->Session->delete('edu_class_id');
        }
        $edu_classes = $this->EduClass->find('all', array(
            'conditions' => array('EduClass.cvalue >=' => 0),
            'order' => 'EduClass.cvalue ASC'
        ));

        $this->set(compact('edu_classes'));
    }

    /**
     * Index of students per section
     *
     * @author  Marcio Pires <marcio@tecnodz.com>
     * @license MIT
     * @version 1.0, 2013-10-22
     * @link    http://www.tecnodz.com
     * @since   1.0
     * @todo    Add a way to filter by section
     * @todo    Add a way to filter by academic year
     * @todo    Add a way to filter by grading type
     */
    public function index_student_per_section_o() {
        $this->loadModel('EduSection');
        $this->loadModel('EduTeacher');
		if($this->Session->check('edu_section_id')) {
			$this->Session->delete('edu_section_id');
		}
		$this->loadModel('EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
		$edu_academic_year_id = $ay['EduAcademicYear']['id'];
		
        $teacher = $this->EduTeacher->find('first', array(
            'conditions' => array('EduTeacher.user_id' => $this->Session->read('Auth.User.id'))
        ));

        $edu_sections = null;
        if (!empty($teacher)) {
            $teacher_id = $teacher['EduTeacher']['id'];
            // if the teacher is homeroom -- for self contained classes
            $edu_sections = $this->EduSection->find('all', array('conditions' => array(
                'edu_academic_year_id' => $edu_academic_year_id,
                'OR' => array('edu_teacher_id' => $teacher_id, 'co_teacher_id' => $teacher_id)
                ), 'order' => 'EduClass.cvalue'));
        } else {
            $edu_sections = $this->EduSection->find('all', array('conditions' => array(
                'edu_academic_year_id' => $edu_academic_year_id, 'EduClass.grading_type <>' => 'G'
                ), 'order' => 'EduClass.cvalue'));
        }
        $sections = array();
        foreach ($edu_sections as $sec) {
            $sections[$sec['EduSection']['id']] = $sec['EduClass']['name'] . ' - ' . $sec['EduSection']['name'];
        }
        /* comment
        $sections = $this->EduSection->find('all', array(
            'conditions' => array('EduSection.edu_academic_year_id' => $edu_academic_year_id),
            'order' => 'EduClass.cvalue'
        ));*/

        $this->set(compact('sections'));
    }

    public function index_student_filter() {
        // empty
    }
	
    /**
     * Print the roster of students in a given section
     *
     * @param int $eduSectionId Section id
     * @param string $format Format of the output, default is 'PDF'
     * @return void
     */
	public function print_roster($eduSectionId, $format = 'PDF') {
		$this->layout = 'ajax';
        $this->loadModel('EduSection');
        $this->loadModel('EduEvaluationValue');
		
		$this->EduSection->recursive = 2;
		$this->EduSection->unbindModel(array('hasMany' => array('EduRegistration')));
                $section = $this->EduSection->read(null, $eduSectionId);
		
		$class = $this->EduSection->EduClass->read(null, $section['EduSection']['edu_class_id']);
		$courses = $this->EduSection->EduClass->EduCourse->find('all', array('conditions' =>
            array('EduCourse.edu_class_id' => $section['EduSection']['edu_class_id'])) );
		$evaluations = $this->EduSection->EduClass->EduEvaluation->find('all', array(
            'conditions' => array('EduEvaluation.edu_class_id' => $section['EduSection']['edu_class_id'])));
		
		$this->EduRegistration->recursive = 2;
		$registrations = $this->EduRegistration->find('all', array('conditions' =>
            array('EduRegistration.edu_section_id' => $eduSectionId, 'EduStudent.deleted' => false),
            'order' => 'EduRegistration.name'));
		$evaluationValues = $this->EduEvaluationValue->find('all');
		
        $this->set('section', $section);
        $this->set('class', $class);
        $this->set('courses', $courses);
        $this->set('evaluations', $evaluations);
        $this->set('evaluation_values', $evaluationValues);
        $this->set('edu_section_id', $eduSectionId);
		$this->set('registrations', $registrations);
		$this->set('format', $format);
		
        $this->set('company_url', $this->getSystemSetting('COMPANY_URL'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_address', $this->getSystemSetting('COMPANY_ADDRESS'));
	}

    /**
     * Print students per section
     *
     * @param int $eduSectionId Section id
     * @param string $format Format of the output, default is 'PDF'
     * @return void
     */
    public function print_students_per_section($eduSectionId, $format = 'PDF') {
		$this->layout = 'ajax';
        $this->loadModel('EduSection');
		$this->EduSection->recursive = 2;
		$this->EduSection->unbindModel(array('hasMany' => array('EduRegistration')));
        
        $section = $this->EduSection->read(null, $eduSectionId);
		$class = $this->EduSection->EduClass->read(null, $section['EduSection']['edu_class_id']);
		
		$this->EduRegistration->recursive = 2;
		$registrations = $this->EduRegistration->find('all', array('conditions' =>
            array('EduRegistration.edu_section_id' => $eduSectionId, 'EduStudent.deleted' => false),
            'order' => 'EduRegistration.name'));
		
        $this->set('section', $section);
        $this->set('class', $class);
        $this->set('edu_section_id', $eduSectionId);
		$this->set('registrations', $registrations);
		$this->set('format', $format);

        $this->set('company_url', $this->getSystemSetting('COMPANY_URL'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_address', $this->getSystemSetting('COMPANY_ADDRESS'));
	}

    /**
     * Promote students to next class
     *
     * @return void
     */
    public function promote_students() {
        if (!empty($this->data)) {
            try {
                $eduSectionId = $this->data['EduRegistration']['edu_section_id'];
                $conditions = array(
                        'EduRegistration.edu_section_id' => $eduSectionId,
                        'EduRegistration.status_id' => 1,
						'EduStudent.deleted' => false
                    );
                $regs = $this->EduRegistration->find('all', array('conditions' => $conditions));

                foreach ($regs as $reg) {
                    $this->EduRegistration->read(null, $reg['EduRegistration']['id']);
                    $this->EduRegistration->set('status_id', 13);  // Promoted
                    $this->EduRegistration->save();
                }
                $this->Session->setFlash(__('The Student promoted successfully', true), '');
                $this->render('/elements/success');
            } catch (Exception $ex) {
                $this->Session->setFlash(__('The Student cannot be promoted. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->loadModel('EduClass');
        $eduClasses = $this->EduClass->find('list', array('conditions' => array('EduClass.grading_type' => 'G')));

        $this->set('edu_classes', $eduClasses);
    }

    
    /**
     * Detain a student
     *
     * @return void
     */
    public function detain_student()
    {
        if (!empty($this->data)) {
            $this->EduRegistration->read(null, $this->data['EduRegistration']['edu_student_id']);
            // to have the # form of the registration id, not the student id
            $this->EduRegistration->set('status_id', $this->data['EduRegistration']['status']);
            $this->EduRegistration->set('remark', $this->data['EduRegistration']['remark']);

            if ($this->EduRegistration->save()) {
                $this->Session->setFlash(__('The Student detained successfully', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The Student cannot be detained. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    /**
     * Save changes to multiple students' records at once.
     *
     * The parameter $p is a string consisting of registration id and allowed value
     * separated by '_' and each registration id and allowed value separated by '__'.
     * For example, '1_1__2_0__3_1' means to set registration id 1, 2 and 3 to allowed
     * value 1, 0 and 1 respectively.
     *
     * @param string $p parameter string
     * @return void
     */
    public function save_changes($p = null) {
        $this->autoRender = false;
        if (!$p) {
            $this->Session->setFlash(__('Invalid parameter', true), '');
            $this->render('/elements/failure');
        }
        $regs = explode('__', $p);
        try {
            foreach ($regs as $reg) {
                if ($reg == '') {
                    break;
                }
                $parts = explode('_', $reg);
                $this->EduRegistration->read(null, $parts[0]);
                $this->EduRegistration->set('allowed', $parts[1]);
                $this->EduRegistration->save();
            }
            $this->Session->setFlash(__('Students records saved successfully', true), '');
            $this->render('/elements/success');
        } catch (Exception $e) {
            $msg = __('Students records cannot be saved. Please contact the administrators', true) .
                ' ' . $e->getMessage();
            $this->Session->setFlash($msg, '');
            $this->render('/elements/failure');
        }
    }

    function index2($id = null) {
        $this->set('parent_id', $id);

        // ghg
    }

    /**
     * This function will register all the promoted students in a given class into the next class.
     * It will create a new section with the name 'A' and then create a new registration for all the promoted students
     * in the newly created section.
     * @param null $id
     */
    function register_all($id = null) {
        if (!empty($this->data)) {
            // collect variables
            $this->loadModel('Edu.EduAcademicYear');
            $this->loadModel('Edu.EduRegistration');
            $this->loadModel('Edu.EduClass');
            $this->loadModel('Edu.EduSection');
            
            $ay = $this->EduAcademicYear->getActiveAcademicYear();
            $prev_ay = $this->EduAcademicYear->getPreviousAcademicYear();

            // get the next class
            $edu_class_id = $this->data['EduRegistration']['edu_class_id'];
            $current_class = $this->EduClass->read(null, $edu_class_id);
            $next_class = $this->EduClass->find('first', array(
                'conditions' => array(
                    'EduClass.name' => $current_class['EduClass']['next_class']
                )
            ));

            // students not promoted 
            // 5537 = Natnael Mulu (year 10)
            // 5262 = Benayas Equbay (year 10)
            // 5279 = Eyosias Tarekegn (year 10)
            // 5866 = Bitania Natnael (year 10)
            // 5458 = Saqar Abdullah (year 10)
            // 5621 = Nolawi Yemiru (year 10)
            // 5511 = Meron Bikila (year 10)
            // 5284 = Nethen Tamrat (year 10)
            // 5572 = Yonatan Dereje (year 11)
            // 5316 = Amanuel Tewolde (year 11)
            // 5449 = Nahom Tewodros (year 11)
            // 5633 = Abel Zekarias (year 7)
            // 5834 = Bamlak Cherinet (year 5)

            // collect the promoted students
            $regs = $this->EduRegistration->find('all', array(
                'conditions' => array(
                    'EduRegistration.edu_class_id' => $edu_class_id, 
                    'EduRegistration.edu_section_id <>' => 0,
                    'EduRegistration.edu_academic_year_id' => $prev_ay['EduAcademicYear']['id'],
                    'EduRegistration.edu_campus_id' => $this->Session->read('Auth.User.edu_campus_id'),
                    'EduStudent.deleted' => 0,
                    'EduRegistration.status_id' => array(13, 14, 0),
                    'EduRegistration.id NOT' => array(5537, 5262, 5279, 5866, 5458, 5621, 5511, 5284, 5572, 5316, 5449, 5633, 5834) // not promoteds
			    )
            ));

            $edu_academic_year_id = $ay['EduAcademicYear']['id'];

            // create the section
            $new_section = array('EduSection' => array(
                'name' => 'A',
                'edu_campus_id' => 1,
                'edu_class_id' => $next_class['EduClass']['id'],
                'edu_academic_year_id' => $edu_academic_year_id,
                'edu_teacher_id' => 0,
                'co_teacher_id' => 0 //,
                //'edu_number_of_sections' => 1
            )); 

            $this->log('About to create a section.', 'register_all');
            $this->log($new_section, 'register_all');

            $this->EduSection->create();
            $this->log('Section creator refreshed.', 'register_all');
            $ret = $this->EduSection->save($new_section);  // TODO: what is wrong with this?
            $this->log('Section creator created the section.', 'register_all');

            if(!$ret) {
                $this->log('Could not create the new section. Error: ', 'register_all'); // . $this->EduSection->validationErrors
            } else {
                $this->log('New section created.', 'register_all');
                $section_id = $this->EduSection->id;

                $this->log('New Section Id: ' . $section_id, 'register_all');

                foreach ($regs as $reg) {
                    $new_reg = array('EduRegistration' => array(
                        'name' => $reg['EduRegistration']['name'],
                        'edu_student_id' => $reg['EduRegistration']['edu_student_id'],
                        'edu_class_id' => $next_class['EduClass']['id'],
                        'edu_section_id' => $section_id,
                        'edu_academic_year_id' => $edu_academic_year_id,
                        'edu_campus_id' => 1,
                        'grand_total_average' => 0,
                        'acgpa' => 0,
                        'rank' => 0,
                        'class_rank' => 0,
                        'status_id' => 1,
                        'failure_count' => 0,
                        'allowed' => 'A',
                        'disciplinary_failure' => 'P',
                        'remark' => 'NA',
                        'general_comment' => 'NA',
                        'photo_file' => 'No file',
                        'scholarship' => 0,
                        'scholarship_reason' => 'NA'
                    ));

                    $this->log($new_reg, 'register_all');

                    $this->EduRegistration->create();
                    if($this->EduRegistration->save($new_reg)) {
                        $reg_id = $this->EduRegistration->id;
                        $this->log('Reg of student ' . $reg['EduRegistration']['edu_student_id'] . ' is registered with reg_id - ' . $reg_id, 'register_all');
                        
                        $this->saveRegistrationEvaluations($reg_id);
                        $this->saveRegistrationQuarters($reg_id);
                    } else {
                        $this->log('Could not register student ' . $reg['EduRegistration']['edu_student_id'] . ' with reg_id - ' . $reg_id, 'register_all');
                    }
                }

            }

            $this->Session->setFlash(__('The students are registered successfully.', true), '');
            $this->render('/elements/success');
        }

        if ($id) {
            $this->set('parent_id', $id);
        }
        $this->loadModel('Edu.EduClass');
        $this->loadModel('Edu.EduAcademicYear');

        $classes = array();
        $messages = array();
        $edu_classes = $this->EduClass->find('list');

        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $prev_ay = $this->EduAcademicYear->getPreviousAcademicYear();

        if($prev_ay === FALSE) {
            $this->log('No previous AY.', 'register_all');
        } else {
            foreach ($edu_classes as $k => $v) {
                $regs = $this->EduRegistration->find('count', array(
                    'conditions' => array(
                        'EduRegistration.edu_class_id' => $k,
                        'EduRegistration.edu_section_id <>' => 0,
                        'EduRegistration.edu_academic_year_id' => $prev_ay['EduAcademicYear']['id'],
                        'EduRegistration.edu_campus_id' => $this->Session->read('Auth.User.edu_campus_id'),
                        'EduStudent.deleted' => 0,
                        'EduRegistration.status_id' => array(13, 14, 0), // promoteds / 14 Not promoteds
                        'EduRegistration.id NOT' => array(5537, 5262, 5279, 5866, 5458, 5621, 5511, 5284, 5572, 5316, 5449, 5633, 5834) // not promoteds
                    )
                ));

                $current_class = $this->EduClass->read(null, $k);
                $next_class = $this->EduClass->find('first', array(
                    'conditions' => array(
                        'EduClass.name' => $current_class['EduClass']['next_class']
                    )
                ));

                $curr_regs = $this->EduRegistration->find('count', array(
                    'conditions' => array(
                        'EduRegistration.edu_class_id' => $next_class['EduClass']['id'], 
                        'EduRegistration.edu_section_id <>' => 0,
                        'EduRegistration.edu_academic_year_id' => $ay['EduAcademicYear']['id'],
                    )
                ));
                
                $classes[$k] = $v . ' with ' . $regs . ' promoted students ' . ($curr_regs > 0? '(REGISTERED)': '');
                
                if($regs == 0){
                    $messages[$k] = 'There is no promoted student available in the selected class.';
                }
            }
        }

        $this->log($classes, 'register_all');
        $edu_classes = $classes;

        $this->set(compact('edu_classes', 'messages'));
    }

    #endregion


    #region Print Card function


    /**
     * Prepares and sets data for printing cards in a given section.
     *
     * @param int $edu_section_id The ID of the education section.
     * @param string $mode The mode of the print, default is 'inner'.
     * @param int|null $edu_registration_id The ID of the education registration, default is null.
     *
     * @return void
     */
    function print_cards($edu_section_id, $mode = 'inner', $edu_registration_id = null) {
        $this->layout = 'ajax';

        $this->loadModel('EduSection');
        $section = $this->EduSection->read(null, $edu_section_id);

        $this->set('section', $section);
        $this->set('edu_section_id', $edu_section_id);
        $this->set('mode', $mode);
        if($edu_registration_id){
            $this->set('edu_registration_id', $edu_registration_id);
        }

        $this->set('company_url', $this->getSystemSetting('COMPANY_URL'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_address', $this->getSystemSetting('COMPANY_ADDRESS'));
    }

    /**
     * Prepares and sets data for printing cards in a given preschool section.
     *
     * @param int $edu_section_id The ID of the education section.
     * @param int|null $edu_registration_id The ID of the education registration, default is null.
     *
     * @return void
     */
    function print_cards_for_preschool_inner($edu_section_id, $edu_registration_id = null) {
        $this->layout = 'ajax';

        $this->EduRegistration->recursive = 2;
        $conditions = array();
        $conditions['EduRegistration.edu_section_id'] = $edu_section_id;
        if($edu_registration_id){
            $conditions['EduRegistration.id'] = $edu_registration_id;
        }
        $registrations = $this->EduRegistration->find('all', array('conditions' => $conditions));


        $this->loadModel('EduSection');
        $this->loadModel('EduEvaluation');
        $this->loadModel('EduRegistrationEvaluation');
        $this->loadModel('EduEvaluationCategory');

        $categories = $this->EduEvaluationCategory->find('all');
        $section = $this->EduSection->read(null, $edu_section_id);
        $evaluations = $this->EduEvaluation->find('all', array('conditions' => array('EduEvaluation.edu_class_id' => $section['EduSection']['edu_class_id'])));

        $registration_evaluations = $this->EduRegistrationEvaluation->find('all', array('conditions' => array('EduRegistration.edu_section_id' => $edu_section_id)));

        $this->set('registrations', $registrations);
        $this->set('section', $section);
        $this->set('categories', $categories);
        $this->set('evaluations', $evaluations);
        $this->set('registration_evaluations', $registration_evaluations);
    }

    /**
     * Prepares and sets data for printing cards in a given numeric section.
     *
     * @param int $edu_section_id The ID of the education section.
     * @param int|null $edu_registration_id The ID of the education registration, default is null.
     *
     * @return void
     */
    function print_cards_for_numeric_inner($edu_section_id, $edu_registration_id = null) {
        $this->layout = 'ajax';

        $this->EduRegistration->recursive = 2;
        $conditions = array();
        $conditions['EduRegistration.edu_section_id'] = $edu_section_id;
		if($edu_registration_id){
			$conditions['EduRegistration.id'] = $edu_registration_id;
		}
        $registrations = $this->EduRegistration->find('all', array('conditions' => $conditions));

        $this->loadModel('EduCourse');
        $this->loadModel('EduSection');
        $this->loadModel('EduEvaluation');
        $this->loadModel('EduRegistrationEvaluation');
        $this->loadModel('EduEvaluationCategory');

        $categories = $this->EduEvaluationCategory->find('all');
        $section = $this->EduSection->read(null, $edu_section_id);
        $evaluations = $this->EduEvaluation->find('all', array('conditions' => array('EduEvaluation.edu_class_id' => $section['EduSection']['edu_class_id'])));

        $registration_evaluations = $this->EduRegistrationEvaluation->find('all', array('conditions' => array('EduRegistration.edu_section_id' => $edu_section_id)));
        
        $class_courses = $this->EduCourse->find('all', array(
            'conditions' => array('EduCourse.edu_class_id' => $section['EduSection']['edu_class_id'])));
        $courses = array();
        foreach ($class_courses as $course) {
            $courses[$course['EduCourse']['id']] = $course['EduSubject']['name'] . '/' . $course['EduSubject']['name_am'];
        }
        $this->log($courses, 'card_printing');

        $this->set('company_url', $this->getSystemSetting('COMPANY_URL'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_address', $this->getSystemSetting('COMPANY_ADDRESS'));

        $this->set('courses', $courses);

        $this->set('registrations', $registrations);
        $this->set('section', $section);
        $this->set('categories', $categories);
        $this->set('evaluations', $evaluations);
        $this->set('registration_evaluations', $registration_evaluations);
    }
	
	function print_achievement_report($id) {
		$this->set('company_url', $this->getSystemSetting('COMPANY_URL'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_address', $this->getSystemSetting('COMPANY_ADDRESS'));
		
		$this->loadModel('EduScale');
		//$this->EduRegistration->recursive = 2;
		$registration = $this->EduRegistration->read(null, $id);
		$scales = $this->EduScale->find('all', array('order' => 'min DESC'));
		
		$this->loadModel('EduTeacher');
		$this->loadModel('EduRegistrationQuarterResult');
		$this->loadModel('EduQuarter');
		$this->loadModel('EduRegistrationEvaluation');
		$this->loadModel('EduCourseItem');
		$this->loadModel('EduSubject');
		$this->loadModel('EduEvaluation');
		$this->loadModel('EduEvaluationValue');
		$this->loadModel('EduEvaluationCategory');
        $this->loadModel('EduAcademicYear');
        
        $ay = $this->EduAcademicYear->read(null, $registration['EduSection']['edu_academic_year_id']);
        $registration['EduSection']['EduAcademicYear'] = $ay['EduAcademicYear'];
		$homeroom = $this->EduTeacher->getTeacher($registration['EduSection']['edu_teacher_id']);
		$registration['Homeroom'] = $homeroom;
		
		$rq_ids = array();
		foreach($registration['EduRegistrationQuarter'] as $rq) {
			$rq_ids[] = $rq['id'];
		}

        if(count($rq_ids) != 3) {
            $this->saveRegistrationQuarters($id);
            $registration = $this->EduRegistration->read(null, $id);
            $rq_ids = array();
            foreach($registration['EduRegistrationQuarter'] as $rq) {
                $rq_ids[] = $rq['id'];
            }
        }

		$this->EduSubject->recursive = 0;
		$rqrs = $this->EduRegistrationQuarterResult->find('all', array('conditions' => array('edu_registration_quarter_id' => $rq_ids), 'order' => 'EduRegistrationQuarter.edu_quarter_id'));
		foreach($rqrs as &$rqr) {
			$cid = $rqr['EduCourse']['id'];
			$sid = $rqr['EduCourse']['edu_subject_id'];
			$course_items = $this->EduCourseItem->find('all', array('conditions' => array('edu_course_id' => $cid)));
			$rqr['EduCourseItem'] = $course_items;
			
			$subject = $this->EduSubject->read(null, $sid);
			$rqr['EduSubject'] = $subject;
		}
		$registration['Rqrs'] = $rqrs;
        $this->log($registration['Rqrs'], 'card_printing_achievement_report');

        // for the time being
        //$this->saveRegistrationEvaluations($id);

        $reg_evaluations = $this->EduRegistrationEvaluation->find('all', array(
            'conditions' => array(
                'EduRegistrationEvaluation.edu_registration_id' => $registration['EduRegistration']['id']
            )
        ));

        $registration['EduRegistrationEvaluation'] = $reg_evaluations;
		
		$this->EduEvaluation->unbindModel(array('hasMany' => array('EduRegistrationEvaluation')));
		$this->EduEvaluation->unbindModel(array('belongsTo' => array('EduClass')));
		
		$this->EduEvaluation->recursive = 1;
		$evaluations = $this->EduEvaluation->find('all', array(
            'conditions' => array (
			    'EduEvaluation.edu_class_id' => $registration['EduRegistration']['edu_class_id']
            )
        ));
		
		$registration['Evaluation'] = $evaluations;
		
		$evaluation_values = $this->EduEvaluationValue->find('list');
		
		$this->EduEvaluationCategory->recursive = 0;
		$registration['EvaluationCategory'] = $this->EduEvaluationCategory->find('all', array(
			'conditions' => array('evaluation_value_group' => 2)
		));

        $registration['ExtraEvaluationCategory'] = $this->EduEvaluationCategory->find('all', array(
			'conditions' => array('evaluation_value_group' => 3)
		));

        $registration['EduQuarter'] = array();
        $this->EduQuarter->recursive = -1;
        foreach($registration['EduRegistrationQuarter'] as $qq) {
            $q = $this->EduQuarter->read(null, $qq['edu_quarter_id']);
            $registration['EduQuarter'][$qq['edu_quarter_id']] = $q;
        }
        //$this->log($registration, 'card_printing_achievement_report');
		
		$this->set('registration', $registration);
		$this->set('evaluation_values', $evaluation_values);
		$this->set('scales', $scales);
	}

    function generate_achievement_reports($queued_job) {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->loadModel('EduRegistration');
        
        // considering $queued_job['QueuedJob']['content'] is like '{"edu_section_id":1}'
        // from $queued_job model object content field, extract the section id
        // get all students in the section
        // for each section students, generate achievement report
        // save the achievement report in pdf in a folder
        // zip the folder and send it to the user
        $data = json_decode($queued_job['QueuedJob']['content'], true);
        $section_id = $data['edu_section_id'];
        $students = $this->EduRegistration->getStudents($section_id);
        
        foreach ($students as $student) {
            $this->print_achievement_report($student['EduRegistration']['id']);
            $this->save_achievement_report($student['EduRegistration']['id']);
        }

        $this->set('students', $students);
        $this->set('section_id', $section_id);
    }

    /**
     * Saves the achievement report for a given registration id.
     *
     * @param int $registration_id The ID of the education registration.
     * @return void
     */
    protected function save_achievement_report($registration_id) {
        // Logic to save the achievement report for the given registration ID
        $this->layout = 'ajax';
        $this->loadModel('EduRegistration');
        $registration = $this->EduRegistration->read(null, $registration_id);
        $this->set('registration', $registration);
        $this->render('/Elements/achievement_report_pdf');
        // Here you would typically generate a PDF and save it to a file
        // You can get the pdf content from the view and save it to a file
        $pdfContent = $this->render('/Elements/achievement_report_pdf', 'pdf');
        $pdfFilePath = '/tmp/achievement_report.pdf';
        file_put_contents($pdfFilePath, $pdfContent);
    }

    #endregion
    
	
	#region List_Date functions

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_student_id = (isset($_REQUEST['edu_student_id'])) ? $_REQUEST['edu_student_id'] : -1;
        if ($id) {
            $edu_student_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_student_id != -1) {
            $conditions['EduRegistration.edu_student_id'] = $edu_student_id;
        }

        $this->set('edu_registrations', $this->EduRegistration->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduRegistration->find('count', array('conditions' => $conditions)));
    }

    function list_data_combo($id = null) {
        $edu_section_id = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : -1;
        $edu_assessment_id = (isset($_REQUEST['edu_assessment_id'])) ? $_REQUEST['edu_assessment_id'] : -1;
       
        $conditions = '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_section_id != -1) {
            $conditions['EduRegistration.edu_section_id'] = $edu_section_id;
        }
        $this->loadModel('Edu.EduAssessmentRecord');

        //$registrations = array();
        $edu_registrations = $this->EduRegistration->find('all', array('conditions' => $conditions));
        foreach($edu_registrations as &$edu_registration) {
            $ar = $this->EduAssessmentRecord->find('first', array('conditions' => array(
                'EduAssessmentRecord.edu_assessment_id' => $edu_assessment_id,
                'EduAssessmentRecord.edu_registration_id' => $edu_registration['EduRegistration']['id']
            )));

            $edu_registration['EduRegistration']['id'] .= '-' . $ar['EduAssessmentRecord']['mark'];
        }

        $this->set('edu_registrations', $edu_registrations);
        $this->set('results', count($edu_registrations));
    }
	
	function list_data_section_students($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_section_id = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : -1;
        if ($id) {
            $edu_section_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_section_id != -1) {
            $conditions['EduRegistration.edu_section_id'] = $edu_section_id;
        }

        $this->set('edu_registrations', $this->EduRegistration->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduRegistration->find('count', array('conditions' => $conditions)));
    }

    function list_data_students($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_class_id = (isset($_REQUEST['edu_class_id'])) ? $_REQUEST['edu_class_id'] : -1;
        if ($id) {
            $edu_class_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_class_id != -1) {
            $conditions['EduRegistration.edu_class_id'] = $edu_class_id;
        }
		$conditions['EduRegistration.edu_campus_id'] = $this->Session->read('Auth.User.edu_campus_id');
		
        $this->EduRegistration->recursive = 3;
        $this->set('edu_registrations', $this->EduRegistration->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduRegistration->find('count', array('conditions' => $conditions)));
    }
    
    function list_registration_students() {
        $query = (isset($_REQUEST['query'])) ? $_REQUEST['query'] : '';
        $conditions = array();

        $conditions['OR'] = array('EduStudent.name LIKE' => '%' . $query . '%',
            'EduStudent.identity_number LIKE' => '%' . $query . '%');
        
        $conditions['EduStudent.deleted'] = false; // 
        $conditions['EduStudent.status_id'] = array(7, 1); // Active and Enrolled but not registered
		// TODO: Active (1) students who should be allowed for registration, should have no active registration
        
        $students = $this->EduRegistration->EduStudent->find('all', array('conditions' => $conditions));
        // TODO: Filter students as allowed ones for registration
        // This code if for OP for this year only (2020)
        $this->loadModel('EduClass');
        foreach($students as &$student) {
            $class = $this->EduClass->read(null, $student['EduStudent']['edu_class_id']);
            if(!empty($class)) {
                $student['EduStudent']['current_class_name'] = $class['EduClass']['name'];
            } else {
                $last_registration = $this->EduRegistration->getLastRegistration($edu_student_id);
                $class =  $this->EduClass->read(null, $last_registration['EduRegistration']['edu_class_id']);

                $student['EduStudent']['current_class_name'] = $class['EduClass']['name'];
            }
        }
        $this->set('edu_students', $students);
        $this->set('results', count($students));
    }

	function list_data_students_per_ay($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_academic_year_id = (isset($_REQUEST['edu_academic_year_id'])) ? $_REQUEST['edu_academic_year_id'] : -1;
        if ($id) {
            $edu_academic_year_id = ($id) ? $id : -1;
        }
		if($edu_academic_year_id > 0)
			$this->Session->write('edu_academic_year_id', $edu_academic_year_id);
		elseif($this->Session->check('edu_academic_year_id'))
			$edu_academic_year_id = $this->Session->read('edu_academic_year_id');
		
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		$this->loadModel('EduSection');
		$this->loadModel('EduAcademicYear');
        if ($edu_academic_year_id == -1) {
			$ay = $this->EduAcademicYear->getActiveAcademicYear();
			$edu_academic_year_id = $ay['EduAcademicYear']['id'];
		}
		$sections = $this->EduSection->find('all', array('conditions' => array(
			'EduSection.edu_academic_year_id' => $edu_academic_year_id, 
			'EduSection.edu_campus_id' => $this->Session->read('Auth.User.edu_campus_id'))));
		$section_ids = array();
		$section_ids[] = 0;  // to include the unsectioneds
		foreach($sections as $section) {
			$section_ids[] = $section['EduSection']['id'];
		}
		$conditions['EduRegistration.edu_section_id'] = $section_ids;
        
        $this->EduRegistration->recursive = 2;
        $this->set('edu_registrations', $this->EduRegistration->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start, 'order' => 'EduClass.cvalue')));
        $this->set('results', $this->EduRegistration->find('count', array('conditions' => $conditions)));
    }
	
    function list_detainment_students() {
        $this->loadModel('EduClass');

        $query = (isset($_REQUEST['query'])) ? $_REQUEST['query'] : '';
        $conditions = array();

        $preschool_classes = $this->EduClass->find('all', array('conditions' => array('EduClass.grading_type' => 'G')));
        $gclass_ids = array();
        foreach ($preschool_classes as $gc) {
            $gclass_ids[] = $gc['EduClass']['id'];
        }

        // TODO: The filter condition should consider the current user(i.e., the secretary of teachers or the homeroom teacher)

        $conditions['OR'] = array('EduStudent.name LIKE' => '%' . $query . '%',
            'EduStudent.identity_number LIKE' => '%' . $query . '%');

        $conditions['EduRegistration.edu_class_id'] = $gclass_ids;
        $conditions['EduRegistration.status'] = array('P', 'N', 'A', 'I');
        $conditions['EduStudent.status'] = 1; // 1 - Active Students
        
        //pr($conditions);

        $registrations = $this->EduRegistration->find('all', array('conditions' => $conditions));

        $this->set('registrations', $registrations);
        $this->set('results', count($registrations));
    }

    function list_registration_classes() {
        $edu_student_id = (isset($_REQUEST['edu_student_id'])) ? $_REQUEST['edu_student_id'] : 0;
        $conditions = array();
		$this->loadModel('Edu.EduClass');
        $this->loadModel('Edu.EduAcademicYear');
        $this->loadModel('Edu.EduSection');
        
        //pr($conditions);
        $student = $this->EduRegistration->EduStudent->read(null, $edu_student_id);
        // TODO: Filter students as allowed ones for registration
		
		$last_registration = $this->EduRegistration->getLastRegistration($edu_student_id);
		
        $classes = array();
        if($last_registration !== FALSE && $last_registration['EduRegistration']['status_id'] == 13){
			$class_ids = $this->EduClass->getApplicableForRegistrationClasses($last_registration['EduRegistration']['edu_class_id'], true);
            $this->log($class_ids, 'last_reg');
			$conditions['EduClass.id'] = $class_ids;
        } elseif ($last_registration !== FALSE && $last_registration['EduRegistration']['status_id'] == 14){
			$class_ids = $this->EduClass->getApplicableForRegistrationClasses($last_registration['EduRegistration']['edu_class_id'], false);
            $conditions['EduClass.id'] = $class_ids;
        } else {
			$class_ids = $this->EduClass->getApplicableForRegistrationClasses($student['EduClass']['id'], true);
            //$this->log($student['EduClass']['id'], 'last_reg22');
            //$this->log($class_ids, 'last_reg2');
			$conditions['EduClass.id'] = $class_ids;
		}
		$this->EduRegistration->EduClass->recursive = 0;
        $classes = $this->EduRegistration->EduClass->find('all', array('conditions' => $conditions));
        
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $this->EduSection->recursive = 0;
        $sections = $this->EduSection->find('all', array('conditions' => array(
                 'EduSection.edu_academic_year_id' => $ay['EduAcademicYear']['id'],
                 'EduSection.edu_class_id' => $conditions['EduClass.id']
              )));
        //$this->log($sections, 'last_reg2');
        $this->set('edu_sections', $sections);
        $this->set('edu_classes', $classes);
        $this->set('results', count($classes));
    }

    function list_data_student_filter() {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 40;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        $conditions['EduRegistration.status'] = 'N';
        $conditions['EduRegistration.edu_section_id <>'] = 0;
        // get prev academic year
        $this->loadModel('Edu.EduAcademicYear');
        $this->loadModel('Edu.EduSection');
        $ay = $this->EduAcademicYear->getPreviousAcademicYear();
        // get sections in the prev academic year
        $sections = $this->EduSection->find('all', array('conditions' => array('EduSection.edu_academic_year_id' => $ay['EduAcademicYear']['id'])));

        // collect section ids and set in the conditions as IN
        $sec_ids = array();
        foreach ($sections as $section) {
            $sec_ids[] = $section['EduSection']['id'];
        }
        $conditions['EduRegistration.edu_section_id'] = $sec_ids;

        // Read Configuration Setting
        $this->loadModel('Setting');
        //$v = $this->Setting->getSetting('FAILS_TO_DISMISSAL');
        //print_r($v);

        $conditions['EduRegistration.failure_count'] = 2; //$v - 1;

        $this->set('edu_registrations', $this->EduRegistration->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduRegistration->find('count', array('conditions' => $conditions)));
    }

    function list_data_report($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_section_id = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : -1;
        if ($id) {
            $edu_section_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_section_id != -1) {
            $conditions['EduRegistration.edu_section_id'] = $edu_section_id;
			$this->Session->write('edu_section_id', $edu_section_id);
        } else {
            $this->loadModel('EduAcademicYear');
            $academic_years = $this->EduAcademicYear->find('all', array(
                'conditions' => array('EduAcademicYear.status_id' => 1),
                'order' => 'EduAcademicYear.start_date DESC'
            ));
			
			if ($this->Session->check('edu_section_id')) {
				$conditions['EduRegistration.edu_section_id'] = $this->Session->read('edu_section_id');
			} else {
				$sections = array();
				foreach ($academic_years as $ay) {
					foreach ($ay['EduSection'] as $s) {
						$sections[] = $s['id'];
					}
				}
				// this lists all the registered students,
				// if you want to display none when no section is
				// specified, you can do like:
				// $conditions['EduRegistration.edu_section_id'] = -1;
				$conditions['EduRegistration.edu_section_id'] = $sections;
			}
        }
		
		$conditions['EduStudent.deleted'] = false;
        $regs = $this->EduRegistration->find('all', array(
            'conditions' => $conditions, 'limit' => $limit, 'offset' => $start, 'order' => 'EduRegistration.name'));
        /*
        commented code
        foreach($regs as $reg) {
            $this->saveRegistrationEvaluations($reg['EduRegistration']['id']);
        }*/
		
        //$ids = array(4157, 4291, 4063, 4066, 4140, 4286, 4894, 4278, 4280, 4044, 4079, 4075); // 10
        //$ids = array(4111, 4084, 4097, 4847, 4120, 4123, 4083, 4274, 4842, 4099, 4154, 4275, 4100, 4888, 4879, 4279, 4106); // 11
        $ids = array(4554, 4643, 4854, 4646, 4885, 4653, 4654, 4661, 4555, 4844, 4673, 4902); // 12

		// foreach ($regs as $reg) {
		// 	//if($reg['EduRegistration']['id'] == 1492) { // do this only for student NAOMI TEMESGEN. G8-B
		// 	//	$this->saveRegistrationQuarters($reg['EduRegistration']['id']);
		// 	//}

        //     if(!in_array($reg['EduRegistration']['id'], $ids) ) { // do this only for student NAOMI TEMESGEN. G8-B
        //         $this->EduRegistration->read(null, $reg['EduRegistration']['id']);
        //         $this->EduRegistration->set('status_id', 13);  // Promoted
        //         $this->EduRegistration->save();

        //         // log the action
        //         $this->log('Promoted student: ' . $reg['EduRegistration']['name'], 'promotion');
        //     } else {
        //         // log the action 
        //         $this->log('Not Promoted student: ' . $reg['EduRegistration']['name'], 'not_promotion');
        //     }
		// }

        $this->set('edu_registrations', $regs);
        $this->set('results', $this->EduRegistration->find('count', array('conditions' => $conditions)));
    }

	function list_data_students_per_class($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_class_id = (isset($_REQUEST['edu_class_id'])) ? $_REQUEST['edu_class_id'] : 0;
		if ($id) {
            $edu_class_id = ($id) ? $id : 0;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';
		if($this->Session->check('edu_class_id') && $edu_class_id == 0) {
			$edu_class_id = $this->Session->read('edu_class_id');
		}

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_class_id != -1) {
                $conditions['EduStudent.edu_class_id'] = $edu_class_id;
        $this->Session->write('edu_class_id', $edu_class_id);
        }
		
		$this->loadModel('EduStudent');
		$conditions['EduStudent.deleted'] = false;
		$students = $this->EduStudent->find('all',
			array('conditions' => $conditions,
			      'limit' => $limit, 'offset' => $start, 'order' => 'EduStudent.name'));
		$this->loadModel('EduClass');
		$edu_class = $this->EduClass->read(null, $edu_class_id);

        $this->set('edu_students', $students);
		$this->set('edu_class', $edu_class);
        $this->set('results', $this->EduStudent->find('count', array('conditions' => $conditions)));
    }
	
	function list_data_for_comment() {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_section_id = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : -1;
        $edu_course_id = (isset($_REQUEST['edu_course_id'])) ? $_REQUEST['edu_course_id'] : -1;
        
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		
		$conditions['EduRegistration.edu_section_id'] = $edu_section_id;
		$conditions['EduStudent.deleted'] = false;
		
		$this->loadModel('EduQuarter');
		$this->loadModel('EduRegistrationQuarterResult');
		
		$quarter = $this->EduQuarter->getActiveQuarter();
		
		$registrations = $this->EduRegistration->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start, 'order' => 'EduRegistration.name'));
		
		foreach($registrations as &$registration) {
			$rq_id =  0;
			$registration['teacher_comment'] = '-' . count($registration['EduRegistrationQuarter']);
			$registration['rqr_id'] = 0;
			$registration['course_result'] = 0;
			foreach($registration['EduRegistrationQuarter'] as $rq) {
				if($rq['edu_quarter_id'] == $quarter['EduQuarter']['id']) {
					$rq_id = $rq['id'];
					$registration['teacher_comment'] = $rq_id . ' ' . $edu_course_id;
					break;
				}
			}
			if($rq_id > 0) {
				$rqr = $this->EduRegistrationQuarterResult->find('first', array(
					'conditions' => array('edu_registration_quarter_id' => $rq_id,
								'edu_course_id' => $edu_course_id)));
				//$registration['teacher_comment'] = count($rqr);
				if($rqr) {
					$registration['teacher_comment'] = $rqr['EduRegistrationQuarterResult']['teacher_comment'];
					$registration['rqr_id'] = $rqr['EduRegistrationQuarterResult']['id'];
					$registration['course_result'] = $rqr['EduRegistrationQuarterResult']['course_result'];
				}
			}
		}

        $this->set('edu_registrations', $registrations);
        $this->set('results', count($registrations));
    }

    #endregion
    
	
	#region Basic CRUD functions

    function search() {
        
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu registration', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduRegistration->recursive = 2;
        $this->set('edu_registration', $this->EduRegistration->read(null, $id));
    }
	
    function register() {
        if (!empty($this->data)) {
            $reg = array('EduRegistration' => array());
            // the value is like IDENTITY No - NAME
            $st_parts = explode(' - ', $this->data['EduRegistration']['edu_student_id']);
            $student = $this->EduRegistration->EduStudent->find('first', array(
				'conditions' => array('EduStudent.identity_number' => $st_parts[0])));
			
	        $cl_parts = explode('-', $this->data['EduRegistration']['edu_class_id']);  // like 7-A
            $class = $this->EduRegistration->EduClass->find('first', array(
				'conditions' => array('EduClass.name' => $cl_parts[0])));
			
            $this->loadModel('EduAcademicYear');
            $this->loadModel('EduSection');
			
	        $ay = $this->EduAcademicYear->getActiveAcademicYear();
			
	        $section = $this->EduSection->find('first', array(
				'conditions' => array('EduSection.name' => $cl_parts[1], 
				          'EduSection.edu_class_id' => $class['EduClass']['id'], 
				          'EduSection.edu_academic_year_id' => $ay['EduAcademicYear']['id'])
				 )
	        );
			
	        $curr_reg = $this->EduRegistration->find('first', array(
                'conditions' => array(
                    'EduRegistration.edu_student_id' => $student['EduStudent']['id'],
                    'EduRegistration.edu_class_id' => $class['EduClass']['id'],
                    'EduRegistration.created BETWEEN ? and ?' => array($ay['EduAcademicYear']['start_date'], $ay['EduAcademicYear']['end_date'])
                )));
			
            if (empty($curr_reg)) {
                $reg['EduRegistration']['name'] = $st_parts[1];
                $reg['EduRegistration']['edu_student_id'] = $student['EduStudent']['id'];
                $reg['EduRegistration']['edu_class_id'] = $class['EduClass']['id'];
                $reg['EduRegistration']['edu_section_id'] = (!empty($section)? $section['EduSection']['id']: 0);
                $reg['EduRegistration']['edu_campus_id'] = $this->Session->read('Auth.User.edu_campus_id');
                $reg['EduRegistration']['grand_total_average'] = 0;
                $reg['EduRegistration']['rank'] = 0;
                $reg['EduRegistration']['status_id'] = 1;
                $reg['EduRegistration']['failure_count'] = 0;
                $reg['EduRegistration']['allowed'] = 'A';
				
		        $this->EduRegistration->create();
                $this->Session->write('edu_student_id', $student['EduStudent']['id']);
                
                $this->autoRender = false;
                if ($this->EduRegistration->save($reg)) {
                    if ($this->data['EduPayment']['amount'] > 0) {
                        // save payment
                        $this->data['EduPayment']['crm_number'] =
                                (isset($this->data['EduPayment']['crm_number']) &&
                                $this->data['EduPayment']['crm_number'] != '')?
                                $this->data['EduPayment']['crm_number']:
                            'AUTO-' . time();
                        $payment_data = $this->data['EduPayment'];
                        $transaction = array(
                            'dr_acct_code' => '1101-01', // TODO: Configurable item
                            'cr_acct_code' => '2101', // TODO: Configurable item
                            'value' => $payment_data['amount'],
                            'description' => $payment_data['description'] . ' of student ' . $st_parts[1],
                            'cheque_number' => (isset($payment_data['cheque_number']) ? $payment_data['cheque_number'] : 'NA'),
                            'invoice_number' => $payment_data['crm_number'], // should be corrected in the ACCT plugin
                            'return' => ''
                        );

                        $this->Session->write('transaction', $transaction);
                        $ret = $this->requestAction(
                            array(
                                'controller' => 'acct_transactions',
                                'action' => 'save_transaction',
                                'plugin' => 'acct'), 
                            array('pass' => $transaction)
                        );
                        $this->log('ACCT returned: ' . $ret, 'debug');

                        if ($ret) {
                            $transaction = $this->Session->read('transaction');
                            $payment = array('EduPayment' => array(
                                    'edu_payment_schedule_id' => 0, // -1 enrollment 0 for registration 
                                    'edu_student_id' => $student['EduStudent']['id'],
                                    'is_paid' => 1,
                                    'date_paid' => date('Y-m-d'),
                                    'paid_amount' => $payment_data['amount'],
                                    'cheque_number' => (isset($payment_data['cheque_number']) ? $payment_data['cheque_number'] : 'NA'),
                                    'invoice' => $payment_data['crm_number'],
                                    'transaction_ref' => $transaction['return']
                            ));
                            $this->loadModel('Edu.EduPayment');
                            $this->EduPayment->create();
                            if (!$this->EduPayment->save($payment)) {
                                $this->log('Payment Save: ' . pr($this->EduPayment->validationErrors, true), 'debug');
                            }
                        }
                        // TODO: Receipt Printing
                        if (isset($this->data['EduPayment']['include_monthly_payments'])) {
                            // Just pass the data to session, because the Monthly Payment module has already called
                            // and we will handle this transaction and the monthly payments in a single receipt
                            $this->Session->write('registration_payment', $transaction);
                        } else {
                            // open the receipt print form to print the registration payment
                            // 1. Create the receipt record
                            // 2. Create the receipt item record.
                            // then open the receipt printer using the receipt id.
                            // Save Receipt Info
                            $this->loadModel('Edu.EduReceipt');
                            $this->loadModel('Edu.EduAcademicYear');
                            $this->loadModel('Edu.EduStudent');
                            // Restart Invoice Number for new Academic year
                            $ay = $this->EduAcademicYear->getActiveAcademicYear();
                            $cond = array('EduReceipt.invoice_date >=' => $ay['EduAcademicYear']['start_date'],
                                'EduReceipt.invoice_date <=' => $ay['EduAcademicYear']['end_date']);
                            $re = $this->EduReceipt->find('first', array('conditions' => $cond, 'order' => 'EduReceipt.reference_number DESC'));
                            $reference_number = 1;
                            if (!empty($re)) {
                                $reference_number = $re['EduReceipt']['reference_number'] + 1;
                            }

                            $this->EduStudent->recursive = 3;
                            $student = $this->EduStudent->read(null, $student['EduStudent']['id']);

                            $receipt = array(
                                'EduReceipt' => array(
                                    'name' => $transaction['description'],
                                    'reference_number' => $reference_number,
                                    'invoice_date' => date('Y-m-d'),
                                    'crm_number' => $payment_data['crm_number'],
                                    'parent_name' => $student['EduParent']['authorized_person'],
                                    'parent_address' => $student['EduParent']['EduParentDetail'][0]['work_address'] . '<br>' . $student['EduParent']['EduParentDetail'][0]['mobile'],
                                    'edu_student_id' => $student['EduStudent']['id'],
                                    'student_name' => $student['EduStudent']['name'],
                                    'student_number' => $student['EduStudent']['identity_number'],
                                    'student_class' => $student['EduRegistration'][0]['EduClass']['name'],
                                    'student_section' => 'Not Set',
                                    'student_academic_year' => $ay['EduAcademicYear']['name'],
                                    'total_before_tax' => $payment_data['amount'],
                                    'total_after_tax' => $payment_data['amount'],
                                    'VAT' => 0,
                                    'TOT' => 0
                                )
                            );

                            $this->EduReceipt->create();
                            if ($this->EduReceipt->save($receipt)) {
                                $item = array('name' => 'Registration Payment', 'amount' => $payment_data['amount'], 'edu_receipt_id' => $this->EduReceipt->id);
                                $this->EduReceipt->EduReceiptItem->create();
                                $this->EduReceipt->EduReceiptItem->save($item);

                                $receipt_id = $this->EduReceipt->id;
                                $this->Session->write('edu_receipt_id', $receipt_id);
                            } else {
                                $this->log('Receipt Return: ' . pr($this->EduReceipt->validationErrors, true), 'debug');
                            }
                        }
                    }
                    $reg_id = $this->EduRegistration->id;
                    
                    $this->saveRegistrationQuarters($reg_id);
                    
                    // save the sms message to parent
                    
                    $this->Session->setFlash(__('The registration has been saved', true), '');
                    $this->render('/elements/success');
                } else {
                    $this->Session->setFlash(__('The registration could not be saved. Please, try again.', true), '');
                    $this->render('/elements/failure');
                }
            } else {
                $this->Session->setFlash(__('The registration could not be saved. Student already registered.', true), '');
                $this->render('/elements/failure');
            }
        }
        
        $this->loadModel('EduClass');
        $classes = $this->EduClass->find('all');
        $class_payments = array();
        $this->loadModel('EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();

        foreach ($classes as $class) {
            $found = false;
            foreach ($class['EduPaymentSchedule'] as $ps) {
                if ($ps['edu_academic_year_id'] == $ay['EduAcademicYear']['id']) {
                    $found = true;
                    break;
                }
            }

            $class_payments[$class['EduClass']['id']] = $class;
        }
        $is_cheque_payment_allowed = $this->getSystemSetting('RECEIVE_PAYMENT_BY_CHEQUE');
        $this->set('class_payments', $class_payments);
        $this->set('is_cheque_payment_allowed', $is_cheque_payment_allowed);
		
		$this->EduRegistration->EduStudent->recursive = 3;
		$student = $this->EduRegistration->EduStudent->read(null, 1);
		$this->log($student, 'regreg');
    }

    /**
     * Save the evaluation data of a registration
     *
     * @param int $id The id of the registration
     * @return void
     */
    function saveRegistrationEvaluations($id) {
        $reg = $this->EduRegistration->read(null, $id);
        $this->loadModel('Edu.EduQuarter');
        $this->loadModel('Edu.EduAcademicYear');
        $this->loadModel('Edu.EduRegistrationQuarter');
        $this->loadModel('Edu.EduRegistrationQuarterResult');
        $this->loadModel('Edu.EduRegistrationEvaluation');
        $this->loadModel('Edu.EduEvaluation');
        
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $ay_id = $ay['EduAcademicYear']['id'];

        $quarters = $this->EduQuarter->find('all', array('conditions' => array(
                'EduQuarter.edu_academic_year_id' => $ay_id,
                'EduQuarter.quarter_type' => 'E')));
        
        $evaluations = $this->EduEvaluation->find('all', array(
            'conditions' => array(
                'EduEvaluation.edu_class_id' => $reg['EduRegistration']['edu_class_id']
            )));
        
        foreach ($quarters as $quarter) {
            foreach($evaluations as $evaluation) {
                $re = $this->EduRegistrationEvaluation->find('first', array(
                    'conditions' => array (
                        'EduRegistrationEvaluation.edu_registration_id' => $id,
                        'EduRegistrationEvaluation.edu_quarter_id' => $quarter['EduQuarter']['id'],
                        'edu_evaluation_id' => $evaluation['EduEvaluation']['id']
                    )
                ));
                if(empty($re)) {
                    $re = array('EduRegistrationEvaluation' => array(
                        'edu_registration_id' => $id,
                        'edu_evaluation_id' => $evaluation['EduEvaluation']['id'],
                        'edu_quarter_id' => $quarter['EduQuarter']['id'],
                        'edu_evaluation_value_id' => 0,
                        'deleted' => 0
                    ));

                    $this->EduRegistrationEvaluation->create();
                    if(!$this->EduRegistrationEvaluation->save($re)) {
                        $this->log(pr($this->EduRegistrationEvaluation->validationErrors, true), 'reg_eval');
                    } else {
                        $this->log(pr($re, true), 'reg_evals');
                    }
                }
            }
        }
    }
	
    /**
     * Save the registration quarters
     *
     * @param int $id The id of the registration
     * @return void
     */
	public function saveRegistrationQuarters($id) {
        $reg = $this->EduRegistration->read(null, $id);
        $this->loadModel('Edu.EduQuarter');
        $this->loadModel('Edu.EduAcademicYear');
        $this->loadModel('Edu.EduRegistrationQuarter');
        $this->loadModel('Edu.EduRegistrationQuarterResult');

        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $ay_id = $ay['EduAcademicYear']['id'];

        $quarters = $this->EduQuarter->find('all', array('conditions' => array(
                'EduQuarter.edu_academic_year_id' => $ay_id,
                'EduQuarter.quarter_type' => 'E')));
        
        foreach ($quarters as $quarter) {
            $rq = $this->EduRegistrationQuarter->find('first', array(
                'conditions' => array(
                    'EduRegistrationQuarter.edu_registration_id' => $id,
                    'EduRegistrationQuarter.edu_quarter_id' => $quarter['EduQuarter']['id']
                )
            ));
            $rq_id = 0;
            if (empty($rq)) {
                $rq_id = $this->createRegistrationQuarters($id, $quarter['EduQuarter']['id']);
            } else {
                $rq_id = $rq['EduRegistrationQuarter']['id'];
            }
            
            $class = $this->EduRegistration->EduClass->read(null, $reg['EduRegistration']['edu_class_id']);
            foreach ($class['EduCourse'] as $course) {
                $rqrs = $this->EduRegistrationQuarterResult->find('all',
                    array(
                        'conditions' => array(
                            'edu_registration_quarter_id' => $rq_id,
                            'edu_course_id' => $course['id']
                        )
                    )
                );
                if (empty($rqrs)) {
                    $rqr = array('EduRegistrationQuarterResult' => array(
                            'edu_registration_quarter_id' => $rq_id,
                            'edu_course_id' => $course['id'],
                            'course_result' => 0,
                            'course_rank' => 0,
                            'result_indicator' => 'N'
                    ));
                    $this->EduRegistrationQuarterResult->create();
                    if ($this->EduRegistrationQuarterResult->save($rqr)) {
                        $this->log($this->EduRegistrationQuarterResult->id, 'rqr_ids');
                    } else {
                        $this->log(pr($this->EduRegistrationQuarterResult->validationErrors, true), 'rqr_ids2');
                    }
                }
            }
        }
    }

    /**
     * Creates a new record for edu_registration_quarters table.
     *  
     * @param int $reg_id The id of the registration.
     * @param int $quarter_id The id of the quarter.
     * @return int The id of the newly created record.
     */
    function createRegistrationQuarters($reg_id, $quarter_id) {
        $this->loadModel('EduRegistrationQuarter');

        $reg_q = array('EduRegistrationQuarter' => array(
            'edu_registration_id' => $reg_id,
            'edu_quarter_id' => $quarter_id,
            'quarter_total' => 0,
            'quarter_average' => 0,
            'cgpa' => 0,
            'quarter_rank' => 0,
            'class_rank' => 0,
            'absentees' => 0,
            'parent_comment' => '-',
            'homeroom_comment' => '-'
        ));
        $this->EduRegistrationQuarter->create();
        if ($this->EduRegistrationQuarter->save($reg_q)) {
            return $this->EduRegistrationQuarter->id;
        }
        return 0;
    }

	function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu registration', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduRegistration->save($this->data)) {
                $this->Session->setFlash(__('The edu registration has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu registration could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu__registration', $this->EduRegistration->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $edu_students = $this->EduRegistration->EduStudent->find('list');
        $edu_sections = $this->EduRegistration->EduSection->find('list');
        $this->set(compact('edu_students', 'edu_sections'));
    }
	
    function edit_teacher_comment($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu registration', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
			
            if ($this->EduRegistration->EduRegistrationQuarter->save($this->data)) {
                $this->Session->setFlash(__('The edu registration has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu registration could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
		$this->loadModel('EduQuarter');
		
		$quarter = $this->EduQuarter->getActiveQuarter();
		
		$eduRegistrationQuarter = $this->EduRegistration->EduRegistrationQuarter->find('first', array(
            'conditions' => array (
                'EduRegistrationQuarter.edu_registration_id' => $id,
                'EduRegistrationQuarter.edu_quarter_id' => $quarter['EduQuarter']['id'])));
                         
                if(empty($eduRegistrationQuarter)) {
            $this->saveRegistrationQuarters($id);

            $eduRegistrationQuarter = $this->EduRegistration->EduRegistrationQuarter->find('first', array(
                'conditions' => array (
                    'EduRegistrationQuarter.edu_registration_id' => $id,
                    'EduRegistrationQuarter.edu_quarter_id' => $quarter['EduQuarter']['id']
                )
            ));
        }

		
        $this->set('edu_registration', $this->EduRegistration->read(null, $id));
        $this->set('edu_registration_quarter', $eduRegistrationQuarter);
		
    }

    public function student_quarter_attendance_list($eduSectionId)
    {
        // body here
        $regs = $this->EduRegistration->find('all', array(
            'conditions' => array('EduRegistration.edu_section_id' => $eduSectionId)));
        
        $section = $this->EduRegistration->EduSection->read(null, $eduSectionId);

        $this->set('regs', $regs);
        $this->set('section', $section);
    }

    public function delete($id, $deleteEverything = 'false')
    {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for registration', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {  $this->EduRegistration->delete($i); }
                $this->Session->setFlash(__('Registration deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Registration was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->deleteRegistration($id, $deleteEverything)){
                $this->Session->setFlash(__('Student with all his/her detail records deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Registration was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    private function deleteRegistration($id, $deleteEverything = 'false')
    {
        if ($deleteEverything == 'true') {
            $reg = $this->EduRegistration->read(null, $id);
            $this->EduRegistration->EduStudent->delete($reg['EduRegistration']['edu_student_id']);
            $regs = $this->EduRegistration->find('all', array(
                'conditions' => array(
                    'EduRegistration.edu_student_id' => $reg['EduRegistration']['edu_student_id'])));
            
            foreach ($regs as $r) { $this->EduRegistration->delete($r['EduRegistration']['id']); }
            return true;
        } else {
            if ($this->EduRegistration->delete($id)) {
                return true;
            }
            return false;
        }
    }

    #endregion
    
	
	#region reporting functions

    /**
     * registered students report form
     */
    function rpt_registered_students() {
        $this->loadModel('Edu.EduAcademicYear');

        $edu_academic_years = $this->EduAcademicYear->find('list', array('order' => 'EduAcademicYear.start_date DESC'));
        $active_ay = $this->EduAcademicYear->getActiveAcademicYear();

        $this->set('edu_academic_years', $edu_academic_years);
        $this->set('active_ay', $active_ay);
    }

    /**
     * registered students report viewer
     */
    function rpt_view_registered_students($id = null, $title = null) {
        $this->layout = 'ajax';

        $conditions = array();
        $this->loadModel('Edu.EduAcademicYear');
        $active_ay = $this->EduAcademicYear->getActiveAcademicYear();
        $sections = array();
        if ($id == null || $id == $active_ay['EduAcademicYear']['id']) {
            $id = $active_ay['EduAcademicYear']['id'];
            $secs = $this->EduRegistration->EduSection->find('all', array(
                'conditions' => array('EduSection.edu_academic_year_id' => $id)));
            foreach ($secs as $sec) {
                $sections[] = $sec['EduSection']['id'];
            }
            $sections[] = 0;
        } else {
            $secs = $this->EduRegistration->EduSection->find('all', array(
                'conditions' => array('EduSection.edu_academic_year_id' => $id)));
            foreach ($secs as $sec) {
                $sections[] = $sec['EduSection']['id'];
            }
        }


        $conditions['EduRegistration.edu_section_id'] = $sections;
        $regs = $this->EduRegistration->find('all', array('conditions' => $conditions));

        $this->set('edu_registrations', $regs);

        $this->set('company_url', Configure::read('company_url'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_tin', Configure::read('company_tin'));
        $this->set('company_address', Configure::read('company_address'));
        $this->set('report_title', str_replace('_', ' ', $title));
        $this->set('academic_year', $active_ay['EduAcademicYear']['name']);
    }

    /**
     * Enrolled students report form
     */
    function rpt_enrolled_students() {
        $this->loadModel('Edu.EduAcademicYear');

        $edu_academic_years = $this->EduAcademicYear->find('list', array('order' => 'EduAcademicYear.start_date DESC'));
        $active_ay = $this->EduAcademicYear->getActiveAcademicYear();

        $this->set('edu_academic_years', $edu_academic_years);
        $this->set('active_ay', $active_ay);
    }

    /**
     * Enrolled students report viewer
     */
    function rpt_view_enrolled_students($id = null, $title = null) {
        $this->layout = 'ajax';

        $conditions = array();
        $this->loadModel('Edu.EduAcademicYear');
        $this->loadModel('Edu.EduStudent');

        $ay = $this->EduAcademicYear->read(null, $id);


        $conditions['EduStudent.registration_date >='] = $ay['EduAcademicYear']['start_date'];
        $conditions['EduStudent.registration_date <='] = $ay['EduAcademicYear']['end_date'];
        $this->EduStudent->recursive = 3;

        $students = $this->EduStudent->find('all', array('conditions' => $conditions));

        $this->set('edu_students', $students);

        $this->set('company_url', Configure::read('company_url'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_tin', Configure::read('company_tin'));
        $this->set('company_address', Configure::read('company_address'));
        $this->set('report_title', str_replace('_', ' ', $title));
        $this->set('academic_year', $ay['EduAcademicYear']['name']);
    }

    #endregion
}

?>
