<?php

class EduPeriod extends EduAppModel {

    var $name = 'EduPeriod';
    var $validate = array(
        'edu_course_Id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'edu_schedule_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'day' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'period' => array(
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
        'EduSchedule' => array(
            'className' => 'Edu.EduSchedule',
            'foreignKey' => 'edu_schedule_id',
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
        'EduTeacher' => array(
            'className' => 'Edu.EduTeacher',
            'foreignKey' => 'edu_teacher_id',
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
        )
    );

}
