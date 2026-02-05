<?php
class EduCommunicationsController extends EduAppController {

	var $name = 'EduCommunications';
	
	function index() {
		$edu_students = $this->EduCommunication->EduStudent->find('all');
		$this->set(compact('edu_students'));
	}
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}

	function index_record_secretary_o() {
		$conditions = array('EduClass.grading_type <>' => 'G');
		$this->loadModel('Edu.EduClass');
		$this->loadModel('Edu.EduSection');
        $edu_classes = $this->EduClass->find('list', array('conditions' => $conditions));
        $edu_sections = $this->EduSection->find('list');
        $this->set(compact('edu_classes', 'edu_sections'));
        $this->set('parent_id', '');
	}
	
	
	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
		$edu_student_id = (isset($_REQUEST['edu_student_id'])) ? $_REQUEST['edu_student_id'] : -1;
		if($id)
			$edu_student_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($edu_student_id != -1) {
            $conditions['EduCommunication.edu_student_id'] = $edu_student_id;
        }
		
		$this->set('edu_communications', $this->EduCommunication->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->EduCommunication->find('count', array('conditions' => $conditions)));
	}

	function list_data_records($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
		$edu_section_id = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : -1;
		if($id)
			$edu_section_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($edu_section_id != -1) {
            $conditions['EduCommunication.edu_section_id'] = $edu_section_id;
        }
		
		$this->loadModel('Edu.EduRegistration');

        $registrations = $this->EduRegistration->find('all', array(
                'conditions' => array('edu_section_id' =>  $edu_section_id)
            ));
		
		$communications = $this->EduCommunication->find('all', array(
			'conditions' => $conditions, 'limit' => $limit, 'offset' => $start));
		
		$communication_records = array();
        foreach ($registrations as $reg) {
            $cr = $this->EduCommunication->find('first', array('conditions' => array(
                    'edu_student_id' => $reg['EduRegistration']['edu_student_id'],
                    'edu_section_id' => $edu_section_id,
					'post_date' => date('Y-m-d')
                )));
            if(empty($cr)){
                $cr = array('EduCommunication' => array(
                        'edu_student_id' => $reg['EduRegistration']['edu_student_id'],
                        'edu_section_id' => $edu_section_id,
                        'post_date' => date('Y-m-d'),
						'teacher_comment' => 'Today was a good day. Encourage your child.',
						'parent_comment' => 'Not Set',
						'user_id' => $this->Session->read('Auth.User.id')
                    ));
                $this->EduCommunication->create();
                $this->EduCommunication->save($cr);
                $cr['EduCommunication']['id'] = $this->EduCommunication->id;
            }
            $cr = $this->EduCommunication->read(null, $cr['EduCommunication']['id']);

            $communication_records[] = array('EduCommunication' => array(
                    'id' =>  $cr['EduCommunication']['id'],
                    'student' => $reg['EduRegistration']['name'],
                    'identity_number' => $reg['EduStudent']['identity_number'],
                    'comment' => $cr['EduCommunication']['teacher_comment']
                ));
        }
		
		$this->set('edu_communications', $communication_records);
		$this->set('results', count($communication_records));
	}
	
	function save_communication_records() {
		$this->autoRender = false;
        
        try{
            foreach ($this->data as $record) {
                $id = str_replace('"', '', $record['id']);
                $teacher_comment = str_replace('"', '', $record['comment']);
                
                $cr = $this->EduCommunication->read(null, $id);
				
                if(!empty($cr) && $cr != null){
                    $this->EduCommunication->set('teacher_comment', $teacher_comment);
                    $this->EduCommunication->save();
                }
            }
            $this->Session->setFlash(__('The PTC records are saved successfully', true), '');
            $this->render('/elements/success');
        } catch(Exception $ex){
            $this->cakeError('cannotSaveRecord', array(
                    'message' => 'The PTC Records are not saved successfully. Please, try again. (MSG: ' . $ex->getMessage() . '). (ERR-101-02)',
                    'helpcode' => 'ERR-101-02'));
        }
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid edu communication', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EduCommunication->recursive = 2;
		$this->set('edu_communication', $this->EduCommunication->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->EduCommunication->create();
			$this->autoRender = false;
			if ($this->EduCommunication->save($this->data)) {
				$this->Session->setFlash(__('The edu communication has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The edu communication could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$edu_students = $this->EduCommunication->EduStudent->find('list');
		$edu_sections = $this->EduCommunication->EduSection->find('list');
		$users = $this->EduCommunication->User->find('list');
		$this->set(compact('edu_students', 'edu_sections', 'users'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid edu communication', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EduCommunication->save($this->data)) {
				$this->Session->setFlash(__('The edu communication has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The edu communication could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$edu_communication = $this->EduCommunication->read(null, $id);
		$this->set('edu_communication', $edu_communication);
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
	
		$edu_students = $this->EduCommunication->EduStudent->find('list');
		$edu_sections = $this->EduCommunication->EduSection->find('list');
		$users = $this->EduCommunication->User->find('list');
		$this->set(compact('edu_students', 'edu_sections', 'users'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for edu communication', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EduCommunication->delete($i);
                }
				$this->Session->setFlash(__('Edu communication deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Edu communication was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EduCommunication->delete($id)) {
				$this->Session->setFlash(__('Edu communication deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Edu communication was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>