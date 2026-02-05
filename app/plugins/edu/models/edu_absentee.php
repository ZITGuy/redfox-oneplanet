<?php

class EduAbsentee extends EduAppModel {

    var $name = 'EduAbsentee';
    var $validate = array(
        'attendance_record_id' => array(
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

    var $belongsTo = array(
        'EduAttendanceRecord' => array(
            'className' => 'Edu.EduAttendanceRecord',
            'foreignKey' => 'edu_attendance_record_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'EduStudent' => array(
            'className' => 'Edu.EduStudent',
            'foreignKey' => 'edu_student_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

}
