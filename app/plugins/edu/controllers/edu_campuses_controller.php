<?php

class EduCampusesController extends EduAppController {

    var $name = 'EduCampuses';

    function index_v() {
        
    }
    
    function index_m() {
        
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start      = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit      = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		
		$campuses = $this->EduCampus->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start));
		$this->loadModel('EduRegistration');
		$this->loadModel('User');
		
		foreach($campuses as &$campus) {
			$campus['EduCampus']['number_of_students'] = $this->EduRegistration->find('count', array('conditions' => array('EduRegistration.edu_campus_id' => $campus['EduCampus']['id'])));
			$campus['EduCampus']['number_of_users'] = $this->User->find('count', array('conditions' => array('User.edu_campus_id' => $campus['EduCampus']['id'])));
		}
		
        $this->set('edu_campuses', $campuses);
        $this->set('results', $this->EduCampus->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid campus', true));
            $this->render('/elements/failure');
        }
        $this->loadModel('EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $current_section_ids = array();
        if($ay) {
            foreach ($ay['EduSection'] as $section) {
                $current_section_ids[] = $section['id'];
            }
        }
        $this->set('current_section_ids', $current_section_ids);
        
        $this->EduCampus->recursive = 2;
        $this->set('edu_campus', $this->EduCampus->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->EduCampus->create();
            $this->autoRender = false;
            if ($this->EduCampus->save($this->data)) {
                $this->Session->setFlash(__('The campus has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The campus could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid campus', true), '');
            $this->render('/elements/failure');
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduCampus->save($this->data)) {
                $this->Session->setFlash(__('The campus has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The campus could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_campus', $this->EduCampus->read(null, $id));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for campus', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduCampus->delete($i);
                }
                $this->Session->setFlash(__('Campus deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Campus was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduCampus->delete($id)) {
                $this->Session->setFlash(__('Campus deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Campus was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}

?>