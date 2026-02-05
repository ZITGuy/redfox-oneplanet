<?php

class EduAssessment extends EduAppModel {

    var $name = 'EduAssessment';
    var $validate = array(
        'edu_teacher_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'edu_section_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'max_value' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                'allowEmpty' => false,
                'required' => true,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'date' => array(
            'date' => array(
                'rule' => array('date'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'status' => array(
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
        'EduAssessmentType' => array(
            'className' => 'Edu.EduAssessmentType',
            'foreignKey' => 'edu_assessment_type_id',
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
        ),
        'EduCourse' => array(
            'className' => 'Edu.EduCourse',
            'foreignKey' => 'edu_course_id',
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
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    var $hasMany = array(
        'EduAssessmentRecord' => array(
            'className' => 'Edu.EduAssessmentRecord',
            'foreignKey' => 'edu_assessment_id',
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

?>