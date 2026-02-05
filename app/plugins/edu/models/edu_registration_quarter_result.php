<?php
class EduRegistrationQuarterResult extends EduAppModel {
	var $name = 'EduRegistrationQuarterResult';
	var $validate = array(
		'edu_registration_quarter_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'edu_course_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'result_indicator' => array(
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
		'EduRegistrationQuarter' => array(
			'className' => 'Edu.EduRegistrationQuarter',
			'foreignKey' => 'edu_registration_quarter_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'EduCourse' => array(
			'className' => 'Edu.EduCourse',
			'foreignKey' => 'edu_course_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>