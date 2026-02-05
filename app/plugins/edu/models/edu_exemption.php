<?php
class EduExemption extends EduAppModel {
	
	var $name = 'EduExemption';
	var $validate = array(
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'EduStudent' => array(
			'className' => 'Edu.EduStudent',
			'foreignKey' => 'edu_student_id',
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
		),
		'EduAcademicYear' => array(
			'className' => 'Edu.EduAcademicYear',
			'foreignKey' => 'edu_academic_year_id',
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
}
?>