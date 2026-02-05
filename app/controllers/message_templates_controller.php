<?php
class MessageTemplatesController extends AppController {

	var $name = 'MessageTemplates';
	
	function index() {
	}
	

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		
		$this->set('message_templates', $this->MessageTemplate->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->MessageTemplate->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid message template', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->MessageTemplate->recursive = 2;
		$this->set('message_template', $this->MessageTemplate->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->MessageTemplate->create();
			$this->autoRender = false;
			if ($this->MessageTemplate->save($this->data)) {
				$this->Session->setFlash(__('The message template has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The message template could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid message template', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->MessageTemplate->save($this->data)) {
				$this->Session->setFlash(__('The message template has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The message template could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$message_template = $this->MessageTemplate->read(null, $id);
		$this->set('message_template', $message_template);
	
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for message template', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->MessageTemplate->delete($i);
                }
				$this->Session->setFlash(__('Message template deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Message template was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->MessageTemplate->delete($id)) {
				$this->Session->setFlash(__('Message template deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Message template was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>