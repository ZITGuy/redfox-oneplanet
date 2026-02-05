<?php
class RelatedHelpItemsController extends AppController {

	var $name = 'RelatedHelpItems';
	
	function index() {
		$help_items = $this->RelatedHelpItem->HelpItem->find('all');
		$this->set(compact('help_items'));
	}
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
		$help_item_id = (isset($_REQUEST['help_item_id'])) ? $_REQUEST['help_item_id'] : -1;
		if($id)
			$help_item_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($help_item_id != -1) {
            $conditions['RelatedHelpItem.help_item_id'] = $help_item_id;
        }
		
		$this->set('related_help_items', $this->RelatedHelpItem->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->RelatedHelpItem->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid related help item', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->RelatedHelpItem->recursive = 2;
		$this->set('related_help_item', $this->RelatedHelpItem->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->RelatedHelpItem->create();
			$this->autoRender = false;
			if ($this->RelatedHelpItem->save($this->data)) {
				$this->Session->setFlash(__('The related help item has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The related help item could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$help_items = $this->RelatedHelpItem->HelpItem->find('list');
		$related_help_items = $this->RelatedHelpItem->RelatedHelpItem->find('list');
		$this->set(compact('help_items', 'related_help_items'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid related help item', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->RelatedHelpItem->save($this->data)) {
				$this->Session->setFlash(__('The related help item has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The related help item could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$related_help_item = $this->RelatedHelpItem->read(null, $id);
		$this->set('related_help_item', $related_help_item);
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
	
		$help_items = $this->RelatedHelpItem->HelpItem->find('list');
		$related_help_items = $this->RelatedHelpItem->RelatedHelpItem->find('list');
		$this->set(compact('help_items', 'related_help_items'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for related help item', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->RelatedHelpItem->delete($i);
                }
				$this->Session->setFlash(__('Related help item deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Related help item was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->RelatedHelpItem->delete($id)) {
				$this->Session->setFlash(__('Related help item deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Related help item was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>