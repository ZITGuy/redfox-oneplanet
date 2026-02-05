<?php

class EduAttendanceRecord extends EduAppModel {

    var $name = 'EduAttendanceRecord';
    var $validate = array(
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
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
        'EduQuarter' => array(
            'className' => 'Edu.EduQuarter',
            'foreignKey' => 'edu_quarter_id',
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
        )
    );
    var $hasMany = array(
        'EduAbsentee' => array(
            'className' => 'Edu.EduAbsentee',
            'foreignKey' => 'edu_attendance_record_id',
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

}
