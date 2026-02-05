<?php
class AcctJournal extends AppModel {
	var $name = 'AcctJournal';
	var $validate = array(
		'acct_transaction_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'acct_account_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'AcctTransaction' => array(
			'className' => 'AcctTransaction',
			'foreignKey' => 'acct_transaction_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'AcctAccount' => array(
			'className' => 'AcctAccount',
			'foreignKey' => 'acct_account_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>