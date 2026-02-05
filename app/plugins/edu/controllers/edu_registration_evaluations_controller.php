<?php
class EduRegistrationEvaluationsController extends EduAppController {

	var $name = 'EduRegistrationEvaluations';
	
	function index() {
		$edu_registrations = $this->EduRegistrationEvaluation->EduRegistration->find('all');
		$this->set(compact('edu_registrations'));
	}
	
	function get_evaluation_values($evaluation_id) {
		$this->loadModel('EduEvaluation');
		$this->loadModel('EduEvaluationCategory');
		$this->loadModel('EduEvaluationValue');
		$evaluation = $this->EduEvaluation->read(null, $evaluation_id);
		$cat_id = $evaluation['EduEvaluationArea']['edu_evaluation_category_id'];
		
		$cat = $this->EduEvaluationCategory->read(null, $cat_id);
		$vg = $cat['EduEvaluationCategory']['evaluation_value_group'];
		
		$evs = $this->EduEvaluationValue->find('all', array('conditions' => array(
			'EduEvaluationValue.evaluation_value_group' => $vg)));
		$this->set('edu_evaluation_values', $evs);
	}
	
	function student_evaluation() {
		$this->loadModel('EduSection');
		$this->loadModel('EduTeacher');
       	 	$this->loadModel('EduAcademicYear');
		
		$ay = $this->EduAcademicYear->getActiveAcademicYear();
        	$edu_academic_year_id = $ay['EduAcademicYear']['id'];
        
        	$teacher = $this->EduTeacher->find('first', array(
                	'conditions' => array('EduTeacher.user_id' => $this->Session->read('Auth.User.id'))
            	));
		$edu_sections = null;
		if(!empty($teacher)) {
			$teacher_id = $teacher['EduTeacher']['id'];
			// if the teacher is homeroom -- for self contained classes
			$edu_sections = $this->EduSection->find('all', array('conditions' => array(
				'edu_academic_year_id' => $edu_academic_year_id,
				'OR' => array('edu_teacher_id' => $teacher_id, 'co_teacher_id' => $teacher_id) 
				//, 'EduClass.uni_teacher' => 0
			), 'order' => 'EduClass.cvalue'));
		} else {
			$edu_sections = $this->EduSection->find('all', array('conditions' => array(
				'edu_academic_year_id' => $edu_academic_year_id, 'EduClass.grading_type <>' => 'G'
				), 'order' => 'EduClass.cvalue'));
		}
		$sections = array();
		foreach($edu_sections as $sec) {
			$sections[$sec['EduSection']['id']] = $sec['EduClass']['name'] . ' - ' . $sec['EduSection']['name'];
		}
		
		$this->set('sections', $sections);
		
		$conditions = array('EduEvaluationValue.evaluation_value_group <>' => 1);

		$edu_evaluation_values = $this->EduRegistrationEvaluation->EduEvaluationValue->find('all', array(
			'conditions' => $conditions));
		$this->set('edu_evaluation_values', $edu_evaluation_values);
	}

	function student_evaluation_for_preschool() {
		$this->loadModel('EduClass');
		$conditions = array('EduClass.grading_type' => 'G');
		$classes = $this->EduClass->find('list', array('conditions' => $conditions));
		$this->set('classes', $classes);
		
		$conditions = array('EduEvaluationValue.evaluation_value_group' => 1);

		$edu_evaluation_values = $this->EduRegistrationEvaluation->EduEvaluationValue->find('all', array(
			'conditions' => $conditions));
		$this->set('edu_evaluation_values', $edu_evaluation_values);
	}
	
	function save_changes() {
		$this->autoRender = false;
		
		foreach($this->data as $record){
			$re_id = $record['id'];
			$gu_name = $record['evaluation_value'];
			$selected_evaluation_value = $this->EduRegistrationEvaluation->EduEvaluationValue->find('first', array(
				'conditions' => array('EduEvaluationValue.description' => str_replace('"', '', $gu_name))));
			$evaluation_value_id = $selected_evaluation_value['EduEvaluationValue']['id'];
			// update the records
			$this->EduRegistrationEvaluation->read(null, str_replace('"', '', $re_id));
			$this->EduRegistrationEvaluation->set('edu_evaluation_value_id', $evaluation_value_id);
			$this->EduRegistrationEvaluation->save();
		}

		///?????
	}
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
		$edu_registration_id = (isset($_REQUEST['edu_registration_id'])) ? $_REQUEST['edu_registration_id'] : -1;
		if($id)
			$edu_registration_id = ($id) ? $id : -1;
        	$conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

       	 	eval("\$conditions = array( " . $conditions . " );");
		if ($edu_registration_id != -1) {
            		$conditions['EduRegistrationEvaluation.edu_registration_id'] = $edu_registration_id;
        	}
		
		$this->set('edu_registration_evaluations', $this->EduRegistrationEvaluation->find('all', array('conditions' => 			$conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->EduRegistrationEvaluation->find('count', array('conditions' => $conditions)));
	}
	
	function list_data_for_evaluation() {
		ini_set('memory_limit', '2048M');
		$selected_section_id = (isset($_REQUEST['selected_section_id'])) ? $_REQUEST['selected_section_id'] : -1;
		$selected_evaluation_id = (isset($_REQUEST['selected_evaluation_id'])) ? $_REQUEST['selected_evaluation_id'] : -1;
		
		$this->loadModel('EduAcademicYear');
		$ay = $this->EduAcademicYear->getActiveAcademicYear();
		$edu_academic_year_id = $ay['EduAcademicYear']['id'];

		$registrations = $this->EduRegistrationEvaluation->EduRegistration->find('all', array(
			'conditions' => array(
				'EduRegistration.edu_section_id' => $selected_section_id,
				'EduRegistration.edu_academic_year_id' => $edu_academic_year_id
			)
		));
		
		//$active_quarter = $this->EduRegistrationEvaluation->EduQuarter->find('first', array(
		//	'conditions' => array('EduQuarter.status' => 'AC')));
		$active_quarter = $this->EduRegistrationEvaluation->EduQuarter->getActiveQuarter();

          $quarter_id = $active_quarter['EduQuarter']['id'];
		
		$evaluation = $this->EduRegistrationEvaluation->EduEvaluation->read(null, $selected_evaluation_id);

		$default_evaluation_value_id = $evaluation['EduEvaluationValue']['id'];

		//$this->log($evaluation, 'evaluation');
		//$this->log($registrations, 'evaluation');
		
		$registration_evaluations = array();
		foreach($registrations as $registration) {
			$this->EduRegistrationEvaluation->recursive = 2;
			$reg_eval = $this->EduRegistrationEvaluation->find('first', array('conditions' => array(
				'EduRegistrationEvaluation.edu_registration_id' => $registration['EduRegistration']['id'],
				'EduRegistrationEvaluation.edu_evaluation_id' => $selected_evaluation_id,
				'EduRegistrationEvaluation.edu_quarter_id' => $quarter_id
			)));
			
			if(empty($reg_eval) || count($reg_eval) == 0) {
				$reg_eval = array('EduRegistrationEvaluation' => array(
					'edu_registration_id' =>  $registration['EduRegistration']['id'],
					'edu_evaluation_id' =>  $selected_evaluation_id,
					'edu_quarter_id' =>  $quarter_id,
					'edu_evaluation_value_id' =>  $default_evaluation_value_id
				));
				$this->EduRegistrationEvaluation->create();
				$this->EduRegistrationEvaluation->save($reg_eval);
				
				$reg_eval = $this->EduRegistrationEvaluation->find('first', array('conditions' => array(
					'EduRegistrationEvaluation.edu_registration_id' => $registration['EduRegistration']['id'],
					'EduRegistrationEvaluation.edu_evaluation_id' => $selected_evaluation_id,
					'EduRegistrationEvaluation.edu_quarter_id' => $quarter_id
				)));
			} else if($reg_eval['EduRegistrationEvaluation']['edu_evaluation_value_id'] <= 0) {
				$reg_eval['EduRegistrationEvaluation']['edu_evaluation_value_id'] = $default_evaluation_value_id;
				$this->EduRegistrationEvaluation->read(null, $reg_eval['EduRegistrationEvaluation']['id']);
				$this->EduRegistrationEvaluation->save($reg_eval);
				$reg_eval = $this->EduRegistrationEvaluation->find('first', array('conditions' => array(
					'EduRegistrationEvaluation.edu_registration_id' => $registration['EduRegistration']['id'],
					'EduRegistrationEvaluation.edu_evaluation_id' => $selected_evaluation_id,
					'EduRegistrationEvaluation.edu_quarter_id' => $quarter_id
				)));
			}
			$registration_evaluations[] = $reg_eval;
		}

		//$this->log($registration_evaluations, 'evaluation');
		
		$this->set('edu_registration_evaluations', $registration_evaluations);
		$this->set('results', count($registration_evaluations));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid edu registration evaluation', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EduRegistrationEvaluation->recursive = 2;
		$this->set('edu_registration_evaluation', $this->EduRegistrationEvaluation->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->EduRegistrationEvaluation->create();
			$this->autoRender = false;
			if ($this->EduRegistrationEvaluation->save($this->data)) {
				$this->Session->setFlash(__('The edu registration evaluation has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The edu registration evaluation could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$edu_registrations = $this->EduRegistrationEvaluation->EduRegistration->find('list');
		$edu_evaluations = $this->EduRegistrationEvaluation->EduEvaluation->find('list');
		$edu_quarters = $this->EduRegistrationEvaluation->EduQuarter->find('list');
		$edu_evaluation_values = $this->EduRegistrationEvaluation->EduEvaluationValue->find('list');
		$this->set(compact('edu_registrations', 'edu_evaluations', 'edu_quarters', 'edu_evaluation_values'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid evaluation', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EduRegistrationEvaluation->save($this->data)) {
				$this->Session->setFlash(__('The evaluation saved successfully', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The evaluation could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		
		$this->loadModel('EduEvaluation');
		$this->loadModel('EduEvaluationArea');
		$this->loadModel('EduEvaluationCategory');
		
		$registration_evaluation = $this->EduRegistrationEvaluation->read(null, $id);
		$this->set('edu_registration_evaluation', $registration_evaluation);
		
		$ev = $this->EduEvaluation->read(null, $registration_evaluation['EduRegistrationEvaluation']['edu_evaluation_id']);
		$eva = $this->EduEvaluationArea->read(null, $ev['EduEvaluation']['edu_evaluation_area_id']);
		$evc = $this->EduEvaluationCategory->read(null, $eva['EduEvaluationArea']['edu_evaluation_category_id']);
		$evg = $evc['EduEvaluationCategory']['evaluation_value_group'];
		
		$cond = array('EduEvaluationValue.evaluation_value_group' => $evg);
		
			
		$evaluation_values = $this->EduRegistrationEvaluation->EduEvaluationValue->find('all', array('conditions' => $cond));
		$edu_evaluation_values = array();
		foreach($evaluation_values as $evv) {
			$edu_evaluation_values[$evv['EduEvaluationValue']['id']] = 
				$evv['EduEvaluationValue']['name'] . ' - ' . $evv['EduEvaluationValue']['description'];
		}
		
		$this->set(compact('edu_evaluation_values'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for evaluation', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EduRegistrationEvaluation->delete($i);
                }
				$this->Session->setFlash(__('Evaluation deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Evaluation was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EduRegistrationEvaluation->delete($id)) {
				$this->Session->setFlash(__('Evaluation deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Evaluation was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>
