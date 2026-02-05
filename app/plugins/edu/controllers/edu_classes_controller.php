<?php

class EduClassesController extends EduAppController {

    var $name = 'EduClasses';

    function index() {
        
    }

    function index_m() {
        
    }
    
    function index_v() {
        
    }

    function search() {
        
    }

    function list_data() {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';
		
        eval("\$conditions = array( " . $conditions . " );");
		
        $this->set('edu_classes', $this->EduClass->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduClass->find('count', array('conditions' => $conditions)));
    }

    function list_data_for_teacher() {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        $this->loadModel('Edu.EduPeriod');
        $this->loadModel('Edu.EduTeacher');
        
        $teacher = $this->EduTeacher->find('first', array(
                'conditions' => array('EduTeacher.user_id' => $this->Session->read('Auth.User.id'))
            ));

        $periods = $this->EduPeriod->find('all', array(
                'conditions' => array(
                        'EduPeriod.edu_teacher_id' => $teacher['EduTeacher']['id']
                    )
            ));
        $class_ids = array();
        foreach ($periods as $period) {
            $class_ids[] = $period['EduSection']['edu_class_id'];
        }

        eval("\$conditions = array( " . $conditions . " );");
        $conditions['EduClass.id'] = $class_ids;

        $this->set('edu_classes', $this->EduClass->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduClass->find('count', array('conditions' => $conditions)));
    }
    
    function list_data_for_campus($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        
        $this->EduClass->recursive = 2;
        $edu_classes = $this->EduClass->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start));

        $this->loadModel('EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $current_section_ids = array();
        if($ay) {
            foreach ($ay['EduSection'] as $section) {
                if($section['edu_campus_id'] == $id){
                    $current_section_ids[] = $section['id'];
                }
            }
        }
        $this->set('current_section_ids', $current_section_ids);

        $this->set('edu_classes', $edu_classes);
        $this->set('results', $this->EduClass->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->cakeError('cannotViewRecord', array(
                'message' => 'Invalid id for Class.',
                'helpcode' => 'ERR-000-00'));
        }
        $this->EduClass->recursive = 2;
        $this->set('edu_class', $this->EduClass->read(null, $id));
        $this->set('payment_schedule_method', $this->getSystemSetting('PAYMENT_SCHEDULE_METHOD'));
    }

    function add() {
        if (!empty($this->data)) {
            $this->EduClass->create();
            $this->autoRender = false;
            if ($this->EduClass->save($this->data)) {
                $this->Session->setFlash(__('The Class has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'The Class could not be saved. (' . pr($this->EduClass->validationErrors, true) .  ').',
                    'helpcode' => 'ERR-000-00'));
            }
        }
        $edu_class_levels = $this->EduClass->EduClassLevel->find('list');
        $this->set(compact('edu_class_levels'));
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->cakeError('cannotViewRecord', array(
                'message' => 'Invalid id for Class. (ERR-000-00)',
                'helpcode' => 'ERR-000-00'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduClass->save($this->data)) {
                $this->Session->setFlash(__('The Class has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'The Class could not be saved. (' . pr($this->EduClass->validationErrors, true) .  '). (ERR-102-02)',
                    'helpcode' => 'ERR-102-02'));
            }
        }
        $this->set('edu_class', $this->EduClass->read(null, $id));

        $edu_class_levels = $this->EduClass->EduClassLevel->find('list');
        $this->set(compact('edu_class_levels'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->cakeError('cannotDeleteRecord', array(
                'message' => 'Invalid id for Class. (ERR-102-01)',
                'helpcode' => 'ERR-102-01'));
        }
        $class = $this->EduClass->read(null, $id);
        if (count($class['EduSection']) > 0 || count($class['EduCourse']) > 0 || count($class['EduRegistration']) > 0 ||
                count($class['EduPaymentSchedule']) > 0 || count($class['EduExtraPaymentSetting']) > 0) {
            $this->cakeError('cannotDeleteRecord', array(
                'message' => 'Class has related records in other locations. (ERR-102-03)',
                'helpcode' => 'ERR-102-03'
			));
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduClass->delete($i);
                }
                $this->Session->setFlash(__('Class successfully deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->cakeError('cannotDeleteRecord', array(
                    'message' => 'Class cannot be deleted. (' .$e->getMessage() . ').',
                    'helpcode' => 'ERR-000-00'));
            }
        } else {
            if ($this->EduClass->delete($id)) {
                $this->Session->setFlash(__('Class deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotDeleteRecord', array(
                    'message' => 'Class cannot be deleted.',
                    'helpcode' => 'ERR-000-00'));
            }
        }
    }

}
