<?php

class EduOutline extends EduAppModel {

    var $name = 'EduOutline';
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
        'edu_course_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'list_order' => array(
            'range' => array(
                'rule' => array('range', 0, 201),
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
        'EduCourse' => array(
            'className' => 'Edu.EduCourse',
            'foreignKey' => 'edu_course_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    var $hasMany = array(
        'EduLessonPlanItem' => array(
            'className' => 'Edu.EduLessonPlanItem',
            'foreignKey' => 'edu_outline_id',
            'dependent' => true,
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
