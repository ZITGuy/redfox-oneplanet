<?php

class EduEmergencyContactsController extends EduAppController {

    var $name = 'EduEmergencyContacts';

    function index() {
        
    }

    function list_data() {
		$emergency_contacts = array();
		if($this->Session->check('emergency_contacts')) {
			$emergency_contacts = $this->Session->read('emergency_contacts');
		} else {
			$this->Session->write('emergency_contacts', $emergency_contacts);
		}
        $this->set('emergency_contacts', $emergency_contacts);
        $this->set('results', count($emergency_contacts));
    }

    function add() {
        if (!empty($this->data)) {
			$this->autoRender = false;
            $emergency_contacts = $this->Session->read('emergency_contacts');
			
			$emergency_contact = array();
			$emergency_contact = $this->data['EduEmergencyContact'];
			
			$emergency_contacts[time()] = $emergency_contact;
			
			$this->Session->write('emergency_contacts', $emergency_contacts);
			
			$this->Session->setFlash(__('Data saved successfully', true));
			$this->render('/elements/success');
        }
    }

    function delete($id = null) {
        $this->autoRender = false;
		
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->deleteRecord($i);
                }
                $this->Session->setFlash(__('Data deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Data was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->deleteRecord($id)) {
                $this->Session->setFlash(__('Data deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Data was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }
	
	function deleteRecord($id) {
		$emergency_contacts = $this->Session->read('emergency_contacts');
		$found = false;
		foreach($emergency_contacts as $k => $v) {
			if($k == $id) {
				unset($emergency_contacts[$k]);
				$this->Session->write('emergency_contacts', $emergency_contacts);
				$found = true;
				break;
			}
		}
		
		return $found;
	}

}
