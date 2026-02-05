<?php

class EduClassLevelsController extends EduAppController {

    var $name = 'EduClassLevels';

    function index() {
        
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");

        $this->set('edu_class_levels', $this->EduClassLevel->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduClassLevel->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu class level', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduClassLevel->recursive = 2;
        $this->set('edu_class_level', $this->EduClassLevel->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->EduClassLevel->create();
            $this->autoRender = false;
            if ($this->EduClassLevel->save($this->data)) {
                $this->Session->setFlash(__('The edu class level has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu class level could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu class level', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduClassLevel->save($this->data)) {
                $this->Session->setFlash(__('The edu class level has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu class level could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_class_level', $this->EduClassLevel->read(null, $id));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for edu class level', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduClassLevel->delete($i);
                }
                $this->Session->setFlash(__('Edu class level deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Edu class level was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduClassLevel->delete($id)) {
                $this->Session->setFlash(__('Edu class level deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Edu class level was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}

?>