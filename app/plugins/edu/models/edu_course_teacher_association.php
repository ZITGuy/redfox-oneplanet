<?php

class EduCourseTeacherAssociation extends EduAppModel {

    var $name = 'EduCourseTeacherAssociation';
    
    //The Associations below have been created with all possible keys, those that are not needed can be removed
       
    var $belongsTo = array(
        'EduCourse' => array(
            'className' => 'Edu.EduCourse',
            'foreignKey' => 'edu_course_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
		'EduSection' => array(
            'className' => 'Edu.EduSection',
            'foreignKey' => 'edu_section_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
		'EduTeacher' => array(
            'className' => 'Edu.EduTeacher',
            'foreignKey' => 'edu_teacher_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
	
}
