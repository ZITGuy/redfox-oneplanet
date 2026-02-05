<?php

class AcctTransactionsController extends AcctAppController {

    var $name = 'AcctTransactions';

    function index() {
        $acct_fiscal_years = $this->AcctTransaction->AcctFiscalYear->find('all');
        $this->set(compact('acct_fiscal_years'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $acct_fiscal_year_id = (isset($_REQUEST['acct_fiscal_year_id'])) ? $_REQUEST['acct_fiscal_year_id'] : -1;
        if ($id)
            $acct_fiscal_year_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($acct_fiscal_year_id != -1) {
            $conditions['AcctTransaction.acct_fiscal_year_id'] = $acct_fiscal_year_id;
        }

        $this->set('acct_transactions', $this->AcctTransaction->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->AcctTransaction->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid acct transaction', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->AcctTransaction->recursive = 2;
        $this->set('acct_transaction', $this->AcctTransaction->read(null, $id));
    }

    /**
     * $transaction = array(
     *          'dr_acct_code' => '', 
     *          'cr_acct_code' => '',
     *          'value' => '',
     *          'description' => '',
     *          'cheque_number' => '',
     *          'invoice_number' => '');
     */
    function save_transaction() {
        $transaction = $this->params['pass'];
        if (is_array($transaction)) {
            $this->loadModel('Acct.AcctFiscalYear');
            $this->loadModel('Acct.AcctAccount');
            $this->loadModel('Acct.AcctCategory');
            $this->loadModel('Acct.AcctJournal');

            //1. get the current active fiscal year
            $fy = $this->AcctFiscalYear->getActiveFiscalYear();

            if (empty($fy)) {
                $this->Session->write('transaction.return', "There is no Active Fiscal Year!");
                $this->set('return', 0);
                return false;
            }

            //2. prepare transaction data
            $data = array('AcctTransaction' => array());
            $data['AcctTransaction']['acct_fiscal_year_id'] = $fy['AcctFiscalYear']['id'];
            $data['AcctTransaction']['user_id'] = $this->Session->read('Auth.User.id');
            $data['AcctTransaction']['description'] = $transaction['description'];
            $data['AcctTransaction']['cheque_number'] = $transaction['cheque_number'];
            $data['AcctTransaction']['invoice_number'] = $transaction['invoice_number'];
            $data['AcctTransaction']['transaction_date'] = date('Y-m-d');
            $data['AcctTransaction']['name'] = time();  // reference number

            $this->Session->write('transaction.return', $data['AcctTransaction']['name']);

            //3. get the debit account detail
            $dr_acct = $this->AcctAccount->find('first', array('conditions' => array('AcctAccount.code' => $transaction['dr_acct_code'])));

            //4. prepare the debit sided account journal entry
            $journal1 = array('AcctJournal' => array());
            $journal1['AcctJournal']['acct_account_id'] = $dr_acct['AcctAccount']['id'];
            $journal1['AcctJournal']['dr'] = $transaction['value'];
            $journal1['AcctJournal']['cr'] = 0;
            $journal1['AcctJournal']['bbf'] = $dr_acct['AcctAccount']['balance'];
            // balance side check
            $this->AcctCategory->recursive = 0;
            $cat1 = $this->AcctCategory->read(null, $dr_acct['AcctAccount']['acct_category_id']);
            $dr_acct['AcctCategory'] = $cat1['AcctCategory'];

            $dr_bbf = 0;
            if ($dr_acct['AcctCategory']['normal_side'] == 'DR')
                $dr_bbf = $journal1['AcctJournal']['dr'];
            else
                $dr_bbf = $journal1['AcctJournal']['dr'] * -1;
            $new_bbf_dr = $journal1['AcctJournal']['bbf'] + $dr_bbf;

            //5. get the credit account detail
            $cr_acct = $this->AcctAccount->find('first', array('conditions' => array('AcctAccount.code' => $transaction['cr_acct_code'])));

            //6. prepare the credit sided account journal entry
            $journal2 = array('AcctJournal' => array());
            $journal2['AcctJournal']['acct_account_id'] = $cr_acct['AcctAccount']['id'];
            $journal2['AcctJournal']['dr'] = 0;
            $journal2['AcctJournal']['cr'] = $transaction['value'];
            $journal2['AcctJournal']['bbf'] = $cr_acct['AcctAccount']['balance'];

            $cat2 = $this->AcctCategory->read(null, $cr_acct['AcctAccount']['acct_category_id']);
            $cr_acct['AcctCategory'] = $cat2['AcctCategory'];
            $cr_bbf = 0;
            if ($cr_acct['AcctCategory']['normal_side'] == 'CR')
                $cr_bbf = $journal2['AcctJournal']['cr'];
            else
                $cr_bbf = $journal2['AcctJournal']['cr'] * -1;

            $new_bbf_cr = $journal2['AcctJournal']['bbf'] + $cr_bbf;

            //7. save the transaction => if successfull save the journal entries. 
            $this->autoRender = false;
            if ($this->AcctTransaction->save($data)) {
                //7.1. Save the debit sided journal entry
                $this->AcctJournal->create();
                $journal1['AcctJournal']['acct_transaction_id'] = $this->AcctTransaction->id;
                $this->AcctJournal->save($journal1);

                //7.1. Save the credit sided journal entry
                $this->AcctJournal->create();
                $journal2['AcctJournal']['acct_transaction_id'] = $this->AcctTransaction->id;
                $this->AcctJournal->save($journal2);

                //7.3. Update the affected accounts' balance field with new bbf.
                $this->AcctAccount->updateAll(array('AcctAccount.balance' => $new_bbf_dr), array('AcctAccount.id' => $journal1['AcctJournal']['acct_account_id']));
                $this->AcctAccount->updateAll(array('AcctAccount.balance' => $new_bbf_cr), array('AcctAccount.id' => $journal2['AcctJournal']['acct_account_id']));

                //7.4. Everything is OK so return true (1) to the caller object.
                $this->set('return', 1);

                return true;
            } else {
                //7.4. Cannot even save the transaction, so return false to the caller object
                $this->set('return', 0);
                return false;
            }
        } else {
            //8. Cannot save because the passed transaction data is not valid, so return false to the caller object
            $this->set('return', 0);
            return false;
        }
    }

    /**
     * $transaction = array(
     *          'dr_side' => array, 
     *          'cr_side' => array,
     *          'description' => '',
     *          'return' => '');
     */
    function save_transaction_v2() {
        $transaction = $this->params['pass'];
        $this->autoRender = false;
        
        if (is_array($transaction)) {
            $this->loadModel('Acct.AcctFiscalYear');
            $this->loadModel('Acct.AcctAccount');
            $this->loadModel('Acct.AcctCategory');
            $this->loadModel('Acct.AcctJournal');

            //1. get the current active fiscal year
            $fy = $this->AcctFiscalYear->getActiveFiscalYear();
            
            if (empty($fy)) {
                $this->Session->write('transaction.return', "There is no Active Fiscal Year!");
                $this->set('return', 0);
                return false;
            }
            //2. prepare transaction data
            $data = array('AcctTransaction' => array());
            $data['AcctTransaction']['acct_fiscal_year_id'] = $fy['AcctFiscalYear']['id'];
            $data['AcctTransaction']['user_id'] = $this->Session->read('Auth.User.id');
            $data['AcctTransaction']['description'] = $transaction['description'];
            $data['AcctTransaction']['cheque_number'] = $transaction['cheque_number'];
            $data['AcctTransaction']['invoice_number'] = $transaction['invoice_number'];
            $data['AcctTransaction']['transaction_date'] = date('Y-m-d');
            $data['AcctTransaction']['name'] = time();  // reference number

            $this->Session->write('transaction.return', $data['AcctTransaction']['name']);
            $journals = array();
            $cr_balance = 0;
            $dr_balance = 0;
            foreach ($transaction['dr_side'] as $k => $v) {
                //3. get the debit account detail
                $dr_acct = $this->AcctAccount->find('first', array('conditions' => array('AcctAccount.code' => $k)));

                //4. prepare the debit sided account journal entry
                $journal1 = array('AcctJournal' => array());
                $journal1['AcctJournal']['acct_account_id'] = $dr_acct['AcctAccount']['id'];
                $journal1['AcctJournal']['dr'] = $v;
                $journal1['AcctJournal']['cr'] = 0;
                $journal1['AcctJournal']['bbf'] = $dr_acct['AcctAccount']['balance'];

                // balance side check
                $this->AcctCategory->recursive = 0;
                $cat1 = $this->AcctCategory->read(null, $dr_acct['AcctAccount']['acct_category_id']);
                $dr_acct['AcctCategory'] = $cat1['AcctCategory'];

                $dr_bbf = 0;
                if ($dr_acct['AcctCategory']['normal_side'] == 'DR') {
                    $dr_bbf = $journal1['AcctJournal']['dr'];
                } else {
                    $dr_bbf = $journal1['AcctJournal']['dr'] * -1;
                }
                $new_bbf_dr = $journal1['AcctJournal']['bbf'] + $dr_bbf;
                $journal1['new_bbf_dr'] = $new_bbf_dr;
                if ($new_bbf_dr < 0) {
                    $this->log('Sorry, Transaction could not be saved. Account ' . $k . ' will have abnormal balance.', 'debug');
                    $this->Session->write('transaction.return', 'Sorry, Transaction could not be saved. Account ' . $k . ' will have abnormal balance.');
                    $this->set('return', 0);
                    return false;
                }
                $dr_balance += $v;
                $journals[] = $journal1;
            }

            foreach ($transaction['cr_side'] as $k => $v) {
                //5. get the credit account detail
                $cr_acct = $this->AcctAccount->find('first', array('conditions' => array('AcctAccount.code' => $k)));

                //6. prepare the credit sided account journal entry
                $journal2 = array('AcctJournal' => array());
                $journal2['AcctJournal']['acct_account_id'] = $cr_acct['AcctAccount']['id'];
                $journal2['AcctJournal']['dr'] = 0;
                $journal2['AcctJournal']['cr'] = $v;
                $journal2['AcctJournal']['bbf'] = $cr_acct['AcctAccount']['balance'];

                $cat2 = $this->AcctCategory->read(null, $cr_acct['AcctAccount']['acct_category_id']);
                $cr_acct['AcctCategory'] = $cat2['AcctCategory'];
                $cr_bbf = 0;
                if ($cr_acct['AcctCategory']['normal_side'] == 'CR')
                    $cr_bbf = $journal2['AcctJournal']['cr'];
                else
                    $cr_bbf = $journal2['AcctJournal']['cr'] * -1;

                $new_bbf_cr = $journal2['AcctJournal']['bbf'] + $cr_bbf;
                $journal2['new_bbf_cr'] = $new_bbf_cr;
                if ($new_bbf_cr < 0) {
                    $this->Session->write('transaction.return', 'Sorry, Transaction could not be saved. Account ' . $k . ' will have abnormal balance.');
                    $this->set('return', 0);
                    return false;
                }
                $cr_balance += $v;
                $journals[] = $journal2;
            }
            // check the transaction is balanced or not
            if ($cr_balance != $dr_balance) {
                //8. Cannot save because the passed transaction data is not valid, so return false to the caller object
                $this->Session->write('transaction.return', 'Debit and Credit Sides are not balanced!');
                $this->set('return', 0);
                return false;
            }

            //7. save the transaction => if successfull save the journal entries. 
            if ($this->AcctTransaction->save($data)) {
                foreach ($journals as $j) {
                    $new_bbf_dr = -1;
                    $new_bbf_cr = -1;
                    if (isset($j['new_bbf_dr'])) {
                        $new_bbf_dr = $j['new_bbf_dr'];
                        unset($j['new_bbf_dr']);
                    } else {
                        $new_bbf_cr = $j['new_bbf_cr'];
                        unset($j['new_bbf_cr']);
                    }
                    //7.1. Save the debit/credit sided journal entry
                    $this->AcctJournal->create();
                    $journal1['AcctJournal']['acct_transaction_id'] = $this->AcctTransaction->id;
                    $this->AcctJournal->save($j);

                    //7.3. Update the affected accounts' balance field with new bbf.
                    if ($new_bbf_dr >= 0) {
                        $this->AcctAccount->updateAll(array('AcctAccount.balance' => $new_bbf_dr), array('AcctAccount.id' => $j['AcctJournal']['acct_account_id']));
                    } else {
                        $this->AcctAccount->updateAll(array('AcctAccount.balance' => $new_bbf_cr), array('AcctAccount.id' => $j['AcctJournal']['acct_account_id']));
                    }
                }
                //7.4. Everything is OK so return true (1) to the caller object.
                $this->set('return', 1);

                return true;
            } else {
                //7.4. Cannot even save the transaction, so return false to the caller object
                $this->set('return', 0);
                return false;
            }
        } else {
            //8. Cannot save because the passed transaction data is not valid, so return false to the caller object
            $this->set('return', 0);
            return false;
        }
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->AcctTransaction->create();
            $this->loadModel('AcctFiscalYear');
            $this->loadModel('AcctAccount');
            $this->loadModel('AcctCategory');
            $this->loadModel('AcctJournal');
            $fy = $this->AcctFiscalYear->getActiveFiscalYear();

            $this->data['AcctTransaction']['acct_fiscal_year_id'] = $fy['AcctFiscalYear']['id'];
            $this->data['AcctTransaction']['user_id'] = $this->Session->read('Auth.User.id');

            $dr_acct = $this->AcctAccount->read(null, $this->data['AcctTransaction']['dr_account_id']);

            $journal1 = array('AcctJournal' => array());
            $journal1['AcctJournal']['acct_account_id'] = $this->data['AcctTransaction']['dr_account_id'];
            $journal1['AcctJournal']['dr'] = $this->data['AcctTransaction']['dr_value'];
            $journal1['AcctJournal']['cr'] = 0;
            $journal1['AcctJournal']['bbf'] = $dr_acct['AcctAccount']['balance'];
            // balance side check
            $this->AcctCategory->recursive = 0;
            $cat1 = $this->AcctCategory->read(null, $dr_acct['AcctAccount']['acct_category_id']);
            $dr_acct['AcctCategory'] = $cat1['AcctCategory'];

            $dr_bbf = 0;
            if ($dr_acct['AcctCategory']['normal_side'] == 'DR')
                $dr_bbf = $journal1['AcctJournal']['dr'];
            else
                $dr_bbf = $journal1['AcctJournal']['dr'] * -1;
            $new_bbf_dr = $journal1['AcctJournal']['bbf'] + $dr_bbf;

            $cr_acct = $this->AcctAccount->read(null, $this->data['AcctTransaction']['cr_account_id']);
            $journal2 = array('AcctJournal' => array());
            $journal2['AcctJournal']['acct_account_id'] = $this->data['AcctTransaction']['cr_account_id'];
            $journal2['AcctJournal']['dr'] = 0;
            $journal2['AcctJournal']['cr'] = $this->data['AcctTransaction']['cr_value'];
            $journal2['AcctJournal']['bbf'] = $cr_acct['AcctAccount']['balance'];

            $cat2 = $this->AcctCategory->read(null, $cr_acct['AcctAccount']['acct_category_id']);
            $cr_acct['AcctCategory'] = $cat2['AcctCategory'];
            $cr_bbf = 0;
            if ($cr_acct['AcctCategory']['normal_side'] == 'CR')
                $cr_bbf = $journal2['AcctJournal']['cr'];
            else
                $cr_bbf = $journal2['AcctJournal']['cr'] * -1;

            $new_bbf_cr = $journal2['AcctJournal']['bbf'] + $cr_bbf;

            unset($this->data['AcctTransaction']['dr_account_id']);
            unset($this->data['AcctTransaction']['dr_value']);
            unset($this->data['AcctTransaction']['cr_account_id']);
            unset($this->data['AcctTransaction']['cr_value']);

            $this->autoRender = false;
            if ($this->AcctTransaction->save($this->data)) {
                $this->AcctJournal->create();
                $journal1['AcctJournal']['acct_transaction_id'] = $this->AcctTransaction->id;
                $this->AcctJournal->save($journal1);

                $this->AcctJournal->create();
                $journal2['AcctJournal']['acct_transaction_id'] = $this->AcctTransaction->id;
                $this->AcctJournal->save($journal2);

                $this->AcctAccount->updateAll(array('AcctAccount.balance' => $new_bbf_dr), array('AcctAccount.id' => $journal1['AcctJournal']['acct_account_id']));
                $this->AcctAccount->updateAll(array('AcctAccount.balance' => $new_bbf_cr), array('AcctAccount.id' => $journal2['AcctJournal']['acct_account_id']));

                $this->Session->setFlash(__('The transaction has been saved', true), '');
                $this->render('/elements/success');
            } else {
                print_r($this->AcctTransaction->validationErrors);
                $this->Session->setFlash(__('The acct transaction could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
        $this->loadModel('AcctAccount');

        $dr_accounts = $this->AcctAccount->find('list');
        $cr_accounts = $this->AcctAccount->find('list');
        $this->set(compact('dr_accounts', 'cr_accounts'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid acct transaction', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->AcctTransaction->save($this->data)) {
                $this->Session->setFlash(__('The acct transaction has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The acct transaction could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('acct_transaction', $this->AcctTransaction->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $acct_fiscal_years = $this->AcctTransaction->AcctFiscalYear->find('list');
        $users = $this->AcctTransaction->User->find('list');
        $this->set(compact('acct_fiscal_years', 'users'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for acct transaction', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->AcctTransaction->delete($i);
                }
                $this->Session->setFlash(__('Acct transaction deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Acct transaction was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->AcctTransaction->delete($id)) {
                $this->Session->setFlash(__('Acct transaction deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Acct transaction was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    #region reporting functions

    /**
     * Enrolled students report form
     */
    function rpt_daily_transactions() {
        
    }

    /**
     * Enrolled students report viewer
     */
    function rpt_view_daily_transactions($date, $title) {
        $this->layout = 'ajax';

        $conditions = array();

        $conditions['AcctTransaction.transaction_date'] = $date;

        $this->AcctTransaction->recursive = 3;

        $transactions = $this->AcctTransaction->find('all', array('conditions' => $conditions));

        $this->set('transactions', $transactions);

        $this->set('company_url', Configure::read('company_url'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_tin', Configure::read('company_tin'));
        $this->set('company_address', Configure::read('company_address'));
        $this->set('report_title', str_replace('_', ' ', $title));
        $this->set('date', $date);
    }

    #endregion
}

?>