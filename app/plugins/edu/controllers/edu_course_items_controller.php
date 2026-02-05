<?php
class EduCourseItemsController extends EduAppController {

	var $name = 'EduCourseItems';
	
	function index() {
		$edu_courses = $this->EduCourseItem->EduCourse->find('all');
		$this->set(compact('edu_courses'));
	}
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
		$edu_course_id = (isset($_REQUEST['edu_course_id'])) ? $_REQUEST['edu_course_id'] : -1;
		if($id)
			$edu_course_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($edu_course_id != -1) {
            $conditions['EduCourseItem.edu_course_id'] = $edu_course_id;
        }
		
		$this->set('edu_course_items', $this->EduCourseItem->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->EduCourseItem->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid course item', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EduCourseItem->recursive = 2;
		$this->set('edu_course_item', $this->EduCourseItem->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->EduCourseItem->create();
			$this->autoRender = false;
			if ($this->EduCourseItem->save($this->data)) {
				$this->Session->setFlash(__('The course item has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The edu course item could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$edu_courses = $this->EduCourseItem->EduCourse->find('list');
		$this->set(compact('edu_courses'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid edu course item', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EduCourseItem->save($this->data)) {
				$this->Session->setFlash(__('The edu course item has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The edu course item could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('edu_course_item', $this->EduCourseItem->read(null, $id));
		
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
			
		$edu_courses = $this->EduCourseItem->EduCourse->find('list');
		$this->set(compact('edu_courses'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for edu course item', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EduCourseItem->delete($i);
                }
				$this->Session->setFlash(__('Edu course item deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Edu course item was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EduCourseItem->delete($id)) {
				$this->Session->setFlash(__('Edu course item deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Edu course item was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>