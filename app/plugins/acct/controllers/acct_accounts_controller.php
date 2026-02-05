<?php

class AcctAccountsController extends AcctAppController {

    var $name = 'AcctAccounts';

    function index() {
        
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }

    function list_data($id = null) {
        $accounts = $this->AcctAccount->find('all', array('order' => 'AcctAccount.lft ASC'));
        $tree_data = array();
        if (count($accounts) > 0) {
            $tree_data = array($this->__getTreeArray($accounts[0], $accounts));
        }
        $this->set('acct_accounts', $tree_data);
    }
    
    function __getTreeArray($node, $adata) {
        $mynode = array();
        $mynode = array(
            'id' => $node['AcctAccount']['id'], 
            'name' => $node['AcctAccount']['name'], 
            'code' => $node['AcctAccount']['code'],
            'acct_category' => $node['AcctCategory']['name'], 
            'balance' => $node['AcctAccount']['balance'], 
            'created_by' => $node['User']['username'],
            'children' => array()
        );
        $children = $this->__getChildNodes($node['AcctAccount']['id'], $adata);
        foreach ($children as $child) {
            $mynode['children'][] = $this->__getTreeArray($child, $adata);
        }
        return $mynode;
    }

    function __getChildNodes($p_id, $adata) {
        $ret = array();
        foreach ($adata as $ad) {
            if ($ad['AcctAccount']['parent_id'] == $p_id) {
                $ret[] = $ad;
            }
        }
        return $ret;
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid acct account', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->AcctAccount->recursive = 2;
        $this->set('acct_account', $this->AcctAccount->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->AcctAccount->create();
            $this->autoRender = false;
            
            $this->data['AcctAccount']['user_id'] = $this->Session->read('Auth.User.id');
            
            if ($this->AcctAccount->save($this->data)) {
                $this->Session->setFlash(__('The acct account has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The acct account could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
        
        $acct_categories = $this->AcctAccount->AcctCategory->generatetreelist(null, null, null, '---');
        
        $users = $this->AcctAccount->User->find('list');
        $this->set(compact('acct_categories', 'users'));
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid acct account', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            $this->data['AcctAccount']['user_id'] = $this->Session->read('Auth.User.id');
            
            if ($this->AcctAccount->save($this->data)) {
                $this->Session->setFlash(__('The acct account has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The acct account could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('acct_account', $this->AcctAccount->read(null, $id));

        if ($id) {
            $this->set('parent_id', $id);
        }

        $acct_categories = $this->AcctAccount->AcctCategory->generatetreelist(null, null, null, '---');
        $users = $this->AcctAccount->User->find('list');
        $this->set(compact('acct_categories', 'users'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for acct account', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->AcctAccount->delete($i);
                }
                $this->Session->setFlash(__('Acct account deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Acct account was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->AcctAccount->delete($id)) {
                $this->Session->setFlash(__('Acct account deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Acct account was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}

?>