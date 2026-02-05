<?php
class EduAssignmentsController extends EduAppController {

	var $name = 'EduAssignments';
	
    function index() {
		$edu_teachers = $this->EduAssignment->EduTeacher->find('all');
		$this->set(compact('edu_teachers'));
    }
	
    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
    }
	
    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_teacher_id = (isset($_REQUEST['edu_teacher_id'])) ? $_REQUEST['edu_teacher_id'] : -1;
        if($id)
            $edu_teacher_id = ($id) ? $id : -1;
		
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_teacher_id != -1) {
            $conditions['EduAssignment.edu_teacher_id'] = $edu_teacher_id;
        }
		
        $this->set('edu_assignments', $this->EduAssignment->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduAssignment->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu assignment', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduAssignment->recursive = 2;
        $this->set('eduAssignment', $this->EduAssignment->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->EduAssignment->create();
            $this->autoRender = false;
            if ($this->EduAssignment->save($this->data)) {
                $this->Session->setFlash(__('The edu assignment has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu assignment could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if($id)
            $this->set('parent_id', $id);
		$edu_teachers = $this->EduAssignment->EduTeacher->find('list');
		$edu_courses = $this->EduAssignment->EduCourse->find('list');
		$edu_sections = $this->EduAssignment->EduSection->find('list');
		$this->set(compact('edu_teachers', 'edu_courses', 'edu_sections'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu assignment', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduAssignment->save($this->data)) {
                $this->Session->setFlash(__('The edu assignment has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu assignment could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_assignment', $this->EduAssignment->read(null, $id));
		
        if($parent_id) {
            $this->set('parent_id', $parent_id);
        }
			
		$edu_teachers = $this->EduAssignment->EduTeacher->find('list');
		$edu_courses = $this->EduAssignment->EduCourse->find('list');
		$edu_sections = $this->EduAssignment->EduSection->find('list');
		$this->set(compact('edu_teachers', 'edu_courses', 'edu_sections'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for edu assignment', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EduAssignment->delete($i);
                }
                $this->Session->setFlash(__('Edu assignment deleted', true), '');
                $this->render('/elements/success');
            }
            catch (Exception $e){
                $this->Session->setFlash(__('Edu assignment was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduAssignment->delete($id)) {
                $this->Session->setFlash(__('Edu assignment deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Edu assignment was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }
}
?>