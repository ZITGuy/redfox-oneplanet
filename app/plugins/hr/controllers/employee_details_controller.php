<?php
class EmployeeDetailsController extends HrAppController {

	var $name = 'EmployeeDetails';
	
	function index() {
		$employees = $this->EmployeeDetail->Employee->find('all');
		$this->set(compact('employees'));
	}
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
		$employee_id = (isset($_REQUEST['employee_id'])) ? $_REQUEST['employee_id'] : -1;
		if($id)
			$employee_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($employee_id != -1) {
            $conditions['EmployeeDetail.employee_id'] = $employee_id;
        }
		
		$this->set('employee_details', $this->EmployeeDetail->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->EmployeeDetail->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid employee detail', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EmployeeDetail->recursive = 2;
		$this->set('employeeDetail', $this->EmployeeDetail->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->EmployeeDetail->create();
			$this->autoRender = false;
			if ($this->EmployeeDetail->save($this->data)) {
                            $con['Scale.grade_id'] = $this->data['EmployeeDetail']['grade_id'];
                            $con['Scale.step_id'] = $this->data['EmployeeDetail']['step_id'];
                            $this->loadModel('Scale');
                            $salary = $this->Scale->find('all', array('conditions' => $con));
                            $this->data['EmployeeDetail']['salary'] = $salary[0]['Scale']['salary'];
                            $this->EmployeeDetail->save($this->data);
				$this->Session->setFlash(__('The employee detail has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The employee detail could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$employees = $this->EmployeeDetail->Employee->find('list');
		$grades = $this->EmployeeDetail->Grade->find('list');
		$steps = $this->EmployeeDetail->Step->find('list');
		$positions = $this->EmployeeDetail->Position->find('list',array('order' => 'name'));
                $branches = $this->EmployeeDetail->Branch->find('list',array('order' => 'name'));
		$this->set(compact('employees', 'grades', 'steps', 'positions','branches'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid employee detail', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EmployeeDetail->save($this->data)) {
                            $con['Scale.grade_id'] = $this->data['EmployeeDetail']['grade_id'];
                            $con['Scale.step_id'] = $this->data['EmployeeDetail']['step_id'];
                            $this->loadModel('Scale');
                            $salary = $this->Scale->find('all', array('conditions' => $con));
                            $this->data['EmployeeDetail']['salary'] = $salary[0]['Scale']['salary'];
                            $this->EmployeeDetail->save($this->data);
				$this->Session->setFlash(__('The employee detail has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The employee detail could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('employee__detail', $this->EmployeeDetail->read(null, $id));
		
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
			
		$employees = $this->EmployeeDetail->Employee->find('list');
		$grades = $this->EmployeeDetail->Grade->find('list');
		$steps = $this->EmployeeDetail->Step->find('list');
		$positions = $this->EmployeeDetail->Position->find('list',array('order' => 'name'));
                $branches = $this->EmployeeDetail->Branch->find('list',array('order' => 'name'));
		$this->set(compact('employees', 'grades', 'steps', 'positions','branches'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for employee detail', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EmployeeDetail->delete($i);
                }
				$this->Session->setFlash(__('Employee detail deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Employee detail was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EmployeeDetail->delete($id)) {
				$this->Session->setFlash(__('Employee detail deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Employee detail was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>