<?php

class EduStudent extends EduAppModel {

    var $name = 'EduStudent';
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
        'birth_date' => array(
            'date' => array(
                'rule' => array('date'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'registration_date' => array(
            'date' => array(
                'rule' => array('date'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'edu_parent_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'user_id' => array(
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
        'EduParent' => array(
            'className' => 'Edu.EduParent',
            'foreignKey' => 'edu_parent_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Maker' => array(
            'className' => 'User',
            'foreignKey' => 'maker_id',
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
        ),
        'EduClass' => array(
            'className' => 'Edu.EduClass',
            'foreignKey' => 'edu_class_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
		
    );
    var $hasMany = array(
        'EduRegistration' => array(
            'className' => 'Edu.EduRegistration',
            'foreignKey' => 'edu_student_id',
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
        'EduStudentCondition' => array(
            'className' => 'Edu.EduStudentCondition',
            'foreignKey' => 'edu_student_id',
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
        'EduSibling' => array(
            'className' => 'Edu.EduSibling',
            'foreignKey' => 'edu_student_id',
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
        'EduAbsentee' => array(
            'className' => 'Edu.EduAbsentee',
            'foreignKey' => 'edu_student_id',
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

    function getStudent($id_number) {
        $student = $this->find('first', array('conditions' => array('EduStudent.identity_number' => $id_number)));
        if (!empty($student)) {
            return $student;
        }
        return FALSE;
    }

}

