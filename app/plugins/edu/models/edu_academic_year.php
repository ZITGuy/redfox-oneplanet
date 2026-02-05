<?php

class EduAcademicYear extends EduAppModel {

    var $name = 'EduAcademicYear';
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
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $hasMany = array(
        'EduSection' => array(
            'className' => 'Edu.EduSection',
            'foreignKey' => 'edu_academic_year_id',
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
        'EduQuarter' => array(
            'className' => 'Edu.EduQuarter',
            'foreignKey' => 'edu_academic_year_id',
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
        'User' => array(
            'className' => 'Status',
            'foreignKey' => 'status_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    function getActiveAcademicYear() {
        $ay = $this->find('first', array('conditions' => array('EduAcademicYear.status_id' => 1)));
        if (!empty($ay)) {
            return $ay;
        }
        return FALSE;
    }

    /**
     * Gets the previous academic year (i.e. the one that finished most recently)
     *
     * @return mixed The previous academic year, or false if there is no previous academic year
     */
    function getPreviousAcademicYear() {
        $ay = $this->find('first', array('conditions' => array('EduAcademicYear.status_id' => 8), 'order' => 'EduAcademicYear.start_date DESC'));

        if (!empty($ay)) {
            return $ay;
        }
        return FALSE;
    }
    
    function isEducationalDay($date) {
        $week_day = date('N', strtotime($date));
        if ($week_day > 5) {
            return FALSE;
        }
        $this->loadModel('Holiday');
        $this->loadModel('EduCalendarEvent');
        $holiday = $this->Holiday->find('first', array(
            'conditions' => array('Holiday.date' => $date)
        ));
        if (!empty($holiday)) {
            return FALSE;
        }
        $events = $this->EduCalendarEvent->find('all', array(
            'conditions' => array('EduCalendarEvent.edu_calendar_event_type_id' => 3)
        ));
        if(empty($events)){
            return FALSE;
        } else {
            foreach($events as $event){
                if($event['EduCalendarEvent']['start_date'] <= $date && 
                        $event['EduCalendarEvent']['end_date'] >= $date){
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

}
