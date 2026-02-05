<?php
class EduTeachersTrainingsController extends EduAppController {

	var $name = 'EduTeachersTrainings';
	
	function index() {
		$edu_teachers = $this->EduTeachersTraining->EduTeacher->find('all');
		$this->set(compact('edu_teachers'));
	}
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
		$edu_teacher_id = (isset($_REQUEST['edu_teacher_id'])) ? $_REQUEST['edu_teacher_id'] : -1;
		if($id)
			$edu_teacher_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($edu_teacher_id != -1) {
            $conditions['EduTeachersTraining.edu_teacher_id'] = $edu_teacher_id;
        }
		
		$this->loadModel('EduTeacher');
		$this->EduTeacher->recursive = 3;
		
		$this->set('edu_teacher', $this->EduTeacher->read(null, $id));
		$this->set('edu_teachers_trainings', $this->EduTeachersTraining->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->EduTeachersTraining->find('count', array('conditions' => $conditions)));
	}
	
	function list_data_v($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
		$edu_training_id = (isset($_REQUEST['edu_training_id'])) ? $_REQUEST['edu_training_id'] : -1;
		if($id)
			$edu_training_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($edu_training_id != -1) {
            $conditions['EduTeachersTraining.edu_training_id'] = $edu_training_id;
        }
		$teacher_trainings = $this->EduTeachersTraining->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start));
		
		App::import('Model', 'Edu.EduTeacher');
        $objTeacher = new EduTeacher;
		foreach($teacher_trainings as &$teacher_training) {
			$teacher = $objTeacher->getTeacher($teacher_training['EduTeachersTraining']['edu_teacher_id']);
			$teacher_training['EduTeacher'] = $teacher;
		}
		
		$this->set('edu_teachers_trainings', $teacher_trainings);
		$this->set('results', $this->EduTeachersTraining->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid edu teachers training', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EduTeachersTraining->recursive = 2;
		$this->set('edu_teachers_training', $this->EduTeachersTraining->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->EduTeachersTraining->create();
			$this->autoRender = false;
			if ($this->EduTeachersTraining->save($this->data)) {
				$this->Session->setFlash(__('The edu teachers training has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The edu teachers training could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$edu_teachers = $this->EduTeachersTraining->EduTeacher->find('list');
		$edu_trainings = $this->EduTeachersTraining->EduTraining->find('list');
		$this->set(compact('edu_teachers', 'edu_trainings'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid edu teachers training', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EduTeachersTraining->save($this->data)) {
				$this->Session->setFlash(__('The edu teachers training has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The edu teachers training could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('edu_teachers_training', $this->EduTeachersTraining->read(null, $id));
		
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
			
		$edu_teachers = $this->EduTeachersTraining->EduTeacher->find('list');
		$edu_trainings = $this->EduTeachersTraining->EduTraining->find('list');
		$this->set(compact('edu_teachers', 'edu_trainings'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for edu teachers training', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EduTeachersTraining->delete($i);
                }
				$this->Session->setFlash(__('Edu teachers training deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Edu teachers training was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EduTeachersTraining->delete($id)) {
				$this->Session->setFlash(__('Edu teachers training deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Edu teachers training was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>