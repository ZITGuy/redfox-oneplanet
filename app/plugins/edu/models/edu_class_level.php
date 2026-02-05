<?php

class EduClassLevel extends EduAppModel {

    var $name = 'EduClassLevel';

    var $hasMany = array(
        'EduClass' => array(
            'className' => 'Edu.EduClass',
            'foreignKey' => 'edu_class_level_id',
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