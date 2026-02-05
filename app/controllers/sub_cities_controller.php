<?php
class SubCitiesController extends AppController {

	var $name = 'SubCities';
	
	function index() {
	}

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		
		$this->set('sub_cities', $this->SubCity->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->SubCity->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid sub city', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->SubCity->recursive = 2;
		$this->set('sub_city', $this->SubCity->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->SubCity->create();
			$this->autoRender = false;
			if ($this->SubCity->save($this->data)) {
				$this->Session->setFlash(__('The sub city has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The sub city could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid sub city', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->SubCity->save($this->data)) {
				$this->Session->setFlash(__('The sub city has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The sub city could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('sub_city', $this->SubCity->read(null, $id));
		
			
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for sub city', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->SubCity->delete($i);
                }
				$this->Session->setFlash(__('Sub city deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Sub city was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->SubCity->delete($id)) {
				$this->Session->setFlash(__('Sub city deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Sub city was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>