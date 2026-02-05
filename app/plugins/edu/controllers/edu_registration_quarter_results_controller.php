<?php
class EduRegistrationQuarterResultsController extends AppController {

	var $name = 'EduRegistrationQuarterResults';
	
	function index() {
		$edu_registration_quarters = $this->EduRegistrationQuarterResult->EduRegistrationQuarter->find('all');
		$this->set(compact('edu_registration_quarters'));
	}
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}

	function search() {
	}
	
	function comment_for_course_o() {
		$this->loadModel('EduClass');
		$this->loadModel('EduSection');
		$conditions = array('EduClass.grading_type <>' => 'G');
        $edu_classes = $this->EduClass->find('list', array('conditions' => $conditions));
        $edu_sections = $this->EduSection->find('list');
        $this->set(compact('edu_classes', 'edu_sections'));
        $this->set('parent_id', '');
	}
	
	function set_comment($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid student selected', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EduRegistrationQuarterResult->save($this->data)) {
				$this->Session->setFlash(__('The comment for student course has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The comment for student course could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('edu_registration_quarter_result', $this->EduRegistrationQuarterResult->read(null, $id));
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
		$eduregistrationquarter_id = (isset($_REQUEST['eduregistrationquarter_id'])) ? $_REQUEST['eduregistrationquarter_id'] : -1;
		if($id)
			$eduregistrationquarter_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($eduregistrationquarter_id != -1) {
            $conditions['EduRegistrationQuarterResult.eduregistrationquarter_id'] = $eduregistrationquarter_id;
        }
		
		$this->set('eduRegistrationQuarterResults', $this->EduRegistrationQuarterResult->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->EduRegistrationQuarterResult->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid edu registration quarter result', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EduRegistrationQuarterResult->recursive = 2;
		$this->set('eduRegistrationQuarterResult', $this->EduRegistrationQuarterResult->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->EduRegistrationQuarterResult->create();
			$this->autoRender = false;
			if ($this->EduRegistrationQuarterResult->save($this->data)) {
				$this->Session->setFlash(__('The edu registration quarter result has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The edu registration quarter result could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$edu_registration_quarters = $this->EduRegistrationQuarterResult->EduRegistrationQuarter->find('list');
		$edu_courses = $this->EduRegistrationQuarterResult->EduCourse->find('list');
		$this->set(compact('edu_registration_quarters', 'edu_courses'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid edu registration quarter result', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EduRegistrationQuarterResult->save($this->data)) {
				$this->Session->setFlash(__('The edu registration quarter result has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The edu registration quarter result could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('edu__registration__quarter__result', $this->EduRegistrationQuarterResult->read(null, $id));
		
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
			
		$edu_registration_quarters = $this->EduRegistrationQuarterResult->EduRegistrationQuarter->find('list');
		$edu_courses = $this->EduRegistrationQuarterResult->EduCourse->find('list');
		$this->set(compact('edu_registration_quarters', 'edu_courses'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for edu registration quarter result', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EduRegistrationQuarterResult->delete($i);
                }
				$this->Session->setFlash(__('Edu registration quarter result deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Edu registration quarter result was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EduRegistrationQuarterResult->delete($id)) {
				$this->Session->setFlash(__('Edu registration quarter result deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Edu registration quarter result was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>