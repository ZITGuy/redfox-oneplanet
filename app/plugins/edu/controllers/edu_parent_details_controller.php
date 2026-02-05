<?php
class EduParentDetailsController extends AppController {

	var $name = 'EduParentDetails';
	
	function index() {
		$edu_parents = $this->EduParentDetail->EduParent->find('all');
		$this->set(compact('edu_parents'));
	}
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}
	
	function index_2_student($id = null, $stud_id = null) {
		$this->loadModel('EduStudent');
		$edu_student = $this->EduStudent->read(null, $stud_id);
		if(!empty($edu_student)) {
			$id = $edu_student['EduStudent']['edu_parent_id'];
		}
		$this->set('parent_id', $id);
		$this->set('student_id', $stud_id);
	}

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
		$edu_parent_id = (isset($_REQUEST['edu_parent_id'])) ? $_REQUEST['edu_parent_id'] : -1;
		if($id)
			$edu_parent_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($edu_parent_id != -1) {
            $conditions['EduParentDetail.edu_parent_id'] = $edu_parent_id;
        }
		
		$this->set('edu_parent_details', $this->EduParentDetail->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->EduParentDetail->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid edu parent detail', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EduParentDetail->recursive = 2;
		$this->set('edu_parent_detail', $this->EduParentDetail->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->EduParentDetail->create();
			$this->autoRender = false;
			if ($this->EduParentDetail->save($this->data)) {
				$this->Session->setFlash(__('The edu parent detail has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The edu parent detail could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$edu_parents = $this->EduParentDetail->EduParent->find('list');
		$this->set(compact('edu_parents'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid edu parent detail', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EduParentDetail->save($this->data)) {
				$this->Session->setFlash(__('The edu parent detail has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The edu parent detail could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$edu_parent_detail = $this->EduParentDetail->read(null, $id);
		$this->set('edu_parent_detail', $edu_parent_detail);
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
	
		$edu_parents = $this->EduParentDetail->EduParent->find('list');
		$this->set(compact('edu_parents'));
	}

	function upload_photo($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Parent', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->layout = 'ajax';
            $this->autoRender = false;
			
			$id = $this->data['EduParentDetail']['id'];
			$parent_detail = $this->EduParentDetail->read(null, $id);
			
			// upload image
            $file = $this->data['EduParentDetail']['photo_file'];
            $file_name = basename($file['name']);
            $fext = substr($file_name, strrpos($file_name, "."));
            $fname = time(); 
            $file_name = $id . '_' . $fname . $fext;
			
            if (!file_exists(IMAGES . 'parents')) {
                mkdir(IMAGES . 'parents', 0777);
            }
			unset($this->data['EduParentDetail']['name']);
			
            if (!move_uploaded_file($file['tmp_name'], IMAGES . 'parents' . DS . $file_name)) {
                unset($this->data['EduParentDetail']['photo_file']);
            } else {
                $this->data['EduParentDetail']['photo_file'] = $file_name;
            }
			
            if ($this->EduParentDetail->save($this->data)) {
				$this->Session->setFlash(__('The Parent Photo has been updated', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The Parent Photo could not be updated. Please, try again.', true) . 'ERROR: ' . pr($this->EduParentDetail->validationErrors, true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_parent_detail', $this->EduParentDetail->read(null, $id));
    }
	
	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for edu parent detail', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EduParentDetail->delete($i);
                }
				$this->Session->setFlash(__('Edu parent detail deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Edu parent detail was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EduParentDetail->delete($id)) {
				$this->Session->setFlash(__('Edu parent detail deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Edu parent detail was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>