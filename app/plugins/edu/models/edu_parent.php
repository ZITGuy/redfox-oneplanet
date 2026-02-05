<?php

class EduParent extends EduAppModel {

    var $name = 'EduParent';
    var $validate = array(
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $hasMany = array(
        'EduStudent' => array(
            'className' => 'Edu.EduStudent',
            'foreignKey' => 'edu_parent_id',
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
        'EduParentDetail' => array(
            'className' => 'Edu.EduParentDetail',
            'foreignKey' => 'edu_parent_id',
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
        'EduSubscription' => array(
            'className' => 'Edu.EduSubscription',
            'foreignKey' => 'edu_parent_id',
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
