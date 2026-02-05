<?php

class EduCoursesController extends EduAppController {

    var $name = 'EduCourses';

    function index_v() {
        $edu_classes = $this->EduCourse->EduClass->find('all');
        $this->set(compact('edu_classes'));
    }
    
    function index_class_m() {
        $edu_classes = $this->EduCourse->EduClass->find('all');
        $this->set(compact('edu_classes'));
    }

    function index_class_v() {
        $edu_classes = $this->EduCourse->EduClass->find('all');
        $this->set(compact('edu_classes'));
    }
    
    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function index_subject_m($id = null) {
        $this->set('parent_id', $id);
    }
    
    function index_subject_v($id = null) {
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
            $conditions['EduCourse.edu_class_id'] = $edu_class_id;
        }

        $this->set('edu_courses', $this->EduCourse->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduCourse->find('count', array('conditions' => $conditions)));
    }

    function list_data2($id = null) { //courses for specific class
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $klass_id = (isset($_REQUEST['edu_class_id'])) ? $_REQUEST['edu_class_id'] : -1;

        if ($id)
            $klass_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($klass_id != -1)
            $conditions['EduCourse.edu_class_id'] = $klass_id;

        $this->set('edu_courses', $this->EduCourse->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduCourse->find('count', array('conditions' => $conditions)));
    }

    function list_data_for_secretary_assessment() { //courses for specific class
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_class_id = (isset($_REQUEST['edu_class_id'])) ? $_REQUEST['edu_class_id'] : -1;

        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_class_id != -1){
            $conditions['EduCourse.edu_class_id'] = $edu_class_id;
        }

        $this->set('edu_courses', $this->EduCourse->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduCourse->find('count', array('conditions' => $conditions)));
    }

    function list_data_for_teacher() { //courses for specific class
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $section_id = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : -1;
		
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        $this->loadModel('Edu.EduSection');
        $this->loadModel('Edu.EduTeacher');
        $this->loadModel('Edu.EduCourseTeacherAssociation');
        $this->loadModel('Edu.EduPeriod');
        
        $teacher = $this->EduTeacher->find('first', array(
                'conditions' => array('EduTeacher.user_id' => $this->Session->read('Auth.User.id'))
            ));
		$teacher_id = $teacher['EduTeacher']['id'];
		//pr($teacher);
        // if the teacher is homeroom for self contained classes
		$section = $this->EduSection->read(null, $section_id);
        $courses = array();
        if($section['EduClass']['uni_teacher'] == 1) {
			$courses = $this->EduCourse->find('all', array('conditions' => array('EduCourse.edu_class_id' => $section['EduClass']['id'])));
            //pr($courses);
        } else {
			/*$ctas = $this->EduCourseTeacherAssociation->find('all', array('conditions' => array(
				//'EduCourseTeacherAssociation.edu_section_id' => $section_id,
				'EduCourseTeacherAssociation.edu_teacher_id' => $teacher_id
			)));
			$course_ids = array();
			foreach ($ctas as $cta) {
				$course_ids[] = $cta['EduCourseTeacherAssociation']['edu_course_id'];
			}*/
			/*
			$periods = $this->EduPeriod->find('all', array('conditions' => array(
				'EduPeriod.edu_teacher_id' => $teacher_id,
				'EduPeriod.edu_section_id' => $section_id
			)));
			
			foreach ($periods as $period) {
				$course_ids[] = $period['EduPeriod']['edu_course_id'];
            }*/
            $subject_ids = array();
            foreach($teacher['EduSubject'] as $subj) {
                $subject_ids[] = $subj['id'];
            }

			$conditions = array(
                'EduCourse.edu_subject_id' => $subject_ids, 
                'EduCourse.edu_class_id' => $section['EduSection']['edu_class_id']
            );
			
			$courses = $this->EduCourse->find('all', array('conditions' => $conditions));
		}

        $this->set('edu_courses', $courses);
        $this->set('results', count($courses));
    }

    function list_data_subject($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_subject_id = (isset($_REQUEST['edu_subject_id'])) ? $_REQUEST['edu_subject_id'] : -1;
        if ($id) {
            $edu_subject_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_subject_id != -1) {
            $conditions['EduCourse.edu_subject_id'] = $edu_subject_id;
        }

        $this->set('edu_courses', $this->EduCourse->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduCourse->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->cakeError('cannotViewRecord', array(
                'message' => 'Invalid id for Course. (ERR-103-01)',
                'helpcode' => 'ERR-103-01'));
        }
        $this->EduCourse->recursive = 2;
        $this->set('edu_course', $this->EduCourse->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->EduCourse->create();
            $this->autoRender = false;
            if (!isset($this->data['EduCourse']['min_for_pass']) || $this->data['EduCourse']['min_for_pass'] == '') {
                $this->data['EduCourse']['min_for_pass'] = 0;
            }

            if ($this->EduCourse->save($this->data)) {
                $this->Session->setFlash(__('The Course has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'The Course could not be saved. (' . pr($this->EduCourse->validationErrors, true) .  '). (ERR-103-02)',
                    'helpcode' => 'ERR-103-02'));
            }
        }
        $edu_subs = $this->EduCourse->EduSubject->find('list', array('order' => 'EduSubject.name ASC'));
        $edu_subjects = array();
        if ($id) {
            $this->set('parent_id', $id);
            $this->set('edu_class', $this->EduCourse->EduClass->read(null, $id));
            
            $class = $this->EduCourse->EduClass->read(null, $id);
            $edu_classes = $this->EduCourse->EduClass->find('list');

            foreach($edu_subs as $subjk => $subjv) {
                $found = false;
                foreach($class['EduCourse'] as $course) {
                    if($course['edu_subject_id'] == $subjk){
                        $found = true;
                        break;
                    }
                }
                if(!$found){
                    $edu_subjects[$subjk] = $subjv;
                }
            }
        }
        
        $this->set(compact('edu_classes', 'edu_subjects'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->cakeError('cannotViewRecord', array(
                'message' => 'Invalid id for Course. (ERR-103-01)',
                'helpcode' => 'ERR-103-01'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduCourse->save($this->data)) {
                $this->Session->setFlash(__('The Course has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'The Course could not be saved. (' . pr($this->EduCourse->validationErrors, true) .  '). (ERR-103-02)',
                    'helpcode' => 'ERR-103-02'));
            }
        }
        $this->set('edu_course', $this->EduCourse->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $edu_classes = $this->EduCourse->EduClass->find('list');
        $edu_subjects = $this->EduCourse->EduSubject->find('list');
        $this->set(compact('edu_classes', 'edu_subjects'));
    }

    function add_subject($id = null) {
        if (!empty($this->data)) {
            $this->EduCourse->create();
            $this->autoRender = false;
            if ($this->EduCourse->save($this->data)) {
                $this->Session->setFlash(__('The Course has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'The Course could not be saved. (' . pr($this->EduCourse->validationErrors, true) .  '). (ERR-103-02)',
                    'helpcode' => 'ERR-103-02'));
            }
        }
        if ($id) {
            $this->set('parent_id', $id);
            $this->set('subject', $this->EduCourse->EduSubject->read(null, $id));
        }
        $edu_classes = $this->EduCourse->EduClass->find('list');
        $edu_subjects = $this->EduCourse->EduSubject->find('list');

        $this->set(compact('edu_classes', 'edu_subjects'));
    }

    function edit_subject($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu course', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduCourse->save($this->data)) {
                $this->Session->setFlash(__('The Course has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'The Course could not be saved. (' . pr($this->EduCourse->validationErrors, true) .  '). (ERR-103-02)',
                    'helpcode' => 'ERR-103-02'));
            }
        }
        $this->set('edu_course', $this->EduCourse->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $edu_classes = $this->EduCourse->EduClass->find('list');
        $edu_subjects = $this->EduCourse->EduSubject->find('list');
        $this->set(compact('edu_classes', 'edu_subjects'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->cakeError('cannotDeleteRecord', array(
                'message' => 'Invalid id for Course. (ERR-103-01)',
                'helpcode' => 'ERR-103-01'));
        }
        $course = $this->EduCourse->read(null, $id);
		$this->loadModel('EduRegistrationQuarterResult');
		$this->loadModel('EduRegistrationResult');
		$this->loadModel('EduOutline');
		$this->loadModel('EduLessonPlan');
		$rqrs = $this->EduRegistrationQuarterResult->find('count', array('conditions' => array('EduRegistrationQuarterResult.edu_course_id' => $id)));
		$rrrs = $this->EduRegistrationResult->find('count', array('conditions' => array('EduRegistrationResult.edu_course_id' => $id)));
		$outs = $this->EduOutline->find('count', array('conditions' => array('EduOutline.edu_course_id' => $id)));
		$lsps = $this->EduLessonPlan->find('count', array('conditions' => array('EduLessonPlan.edu_course_id' => $id)));
		
        if (count($course['EduAssessment']) > 0 || count($course['EduPeriod']) > 0 || $rqrs > 0 || $rrrs > 0 || $outs > 0 || $lsps > 0) {
            $this->cakeError('cannotDeleteRecord', array(
                'message' => 'Course has related records in other locations. (ERR-103-03)',
                'helpcode' => 'ERR-103-03'));
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduCourse->delete($i);
                }
                $this->Session->setFlash(__('Course successfully deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->cakeError('cannotDeleteRecord', array(
                    'message' => 'Course cannot be deleted. (' .$e->getMessage() . '). (ERR-103-04)',
                    'helpcode' => 'ERR-103-04'));
            }
        } else {
            if ($this->EduCourse->delete($id)) {
                $this->Session->setFlash(__('Course successfully deleted', true), '');
                $this->render('/elements/success');
            } else {
                if(Configure::read('soft_deleted') == 'yes'){
                    Configure::write('soft_deleted', '');
                    $this->Session->setFlash(__('Course successfully deleted', true), '');
                    $this->render('/elements/success');
                } else {
                    $this->cakeError('cannotDeleteRecord', array(
                        'message' => 'Course cannot be deleted. (ERR-103-05)',
                        'helpcode' => 'ERR-103-05'));
                }
            }
        }
    }

}
