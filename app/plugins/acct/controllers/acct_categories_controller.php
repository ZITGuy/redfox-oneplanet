<?php

class AcctCategoriesController extends AcctAppController {

    var $name = 'AcctCategories';

    function index() {
        
    }

    function search() {
        
    }

    function list_data() {
        $categories = $this->AcctCategory->find('all', array('order' => 'AcctCategory.lft ASC'));
        $tree_data = array();
        if (count($categories) > 0) {
            $tree_data = array($this->__getTreeArray($categories[0], $categories));
        }
        $this->set('acct_categories', $tree_data);
    }
    
    function __getTreeArray($node, $adata) {
        $mynode = array();
        $mynode = array(
            'id' => $node['AcctCategory']['id'], 
            'name' => $node['AcctCategory']['name'], 
            'normal_side' => $node['AcctCategory']['normal_side'],
            'prefix' => $node['AcctCategory']['prefix'], 
            'code' => $node['AcctCategory']['code'], 
            'postfix' => $node['AcctCategory']['postfix'], 
            'last_code' => $node['AcctCategory']['last_code'], 
            'children' => array()
        );
        $children = $this->__getChildNodes($node['AcctCategory']['id'], $adata);
        foreach ($children as $child) {
            $mynode['children'][] = $this->__getTreeArray($child, $adata);
        }
        return $mynode;
    }

    function __getChildNodes($p_id, $adata) {
        $ret = array();
        foreach ($adata as $ad) {
            if ($ad['AcctCategory']['parent_id'] == $p_id) {
                $ret[] = $ad;
            }
        }
        return $ret;
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid acct category', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->AcctCategory->recursive = 2;
        $this->set('acct_category', $this->AcctCategory->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->AcctCategory->create();
            $this->autoRender = false;
            if ($this->AcctCategory->save($this->data)) {
                $this->Session->setFlash(__('The acct category has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The acct category could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid acct category', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->AcctCategory->save($this->data)) {
                $this->Session->setFlash(__('The acct category has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The acct category could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }
        
        $this->set('acct_category', $this->AcctCategory->read(null, $id));
        
        $parentAcctCategories = $this->AcctCategory->ParentAcctCategory->find('list');
        $this->set(compact('parentAcctCategories'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for acct category', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->AcctCategory->delete($i);
                }
                $this->Session->setFlash(__('Acct category deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Acct category was not deleted' . $e->getMessage(), true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->AcctCategory->delete($id)) {
                $this->Session->setFlash(__('Acct category deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Acct category was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}

?>