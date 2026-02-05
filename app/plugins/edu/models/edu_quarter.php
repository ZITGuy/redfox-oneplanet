<?php

class EduQuarter extends EduAppModel {

    var $name = 'EduQuarter';
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
        'start_date' => array(
            'date' => array(
                'rule' => array('date'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'end_date' => array(
            'date' => array(
                'rule' => array('date'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'edu_academic_year_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
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
        'EduAcademicYear' => array(
            'className' => 'Edu.EduAcademicYear',
            'foreignKey' => 'edu_academic_year_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Maker' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    var $hasMany = array(
        'EduCalendarEvent' => array(
            'className' => 'Edu.EduCalendarEvent',
            'foreignKey' => 'edu_quarter_id',
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
        'EduAssessment' => array(
            'className' => 'Edu.EduAssessment',
            'foreignKey' => 'edu_quarter_id',
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

    public function getActiveEducationalQuarter()
    {
        $this->loadModel('Edu.EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $q = array();
        if ($ay !== FALSE){
            $q = $this->find('first', array(
                    'conditions' => array(
                        'EduQuarter.status_id' => 1,
                        'EduQuarter.quarter_type' => 'E',
                        'EduQuarter.edu_academic_year_id' => $ay['EduAcademicYear']['id']
                    )
                )
            );
        }
        
        if (!empty($q)) {
            return $q;
        }
        return false;
    }

    function getLastEducationalQuarter() {
        $this->loadModel('Edu.EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $q = array();
        if ($ay !== false) {
            $q = $this->find('first', array(
                'conditions' => array(
                    'EduQuarter.quarter_type' => 'E',
                    'EduQuarter.edu_academic_year_id' => $ay['EduAcademicYear']['id']
                ),
                'order' => 'EduQuarter.start_date DESC'
            ));
        }
        
        if (!empty($q)) {
            return $q;
        }
        return false;
    }

    public function getActiveQuarter()
    {
        $this->loadModel('Edu.EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $q = array();
        if ($ay !== false) {
            $q = $this->find('first', array(
                'conditions' => array(
                    'EduQuarter.status_id' => 1,
                    'EduQuarter.edu_academic_year_id' => $ay['EduAcademicYear']['id']
                )
            ));
        }
        
        if (!empty($q)) {
            return $q;
        }
        return false;
    }

    public function getNextQuarter($id)
    {
        $quarter = $this->read(null, $id);
        $nextQuarter = $this->find('first', array(
            'conditions' => array(
                'EduQuarter.edu_academic_year_id' => $quarter['EduAcademicYear']['id'],
                'EduQuarter.start_date >' => $quarter['EduQuarter']['start_date']
            ),
            'order' => 'EduQuarter.start_date'
        ));
        if (!empty($nextQuarter)) {
            return $nextQuarter;
        }
        return false;
    }

    public function getNextEducationalQuarter($id)
    {
        $quarter = $this->read(null, $id);
        $nextEducationalQuarter = $this->find('first', array(
            'conditions' => array(
                'EduQuarter.edu_academic_year_id' => $quarter['EduAcademicYear']['id'],
                'EduQuarter.start_date >' => $quarter['EduQuarter']['start_date'],
                'EduQuarter.quarter_type' => 'E'
            ),
            'order' => 'EduQuarter.start_date'
        ));
        if (!empty($nextEducationalQuarter)) {
            return $nextEducationalQuarter;
        }
        return false;
    }
}
