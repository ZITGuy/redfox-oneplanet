<?php

class EduEvaluation extends EduAppModel {

    var $name = 'EduEvaluation';
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
        'edu_evaluation_area_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'order_level' => array(
            'range' => array(
                'rule' => array('range', 0, 500),
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
        'EduEvaluationArea' => array(
            'className' => 'Edu.EduEvaluationArea',
            'foreignKey' => 'edu_evaluation_area_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'EduEvaluationValue' => array(
            'className' => 'Edu.EduEvaluationValue',
            'foreignKey' => 'edu_evaluation_value_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    var $hasMany = array(
        'EduRegistrationEvaluation' => array(
            'className' => 'Edu.EduRegistrationEvaluation',
            'foreignKey' => 'edu_evaluation_id',
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

?>