<?php
class EduEvaluationValuesController extends EduAppController {

	var $name = 'EduEvaluationValues';
	
	function index() {
	}
	

	function search() {
	}
	
	function get_evaluation_values_combo($id = null) {
		$evaluation_values = array();
		if($id != null) {
			$this->loadModel('EduEvaluation');
			
			$this->EduEvaluation->recursive = 3;
			$evaluation = $this->EduEvaluation->read(null, $id);
			
			if(isset($evaluation['EduEvaluationArea']['EduEvaluationCategory']['evaluation_value_group'])) {
				$value_group = $evaluation['EduEvaluationArea']['EduEvaluationCategory']['evaluation_value_group'];
				
				$e_values = $this->EduEvaluationValue->find('all', array('conditions' =>array('EduEvaluationValue.evaluation_value_group' => $value_group)));
				
				foreach($e_values as $evalue) {
					$evaluation_values[$evalue['EduEvaluationValue']['id']] = $evalue['EduEvaluationValue']['description'];
				}
			}
		}
		$this->set('evaluation_values', $evaluation_values);
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		
		$this->set('edu_evaluation_values', $this->EduEvaluationValue->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->EduEvaluationValue->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid evaluation_value', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EduEvaluationValue->recursive = 2;
		$this->set('edu_evaluation_value', $this->EduEvaluationValue->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->EduEvaluationValue->create();
			$this->autoRender = false;
			if ($this->EduEvaluationValue->save($this->data)) {
				$this->Session->setFlash(__('The evaluation_value has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The evaluation_value could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid evaluation_value', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EduEvaluationValue->save($this->data)) {
				$this->Session->setFlash(__('The evaluation_value has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The evaluation_value could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('edu_evaluation_value', $this->EduEvaluationValue->read(null, $id));
		
			
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for evaluation_value', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EduEvaluationValue->delete($i);
                }
				$this->Session->setFlash(__('EvaluationValue deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('EvaluationValue was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EduEvaluationValue->delete($id)) {
				$this->Session->setFlash(__('EvaluationValue deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('EvaluationValue was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>