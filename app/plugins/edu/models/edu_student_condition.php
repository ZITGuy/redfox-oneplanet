<?php

class EduStudentCondition extends EduAppModel {

    var $name = 'EduStudentCondition';
    var $validate = array();
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $belongsTo = array(
        'EduStudent' => array(
            'className' => 'Edu.EduStudent',
            'foreignKey' => 'edu_student_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

}
