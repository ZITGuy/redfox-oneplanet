<?php

/**
 * GroupsController
 *
 * @package nma
 * @author Behailu
 * @copyright 2011
 * @version $Id$
 * @access public
 */
class GroupsController extends AppController {

	public $name = 'Groups';
	
	public function index()
	{
		// empty
	}
	
	public function search()
	{
		// empty
	}
	
	public function list_data()
	{
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        //$conditions['Group.name <>'] = 'Super Administrator';
        
		$groups = $this->Group->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start));
		$this->set('groups', $groups);
		$this->set('results', $this->Group->find('count', array('conditions' => $conditions)));
	}
	
	public function view($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid group', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Group->recursive = 2;
		$this->set('group', $this->Group->read(null, $id));
	}

	public function add($id = null)
	{
		if (!empty($this->data)) {
			$tasks = explode(",", $this->data['Group']['Task']);
            
			$this->data['Task'] = array('Task' => $tasks);
			
			$this->Group->create();
			$this->autoRender = false;
			if ($this->Group->save($this->data)) {
				$this->Session->setFlash(__('The group has been saved', true));
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
				$this->render('/elements/failure');
			}
		}
		$tasks = $this->Group->Task->find('list');
		$this->set(compact('tasks'));
	}

	public function edit($id = null, $parentId = null)
	{
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid group', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			
			$tasks = explode(",", $this->data['Group']['Task']);
            
			$this->data['Task'] = array('Task' => $tasks);
			
			if ($this->Group->save($this->data)) {
				$this->Session->setFlash(__('The group has been saved', true));
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
				$this->render('/elements/failure');
			}
		}
		$this->set('parent_id', $parentId);
		$this->set('group', $this->Group->read(null, $id));

		$tasks = $this->Group->Task->find('list');
		$this->set(compact('tasks'));
	}

	public function delete($id = null)
	{
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for group', true));
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->Group->delete($i);
                }
				$this->Session->setFlash(__('Group deleted', true));
				$this->render('/elements/success');
            } catch (Exception $e){
				$this->Session->setFlash(__('Group was not deleted', true));
				$this->render('/elements/failure');
            }
        } else {
            if ($this->Group->delete($id)) {
				$this->Session->setFlash(__('Group deleted', true));
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Group was not deleted', true));
				$this->render('/elements/failure');
			}
        }
	}
}
