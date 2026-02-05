<?php
class EduClassPayment extends EduAppModel {
	var $name = 'EduClassPayment';
	var $validate = array(
		'edu_class_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'edu_academic_year_id' => array(
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
		'EduClass' => array(
			'className' => 'Edu.EduClass',
			'foreignKey' => 'edu_class_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'EduAcademicYear' => array(
			'className' => 'Edu.EduAcademicYear',
			'foreignKey' => 'edu_academic_year_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>