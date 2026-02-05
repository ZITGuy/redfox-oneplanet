<?php

class PermissionsController extends AppController {

    var $name = 'Permissions';

    function index() {
        
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }

    function list_data($id) {
        $start = ($_REQUEST['start'] != '') ? $_REQUEST['start'] : 0;
        $limit = ($_REQUEST['limit'] != '') ? $_REQUEST['limit'] : 20;
        $task_id = (isset($_REQUEST['task_id'])) ? $_REQUEST['task_id'] : -1;
        if ($id)
            $task_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($task_id != -1) {
            $conditions['Permission.task_id'] = $task_id;
        }

        $this->set('permissions', $this->Permission->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->Permission->find('count', array('conditions' => $conditions)));
    }

    function add($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('The permission could not be created. Task selected is not valid.', true));
            $this->render('/elements/failure');
            return;
        }
        if (!empty($this->data)) {
            $this->Permission->create();
            $this->autoRender = false;
            if ($this->Permission->save($this->data)) {
                $this->Session->setFlash(__('The permission has been saved', true));
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The permission could not be saved. Please, try again.', true));
                $this->render('/elements/failure');
            }
        }
        $this->set('parent_id', $id);
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid permission', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if (!isset($this->data['Permission']['prerequisite']) ||
                    $this->data['Permission']['prerequisite'] == null)
                $this->data['Permission']['prerequisite'] = 0;
            if ($this->Permission->save($this->data)) {
                $this->Session->setFlash(__('The permission has been saved', true));
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The permission could not be saved. Please, try again.', true));
                $this->render('/elements/failure');
            }
        }
        $this->set('permission', $this->Permission->read(null, $id));

        $prerequisites = $this->Permission->find('list', array('conditions' => array('Permission.name LIKE' => '%:%'), 'order' => 'name ASC'));
        $this->set(compact('prerequisites'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for permission', true));
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->Permission->delete($i);
                }
                $this->Session->setFlash(__('Permission deleted', true));
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Permission was not deleted', true));
                $this->render('/elements/failure');
            }
        } else {
            if ($this->Permission->delete($id)) {
                $this->Session->setFlash(__('Permission deleted', true));
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Permission was not deleted', true));
                $this->render('/elements/failure');
            }
        }
    }
}

?>