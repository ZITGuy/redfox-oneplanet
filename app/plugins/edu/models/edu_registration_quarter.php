<?php
class EduRegistrationQuarter extends EduAppModel {
	var $name = 'EduRegistrationQuarter';
	var $validate = array(
		'edu_registration_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'edu_quarter_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'quarter_rank' => array(
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
		'EduRegistration' => array(
			'className' => 'Edu.EduRegistration',
			'foreignKey' => 'edu_registration_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'EduQuarter' => array(
			'className' => 'Edu.EduQuarter',
			'foreignKey' => 'edu_quarter_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'EduRegistrationQuarterResult' => array(
			'className' => 'Edu.EduRegistrationQuarterResult',
			'foreignKey' => 'edu_registration_quarter_id',
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

}
?>