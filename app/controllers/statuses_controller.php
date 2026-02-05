<?php
class StatusesController extends AppController {

	var $name = 'Statuses';
	
	function index() {
	}
	

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		
		$this->set('statuses', $this->Status->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->Status->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid status', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Status->recursive = 2;
		$this->set('status', $this->Status->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->Status->create();
			$this->autoRender = false;
			if ($this->Status->save($this->data)) {
				$this->Session->setFlash(__('The status has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The status could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid status', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->Status->save($this->data)) {
				$this->Session->setFlash(__('The status has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The status could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('status', $this->Status->read(null, $id));
		
			
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for status', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->Status->delete($i);
                }
				$this->Session->setFlash(__('Status deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Status was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->Status->delete($id)) {
				$this->Session->setFlash(__('Status deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Status was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>