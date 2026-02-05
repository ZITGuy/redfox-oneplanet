<?php

class EduEvaluationAreasController extends EduAppController {

    var $name = 'EduEvaluationAreas';

    function index() {
        $edu_evaluation_categories = $this->EduEvaluationArea->EduEvaluationCategory->find('all');
        $this->set(compact('edu_evaluation_categories'));
    }

    function index_m() {
        $edu_evaluation_categories = $this->EduEvaluationArea->EduEvaluationCategory->find('all');
        $this->set(compact('edu_evaluation_categories'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);

        $this->set('category', $this->EduEvaluationArea->EduEvaluationCategory->read(null, $id));
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_evaluation_category_id = (isset($_REQUEST['edu_evaluation_category_id'])) ? $_REQUEST['edu_evaluation_category_id'] : -1;
        if ($id)
            $edu_evaluation_category_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_evaluation_category_id != -1) {
            $conditions['EduEvaluationArea.edu_evaluation_category_id'] = $edu_evaluation_category_id;
        }

        $this->set('edu_evaluation_areas', $this->EduEvaluationArea->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduEvaluationArea->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu evaluation area', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduEvaluationArea->recursive = 2;
        $this->set('edu_evaluation_area', $this->EduEvaluationArea->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->EduEvaluationArea->create();
            $this->autoRender = false;
            if ($this->EduEvaluationArea->save($this->data)) {
                $this->Session->setFlash(__('The edu evaluation area has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu evaluation area could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
        $edu_evaluation_categories = $this->EduEvaluationArea->EduEvaluationCategory->find('list');
        $this->set(compact('edu_evaluation_categories'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu evaluation area', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduEvaluationArea->save($this->data)) {
                $this->Session->setFlash(__('The edu evaluation area has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu evaluation area could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_evaluation_area', $this->EduEvaluationArea->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $edu_evaluation_categories = $this->EduEvaluationArea->EduEvaluationCategory->find('list');
        $this->set(compact('edu_evaluation_categories'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for edu evaluation area', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduEvaluationArea->delete($i);
                }
                $this->Session->setFlash(__('Edu evaluation area deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Edu evaluation area was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduEvaluationArea->delete($id)) {
                $this->Session->setFlash(__('Edu evaluation area deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Edu evaluation area was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}

?>