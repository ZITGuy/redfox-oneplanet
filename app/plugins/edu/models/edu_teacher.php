<?php

class EduTeacher extends EduAppModel {

    var $name = 'EduTeacher';
    var $validate = array(
        'date_of_employment' => array(
            'date' => array(
                'rule' => array('date'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'tidentity_number' => array(
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
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    var $hasMany = array(
        'EduSection' => array(
            'className' => 'Edu.EduSection',
            'foreignKey' => 'edu_teacher_id',
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
        'EduAssignment' => array(
            'className' => 'Edu.EduAssignment',
            'foreignKey' => 'edu_teacher_id',
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
    
    var $hasAndBelongsToMany = array(
        'EduSubject' => array(
            'className' => 'Edu.EduSubject',
            'joinTable' => 'edu_subjects_teachers',
            'foreignKey' => 'edu_teacher_id',
            'associationForeignKey' => 'edu_subject_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        ),
	    'EduClass' => array(
            'className' => 'Edu.EduClass',
            'joinTable' => 'edu_classes_teachers',
            'foreignKey' => 'edu_teacher_id',
            'associationForeignKey' => 'edu_class_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        ),
        'EduSection' => array(
            'className' => 'Edu.EduSection',
            'joinTable' => 'edu_sections_teachers',
            'foreignKey' => 'edu_teacher_id',
            'associationForeignKey' => 'edu_section_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );
	
	public function getTeacher($id) {
		$this->unbindModel(array('hasMany' => array('EduSection', 'EduAssignment')));
		$this->unbindModel(array('hasAndBelongsToMany' => array('EduSubject', 'EduClass', 'EduSection')));
		$this->recursive = 2;
		$t = $this->find('first', array(
			'conditions' => array(
				'EduTeacher.id' => $id
			)
		));
        
        if (!empty($t)) {
            return $t;
        }
        return FALSE;
    }

}
