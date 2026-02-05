<?php
class EduDepartmentsController extends EduAppController {

	var $name = 'EduDepartments';
	
	function index() {
	}
	

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		
		$this->set('departments', $this->EduDepartment->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->EduDepartment->find('count', array('conditions' => $conditions)));
	}

	function list_data_department_subject($id = null) { // $id is teacher_id
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_department_id = (isset($_REQUEST['edu_department_id'])) ? $_REQUEST['edu_department_id'] : -1;
        if ($id) {
            $edu_department_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';
		
		$subjects = array();
        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_department_id != -1) {
            $this->loadModel('Edu.EduSubjectsDepartment');
			$this->EduSubjectsDepartment->recursive = 2;
			$this->EduSubjectsDepartment->bindModel(
				array('belongsTo' => array('EduSubject'))
			);
			$this->EduSubjectsDepartment->bindModel(
				array('belongsTo' => array('EduDepartment'))
			);
            $department_subjects = $this->EduSubjectsDepartment->find('all', 
                array('conditions' => array('edu_department_id' => $edu_department_id)));
            
			foreach ($department_subjects as $department_subject) {
                $subjects[] = $department_subject['EduSubject'];
            }
        }
        $this->set('subjects', $subjects);
        $this->set('results', count($subjects));
    }
	
	function list_data_department_class($id = null) { // $id is department_id
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_department_id = (isset($_REQUEST['edu_department_id'])) ? $_REQUEST['edu_department_id'] : -1;
        if ($id) {
            $edu_department_id = ($id) ? $id : -1;
        }
        
		$classes = array();
        if ($edu_department_id != -1) {
            $this->loadModel('Edu.EduClassesDepartment');
			$this->EduClassesDepartment->recursive = 2;
			$this->EduClassesDepartment->bindModel(
				array('belongsTo' => array('EduClass'))
			);
			$this->EduClassesDepartment->bindModel(
				array('belongsTo' => array('EduDepartment'))
			);
            $department_classes = $this->EduClassesDepartment->find('all', 
                    array('conditions' => array('edu_department_id' => $edu_department_id)));
            
			foreach ($department_classes as $department_class) {
                $classes[] = $department_class['EduClass'];
            }
        }
        $this->set('classes', $classes);
        $this->set('results', count($classes));
    }

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Department', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EduDepartment->recursive = 2;
		$this->set('department', $this->EduDepartment->read(null, $id));
	}

	function associate($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid department', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->layout = 'ajax';
            $this->autoRender = false;
            
			// 3 Associate Department (including class and subjects)
			$department['EduDepartment'] = array(
				'id' => $this->data['EduDepartment']['id'],
			);
			
			$classes = $this->data['EduClass'];

			$this->data['EduClass'] = array('EduClass' => array());
			foreach ($classes as $key => $value) {
				if($key != 'None')
					$this->data['EduClass']['EduClass'][] = $key;
			}
            
            $subjects = $this->data['EduSubject'];

			$this->data['EduSubject'] = array('EduSubject' => array());
			foreach ($subjects as $key => $value) {
				if($key != 'None')
					$this->data['EduSubject']['EduSubject'][] = $key;
			}
			
			// Saving the favorite subjects and classes of the department along side.
			$department_data = array(
				'EduDepartment' => $department['EduDepartment'], 
				'EduClass' => $this->data['EduClass'], 
				'EduSubject' => $this->data['EduSubject']);
			
			//$this->EduDepartment->create();
			if ($this->EduDepartment->save($department_data)) {
				$this->Session->setFlash(__('The department has been successfully associated', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The department could not be associated.', true), '');
				$this->render('/elements/failure');
			}
        }
        $this->set('edu_department', $this->EduDepartment->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

		$this->set('classes', $this->EduDepartment->EduClass->find('list', array('conditions' => array(), 'order' => 'cvalue ASC')));
        $this->set('subjects', $this->EduDepartment->EduSubject->find('list', array('conditions' => array(), 'order' => 'name ASC')));
    }

	function add($id = null) {
		if (!empty($this->data)) {
			$this->EduDepartment->create();
			$this->autoRender = false;
			if ($this->EduDepartment->save($this->data)) {
				$this->Session->setFlash(__('The Department has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The Department could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}

		$users = $this->EduDepartment->User->find('list');
		$this->set('users', $users);
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Department', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EduDepartment->save($this->data)) {
				$this->Session->setFlash(__('The Department has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The Department could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('department', $this->EduDepartment->read(null, $id));
		$users = $this->EduDepartment->User->find('list');
		$this->set('users', $users);
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Department', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EduDepartment->delete($i);
                }
				$this->Session->setFlash(__('Department deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Department was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EduDepartment->delete($id)) {
				$this->Session->setFlash(__('Department deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Department was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>