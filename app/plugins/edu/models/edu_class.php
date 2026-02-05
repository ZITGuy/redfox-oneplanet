<?php

class EduClass extends EduAppModel {

    var $name = 'EduClass';
	
	function beforeFind($queryData) { // mixed
        parent::beforeFind($queryData);
        
		if(!isset($queryData['conditions'][$this->name . '.cvalue']) && !isset($queryData['conditions'][$this->name . '.cvalue >']))
			$queryData['conditions'][$this->name . '.cvalue >'] = 0; 
        
        return $queryData;
    }
	
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
        'cvalue' => array(
            'range' => array(
                'rule' => array('range', -5, 20),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'min_for_promotion' => array(
            'range' => array(
                'rule' => array('range', 0, 101),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'uni_teacher' => array(
            'boolean' => array(
                'rule' => array('boolean'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'course_item_enabled' => array(
            'boolean' => array(
                'rule' => array('boolean'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $hasMany = array(
        'EduCourse' => array(
            'className' => 'Edu.EduCourse',
            'foreignKey' => 'edu_class_id',
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
        'EduSection' => array(
            'className' => 'Edu.EduSection',
            'foreignKey' => 'edu_class_id',
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
        'EduPaymentSchedule' => array(
            'className' => 'Edu.EduPaymentSchedule',
            'foreignKey' => 'edu_class_id',
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
        'EduExtraPaymentSetting' => array(
            'className' => 'Edu.EduExtraPaymentSetting',
            'foreignKey' => 'edu_class_id',
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
        'EduRegistration' => array(
            'className' => 'Edu.EduRegistration',
            'foreignKey' => 'edu_class_id',
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
        'EduEvaluation' => array(
            'className' => 'Edu.EduEvaluation',
            'foreignKey' => 'edu_class_id',
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
    
    var $belongsTo = array(
        'EduClassLevel' => array(
            'className' => 'Edu.EduClassLevel',
            'foreignKey' => 'edu_class_level_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
	
	var $hasAndBelongsToMany = array(
        'EduTeacher' => array(
            'className' => 'Edu.EduTeacher',
            'joinTable' => 'edu_classes_teachers',
            'foreignKey' => 'edu_class_id',
            'associationForeignKey' => 'edu_teacher_id',
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

	function getApplicableForRegistrationClasses( $edu_class_id, $is_promoted = true ) {
		$setting = 0;
		$class_ids = array();
		$curr_class = $this->read(null, $edu_class_id);
		// get lower classes (if applicable)
		if($setting > 0) {
			$lower_classes = $this->find('all', array(
					'conditions' => array('EduClass.cvalue <' => $curr_class['EduClass']['cvalue']), 
					'limit' => $setting, 'order' => 'EduClass.cvalue DESC'));
			foreach($lower_classes as $lower_class) {
				$class_ids[] = $lower_class['EduClass']['id'];
			}
		}
		
		// include the current in the list
		$class_ids[] = $edu_class_id;
		if( $is_promoted ){
			// if promoted is true include the next class (if applicable)
            if($edu_class_id == 13) { // for grade 10 student 
                $upper_class = $this->find('all', array(
					'conditions' => array('EduClass.id' => array(14, 16)), 
					'order' => 'EduClass.cvalue'));
            } elseif ($edu_class_id == 14) { // 11 Natural
                $upper_class = $this->find('all', array(
					'conditions' => array('EduClass.id' => array(15)), 
					'order' => 'EduClass.cvalue'));
            } elseif ($edu_class_id == 16) { // 11 Social
                $upper_class = $this->find('all', array(
					'conditions' => array('EduClass.id' => array(17)), 
					'order' => 'EduClass.cvalue'));
            } else {
			    $upper_class = $this->find('all', array(
					'conditions' => array('EduClass.cvalue >' => $curr_class['EduClass']['cvalue']), 
					'order' => 'EduClass.cvalue', 'limit' => 2));
            }
			if( $upper_class ) {
                foreach($upper_class as $uc) {
                    $class_ids[] = $uc['EduClass']['id'];
                }
			}
		}
		return $class_ids;
	}
}
