<?php
class EduGradeRuleValuesController extends EduAppController {

	var $name = 'EduGradeRuleValues';
	
	function index() {
		$grade_rules = $this->EduGradeRuleValue->EduGradeRule->find('all');
		$this->set(compact('grade_rules'));
	}
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
		$graderule_id = (isset($_REQUEST['graderule_id'])) ? $_REQUEST['graderule_id'] : -1;
		if($id)
			$graderule_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($graderule_id != -1) {
            $conditions['EduGradeRuleValue.graderule_id'] = $graderule_id;
        }
		
		$this->set('gradeRuleValues', $this->EduGradeRuleValue->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->EduGradeRuleValue->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid grade rule value', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EduGradeRuleValue->recursive = 2;
		$this->set('gradeRuleValue', $this->EduGradeRuleValue->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->EduGradeRuleValue->create();
			$this->autoRender = false;
			if ($this->EduGradeRuleValue->save($this->data)) {
				$this->Session->setFlash(__('The grade rule value has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The grade rule value could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$grade_rules = $this->EduGradeRuleValue->GradeRule->find('list');
		$this->set(compact('grade_rules'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid grade rule value', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EduGradeRuleValue->save($this->data)) {
				$this->Session->setFlash(__('The grade rule value has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The grade rule value could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('grade__rule__value', $this->EduGradeRuleValue->read(null, $id));
		
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
			
		$grade_rules = $this->EduGradeRuleValue->EduGradeRule->find('list');
		$this->set(compact('grade_rules'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for grade rule value', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EduGradeRuleValue->delete($i);
                }
				$this->Session->setFlash(__('Grade rule value deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Grade rule value was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EduGradeRuleValue->delete($id)) {
				$this->Session->setFlash(__('Grade rule value deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Grade rule value was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>