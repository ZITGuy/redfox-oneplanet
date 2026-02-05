<?php
class EduRegistrationEvaluation extends EduAppModel {
	var $name = 'EduRegistrationEvaluation';
	var $validate = array(
		'edu_registration_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'edu_evaluation_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'edu_quarter_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'edu_evaluation_value_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
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
		'EduEvaluation' => array(
			'className' => 'Edu.EduEvaluation',
			'foreignKey' => 'edu_evaluation_id',
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
		),
		'EduEvaluationValue' => array(
			'className' => 'Edu.EduEvaluationValue',
			'foreignKey' => 'edu_evaluation_value_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>