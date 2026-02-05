<?php
class EduScalesController extends EduAppController {

	var $name = 'EduScales';
	
	function index() {
	}
	

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		
		$this->set('edu_scales', $this->EduScale->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->EduScale->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid scale', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EduScale->recursive = 2;
		$this->set('edu_scale', $this->EduScale->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->EduScale->create();
			$this->autoRender = false;
			if ($this->EduScale->save($this->data)) {
				$this->Session->setFlash(__('The scale has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The scale could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid scale', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EduScale->save($this->data)) {
				$this->Session->setFlash(__('The scale has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The scale could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$edu_scale = $this->EduScale->read(null, $id);
		$this->set('edu_scale', $edu_scale);
	
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for scale', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EduScale->delete($i);
                }
				$this->Session->setFlash(__('Scale deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Scale was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EduScale->delete($id)) {
				$this->Session->setFlash(__('Scale deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Scale was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>