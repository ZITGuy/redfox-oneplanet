<?php

class EduQuarterSummary extends EduAppModel {

    var $name = 'EduQuarterSummary';
    var $validate = array(
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
        'edu_quarter_id' => array(
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
        'EduQuarter' => array(
            'className' => 'Edu.EduQuarter',
            'foreignKey' => 'edu_quarter_id',
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
}
