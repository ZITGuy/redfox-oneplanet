<?php
class EduAssessmentRecord extends EduAppModel {
	var $name = 'EduAssessmentRecord';
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
		'edu_assessment_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'mark' => array(
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
		'EduAssessment' => array(
			'className' => 'Edu.EduAssessment',
			'foreignKey' => 'edu_assessment_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>