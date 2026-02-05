<?php

class EduEvaluationsController extends EduAppController {
    
    public $name = 'EduEvaluations';
    
    public function index()
    {
        $edu_classes = $this->EduEvaluation->EduClass->find('all');
        $this->set(compact('edu_classes'));
    }

    public function index2($id = null)
    {
        $this->set('parent_id', $id);
    }

    public function search()
    {
        // empty body
    }

    public function list_data($id = null)
    {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_class_id = (isset($_REQUEST['edu_class_id'])) ? $_REQUEST['edu_class_id'] : -1;
        if ($id) {
            $edu_class_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_class_id != -1) {
            $conditions['EduEvaluation.edu_class_id'] = $edu_class_id;
        }
        $this->EduEvaluation->recursive = 1;
        $eduEvaluations = $this->EduEvaluation->find('all', array(
            'conditions' => $conditions, 'limit' => $limit, 'offset' => $start));
        $this->set('edu_evaluations', $eduEvaluations);
        $this->set('results', $this->EduEvaluation->find('count', array('conditions' => $conditions)));
		
		$this->loadModel('Edu.EduEvaluationCategory');
		$categories = $this->EduEvaluationCategory->find('list');
		
		$this->set('categories', $categories);
    }

    public function list_data2($id = null)
    {
        $edu_class_id = (isset($_REQUEST['edu_class_id'])) ? $_REQUEST['edu_class_id'] : -1;
        if ($id) {
            $edu_class_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_class_id != -1) {
            $conditions['EduEvaluation.edu_class_id'] = $edu_class_id;
        }
        $this->set('edu_evaluations', $this->EduEvaluation->find('all', array('conditions' => $conditions)));
        $this->set('results', $this->EduEvaluation->find('count', array('conditions' => $conditions)));
    }
	
	public function list_data_for_section($id = null)
    {
        $edu_section_id = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : -1;
        if ($id) {
            $edu_section_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

		$evaluateds = array();
        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_section_id != -1) {
			$this->loadModel('EduSection');
			$section = $this->EduSection->read(null, $edu_section_id);
            $conditions['EduEvaluation.edu_class_id'] = $section['EduSection']['edu_class_id'];
        
			// get the list of students registration evaluation
			// for each of the evaluations in this quarter
			$this->loadModel('EduQuarter');
			$this->loadModel('EduRegistrationEvaluation');
			
			$active_quarter = $this->EduQuarter->getActiveQuarter();
			$reg_evs = $this->EduRegistrationEvaluation->find('all', array(
				'conditions' => array(
					'EduRegistrationEvaluation.edu_quarter_id' => $active_quarter['EduQuarter']['id'],
					'EduRegistration.edu_section_id' => $edu_section_id
				)
			));
			
			foreach ($reg_evs as $reg_ev) {
				$evaluateds[$reg_ev['EduRegistrationEvaluation']['edu_evaluation_id']] =
                    $reg_ev['EduRegistrationEvaluation']['edu_evaluation_id'];
			}
		}
		
		$this->set('evaluateds', $evaluateds);
        $this->set('edu_evaluations', $this->EduEvaluation->find('all', array('conditions' => $conditions)));
        $this->set('results', $this->EduEvaluation->find('count', array('conditions' => $conditions)));
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->cakeError('cannotViewRecord', array(
                'message' => 'Invalid id for Evaluation. (ERR-104-01)',
                'helpcode' => 'ERR-104-01'));
        }
        $this->EduEvaluation->recursive = 2;
        $this->set('edu_evaluation', $this->EduEvaluation->read(null, $id));
    }

    public function add($id = null)
    {
        if (!empty($this->data)) {
            $this->EduEvaluation->create();
            $this->autoRender = false;
            if ($this->EduEvaluation->save($this->data)) {
                $this->Session->setFlash(__('The Evaluation has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'The Evaluation could not be saved. (' .
                        pr($this->EduEvaluation->validationErrors, true) .  '). (ERR-104-02)',
                    'helpcode' => 'ERR-104-02'));
            }
        }
        $evaluation_value_group = 2;
        if ($id) {
            $this->set('parent_id', $id);

            $class = $this->EduEvaluation->EduClass->read(null, $id);

            $evaluation_value_group = ($class['EduClass']['grading_type'] == 'G');
        }

        $evaluation_values = $this->EduEvaluation->EduEvaluationValue->find('all', array('conditions' => array(
                //'EduEvaluationValue.evaluation_value_group' => $evaluation_value_group
            )));

        $edu_evaluation_values = array();
        foreach ($evaluation_values as $evaluation_value) {
             $edu_evaluation_values[$evaluation_value['EduEvaluationValue']['id']] =
                $evaluation_value['EduEvaluationValue']['description'];
        }

        $edu_classes = $this->EduEvaluation->EduClass->find('list');
        $edu_evaluation_areas = $this->EduEvaluation->EduEvaluationArea->find('list');
        $this->set(compact('edu_classes', 'edu_evaluation_areas', 'edu_evaluation_values'));
    }
	
	public function add_plus($id = null)
    {
		$this->loadModel('EduEvaluationCategory');
        if (!empty($this->data)) {
			$cat_id = $this->data['EduEvaluation']['edu_evaluation_category_id'];
			$class_id = $this->data['EduEvaluation']['edu_class_id'];
			$cat = $this->EduEvaluationCategory->read(null, $cat_id);
			$this->autoRender = false;
			
			$order_level = 1;
			foreach($cat['EduEvaluationArea'] as $ea) {
				$this->EduEvaluation->create();
				$evaluation = array('EduEvaluation' => array(
					'edu_class_id' => $class_id,
					'edu_evaluation_area_id' => $ea['id'],
					'order_level' => $order_level++,
					'edu_evaluation_value_id' => $ea['edu_evaluation_value_id']
					
				));
				$this->EduEvaluation->save($evaluation);
			}
			$this->Session->setFlash(__('Evaluations created successfully', true), '');
			$this->render('/elements/success');
        }
		
		if ($id) {
            $this->set('parent_id', $id);
		}

        $edu_classes = $this->EduEvaluation->EduClass->find('list');
        $categories = $this->EduEvaluationCategory->find('all');
		$edu_evaluation_categories = array();
		foreach ($categories as $category) {
			if (count($category['EduEvaluationArea']) > 0) {
				$edu_evaluation_categories[$category['EduEvaluationCategory']['id']] =
                    $category['EduEvaluationCategory']['name'];
		    }
        }
		
        $this->set(compact('edu_classes', 'edu_evaluation_categories'));
    }

    public function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->cakeError('cannotViewRecord', array(
                'message' => 'Invalid id for Evaluation. (ERR-104-01)',
                'helpcode' => 'ERR-104-01'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduEvaluation->save($this->data)) {
                $this->Session->setFlash(__('The evaluation has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotSaveRecord', array(
                    'message' => 'The Evaluation could not be saved. (' .
                        pr($this->EduEvaluation->validationErrors, true) .  '). (ERR-104-02)',
                    'helpcode' => 'ERR-104-02'));
            }
        }
		$evaluation = $this->EduEvaluation->read(null, $id);
        $this->set('edu_evaluation', $evaluation);
        $applicable_for_preschool = true;
		$area_id = $evaluation['EduEvaluation']['edu_evaluation_area_id'];
		$area = $this->EduEvaluation->EduEvaluationArea->read(null, $area_id);
		$value_group = $area['EduEvaluationCategory']['evaluation_value_group'];
		$cat_id = $area['EduEvaluationArea']['edu_evaluation_category_id'];
		
        if ($parent_id) {
            $this->set('parent_id', $parent_id);
		}
        
		$evaluation_values = $this->EduEvaluation->EduEvaluationValue->find('all', array('conditions' => array(
                'EduEvaluationValue.evaluation_value_group' => $value_group
            )));

        $edu_evaluation_values = array();
        foreach ($evaluation_values as $evaluation_value) {
             $edu_evaluation_values[$evaluation_value['EduEvaluationValue']['id']] =
                $evaluation_value['EduEvaluationValue']['description'];
        }

        $eduClasses = $this->EduEvaluation->EduClass->find('list');
        $eduEvaluationAreas = $this->EduEvaluation->EduEvaluationArea->find('list', array(
            'conditions' => array('EduEvaluationArea.edu_evaluation_category_id' => $cat_id)));
        $this->set('edu_classes', $eduClasses);
        $this->set('edu_evaluation_areas', $eduEvaluationAreas);
        $this->set('edu_evaluation_values', $eduEvaluationValues);
    }

    public function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->cakeError('cannotViewRecord', array(
                'message' => 'Invalid id for Evaluation. (ERR-104-01)',
                'helpcode' => 'ERR-104-01'));
        }

        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduEvaluation->delete($i);
                }
                $this->Session->setFlash(__('Edu evaluation deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->cakeError('cannotDeleteRecord', array(
                    'message' => 'Evaluation cannot be deleted. (' .$e->getMessage() . '). (ERR-104-04)',
                    'helpcode' => 'ERR-104-04'));
            }
        } else {
            if ($this->EduEvaluation->delete($id)) {
                $this->Session->setFlash(__('Evaluation deleted', true), '');
                $this->render('/elements/success');
            } else {
				if(Configure::read('soft_deleted') == 'yes'){
                    Configure::write('soft_deleted', '');
                    $this->Session->setFlash(__('Evaluation successfully deleted', true), '');
                    $this->render('/elements/success');
                } else {
                    $this->cakeError('cannotDeleteRecord', array(
                        'message' => 'Evaluation cannot be deleted. (ERR-104-05)',
                        'helpcode' => 'ERR-104-05'));
                }
            }
        }
    }
}
