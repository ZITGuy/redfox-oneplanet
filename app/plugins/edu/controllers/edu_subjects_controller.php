<?php
App::import('Amharic');

class EduSubjectsController extends AppController {

    var $name = 'EduSubjects';

    function index_m() {
        
    }
    
    function index_o() {
        
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

        $this->set('edu_subjects', $this->EduSubject->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduSubject->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->cakeError('cannotViewRecord', array(
                'message' => 'Invalid id for Subject. (ERR-101-01)',
                'helpcode' => 'ERR-101-01'));
        }
        $this->EduSubject->recursive = 2;
        $this->set('edu_subject', $this->EduSubject->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->EduSubject->create();
            $this->autoRender = false;
            $this->data['EduSubject']['name_am'] = Amharic::encode_amharic($this->data['EduSubject']['name_am']);

            if ($this->EduSubject->save($this->data)) {
                $this->Session->setFlash(__('The Subject has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'The subject could not be saved. (' . pr($this->EduSubject->validationErrors, true) .  ').',
                    'helpcode' => 'ERR-000-00'));
            }
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->cakeError('cannotViewRecord', array(
                'message' => 'Invalid id for Subject.',
                'helpcode' => 'ERR-000-00'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            $this->data['EduSubject']['name_am'] = Amharic::encode_amharic($this->data['EduSubject']['name_am']);
            if ($this->EduSubject->save($this->data)) {
                $this->Session->setFlash(__('The Subject has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'The subject could not be saved. (' . pr($this->EduSubject->validationErrors, true) .  ').',
                    'helpcode' => 'ERR-000-00'));
            }
        }
        $edu_subject = $this->EduSubject->read(null, $id);
        $edu_subject['EduSubject']['name_am'] = Amharic::decode_amharic($edu_subject['EduSubject']['name_am']);
        
        $this->set('edu_subject', $edu_subject);
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->cakeError('cannotDeleteRecord', array(
                'message' => 'Invalid id for Subject.',
                'helpcode' => 'ERR-000-00'));
        }
        $subject = $this->EduSubject->read(null, $id);
        if (count($subject['EduCourse']) > 0 || count($subject['EduTeacher']) > 0) {
            $this->cakeError('cannotDeleteRecord', array(
                'message' => 'Subject has related records in other locations. (ERR-101-03)',
                'helpcode' => 'ERR-101-03'));
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduSubject->delete($i);
                }
                $this->Session->setFlash(__('Subject successfully deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->cakeError('cannotDeleteRecord', array(
                    'message' => 'Subject cannot be deleted. (' .$e->getMessage() . '). (ERR-101-04)',
                    'helpcode' => 'ERR-101-04'));
            }
        } else {
            if ($this->EduSubject->delete($id)) {
                $this->Session->setFlash(__('Subject successfully deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotDeleteRecord', array(
                    'message' => 'Subject cannot be deleted. (ERR-101-05)',
                    'helpcode' => 'ERR-101-05'));
            }
        }
    }

}
