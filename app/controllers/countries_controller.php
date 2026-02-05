<?php
class CountriesController extends AppController {

	var $name = 'Countries';
	
	function index() {
	}
	

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		
		$this->set('countries', $this->Country->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->Country->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid country', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Country->recursive = 2;
		$this->set('country', $this->Country->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->Country->create();
			$this->autoRender = false;
			if ($this->Country->save($this->data)) {
				$this->Session->setFlash(__('The country has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The country could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid country', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->Country->save($this->data)) {
				$this->Session->setFlash(__('The country has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The country could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('country', $this->Country->read(null, $id));
		
			
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for country', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->Country->delete($i);
                }
				$this->Session->setFlash(__('Country deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Country was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->Country->delete($id)) {
				$this->Session->setFlash(__('Country deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Country was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>