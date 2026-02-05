<?php

class AuditTrailsController extends AppController {

    var $name = 'AuditTrails';

    function index() {
        $users = $this->AuditTrail->User->find('all');
        $this->set(compact('users'));
    }

    function index2($id = null, $audited_model = null) {
        $this->set('parent_id', $id);
        $this->set('audited_model', $audited_model);
    }

    function search() {
        
    }
    
    function search_audit_trail() {
        
    }
	
	function search_audit_trail2() {
        
    }

    function list_data($id = null, $audited_model = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $user_id = (isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : -1;
        $table_name = 'N/A';
        $record_id = -1;
        if ($id) {
            $user_id = ($id) ? $id : -1;
            if($audited_model){
                $record_id = $id;
                $user_id = -1;
                $table_name = $audited_model;
            }
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($user_id != -1) {
            $conditions['AuditTrail.user_id'] = $user_id;
        }
        if ($record_id != -1) {
            $conditions['AuditTrail.record_id'] = $record_id;
            $conditions['AuditTrail.table_name'] = $table_name;
        }
        
        $this->set('audit_trails', $this->AuditTrail->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->AuditTrail->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid audit trail', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->AuditTrail->recursive = 2;
        $this->set('audit_trail', $this->AuditTrail->read(null, $id));
    }
    
    function view_audit_trail($user_id = 0, $from_date = null, $to_date = null) {
        $this->layout = 'ajax';
        
        $table_name = 'N/A';
        $conditions = array();
        if ($user_id > 0) {
            $conditions['AuditTrail.user_id'] = $user_id;
        }
        if($from_date){
            $conditions['AuditTrail.created >='] = $from_date . ' 00:00:00';
        }
        if($to_date){
            $conditions['AuditTrail.created <='] = $to_date . ' 23:59:59';
        }
        
        $this->set('user_id', $user_id);
        $this->set('from_date', $from_date);
        $this->set('to_date', $to_date);
        $this->set('audit_trails', $this->AuditTrail->find('all', array('conditions' => $conditions)));
        
        $this->set('company_url', Configure::read('company_url'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_tin', Configure::read('company_tin'));
        $this->set('company_address', Configure::read('company_address'));
    }
	
	function view_audit_trail2($user_id = 0, $from_date = null, $to_date = null) {
        $this->layout = 'ajax';
        
        $table_name = 'N/A';
        $conditions = array();
        if ($user_id > 0) {
            $conditions['AuditTrail.user_id'] = $user_id;
        }
        if($from_date){
            $conditions['AuditTrail.created >='] = $from_date . ' 00:00:00';
        }
        if($to_date){
            $conditions['AuditTrail.created <='] = $to_date . ' 23:59:59';
        }
        
        $this->set('user_id', $user_id);
        $this->set('from_date', $from_date);
        $this->set('to_date', $to_date);
        $this->set('audit_trails', $this->AuditTrail->find('all', array('conditions' => $conditions)));
        
        $this->set('company_url', Configure::read('company_url'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_tin', Configure::read('company_tin'));
        $this->set('company_address', Configure::read('company_address'));
    }

}

