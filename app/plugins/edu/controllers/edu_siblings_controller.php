<?php

class EduSiblingsController extends EduAppController {

    var $name = 'EduSiblings';

    function index() {
        
    }

    function list_data() {
		$siblings = array();
		if($this->Session->check('siblings')) {
			$siblings = $this->Session->read('siblings');
		} else {
			$this->Session->write('siblings', $siblings);
		}
        $this->set('siblings', $siblings);
        $this->set('results', count($siblings));
    }

    function add() {
        if (!empty($this->data)) {
			$this->autoRender = false;
            $siblings = $this->Session->read('siblings');
			
			$sibling = array();
			$sibling = $this->data['EduSibling'];
			
			$siblings[time()] = $sibling;
			
			$this->Session->write('siblings', $siblings);
			
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
		$siblings = $this->Session->read('siblings');
		$found = false;
		foreach($siblings as $k => $v) {
			if($k == $id) {
				unset($siblings[$k]);
				$this->Session->write('siblings', $siblings);
				$found = true;
				break;
			}
		}
		
		return $found;
	}

}
