<?php

class TasksController extends AppController {

    var $name = 'Tasks';

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
        $tasks = $this->Task->find('all', array('order' => 'Task.lft ASC'));
        $treeData = array();
        if (!empty($tasks)) {
            $treeData = array($this->getTreeArray($tasks[0], $tasks));
        }
        $this->set('tasks', $treeData);
    }

    public function list_data2($groupId = null)
    {
        $tasks = $this->Task->find('all', array('order' => 'Task.lft ASC'));
        $treeData = array();
        $selectedTasks = array();
        if ($groupId) {
            $selTasks = $this->Task->Group->read(null, $groupId);
            $selTasks = $selTasks['Task'];
            foreach ($selTasks as $st) {
                $selectedTasks[] = $st['id'];
            }
        }

        if (!empty($tasks)) {
            $treeData = array($this->getTreeArray2($tasks[0], $tasks));
        }
        $this->set('tasks', $treeData);
        $this->set('selected_tasks', $selectedTasks);
    }

    public function list_data3($eventTypeId = null)
    {
        $tasks = $this->Task->find('all', array('order' => 'Task.lft ASC'));
        $treeData = array();
        $selectedTasks = array();
        if ($eventTypeId) {
            $selTasks = $this->Task->EduCalendarEventType->read(null, $eventTypeId);
            $selTasks = $selTasks['Task'];
            foreach ($selTasks as $st) {
                $selectedTasks[] = $st['id'];
            }
        }

        if (!empty($tasks)) {
            $treeData = array($this->getTreeArray2($tasks[0], $tasks));
        }
        $this->set('tasks', $treeData);
        $this->set('selected_tasks', $selectedTasks);
    }

    public function getTreeArray($node, $adata)
    {
        $mynode = array();
        $mynode = array(
            'id' => $node['Task']['id'],
            'name' => $node['Task']['name'],
            'controller' => $node['Task']['controller'],
            'action' => $node['Task']['action'],
            'iconcls' => $node['Task']['iconcls'],
            'list_order' => $node['Task']['list_order'],
            'built_in' => $node['Task']['built_in'],
            'children' => array()
        );
        $children = $this->getChildNodes($node['Task']['id'], $adata);
        $siblings = array();
        foreach ($children as $child) {
            $siblings[$child['Task']['list_order']] = $child;
        }
        ksort($siblings);
        foreach ($siblings as $child) {
            $mynode['children'][] = $this->getTreeArray($child, $adata);
        }
        return $mynode;
    }

    public function getTreeArray2($node, $adata) {
        $mynode = array();
        $mynode = array(
            'id' => $node['Task']['id'],
            'name' => $node['Task']['name'],
            'controller' => $node['Task']['controller'],
            'action' => $node['Task']['action'],
            'iconcls' => $node['Task']['iconcls'],
            'list_order' => $node['Task']['list_order'],
            'built_in' => $node['Task']['built_in'],
            'children' => array()
        );
        $children = $this->getChildNodes($node['Task']['id'], $adata);
        foreach ($children as $child) {
            $mynode['children'][] = $this->getTreeArray2($child, $adata);
        }
        return $mynode;
    }


    public function getTreeArrayPermitted($node, $adata)
    {
        $mynode = array();
        $mynode = array(
            'id' => $node['Task']['id'],
            'name' => $node['Task']['name'],
            'controller' => $node['Task']['controller'],
            'action' => $node['Task']['action'],
            'iconcls' => $node['Task']['iconcls'],
            'list_order' => $node['Task']['list_order'],
            'built_in' => $node['Task']['built_in'],
            'children' => array()
        );
        $children = $this->getChildNodes($node['Task']['id'], $adata);
        if (count($children) == 0 && $mynode['controller'] == '#') {
            return array();
        }

        $siblings = array();
        foreach ($children as $child) {
            $siblings[$child['Task']['list_order']] = $child;
        }
        ksort($siblings);
        foreach ($siblings as $child) {
            if ($child['Task']['controller'] == '#' ||
                $this->__permitted(strtolower($child['Task']['controller']), strtolower($child['Task']['action']))) {
                if ($child['Task']['controller'] != '#' && !$this->is_permitted_now($child['Task']['id'])) {
                    continue;
                }

                $n = $this->getTreeArrayPermitted($child, $adata);
                if (!empty($n)) {
                    $mynode['children'][] = $n;
                }
            }
        }
        if (count($mynode['children']) == 0 && $mynode['controller'] == '#') {
            return array();
        }
        return $mynode;
    }

    public function getChildNodes($p_id, $adata)
    {
        $ret = array();
        foreach ($adata as $ad) {
            if ($ad['Task']['parent_id'] == $p_id) {
                $ret[] = $ad;
            }
        }
        return $ret;
    }

    public function add($id = null)
    {
        if (!empty($this->data)) {
            $this->Task->create();
            $this->autoRender = false;
            $this->data['Task']['built_in'] = true;

            if ($this->Task->save($this->data)) {
                $this->Session->setFlash(__('The Task has been saved', true));
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The Task could not be saved. Please, try again.', true));
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Task', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            $this->data['Task']['built_in'] = true;

            if ($this->Task->save($this->data)) {
                $this->Session->setFlash(__('The Task has been saved', true));
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The Task could not be saved. Please, try again.', true));
                $this->render('/elements/failure');
            }
        }
        $this->set('task', $this->Task->read(null, $id));
    }

    public function delete($id = null)
    {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Task', true));
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->Task->delete($i);
                }
                $this->Session->setFlash(__('Task deleted', true));
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Task was not deleted', true));
                $this->render('/elements/failure');
            }
        } else {
            if ($this->Task->delete($id)) {
                $this->Session->setFlash(__('Task deleted', true));
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Task was not deleted', true));
                $this->render('/elements/failure');
            }
        }
    }

    public function active_tasks()
    {
        $this->loadModel('Group');
		$this->Task->unbindModel(array('hasMany' => array('Permission')));
		$this->Task->unbindModel(array('hasAndBelongsToMany' => array('EduCalendarEventType', 'Group')));
		
        // user tasks
        $groups = $this->Session->read('Auth.Group');
        $tIds = array();
        foreach ($groups as $g) {
            $group = $this->Group->read(null, $g['id']);
            foreach ($group['Task'] as $t) {
                if (!in_array($t['id'], $tIds)) {
                    $tIds[] = $t['id'];
                }
            }
        }

        $tasks = $this->Task->find('all', array(
            'conditions' => array(
               'OR' => array('Task.id' => $tIds, 'Task.controller' => '#')),
            'order' => 'Task.lft ASC'));
        
        $treeData = array();
        if (!empty($tasks)) {
            $treeData = array($this->getTreeArrayPermitted($tasks[0], $tasks));
        }
        
        $this->set('permittedTasks', $treeData);
        $this->Session->write('PermittedTasks', $treeData);
    }
}
