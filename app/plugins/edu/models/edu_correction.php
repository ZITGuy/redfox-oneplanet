<?php

class EduCorrection extends EduAppModel {

    public $name = 'EduCorrection';
    public $validate = array(
        'assessment_record_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
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
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $belongsTo = array(
        'EduSection' => array(
            'className' => 'Edu.EduSection',
            'foreignKey' => 'edu_section_id',
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
        'EduCourse' => array(
            'className' => 'Edu.EduCourse',
            'foreignKey' => 'edu_course_id',
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
        ),
        /*'EduAssessmentRecord' => array(
            'className' => 'Edu.EduAssessmentRecord',
            'foreignKey' => 'edu_assessment_record_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),*/
        'EduRegistration' => array(
            'className' => 'Edu.EduRegistration',
            'foreignKey' => 'edu_registration_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
