<?php

class EduLessonPlanItem extends EduAppModel {

    var $name = 'EduLessonPlanItem';
    var $validate = array(
        'edu_lesson_plan_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'edu_period_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'edu_day_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'edu_outline_id' => array(
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
        'EduLessonPlan' => array(
            'className' => 'Edu.EduLessonPlan',
            'foreignKey' => 'edu_lesson_plan_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'EduPeriod' => array(
            'className' => 'Edu.EduPeriod',
            'foreignKey' => 'edu_period_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'EduDay' => array(
            'className' => 'Edu.EduDay',
            'foreignKey' => 'edu_day_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'EduOutline' => array(
            'className' => 'Edu.EduOutline',
            'foreignKey' => 'edu_outline_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
