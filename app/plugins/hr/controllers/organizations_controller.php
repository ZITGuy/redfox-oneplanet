<?php
class OrganizationsController extends HrAppController {

	var $name = 'Organizations';
	
    function index() {
    }
	

    function search() {
    }
	
    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		
        $this->set('organizations', $this->Organization->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->Organization->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid organization', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Organization->recursive = 2;
        $this->set('organization', $this->Organization->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->Organization->create();
            $this->autoRender = false;
            if ($this->Organization->save($this->data)) {
                $this->Session->setFlash(__('The organization has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The organization could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid organization', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->Organization->save($this->data)) {
                $this->Session->setFlash(__('The organization has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The organization could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('organization', $this->Organization->read(null, $id));


    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for organization', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->Organization->delete($i);
                }
                $this->Session->setFlash(__('Organization deleted', true), '');
                $this->render('/elements/success');
            }
            catch (Exception $e){
                $this->Session->setFlash(__('Organization was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->Organization->delete($id)) {
                $this->Session->setFlash(__('Organization deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Organization was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }
}
?>