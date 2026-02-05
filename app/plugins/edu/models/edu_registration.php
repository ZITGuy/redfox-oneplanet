<?php

class EduRegistration extends EduAppModel {

    var $name = 'EduRegistration';
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
        'edu_student_id' => array(
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
		'scholarship' => array(
            'range' => array(
                'rule' => array('range', -1, 101),
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
        'EduStudent' => array(
            'className' => 'Edu.EduStudent',
            'foreignKey' => 'edu_student_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'EduClass' => array(
            'className' => 'Edu.EduClass',
            'foreignKey' => 'edu_class_id',
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
        'EduCampus' => array(
            'className' => 'Edu.EduCampus',
            'foreignKey' => 'edu_campus_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Status' => array(
            'className' => 'Status',
            'foreignKey' => 'status_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    var $hasMany = array(
        'EduRegistrationQuarter' => array(
            'className' => 'Edu.EduRegistrationQuarter',
            'foreignKey' => 'edu_registration_id',
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
        'EduRegistrationResult' => array(
            'className' => 'Edu.EduRegistrationResult',
            'foreignKey' => 'edu_registration_id',
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
        'EduRequiredDocument' => array(
            'className' => 'Edu.EduRequiredDocument',
            'foreignKey' => 'edu_registration_id',
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
        'EduRegistrationEvaluation' => array(
            'className' => 'Edu.EduRegistrationEvaluation',
            'foreignKey' => 'edu_registration_id',
            'dependent' => false,
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

	function getLastRegistration($student_id) {
        $reg = $this->find('first', array('conditions' => array('EduRegistration.edu_student_id' => $student_id), 
										   'order' => 'EduRegistration.created DESC'));
        if($reg){
            return $reg;
        }
        return FALSE;
    }
}

?>