<?php

class EduSectionsController extends EduAppController {

    var $name = 'EduSections';

    function index() {
        $edu_classes = $this->EduSection->EduClass->find('all');
        $this->set(compact('edu_classes'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_class_id = (isset($_REQUEST['edu_class_id'])) ? $_REQUEST['edu_class_id'] : -1;
        if ($id) {
            $edu_class_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_class_id != -1) {
            $conditions['EduSection.edu_class_id'] = $edu_class_id;
        }
		$this->loadModel('Edu.EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
		
		$conditions['EduSection.edu_academic_year_id'] = $ay['EduAcademicYear']['id'];

        $this->set('edu_sections', $this->EduSection->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduSection->find('count', array('conditions' => $conditions)));
    }

	function list_data_for_ay($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_academic_year_id = (isset($_REQUEST['edu_academic_year_id'])) ? $_REQUEST['edu_academic_year_id'] : -1;
        if ($id) {
            $edu_academic_year_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_academic_year_id != -1) {
            $conditions['EduSection.edu_academic_year_id'] = $edu_academic_year_id;
        }

        $this->set('edu_sections', $this->EduSection->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduSection->find('count', array('conditions' => $conditions)));
    }
	
    function list_data_for_promotion($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_class_id = (isset($_REQUEST['edu_class_id'])) ? $_REQUEST['edu_class_id'] : -1;
        if ($id) {
            $edu_class_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_class_id != -1) {
            $conditions['EduSection.edu_class_id'] = $edu_class_id;
        }

        $edu_sections =  $this->EduSection->find('all', array('conditions' => $conditions));
        $sections = array();
        foreach ($edu_sections as $section) {
            $found = false;
            foreach ($section['EduRegistration'] as $reg) {
                if($reg['status_id'] == 1) {
                    $found = true;
                    break;
                }
            }
            if($found){
                $sections[] = $section;
            }
        }

        $this->set('edu_sections', $sections);
        $this->set('results', $this->EduSection->find('count', array('conditions' => $conditions)));
    }

    function list_data_for_teacher($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_class_id = (isset($_REQUEST['edu_class_id'])) ? $_REQUEST['edu_class_id'] : -1;
        if ($id) {
            $edu_class_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        $this->loadModel('Edu.EduPeriod');
        $this->loadModel('Edu.EduTeacher');
		$this->loadModel('Edu.EduCourseTeacherAssociation');
		$this->loadModel('Edu.EduAcademicYear');

		$ay = $this->EduAcademicYear->getActiveAcademicYear();
        $edu_academic_year_id = $ay['EduAcademicYear']['id'];
		
        $teacher = $this->EduTeacher->find('first', array(
                'conditions' => array('EduTeacher.user_id' => $this->Session->read('Auth.User.id'))
            ));
		$teacher_id = $teacher['EduTeacher']['id'];
        
		// if the teacher is homeroom for self contained classes
		$sections = $this->EduSection->find('all', array('conditions' => array(
			//'edu_teacher_id' => $teacher_id,
			'edu_academic_year_id' => $edu_academic_year_id
		)));
		
        $curr_section_ids = array();
        $section_ids = array();
        foreach ($sections as $section) {
            $curr_section_ids[] = $section['EduSection']['id'];
        }
		foreach ($teacher['EduSection'] as $section) {
            if(in_array($section['id'], $curr_section_ids))
                $section_ids[] = $section['id'];
        }
		// if the teacher is associated with courses in the section
		// through the periods of schedules 
        /*
        $all_sections = array();
		foreach($ay['EduSection'] as $sec) {
			if($sec['edu_class_id'] == $edu_class_id)
				$all_sections[] = $sec['id'];
		}
        
        
		$periods = $this->EduPeriod->find('all', array('conditions' => array(
			'EduPeriod.edu_teacher_id' => $teacher_id,
			'EduPeriod.edu_section_id' => $all_sections
		)));
		
		foreach ($periods as $period) {
            $section_ids[] = $period['EduSection']['id'];
        }
        */

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_class_id != -1) {
            $conditions['EduSection.edu_class_id'] = $edu_class_id;
        }

        $conditions['EduSection.id'] = $section_ids;
        
        $this->set('edu_sections', $this->EduSection->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduSection->find('count', array('conditions' => $conditions)));
    }

    function list_data2($id = null) {
        $start_time = time();
        $this->loadModel('Edu.EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $edu_academic_year_id = $ay['EduAcademicYear']['id'];
        $edu_class_id = 0;
        if (!$id) {
			if($this->Session->check('edu_class_id'))
				$edu_class_id = $this->Session->read('edu_class_id');
			else
				$edu_class_id = (isset($_REQUEST['edu_class_id'])) ? $_REQUEST['edu_class_id'] : -1;
        } else {
            $edu_class_id = $id;
        }

        $conditions = array(
            'EduSection.edu_class_id' => $edu_class_id,
            'EduSection.edu_academic_year_id' => $edu_academic_year_id
        );
        $edu_sections = $this->EduSection->find('all', array('conditions' => $conditions));
		//pr($conditions);
        foreach($edu_sections as &$section) {
            $this->EduSection->EduTeacher->recursive = 2;
            $section['EduTeacher'] = $this->EduSection->EduTeacher->read(null, $section['EduSection']['edu_teacher_id']);
            $section['EduTeacher2'] = $this->EduSection->EduTeacher->read(null, $section['EduSection']['co_teacher_id']);
        }
	if(count($edu_sections) == 0) {
		$edu_sections[] = array('EduSection' => array('íd' => 0, 'name' => 'Unsectioned'), 
					'EduRegistration' => array(),
					'EduCampus' => array('name' => 'Main'),
					'EduTeacher' => array('EduTeacher' => array()),
					'EduTeacher2' => array());
	}
        $this->set('edu_sections', $edu_sections);
        $this->set('results', $this->EduSection->find('count', array('conditions' => $conditions)));

        $time_diff = time() - $start_time;
        $this->log($time_diff . ' sec. in SectionsController->list_data2 ', 'time_mgt');
    }
	
	function list_data_prev($id = null) {
        $this->loadModel('Edu.EduAcademicYear');
        $ay = $this->EduAcademicYear->getPreviousAcademicYear();
        $edu_academic_year_id = $ay['EduAcademicYear']['id'];
        $edu_class_id = 0;
        if (!$id) {
            $edu_class_id = $this->Session->read('edu_class_id');
        } else {
            $edu_class_id = $id;
        }

        $conditions = array(
            'EduSection.edu_class_id' => $edu_class_id,
            'EduSection.edu_academic_year_id' => $edu_academic_year_id
        );
        $edu_sections = $this->EduSection->find('all', array('conditions' => $conditions));
        foreach($edu_sections as &$section) {
            $this->EduSection->EduTeacher->recursive = 2;
            $section['EduTeacher'] = $this->EduSection->EduTeacher->read(null, $section['EduSection']['edu_teacher_id']);
        }
        $this->set('edu_sections', $edu_sections);
        $this->set('results', $this->EduSection->find('count', array('conditions' => $conditions)));
    }
    
	function sections_detail($id = null) {
        $start_time = time();
        $edu_class_id = 0;
        if (!$id) {
            $edu_class_id = $this->Session->read('edu_class_id');
        } else {
            $edu_class_id = $id;
        }
        $class = $this->EduSection->EduClass->read(null, $edu_class_id);
		
		//pr($class);
		$teacher_ids = array();
		foreach($class['EduTeacher'] as $ct) {
			$teacher_ids[] = $ct['id'];
		}
		
        $this->EduSection->EduTeacher->unbindModel(array(
            'hasAndBelongsToMany' => array('EduSubject', 'EduClass', 'EduSection'), 
            'hasMany' => array('EduSection', 'EduAssignment'))
        );
        $this->EduSection->EduTeacher->recursive = 2;
        $teachers = $this->EduSection->EduTeacher->find('all', array(
            'conditions' => array('EduTeacher.id' => $teacher_ids)
        ));

        $time_diff = time() - $start_time;
        //$this->log($time_diff . ' sec. in SectionsController->section_detail ', 'time_mgt');

        $this->set('teachers', $teachers);
        $this->set('class', $class);
    }
    
    function save_changes() {
        $this->autoRender = false;
        foreach ($this->data as $record) {
            $id = $record['id'];
            $name = $record['name'];
            $homeroom = $record['homeroom'];
            $homeroom2 = $record['homeroom2'];

            $id = str_replace('"', '', $id);
            $name = str_replace('"', '', $name);
            $homeroom = str_replace('"', '', $homeroom);
            $homeroom2 = str_replace('"', '', $homeroom2);
            $teacher_id = 0;
            $teacher_id2 = 0;
            if($homeroom == 'None') {
                $teacher_id = 0;
            } else {
                $parts = explode(': ', $homeroom);
                $teacher = $this->EduSection->EduTeacher->find('first', array(
                    'conditions' => array('identity_number' => $parts[1])));
                $teacher_id = $teacher['EduTeacher']['id'];
            }

            if($homeroom2 == 'None') {
                $teacher_id2 = 0;
            } else {
                $parts2 = explode(': ', $homeroom2);
                $teacher2 = $this->EduSection->EduTeacher->find('first', array(
                    'conditions' => array('identity_number' => $parts2[1])));
                $teacher_id2 = $teacher2['EduTeacher']['id'];
            }
            
            $this->EduSection->read(null, $id);
            $this->EduSection->set('name', $name);
            $this->EduSection->set('edu_teacher_id', $teacher_id);
            $this->EduSection->set('co_teacher_id', $teacher_id2);
            $this->EduSection->save();
        }
        $this->Session->setFlash(__('Records updated successfully', true), '');
        $this->render('/elements/success');
    }
    
    function save_student_section_changes() {
        $this->autoRender = false;
        $this->loadModel('Edu.EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $edu_academic_year_id = $ay['EduAcademicYear']['id'];
        foreach ($this->data as $record) {
            $id = $record['id'];
            $section = $record['section'];

            $id = str_replace('"', '', $id);
            $section = str_replace('"', '', $section);
            $section_id = 0;
            if($section == 'None') {
                $section_id = 0;
            } else {
                $parts = explode(' - ', $section);
                $c = $this->EduSection->EduClass->find('first', array(
                    'conditions' => array('EduClass.name' => $parts[1])
                ));
                //pr($c);
                $s = $this->EduSection->find('first', array(
                    'conditions' => array(
                        'EduSection.name' => $parts[0], 
                        'EduSection.edu_academic_year_id' => $edu_academic_year_id, 
                        'EduSection.edu_class_id' => $c['EduClass']['id'])));
                //pr($s);
                $section_id = $s['EduSection']['id'];
            }
            
            $this->EduSection->EduRegistration->read(null, $id);
            $this->EduSection->EduRegistration->set('edu_section_id', $section_id);
            $this->EduSection->EduRegistration->save();
        }
        $this->Session->setFlash(__('Records updated successfully', true), '');
        $this->render('/elements/success');
    }

    function delete_all($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid Id for Class Sections', true), '');
            $this->render('/elements/failure');
        }
        
        $this->loadModel('Edu.EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $edu_academic_year_id = $ay['EduAcademicYear']['id'];
        $edu_class_id = $id;
        
        $sections = $this->EduSection->find('all', array(
            'conditions' => array(
                'EduSection.edu_class_id' => $edu_class_id,
                'EduSection.edu_academic_year_id' => $edu_academic_year_id
                )
        ));
        
        foreach($sections as $section) {
            $this->EduSection->EduRegistration->updateAll(
                array('EduRegistration.edu_section_id' => 0),
                array('EduRegistration.edu_section_id' => $section['EduSection']['id'])
            );
            
            $this->EduSection->delete($section['EduSection']['id']);
        }
        $this->Session->setFlash(__('Sections are deleted successfully', true), '');
        $this->render('/elements/success');
    }
    
	function course_teacher_association() {
		$this->loadModel('EduClass');
		
        $classes = $this->EduClass->find('list', array('order' => 'cvalue', 'conditions' => array('EduClass.uni_teacher' => 0)));
		
        $this->set('classes', $classes);
	}
	
	function list_data_course_teacher_association() {
		$this->loadModel('EduCourseTeacherAssociation');
		$edu_section_id = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : -1;
		
		$assocs = $this->EduCourseTeacherAssociation->find('all', array('conditions' => array('edu_section_id' => $edu_section_id)));
		//$this->log($assocs, 'association');
		if(count($assocs) == 0) {
			$this->loadModel('EduCourse');
			//$this->log('Inside the if.', 'association');
			$edu_section = $this->EduSection->read(null, $edu_section_id);
			$edu_class_id = $edu_section['EduSection']['edu_class_id'];
			
			$courses = $this->EduCourse->find('all', array('conditions' => array('edu_class_id' => $edu_class_id)));
			
			foreach($courses as $course) {
				$this->EduCourseTeacherAssociation->create();
				$cta = array('EduCourseTeacherAssociation' => array(
					'edu_section_id' => $edu_section_id,
					'edu_course_id' => $course['EduCourse']['id'],
					'edu_teacher_id' => 0
				));
				
				$this->EduCourseTeacherAssociation->save($cta);
			}
			
			$assocs = $this->EduCourseTeacherAssociation->find('all', array('conditions' => array('edu_section_id' => $edu_section_id)));
		}
		
		$this->loadModel('EduTeacher');
        
		$this->EduTeacher->unbindModel(
			array('hasMany' => array('EduSection'))
		);
		$this->EduTeacher->unbindModel(
			array('hasMany' => array('EduAssignment'))
		);
		$this->EduTeacher->unbindModel(
			array('hasAndBelongsToMany' => array('EduSubject'))
		);
		$this->EduTeacher->unbindModel(
			array('hasAndBelongsToMany' => array('EduClass'))
		);
		
        $this->EduTeacher->recursive = 2;
        $teachers = $this->EduTeacher->find('all');
		
		$ts = array();
		foreach($teachers as $teacher){ 
			$p = $teacher['User']['Person'];
			$ts[$teacher['EduTeacher']['id']] = $p['first_name'] . ' ' . $p['middle_name'] . ' ' . $p['last_name'];
		}
		$this->set('teachers', $ts);
		$this->set('assocs', $assocs);
	}
	
	function get_teacher_id_by_full_name($full_name) {
		$this->loadModel('EduTeacher');
        
		$this->EduTeacher->unbindModel(
			array('hasMany' => array('EduSection'))
		);
		$this->EduTeacher->unbindModel(
			array('hasMany' => array('EduAssignment'))
		);
		$this->EduTeacher->unbindModel(
			array('hasAndBelongsToMany' => array('EduSubject'))
		);
		$this->EduTeacher->unbindModel(
			array('hasAndBelongsToMany' => array('EduClass'))
		);
		
        $this->EduTeacher->recursive = 2;
        $teachers = $this->EduTeacher->find('all');
		
		$ts = array();
		foreach($teachers as $teacher){ 
			$p = $teacher['User']['Person'];
			$ts[$p['first_name'] . ' ' . $p['middle_name'] . ' ' . $p['last_name']] = $teacher['EduTeacher']['id'];
		}
		
		if(isset($ts[$full_name])) {
			return $ts[$full_name];
		}
		return 0;
		
	}
	
	function save_course_teacher_associations() {
        $this->autoRender = false;
		$this->loadModel('EduCourseTeacherAssociation');
        $this->loadModel('EduAcademicYear');
        
		foreach ($this->data as $record) {
            $id = $record['id'];
			$id = str_replace('"', '', $id);
            $edu_teacher_id = $this->get_teacher_id_by_full_name($record['edu_teacher_id']);
			$this->log('Teacher ID: ' . $edu_teacher_id, 'association');
            $cta = $this->EduCourseTeacherAssociation->read(null, $id);
			$this->log($cta, 'association');
			
            $this->EduCourseTeacherAssociation->set('edu_teacher_id', $edu_teacher_id);
            $this->EduCourseTeacherAssociation->save();
        }
        $this->Session->setFlash(__('Records updated successfully', true), '');
        $this->render('/elements/success');
    }
	
    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu section', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduSection->recursive = 2;
        $this->set('edu_section', $this->EduSection->read(null, $id));
    }
    
    function section_students($id = null) {
        $this->EduSection->recursive = 2;
        $section = $this->EduSection->read(null, $id);
        $this->set('section', $section);
        
        $this->loadModel('Edu.EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        
        $sections = $this->EduSection->find('all', array(
            'conditions' => array(
                'EduSection.edu_class_id' => $section['EduSection']['edu_class_id'],
                'EduSection.edu_academic_year_id' => $ay['EduAcademicYear']['id'],
				'EduSection.edu_campus_id' => $this->Session->read('Auth.User.edu_campus_id')
            )
        ));
        
        $this->set('edu_sections', $sections);
    }

    function rpt_students_per_section() {
        $ay = $this->EduSection->EduAcademicYear->getActiveAcademicYear();

        $edu_sections = $this->EduSection->find('all', array(
            'conditions' => array('EduSection.edu_academic_year_id' => $ay['EduAcademicYear']['id']),
            'order' => 'EduSection.edu_class_id, EduSection.name ASC'
        ));

        $this->set(compact('edu_sections'));
    }

    function rpt_view_students_per_section($id, $title) {
        $this->layout = 'ajax';

        $this->loadModel('Edu.EduAcademicYear');
        $this->loadModel('Edu.EduRegistration');
        $this->loadModel('Edu.EduSection');
        $active_ay = $this->EduAcademicYear->getActiveAcademicYear();

        $conditions = array();
        $conditions['EduRegistration.edu_section_id'] = $id;
        $regs = $this->EduRegistration->find('all', array('conditions' => $conditions, 'order' => 'EduRegistration.name ASC'));

        $section = $this->EduSection->read(null, $id);

        $this->set('company_url', Configure::read('company_url'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_tin', Configure::read('company_tin'));
        $this->set('company_address', Configure::read('company_address'));
        $this->set('report_title', str_replace('_', ' ', $title));
        $this->set('academic_year', $active_ay['EduAcademicYear']['name']);
        $this->set('section', $section['EduSection']['name']);
        $this->set('regs', $regs);
    }

    function create_sections($id = null) {
        if (!empty($this->data)) {
            // collect variables
            $this->loadModel('Edu.EduAcademicYear');
            $ay = $this->EduAcademicYear->getActiveAcademicYear();

            $class = $this->data['EduSection']['edu_class_id'];
            $edu_class_id = $this->data['EduSection']['edu_class_id'];

            $academic_year = $ay['EduAcademicYear']['id'];
            $section_size = $this->data['EduSection']['edu_section_size'];
            $number_of_section = $this->data['EduSection']['edu_number_of_sections'];

            if ($section_size == 0 && $number_of_section == 0) {
                $this->Session->setFlash(__('Sectioning cannot be applied while either section size[Number of students per section] or number of sections are zero.', true), '');
                $this->render('/elements/failure');
                return;
            }

            // calculate sizes
            $this->loadModel('Edu.EduRegistration');
            $regs = $this->EduRegistration->find('count', array(
                'conditions' => array(
                    'EduRegistration.edu_class_id' => $edu_class_id,
                    'EduRegistration.edu_section_id' => 0,
					'EduRegistration.edu_campus_id' => $this->Session->read('Auth.User.edu_campus_id'),
					'EduStudent.deleted' => 0
                )
            ));
			
			if ($regs == 0) {
                $this->Session->setFlash(__('Sectioning cannot be applied while no students found to be sectioned.', true), '');
                $this->render('/elements/failure');
                return;
            }
			
            $sections = array();
            $names = array(0 => 'A', 1 => 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N');
			
            if ($section_size > 0) {
                $number_of_section = ceil($regs / $section_size); // 26/8 => ceil(3.25) = 4
            } else {
                $section_size = ceil($regs / $number_of_section); // 26/3 => ceil(8.67) = 9
            }

            for ($i = 0; $i < $number_of_section; $i++) {
                $sections[$i] = array(
                    'name' => $names[$i],
                    'edu_class_id' => $edu_class_id,
                    'edu_academic_year_id' => $academic_year,
                    'size' => $section_size,
                    'students' => array());
            }

            $female_students = $this->EduRegistration->find('all', array(
                'conditions' => array('EduRegistration.edu_class_id' => $edu_class_id,
                    'EduRegistration.edu_section_id' => 0,
					'EduRegistration.edu_campus_id' => $this->Session->read('Auth.User.edu_campus_id'),
                    'EduStudent.gender' => 'F',
					'EduStudent.deleted' => 0),
				'order' => 'EduStudent.birth_date'));
            $male_students = $this->EduRegistration->find('all', array(
                'conditions' => array('EduRegistration.edu_class_id' => $edu_class_id,
                    'EduRegistration.edu_section_id' => 0,
					'EduRegistration.edu_campus_id' => $this->Session->read('Auth.User.edu_campus_id'),
                    'EduStudent.gender' => 'M',
					'EduStudent.deleted' => 0),
				'order' => 'EduStudent.birth_date'));
				
			$this->log('female_students', 'sectioning');
			$this->log($female_students, 'sectioning');
			$this->log('male_students', 'sectioning');
			$this->log($male_students, 'sectioning');
            $j = 0;
            foreach ($female_students as $fst) {
                $sections[$j]['students'][] = $fst['EduRegistration']['id'];
                $j++;
                if ($j >= count($sections)) {
                    $j = 0;
                }
            }
            $k = $j;  // i.e.; continue from the stop to make proportional assignment
            foreach ($male_students as $mst) {
                $sections[$k]['students'][] = $mst['EduRegistration']['id'];
                $k++;
                if ($k >= count($sections)) {
                    $k = 0;
                }
            }
			
			$this->log('sections', 'sectioning');
			$this->log($sections, 'sectioning');
			
            $class_obj = $this->EduSection->EduClass->read(null, $class);
            
			// collect teacher ids that are associated with the current class
			$teacher_ids = array();
			foreach($class_obj['EduTeacher'] as $tchr) {
				$teacher_ids[] = $tchr['id'];
			}
			
            // collect teachers in the cycle
            $teachers = $this->EduSection->EduTeacher->find('all', array(
                    'conditions' => array(
                        'EduTeacher.id' => $teacher_ids
                    )
                ));
            $ts = array();
            foreach ($teachers as $teacher) {
                $section_numbers  = 0;
                foreach ($teacher['EduSection'] as $s) {
                    if ($s['edu_academic_year_id'] == $academic_year) {
                        $section_numbers++;
                    }
                }
                $ts[$teacher['EduTeacher']['id']] = $section_numbers;
            }
            
            asort($ts);
			$this->log('teachers', 'sectioning');
			$this->log($ts, 'sectioning');
            
            $teachers_list = array();
            foreach ($ts as $k => $v) {
                $teachers_list[] = $k;
            }
            // start saving the sections and update student registration by giving the section_id
            $l = 0;
            foreach ($sections as $s) {
                $sec_data = array('EduSection' => array());
                $sec_data['EduSection']['name'] = $s['name'];
                $sec_data['EduSection']['edu_class_id'] = $s['edu_class_id'];
                $sec_data['EduSection']['edu_academic_year_id'] = $s['edu_academic_year_id'];
				$sec_data['EduSection']['edu_campus_id'] = $this->Session->read('Auth.User.edu_campus_id');
                // homeroom
                $sec_data['EduSection']['edu_teacher_id'] = $teachers_list[$l];
                $l++;
                if ($l >= count($teachers_list)) {
                    $l = 0;
                }
                
                $this->EduSection->create();
                $this->EduSection->save($sec_data);

                foreach ($s['students'] as $stu) {
                    $this->EduSection->EduRegistration->read(null, $stu);
                    $this->EduSection->EduRegistration->set('edu_section_id', $this->EduSection->id);
                    if($this->EduSection->EduRegistration->save()) {
						// just leave it
					} else {
						$this->log('unable to save registration ' . $stu, 'sectioning');
						$this->log($this->EduSection->EduRegistration->validationErrors, 'sectioning');
					}
                }
            }
            $this->Session->write('edu_class_id', $class);

            $this->Session->setFlash(__('The Sections are created successfully.', true), '');
            $this->render('/elements/success');
        }
        if ($id) {
            $this->set('parent_id', $id);
        }
        $classes = array();
        $messages = array();
        $edu_classes = $this->EduSection->EduClass->find('list');
        $this->loadModel('EduRegistration');
        foreach ($edu_classes as $k => $v) {
            $regs = $this->EduRegistration->find('count', array(
                'conditions' => array('EduRegistration.edu_class_id' => $k,
				'EduRegistration.edu_section_id' => 0,
				'EduRegistration.edu_campus_id' => $this->Session->read('Auth.User.edu_campus_id'),
				'EduStudent.deleted' => 0
			)));
           
            $cls = $this->EduSection->EduClass->read(null, $k);
            $teachers = $this->EduSection->EduTeacher->find('all', array(
                    'conditions' => array(
                        // commment 'EduTeacher.edu_class_level_id' => $cls['EduClass']['edu_class_level_id']
                    )
                ));
			if($regs > 0) {
				$classes[$k] = $v . ' with ' . $regs . ' students and ' . count($cls['EduTeacher']) . ' teachers';
				
				// this if has no effect at all.
				if($regs == 0 || count($cls['EduTeacher']) == 0){
					if($regs == 0){
						$messages[$k] = 'There is no student available in the selected class.';
					} else {
						$messages[$k] = 'There is no teacher in the cycle to assign as homeroom.';
					}
				}
			}
            /* comment pr($this->EduRegistration->find('all', array(
              'conditions' => array('EduRegistration.edu_class_id' => $k,
              'EduRegistration.edu_section_id' => 0,
              'EduStudent.gender' => 'M'), 'order' => 'EduStudent.birth_date')));
             */
        }
        $edu_classes = $classes;

        $edu_academic_years = $this->EduSection->EduAcademicYear->find('list', array(
            'conditions' => array('EduAcademicYear.end_date >' => date('Y-m-d'))));
        $this->set(compact('edu_classes', 'edu_academic_years', 'messages'));
    }

    public function add($id = null)
    {
        if (!empty($this->data)) {
            $this->EduSection->create();
            $this->loadModel('Edu.EduAcademicYear');
            $ay = $this->EduAcademicYear->getActiveAcademicYear();
            $this->data['EduSection']['edu_academic_year_id'] = $ay['EduAcademicYear']['id'];
            $this->data['EduSection']['edu_campus_id'] = $this->Session->read('Auth.User.edu_campus_id');
            
            $this->autoRender = false;
            if ($this->EduSection->save($this->data)) {
                $this->Session->setFlash(__('The section has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The section could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id != null) {
            $this->set('parent_id', $id);
        }
        $eduClasses = $this->EduSection->EduClass->find('list');
        $this->set('edu_classes', $eduClasses);
    }

    public function edit($id = null, $parent_id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu section', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduSection->save($this->data)) {
                $this->Session->setFlash(__('The edu section has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu section could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_section', $this->EduSection->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $edu_classes = $this->EduSection->EduClass->find('list');
        $edu_academic_years = $this->EduSection->EduAcademicYear->find('list');
        $this->set(compact('edu_classes', 'edu_academic_years'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for edu section', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduSection->delete($i);
                }
                $this->Session->setFlash(__('Edu section deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Edu section was not deleted', true) . ' ::: Code: ' .
                    $e->getCode() . ', Message: ' . $e->getMessage(), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduSection->delete($id)) {
                $this->Session->setFlash(__('Edu section deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Edu section was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}
