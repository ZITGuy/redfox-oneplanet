<?php

class AcctJournalsController extends AcctAppController {

    var $name = 'AcctJournals';

    function index() {
        $acct_transactions = $this->AcctJournal->AcctTransaction->find('all');
        $this->set(compact('acct_transactions'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $acct_transaction_id = (isset($_REQUEST['acct_transaction_id'])) ? $_REQUEST['acct_transaction_id'] : -1;
        if ($id)
            $acct_transaction_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($acct_transaction_id != -1) {
            $conditions['AcctJournal.acct_transaction_id'] = $acct_transaction_id;
        }

        $this->set('acct_journals', $this->AcctJournal->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->AcctJournal->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid acct journal', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->AcctJournal->recursive = 2;
        $this->set('acct_journal', $this->AcctJournal->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->AcctJournal->create();
            $this->autoRender = false;
            if ($this->AcctJournal->save($this->data)) {
                $this->Session->setFlash(__('The acct journal has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The acct journal could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
        $acct_transactions = $this->AcctJournal->AcctTransaction->find('list');
        $acct_accounts = $this->AcctJournal->AcctAccount->find('list');
        $this->set(compact('acct_transactions', 'acct_accounts'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid acct journal', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->AcctJournal->save($this->data)) {
                $this->Session->setFlash(__('The acct journal has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The acct journal could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('acct_journal', $this->AcctJournal->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $acct_transactions = $this->AcctJournal->AcctTransaction->find('list');
        $acct_accounts = $this->AcctJournal->AcctAccount->find('list');
        $this->set(compact('acct_transactions', 'acct_accounts'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for acct journal', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->AcctJournal->delete($i);
                }
                $this->Session->setFlash(__('Acct journal deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Acct journal was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->AcctJournal->delete($id)) {
                $this->Session->setFlash(__('Acct journal deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Acct journal was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}

?>