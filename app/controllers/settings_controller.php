<?php

class SettingsController extends AppController {

    var $name = 'Settings';

    function index() {
        
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");

        $this->set('settings', $this->Setting->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->Setting->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid setting', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Setting->recursive = 2;
        $this->set('setting', $this->Setting->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->Setting->create();
            $this->autoRender = false;
            if ($this->Setting->save($this->data)) {
                $this->Session->setFlash(__('The setting has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The setting could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid setting', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->Setting->save($this->data)) {
                $this->Session->setFlash(__('The setting has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The setting could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('setting', $this->Setting->read(null, $id));
    }
    
    function system_settings() {
        if (!empty($this->data)) {
            $this->autoRender = false;
            
            if(!isset($this->data['Setting']['receive_payment_by_cheque']) && isset($this->data['Setting']['receivable_gl_account'])){
                $this->data['Setting']['receive_payment_by_cheque'] = 'False';
            }
            if(!isset($this->data['Setting']['handle_summer_as_term']) && isset($this->data['Setting']['education_term'])){
                $this->data['Setting']['handle_summer_as_term'] = 'False';
            }
            if(!isset($this->data['Setting']['sms_enabled']) && isset($this->data['Setting']['sms_short_code'])){
                $this->data['Setting']['sms_enabled'] = 'False';
            }

            $sms_selected_preferences = $this->data['Setting']['sms_selected_preferences'];
            unset($this->data['Setting']['sms_selected_preferences']);
            
            foreach($this->data['Setting'] as $setting_key => $setting_value) {
                $setting = $this->Setting->find('first', array('conditions' => array('Setting.setting_key' => strtoupper($setting_key))));
                if($setting) {
                    $this->Setting->read(null, $setting['Setting']['id']);
                    if ($setting_value == 'on' && ($setting_key == 'receive_payment_by_cheque' ||
                            $setting_key == 'handle_summer_as_term' ||
                            $setting_key == 'sms_enabled' || $setting_key == 'email_enabled')) {
                        $setting_value = 'True';
                    }
                    
                    if ($setting_key == 'company_tin') {
                        while(strlen($setting_value) < 10){
                            $setting_value = '0' . $setting_value;
                        }
                    }
                    $this->Setting->set('setting_value', $setting_value);
                    $this->Setting->save();
                }
            }

            $sms_selected_preferences = str_replace('"', '', $sms_selected_preferences);
            $selecteds = explode(',', $sms_selected_preferences);
            $this->loadModel('SmsPreference');
            if(count($selecteds) > 0){
                $prefs = $this->SmsPreference->find('all');
                foreach ($prefs as $pref) {
                    $this->SmsPreference->read(null, $pref['SmsPreference']['id']);
                    $this->SmsPreference->set('is_selected', 0);
                    $this->SmsPreference->save();
                }
            }

            foreach ($selecteds as $selected_id) {
                $this->SmsPreference->read(null, $selected_id);
                $this->SmsPreference->set('is_selected', 1);
                $this->SmsPreference->save();
            }
            
            $this->Session->setFlash(__('The settings has been saved', true), '');
            $this->render('/elements/success');
        }
        
        $this->loadModel('Acct.AcctAccount');
        $ex_accounts = $this->AcctAccount->find('all', array('conditions' => array('AcctAccount.acct_category_id' => 6))); // Expenses account calegory: BUILT-IN
        
        //TODO: Collect the categories of asset
        
        $asset_accounts = $this->AcctAccount->find('all', array('conditions' => array('AcctAccount.acct_category_id' => array(2, 7, 8)))); // Expenses account calegory: BUILT-IN
        
        $income_accounts = $this->AcctAccount->find('all', array('conditions' => array('AcctAccount.acct_category_id' => 5))); // Expenses account calegory: BUILT-IN
        
        $this->set('ex_accounts', $ex_accounts);
        $this->set('as_accounts', $asset_accounts);
        $this->set('re_accounts', $income_accounts);
        $this->set('settings', $this->Setting->find('all'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for setting', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->Setting->delete($i);
                }
                $this->Session->setFlash(__('Setting deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Setting was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->Setting->delete($id)) {
                $this->Session->setFlash(__('Setting deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Setting was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}

?>