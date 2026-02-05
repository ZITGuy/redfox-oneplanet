<?php

class EduOutlinesController extends EduAppController {

    var $name = 'EduOutlines';

    function index_m() {
        $edu_courses = $this->EduOutline->EduCourse->find('all');
        $this->set(compact('edu_courses'));
    }
    
    function index_v() {
        $edu_courses = $this->EduOutline->EduCourse->find('all');
        $this->set(compact('edu_courses'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_course_id = (isset($_REQUEST['edu_course_id'])) ? $_REQUEST['edu_course_id'] : -1;
        if ($id) {
            $edu_course_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_course_id != -1) {
            $conditions['EduOutline.edu_course_id'] = $edu_course_id;
        }

        $this->set('edu_outlines', $this->EduOutline->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduOutline->find('count', array('conditions' => $conditions)));
    }
    
    function list_data_for_lesson_plan($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_course_id = (isset($_REQUEST['edu_course_id'])) ? $_REQUEST['edu_course_id'] : -1;
        if ($id) {
            $edu_course_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_course_id != -1) {
            $conditions['EduOutline.edu_course_id'] = $edu_course_id;
        }

        $this->set('edu_outlines', $this->EduOutline->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduOutline->find('count', array('conditions' => $conditions)));
    }
    

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu outline', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduOutline->recursive = 2;
        $this->set('edu_outline', $this->EduOutline->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->EduOutline->create();
            $this->autoRender = false;
            if ($this->EduOutline->save($this->data)) {
                $this->Session->setFlash(__('The edu outline has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu outline could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id) {
            $this->set('parent_id', $id);
        }
        
        $courses = $this->EduOutline->EduCourse->find('all', array('conditions' => array()));
        $edu_courses = array();
        foreach($courses as $course){
            $edu_courses[$course['EduCourse']['id']] = $course['EduCourse']['description'];
        }
        $this->set(compact('edu_courses'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu outline', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduOutline->save($this->data)) {
                $this->Session->setFlash(__('The edu outline has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu outline could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_outline', $this->EduOutline->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $courses = $this->EduOutline->EduCourse->find('all', array('conditions' => array()));
        $edu_courses = array();
        foreach($courses as $course){
            $edu_courses[$course['EduCourse']['id']] = $course['EduCourse']['description'];
        }
        $this->set(compact('edu_courses'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->cakeError('cannotDeleteRecord', array(
                'message' => 'Invalid id for Course Outline. (ERR-1204)',
                'helpcode' => 'ERR-1404'));
        }
        $outline = $this->EduOutline->read(null, $id);
        if (count($outline['EduLessonPlanItem']) > 0) {
            $this->cakeError('cannotDeleteRecord', array(
                'message' => 'Course Outline has related records in other locations. (ERR-1205)',
                'helpcode' => 'ERR-1205'));
        }
        
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduOutline->delete($i);
                }
                $this->Session->setFlash(__('Outline deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->cakeError('cannotDeleteRecord', array(
                    'message' => 'Course Outline cannot be deleted. (' .$e->getMessage() . '). (ERR-1206)',
                    'helpcode' => 'ERR-1206'));
            }
        } else {
            if ($this->EduOutline->delete($id)) {
                $this->Session->setFlash(__('Outline deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotDeleteRecord', array(
                    'message' => 'Course Outline cannot be deleted. (ERR-1206)',
                    'helpcode' => 'ERR-1206'));
            }
        }
    }

}
