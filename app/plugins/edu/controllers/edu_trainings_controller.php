<?php
class EduTrainingsController extends EduAppController {

	var $name = 'EduTrainings';
	
	function index() {
	}
	
	function index_v() {
	}

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		
		$this->set('edu_trainings', $this->EduTraining->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->EduTraining->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid edu training', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EduTraining->recursive = 2;
		$this->set('edu_training', $this->EduTraining->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->EduTraining->create();
			$this->autoRender = false;
			if ($this->EduTraining->save($this->data)) {
				$this->Session->setFlash(__('The training has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The training could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		
		$categories = $this->EduTraining->EduTrainingCategory->find('list', array('order' => 'name'));
		
		$this->set('categories', $categories);
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid training', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EduTraining->save($this->data)) {
				$this->Session->setFlash(__('The training has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The training could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('edu_training', $this->EduTraining->read(null, $id));
		
		$categories = $this->EduTraining->EduTrainingCategory->find('list', array('order' => 'name'));
		$this->set('categories', $categories);	
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for training', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EduTraining->delete($i);
                }
				$this->Session->setFlash(__('Training deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Edu training was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EduTraining->delete($id)) {
				$this->Session->setFlash(__('Training deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Training was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>