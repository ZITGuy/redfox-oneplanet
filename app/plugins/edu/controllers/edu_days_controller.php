<?php

class EduDaysController extends EduAppController {

    var $name = 'EduDays';

    function index() {
        $edu_quarters = $this->EduDay->EduQuarter->find('all');
        $this->set(compact('edu_quarters'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_quarter_id = (isset($_REQUEST['edu_quarter_id'])) ? $_REQUEST['edu_quarter_id'] : -1;
        if ($id) {
            $edu_quarter_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_quarter_id != -1) {
            $conditions['EduDay.edu_quarter_id'] = $edu_quarter_id;
        }

        $this->set('edu_days', $this->EduDay->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduDay->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if ( !$id ) {
            $this->Session->setFlash( __( 'Invalid education day', true ) );
            $this->redirect( array( 'action' => 'index' ) );
        }
        $this->EduDay->recursive = 2;
        $this->set( 'eduDay', $this->EduDay->read( null, $id ) );
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->EduDay->create();
            $this->autoRender = false;
            if ($this->EduDay->save($this->data)) {
                $this->Session->setFlash(__('The edu day has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu day could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id) {
            $this->set('parent_id', $id);
        }
        $edu_quarters = $this->EduDay->EduQuarter->find('list');
        $this->set(compact('edu_quarters'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu day', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduDay->save($this->data)) {
                $this->Session->setFlash(__('The edu day has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu day could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_day', $this->EduDay->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $edu_quarters = $this->EduDay->EduQuarter->find('list');
        $this->set(compact('edu_quarters'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for edu day', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduDay->delete($i);
                }
                $this->Session->setFlash(__('Edu day deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Edu day was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduDay->delete($id)) {
                $this->Session->setFlash(__('Edu day deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Edu day was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}

?>