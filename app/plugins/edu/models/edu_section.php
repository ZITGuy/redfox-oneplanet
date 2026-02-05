<?php

class EduSection extends EduAppModel {

    var $name = 'EduSection';
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
        'edu_section_size' => array(
            'range' => array(
                'rule' => array('range', -1, 101),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'edu_number_of_sections' => array(
            'range' => array(
                'rule' => array('range', -1, 11),
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
        ),
        'EduTeacher' => array(
            'className' => 'Edu.EduTeacher',
            'foreignKey' => 'edu_teacher_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
		'EduCampus' => array(
            'className' => 'Edu.EduCampus',
            'foreignKey' => 'edu_campus_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    var $hasMany = array(
        'EduAssessment' => array(
            'className' => 'Edu.EduAssessment',
            'foreignKey' => 'edu_section_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'EduAssignment' => array(
            'className' => 'Edu.EduAssignment',
            'foreignKey' => 'edu_section_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'EduRegistration' => array(
            'className' => 'Edu.EduRegistration',
            'foreignKey' => 'edu_section_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )/*,
        'EduTeacherAllocation' => array(
            'className' => 'EduTeacherAllocation',
            'foreignKey' => 'edu_section_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )*/
    );

}

?>