<?php

class AcctFiscalYearsController extends AcctAppController {

    var $name = 'AcctFiscalYears';

    function index() {
        
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");

        $this->set('acct_fiscal_years', $this->AcctFiscalYear->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->AcctFiscalYear->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid acct fiscal year', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->AcctFiscalYear->recursive = 2;
        $this->set('acct_fiscal_year', $this->AcctFiscalYear->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->AcctFiscalYear->create();
            $this->autoRender = false;
            if ($this->AcctFiscalYear->save($this->data)) {
                $this->Session->setFlash(__('The acct fiscal year has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The acct fiscal year could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid acct fiscal year', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->AcctFiscalYear->save($this->data)) {
                $this->Session->setFlash(__('The acct fiscal year has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The acct fiscal year could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('acct_fiscal_year', $this->AcctFiscalYear->read(null, $id));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for acct fiscal year', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->AcctFiscalYear->delete($i);
                }
                $this->Session->setFlash(__('Acct fiscal year deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Acct fiscal year was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->AcctFiscalYear->delete($id)) {
                $this->Session->setFlash(__('Acct fiscal year deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Acct fiscal year was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}

?>