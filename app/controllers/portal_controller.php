<?php
class PortalController extends AppController {

	var $name = 'Portal';

    var $helpers = array('Html', 'Session');
    var $uses = array();

    function migrate1() {
        // Migrate all data related to: 
        //  - Classes, Courses, and Subjects

        $message = 'Group 1 data migrated';
		$this->set('message',  $message);
	}
	
	function migrate2($id = null) {
        // Migrate all data related to:
        //  - Academic Years, Sections, Terms/Quarters, Assessments, Assessment Records, 
        //    Assessment Types, Calendar events, calendar event types, Scales, and Section Teachers
		 $message = 'Group 2 data migrated';
		$this->set('message',  $message);
	}

    function migrate3($id = null) {
        // Migrate all data related to:
        //  - Evaluations, Evaluation areas, Evaluation Categories, Evaluation Values
		 $message = 'Group 3 data migrated';
		$this->set('message',  $message);
	}

    function migrate4($id = null) {
        // Migrate data related to:
        //  - Registrations, registration evaluations, registration quarters, registration quarter results, 
        //    registration results, student conditions and student_status,
		 $message = 'Group 4 data migrated';
		$this->set('message',  $message);
	}

    function migrate_achievement_reports($id = null) {
        // Migrate data related to:
        //  - Achievement Reports
		 $message = 'Achievement Reports data migrated';
		$this->set('message',  $message);
	}

}