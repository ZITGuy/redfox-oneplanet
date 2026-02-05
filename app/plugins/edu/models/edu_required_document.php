<?php

class EduRequiredDocument extends EduAppModel {

    var $name = 'EduRequiredDocument';
    var $validate = array();
    var $belongsTo = array(
        'EduRegistration' => array(
            'className' => 'Edu.EduRegistration',
            'foreignKey' => 'edu_registration_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

}

?>