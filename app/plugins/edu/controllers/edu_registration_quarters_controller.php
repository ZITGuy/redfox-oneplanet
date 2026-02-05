<?php
class EduRegistrationQuartersController extends AppController {

	var $name = 'EduRegistrationQuarters';
	
	function index() {
		$edu_registrations = $this->EduRegistrationQuarter->EduRegistration->find('all');
		$this->set(compact('edu_registrations'));
	}
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
		$eduregistration_id = (isset($_REQUEST['eduregistration_id'])) ? $_REQUEST['eduregistration_id'] : -1;
		if($id)
			$eduregistration_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($eduregistration_id != -1) {
            $conditions['EduRegistrationQuarter.eduregistration_id'] = $eduregistration_id;
        }
		
		$this->set('eduRegistrationQuarters', $this->EduRegistrationQuarter->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->EduRegistrationQuarter->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid edu registration quarter', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EduRegistrationQuarter->recursive = 2;
		$this->set('eduRegistrationQuarter', $this->EduRegistrationQuarter->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->EduRegistrationQuarter->create();
			$this->autoRender = false;
			if ($this->EduRegistrationQuarter->save($this->data)) {
				$this->Session->setFlash(__('The edu registration quarter has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The edu registration quarter could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$edu_registrations = $this->EduRegistrationQuarter->EduRegistration->find('list');
		$edu_quarters = $this->EduRegistrationQuarter->EduQuarter->find('list');
		$this->set(compact('edu_registrations', 'edu_quarters'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid edu registration quarter', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EduRegistrationQuarter->save($this->data)) {
				$this->Session->setFlash(__('The edu registration quarter has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The edu registration quarter could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('edu__registration__quarter', $this->EduRegistrationQuarter->read(null, $id));
		
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
			
		$edu_registrations = $this->EduRegistrationQuarter->EduRegistration->find('list');
		$edu_quarters = $this->EduRegistrationQuarter->EduQuarter->find('list');
		$this->set(compact('edu_registrations', 'edu_quarters'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for edu registration quarter', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EduRegistrationQuarter->delete($i);
                }
				$this->Session->setFlash(__('Edu registration quarter deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Edu registration quarter was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EduRegistrationQuarter->delete($id)) {
				$this->Session->setFlash(__('Edu registration quarter deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Edu registration quarter was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>