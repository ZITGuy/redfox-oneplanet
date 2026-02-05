<?php

class SmsPreferencesController extends AppController {

    var $name = 'SmsPreferences';

    function index() {
        
    }

    function search() {
        
    }

    function list_data() {
        $tasks = $this->SmsPreference->find('all', array('order' => 'SmsPreference.lft ASC'));
        $tree_data = array();
        if (count($tasks) > 0) {
            $tree_data = array($this->getTreeArray($tasks[0], $tasks));
        }
        $this->set('tasks', $tree_data);
    }

    function list_data_2($group_id = null) {
        $conditions = array('SmsPreference.category' => array('ROOT', 'SMS'));
        $sms_preferences = $this->SmsPreference->find('all', array('conditions' => $conditions, 'order' => 'SmsPreference.lft ASC'));
        $tree_data = array();

        if (count($sms_preferences) > 0) {
            $tree_data = array($this->getTreeArray($sms_preferences[0], $sms_preferences));
        }
        $this->set('sms_preferences', $tree_data);
    }

    function list_data_3($group_id = null) {
        $conditions = array('SmsPreference.category' => array('ROOT', 'EMAIL'));
        $sms_preferences = $this->SmsPreference->find('all', array('conditions' => $conditions, 'order' => 'SmsPreference.lft ASC'));
        $tree_data = array();

        if (count($sms_preferences) > 0) {
            $tree_data = array($this->getTreeArray($sms_preferences[0], $sms_preferences));
        }
        $this->set('sms_preferences', $tree_data);
    }

    function getTreeArray($node, $adata) {
        $mynode = array();
        $mynode = array(
            'id' => $node['SmsPreference']['id'],
            'name' => $node['SmsPreference']['name'],
            'is_selected' => $node['SmsPreference']['is_selected'],
            'children' => array()
        );
        $children = $this->getChildNodes($node['SmsPreference']['id'], $adata);
        foreach ($children as $child) {
            $mynode['children'][] = $this->getTreeArray($child, $adata);
        }
        return $mynode;
    }

    function getChildNodes($p_id, $adata) {
        $ret = array();
        foreach ($adata as $ad) {
            if ($ad['SmsPreference']['parent_id'] == $p_id) {
                $ret[] = $ad;
            }
        }
        return $ret;
    }

    function add($id = null) {
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

    function edit($id = null) {
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

    function delete($id = null) {
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
    
}
