<?php

class AcctFiscalYear extends AcctAppModel {

    var $name = 'AcctFiscalYear';
    var $validate = array(
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'start_date' => array(
            'date' => array(
                'rule' => array('date'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'end_date' => array(
            'date' => array(
                'rule' => array('date'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    
    var $hasMany = array(
        'AcctTransaction' => array(
            'className' => 'Acct.AcctTransaction',
            'foreignKey' => 'acct_fiscal_year_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    
    
    function getActiveFiscalYear() {
        $dt = date('Y-m-d');
        $fy = $this->find('first', array('conditions'=>array(
            'AcctFiscalYear.start_date <=' => $dt, 
            'AcctFiscalYear.end_date >=' => $dt)));
        
        return $fy;
    }

}

?>