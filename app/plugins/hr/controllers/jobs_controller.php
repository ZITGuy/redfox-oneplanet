<?php
class JobsController extends HrAppController {

	var $name = 'Jobs';
	
	function index() {
		$grades = $this->Job->Grade->find('all');
		$this->set(compact('grades'));
	}
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}
        function index3() {
		$grades = $this->Job->Grade->find('all');
		$this->set(compact('grades'));
	}
	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
		$grade_id = (isset($_REQUEST['grade_id'])) ? $_REQUEST['grade_id'] : -1;
		if($id)
			$grade_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($grade_id != -1) {
            $conditions['Job.grade_id'] = $grade_id;
        }
		
		$this->set('jobs', $this->Job->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->Job->find('count', array('conditions' => $conditions)));
	}
        
       function list_data3($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
		$grade_id = (isset($_REQUEST['grade_id'])) ? $_REQUEST['grade_id'] : -1;
		if($id)
			$grade_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($grade_id != -1) {
            $conditions['Job.grade_id'] = $grade_id;
        }
		$conditions['Job.end_date >='] = date('Y-m-d');
		$this->set('jobs', $this->Job->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->Job->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid job', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Job->recursive = 2;
		$this->set('job', $this->Job->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
                        // Strip out carriage returns
                        $this->data['Job']['description'] = ereg_replace("\r",'',$this->data['Job']['description']);
                        // Handle paragraphs
                        $this->data['Job']['description'] = ereg_replace("\n\n",'</p><p>',$this->data['Job']['description']);
                        // Handle line breaks
                        $this->data['Job']['description'] = ereg_replace("\n",'<br />',$this->data['Job']['description']);
			$this->Job->create();
			$this->autoRender = false;
			if ($this->Job->save($this->data)) {
				$this->Session->setFlash(__('The Internal Vacancy has been Posted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The job could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$grades = $this->Job->Grade->find('list');
		$locations = $this->Job->Location->find('list');
		$this->set(compact('grades', 'locations'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid job', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
                        // Strip out carriage returns
                        $this->data['Job']['description'] = ereg_replace("\r",'',$this->data['Job']['description']);
                        // Handle paragraphs
                        $this->data['Job']['description'] = ereg_replace("\n\n",'</p><p>',$this->data['Job']['description']);
                        // Handle line breaks
                        $this->data['Job']['description'] = ereg_replace("\n",'<br />',$this->data['Job']['description']);
			$this->autoRender = false;
			if ($this->Job->save($this->data)) {
				$this->Session->setFlash(__('Changes have been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The job could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('job', $this->Job->read(null, $id));
		
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
			
		$grades = $this->Job->Grade->find('list');
		$locations = $this->Job->Location->find('list');
		$this->set(compact('grades', 'locations'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for job', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->Job->delete($i);
                }
				$this->Session->setFlash(__('Job deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Job was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->Job->delete($id)) {
				$this->Session->setFlash(__('Job deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Job was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>