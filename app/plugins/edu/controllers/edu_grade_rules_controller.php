<?php
class EduGradeRulesController extends EduAppController {

	var $name = 'EduGradeRules';
	
	function index() {
	}
	

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		
		$this->set('gradeRules', $this->EduGradeRule->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->EduGradeRule->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid grade rule', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EduGradeRule->recursive = 2;
		$this->set('gradeRule', $this->EduGradeRule->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->EduGradeRule->create();
			$this->autoRender = false;
			if ($this->EduGradeRule->save($this->data)) {
				$this->Session->setFlash(__('The grade rule has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The grade rule could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid grade rule', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EduGradeRule->save($this->data)) {
				$this->Session->setFlash(__('The grade rule has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The grade rule could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('grade__rule', $this->EduGradeRule->read(null, $id));
		
			
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for grade rule', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EduGradeRule->delete($i);
                }
				$this->Session->setFlash(__('Grade rule deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Grade rule was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EduGradeRule->delete($id)) {
				$this->Session->setFlash(__('Grade rule deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Grade rule was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>