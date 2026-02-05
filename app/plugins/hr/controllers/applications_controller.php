<?php
class ApplicationsController extends HrAppController {

	var $name = 'Applications';
	
	function index() {
		$employees = $this->Application->Employee->find('all');
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
		$job_id = (isset($_REQUEST['job_id'])) ? $_REQUEST['job_id'] : -1;
		if($id)
			$job_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($job_id != -1) {
            $conditions['Application.job_id'] = $job_id;
        }
		$this->Application->recursive = 3;
		$this->set('applications', $this->Application->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->Application->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid application', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Application->recursive = 3;
		$this->set('application', $this->Application->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
                    if($this->Application->find('count',array('conditions'=>array('employee_id'=>$this->data['Application']['employee_id'],'job_id'=>$this->data['Application']['job_id'])))>0){
                        $this->Session->setFlash(__('you already have applied for this post!', true), '');
				$this->render('/elements/failure');
                    }else{
			$this->Application->create();
			$this->autoRender = false;
			if ($this->Application->save($this->data)) {
				$this->Session->setFlash(__('The application has been send', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The application could not be sent. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
                    }
		}
		if($id)
			$this->set('parent_id', $id);
		$employees = $this->Application->Employee->find('list');
		$jobs = $this->Application->Job->find('list');
		$this->set(compact('employees', 'jobs'));
                $this->set('employee', $this->Application->Employee->findByuser_id($this->Session->read('Auth.User.id')));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid application', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->Application->save($this->data)) {
				$this->Session->setFlash(__('The application has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The application could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('application', $this->Application->read(null, $id));
		
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
			
		$employees = $this->Application->Employee->find('list');
		$jobs = $this->Application->Job->find('list');
		$this->set(compact('employees', 'jobs'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for application', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->Application->delete($i);
                }
				$this->Session->setFlash(__('Application deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Application was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->Application->delete($id)) {
				$this->Session->setFlash(__('Application deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Application was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>