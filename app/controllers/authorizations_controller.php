<?php
class AuthorizationsController extends AppController {

	var $name = 'Authorizations';
	
	function index() {
		$makers = $this->Authorization->Maker->find('all');
		$this->set(compact('makers'));
	}
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
		$maker_id = (isset($_REQUEST['maker_id'])) ? $_REQUEST['maker_id'] : -1;
		if($id)
			$maker_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($maker_id != -1) {
            $conditions['Authorization.maker_id'] = $maker_id;
        }
		
		$this->set('authorizations', $this->Authorization->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->Authorization->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid authorization', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Authorization->recursive = 2;
		$this->set('authorization', $this->Authorization->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->Authorization->create();
			$this->autoRender = false;
			if ($this->Authorization->save($this->data)) {
				$this->Session->setFlash(__('The authorization has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The authorization could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$makers = $this->Authorization->Maker->find('list');
		$authorizers = $this->Authorization->Authorizer->find('list');
		$this->set(compact('makers', 'authorizers'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid authorization', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->Authorization->save($this->data)) {
				$this->Session->setFlash(__('The authorization has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The authorization could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('authorization', $this->Authorization->read(null, $id));
		
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
		
		$makers = $this->Authorization->Maker->find('list');
		$authorizers = $this->Authorization->Authorizer->find('list');
		$this->set(compact('makers', 'authorizers'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for authorization', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->Authorization->delete($i);
                }
				$this->Session->setFlash(__('Authorization deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Authorization was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->Authorization->delete($id)) {
				$this->Session->setFlash(__('Authorization deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Authorization was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>