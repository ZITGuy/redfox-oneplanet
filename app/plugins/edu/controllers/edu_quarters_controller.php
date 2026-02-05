<?php

class EduQuartersController extends EduAppController {

    var $name = 'EduQuarters';

    function index() {
        $edu_academic_years = $this->EduQuarter->EduAcademicYear->find('all');
        $this->set(compact('edu_academic_years'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);

        $edu_academic_year = $this->EduQuarter->EduAcademicYear->read(null, $id);
        $this->set('edu_academic_year', $edu_academic_year);
		
		$today = $this->today();
		$this->set('today', $today);
    }

    function manage_quarters() {
        $this->layout = 'ajax';
        $ay = $this->EduQuarter->EduAcademicYear->getActiveAcademicYear();
		
        $this->set('ay', $ay);
    }
    
    function search() {
        // hbhk
    }

    function check_for_closing() {
        //
    }

    function open_quarter($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Quarter', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduQuarter->recursive = 2;

        $this->EduQuarter->read(null, $id);
        $this->EduQuarter->set('status_id', 1);
        if ($this->EduQuarter->save()) {
            $this->maintain_education_days($id, false);  // do not regenerate if they are already maintained
            $this->Session->setFlash(__('The quarter opened successfully', true), '');
            $this->render('/elements/success');
        } else {
            $this->Session->setFlash(__('The quarter is not opened successfully', true), '');
            $this->render('/elements/failure');
        }
    }

    function open_this_quarter($id) {
        try{
            $this->EduQuarter->recursive = 2;
            $q =$this->EduQuarter->read(null, $id);
            $qt = 'N';
            if($q['EduQuarter']['quarter_type'] == 'E') {
                $qt = 'E';
            }
            $this->EduQuarter->set('status_id', 1);
            if ($this->EduQuarter->save()) {
                if($qt == 'E'){
                    $this->maintain_education_days($id, false);  // do not regenerate if they are already maintained
                }
				$eod_response = $this->requestAction(array('controller' => 'eod_processes', 'action' => 'run_internal'));
                
				// TODO ???????
				return 'T';
            } else {
                throw new Exception("Error Processing Request", 1);
            }
        } catch(Exception $ex) {
            return $ex->getMessage();
        }
    }

    function close_quarter() {
        $this->EduQuarter->recursive = 2;
        $active_quarter = $this->EduQuarter->getActiveQuarter();
        $id = $active_quarter['EduQuarter']['id'];
        
		////////////////////////////////////////////
		////////////////////////////////////////////
		////////////////////////////////////////////
		// check if everything is OK
        //if(!$this->is_ready_for_closing()) {
        //    // if something is not ok
        //    $this->cakeError('cannotViewRecord', array(
        //        'message' => 'You cannot close the term while there is issue to be resolved first. (ERR-101-01)',
        //        'helpcode' => 'ERR-101-01'));
        //}

        // what is the quarter type: FN => first non-educational, E => educational or LN => last non educational?
        $qt = 'FN'; // FN, E, N, LN  --> $active_quarter['EduQuarter']['quarter_type'] = N or E
        // if the start_date of the quarter is equal to the start_date of the academic year
        // that is the FN, otherwise it is the LN
        if($active_quarter['EduQuarter']['quarter_type'] == 'E') {
            $qt = 'E';
        } else {
            if($active_quarter['EduAcademicYear']['start_date'] == $active_quarter['EduQuarter']['start_date']){
                $qt = 'FN';
            } elseif ($active_quarter['EduAcademicYear']['end_date'] == $active_quarter['EduQuarter']['end_date']) {
                $qt = 'LN';
            } else {
                $qt = 'N';
            }
        }
        $this->log('Quarter Type is ' . $qt, 'qclose');

        if($qt == 'N' || $qt == 'FN') {
            // Non-Educational ==> Just close it and open the next
            $next_quarter = $this->EduQuarter->getNextQuarter($id);
            if($next_quarter === FALSE){
                $this->cakeError('cannotViewRecord', array(
					'message' => 'The quarter is not closed successfully. (ERR-101-02)',
					'helpcode' => 'ERR-101-02'));
            } else {
                $this->EduQuarter->read(null, $id);
                $this->EduQuarter->set('status_id', 8); // CLOSED
                $this->EduQuarter->save();

                $response = $this->open_this_quarter($next_quarter['EduQuarter']['id']);
				// TODO: no the response 
				
                $this->Session->setFlash(__('The quarter is closed successfully', true), '');
                $this->render('/elements/success');
            }
        } elseif($qt == 'LN') {
            //$this->summarize_year();
            $this->loadModel('EduAcademicYear');

            $this->EduQuarter->read(null, $id);
            $this->EduQuarter->set('status_id', 8);
            $this->EduQuarter->save();

            $this->EduAcademicYear->read(null, $active_quarter['EduQuarter']['edu_academic_year_id']);
            $this->EduAcademicYear->set('status_id', 8);
            $this->EduAcademicYear->save();

            $aca_year = $this->EduAcademicYear->find('first', array(
                    'conditions' => array('EduAcademicYear.status_id' => 2)
                ));
            if(!empty($aca_year)){
                $this->EduAcademicYear->read(null, $aca_year['EduAcademicYear']['id']);
                $this->EduAcademicYear->set('status_id', 1);
                $this->EduAcademicYear->save();
            }

            $this->Session->setFlash(__('The quarter and the Academic year are closed successfully', true), '');
            $this->render('/elements/success');

        } elseif($qt == 'E') {
            try {
				$this->EduQuarter->recursive = 0;
				
				$this->EduQuarter->read(null, $id);
				$this->EduQuarter->set('status_id', 8);  // 8 - CLOSED
				if($this->EduQuarter->save()){
					$this->log('Q1 Status changed', 'qclose');
					
					$next_quarter = $this->EduQuarter->getNextQuarter($id);
					$this->open_this_quarter($next_quarter['EduQuarter']['id']);
					
					$this->Session->setFlash(__('The quarter is closed successfully', true), '');
					$this->render('/elements/success');
				} else {
					$this->log('Q1 Status not changed', 'qclose');
					$this->Session->setFlash(__('The quarter is not closed successfully', true), '');
					$this->render('/elements/failure');
				}
            } catch(Exception $ex) {
				$this->log('ERR: ' . $ex->getMessage(), 'qclose');
                $this->Session->setFlash(__('The quarter is not closed successfully', true) . ' ERR: ' . $ex->getMessage(), '');
                $this->render('/elements/failure'); // TODO: cakeError ???
            }
        }
    }
	

    function list_data_check_closing() {
        $active_quarter = $this->EduQuarter->getActiveQuarter();

        //pr($active_quarter);

        $this->loadModel('EduSection');
        $this->loadModel('EduCourse');
        

        $issues = array();
        
        $last_educational_quarter = false; // last educational quarter
        // what is the quarter type: first non-educational, educational or last non educational?
        $qt = 'FN'; // FN, E, N, LN  --> $active_quarter['EduQuarter']['quarter_type'] = N or E
        // if the start_date of the quarter is equal to the start_date of the academic year
        // that is the FN, otherwise it is the LN
        if($active_quarter['EduQuarter']['quarter_type'] == 'E') {
            $qt = 'E';
            $leq = $this->EduQuarter->getLastEducationalQuarter();
            if($active_quarter['EduQuarter']['id'] == $leq['EduQuarter']['id']){
                $last_educational_quarter =  true;
            }
        } else {
            if($active_quarter['EduAcademicYear']['start_date'] == $active_quarter['EduQuarter']['start_date']){
                $qt = 'FN';
            } elseif ($active_quarter['EduAcademicYear']['end_date'] == $active_quarter['EduQuarter']['end_date']) {
                $qt = 'LN';
            } else {
                $qt = 'N';
            }
        }
		
		//pr($qt);

        if($qt == 'N') {
            // nothing to do here ????
        } else {
            // common checks
            $ay_id = $active_quarter['EduAcademicYear']['id'];  //??????
            $sections = $this->EduSection->find('all', array(
                'conditions' => array(
                    'EduSection.edu_academic_year_id' => $ay_id,
					'EduClass.cvalue >=' => 4 
            )));
			
			$issues[] = array('issue' => 'All Days Are Occupied!', 'status' => $this->all_days_occupied($active_quarter));
            
            // specific checks for each of the quarter types
            if($qt == 'FN') {
                $sections_are_maintained = 'OK';
                if(empty($sections)){
                    $sections_are_maintained = 'NOT_OK';
                }

                $issues[] = array('issue' => 'Sections Are Maintained!', 'status' => $sections_are_maintained);
                $issues[] = array('issue' => 'Classes Are Maintained', 'status' => $this->classes_are_maintained());
                $this->loadModel('EduRegistration');

                $unsectioned_students = $this->EduRegistration->find('all', array('conditions' => array(
                        'EduRegistration.edu_section_id' => 0
                    )));
                
                if(count($unsectioned_students) > 0){
                    $issues[] = array('issue' => 'There is unsectioned student in class ' . $unsectioned_students[0]['EduClass']['name'], 'status' => 'NOT_OK');
                }
                $issues[] = array('issue' => 'Classes are with Sections', 'status' => $this->classes_with_sections($active_quarter));
                $issues[] = array('issue' => 'Sections are having Students', 'status' => $this->sections_with_students($active_quarter));
                $issues[] = array('issue' => 'Events are maintained', 'status' => $this->events_are_maintained($active_quarter));
            } elseif($qt == 'LN') {

            } else { 
                // educational (E)
                $this->loadModel('EduDay');
                $this->loadModel('EduAttendanceRecord');
                $this->loadModel('EduAssessment');
                $this->loadModel('EduEvaluation');
                $this->loadModel('EduRegistration');
                $this->loadModel('EduRegistrationEvaluation');

                // check for attendances
                $days = $this->EduDay->find('count', array(
                        'conditions' => array(
                            'EduDay.edu_quarter_id' => $active_quarter['EduQuarter']['id']
                        )
                    ));
                $attendance_status = 'OK';
                
                foreach ($sections as $section) {
                    $attendance_record_count = $this->EduAttendanceRecord->find('count', array(
                        'conditions' => array(
                                'EduAttendanceRecord.edu_section_id' => $section['EduSection']['id'],
                                'EduAttendanceRecord.edu_quarter_id' => $active_quarter['EduQuarter']['id'],
                                'EduAttendanceRecord.status' => 'S' // S=Submitted, N-Not submitted
                            )
                        ));
                    if($days > $attendance_record_count) {
                        $attendance_status = 'NOT_OK';
                        break;
                    }
                }
               // $issues[] = array('issue' => 'Attendances are taken', 'status' => $attendance_status);
                
                // Check for Evaluations
                $evaluation_status = 'OK';
                
                foreach ($sections as $section) {
                    $evaluations_count = $this->EduEvaluation->find('count', array('conditions' => array(
                            'EduEvaluation.edu_class_id' => $section['EduClass']['id']
                        )));
                    $student_count = $this->EduRegistration->find('count', array('conditions' => array(
                            'edu_section_id' => $section['EduSection']['id']
                        )));
                    $expected_count = $evaluations_count * $student_count;

                    $evaluation_record_count = $this->EduRegistrationEvaluation->find('count', array(
                        'conditions' => array(
                                'EduRegistration.edu_section_id' => $section['EduSection']['id'],
                                'EduRegistrationEvaluation.edu_quarter_id' => $active_quarter['EduQuarter']['id']
                            )
                        ));
                    if($expected_count > $evaluation_record_count) {
                        $evaluation_status = 'NOT_OK';
                        break;
                    }
                }
                $issues[] = array('issue' => 'Evaluations maintained', 'status' => $evaluation_status);

                // check for assessments if the classes are not evaluation_value
                $unsubmitted_assessments = $this->EduAssessment->find('count', array(
                        'conditions' => array(
                                'EduAssessment.status' => 'S',
                                'EduAssessment.edu_quarter_id' => $active_quarter['EduQuarter']['id']
                            )
                    ));
                $submitted_assessments = $this->EduAssessment->find('count', array(
                        'conditions' => array(
                                'EduAssessment.status' => 'SB',
                                'EduAssessment.edu_quarter_id' => $active_quarter['EduQuarter']['id']
                            )
                    ));
                if($unsubmitted_assessments + $submitted_assessments > 0){
                    $submitted_assessments_status = 'OK';
                    if($submitted_assessments == 0) {
                        $submitted_assessments_status = 'NOT_OK';
                    }
                    $issues[] = array('issue' => 'Assessments are maintained', 'status' => $submitted_assessments_status);

                    $assessment_status = 'OK';
                    if($unsubmitted_assessments > 0 || $submitted_assessments == 0) {
                        $assessment_status = 'NOT_OK';
                    }
                    $issues[] = array('issue' => 'Assessments are submitted', 'status' => $assessment_status);
                }
                
                // for each class (collect the sections if the class is not evaluation_value)
                // read(count assessments that holds the sections specified)

                foreach ($sections as $section) {
                    if($section['EduClass']['grading_type'] != 'G' && count($section['EduAssessment']) == 0){
                        $issues[] = array('issue' => 'Assessments are not maintained for section ' . $section['EduClass']['name'] . ' - ' . $section['EduSection']['name'], 'status' => 'NOT_OK');
                    }
                    if($section['EduClass']['grading_type'] == 'G') {
                        
						// ?????????? - consider the year summary is to be done in the conclusion quarter
						$reg_count = $this->EduRegistration->find('count', array(
                                'conditions' => array(
                                        'EduRegistration.edu_section_id' => $section['EduSection']['id'],
                                        'EduRegistration.status_id' => 1,
										'EduStudent.deleted' => false
                                    )
                            ));
                        if($reg_count > 0 && $last_educational_quarter) {
                            $issues[] = array('issue' => 'Promotion for section ' . $section['EduClass']['name'] . '-' . $section['EduSection']['name'] . ' is not run.', 'status' => 'NOT_OK');
                        }

						// check for evaluations
                        $regs = $this->EduRegistration->find('all', array(
                                'conditions' => array(
                                        'EduRegistration.edu_section_id' => $section['EduSection']['id'],
										'EduStudent.deleted' => false
                                    )
                            ));

                        $found = false;
                        foreach ($regs as $reg) {
                            if(count($reg['EduRegistrationEvaluation']) == 0){
                                $found = true;
                                break;
                            }
                        }
                        if($found) {
                            $issues[] = array('issue' => 'Evaluation for section ' . $section['EduClass']['name'] . ' - ' . $section['EduSection']['name'] . ' are not maintained.', 'status' => 'NOT_OK');
                        }
                    }
                }
				
				// check for pending summerizations
				$this->loadModel('EduQuarterSummary');
				
				$summaries = $this->EduQuarterSummary->find('count', array(
					'conditions' => array(
						'EduQuarterSummary.edu_quarter_id' => $active_quarter['EduQuarter']['id'],
						'EduQuarterSummary.status <>' => 'COMPLETED'
					)
				));
				
				if($summaries > 0) {
					$issues[] = array('issue' => $summaries . ' Result Summary is not done completely.', 'status' => 'NOT_OK');
				} else {
					$issues[] = array('issue' => 'Result Summary is done completely.', 'status' => 'OK');
				}
            }
        }
        
        $this->set('issues', $issues);
        $this->set('results', count($issues));
    }

    function is_ready_for_closing(){
        $active_quarter = $this->EduQuarter->getActiveQuarter();

        $this->loadModel('EduSection');
        $this->loadModel('EduCourse');
		
		// issue collector if there is any
        $issues = array();
        
        $last_educational_quarter = false; // last educational quarter
        // what is the quarter type: first non-educational, educational or last non educational?
        $qt = 'FN'; // FN, E, N, LN  --> $active_quarter['EduQuarter']['quarter_type'] = N or E
        // if the start_date of the quarter is equal to the start_date of the academic year
        // that is the FN, otherwise it is the LN
        if($active_quarter['EduQuarter']['quarter_type'] == 'E') {
            $qt = 'E';
            $leq = $this->EduQuarter->getLastEducationalQuarter();
            if($active_quarter['EduQuarter']['id'] == $leq['EduQuarter']['id']){
                $last_educational_quarter =  true;
            }
        } else {   // $qt = 'N' 
            if($active_quarter['EduAcademicYear']['start_date'] == $active_quarter['EduQuarter']['start_date']){
                $qt = 'FN';
            } elseif ($active_quarter['EduAcademicYear']['end_date'] == $active_quarter['EduQuarter']['end_date']) {
                $qt = 'LN';
            } else {
                $qt = 'N';
            }
        }

        if($qt == 'N') {
            // nothing to do here ????
        } else {
            // common checks
            $ay_id = $active_quarter['EduAcademicYear']['id'];  //??????
            $sections = $this->EduSection->find('all', array(
                'conditions' => array(
                    'EduSection.edu_academic_year_id' => $ay_id
            )));

            // specific checks for each of the quarter types
            if($qt == 'FN') {
                $sections_are_maintained = 'OK';
                if(empty($sections)){
                    return false;   // ???????
                }

                $issues[] = array('issue' => 'All Days Are Occupied!', 'status' => $this->all_days_occupied($active_quarter));
                $issues[] = array('issue' => 'Classes Are Maintained', 'status' => $this->classes_are_maintained());
                $this->loadModel('EduRegistration');

                $unsectioned_students = $this->EduRegistration->find('all', array('conditions' => array(
                        'EduRegistration.edu_section_id' => 0
                    )));
                
                if(count($unsectioned_students) > 0){
                    return false;   // ???????
                }
                $issues[] = array('issue' => 'Classes Are with Sections', 'status' => $this->classes_with_sections($active_quarter));
                $issues[] = array('issue' => 'Sections Are having Students', 'status' => $this->sections_with_students($active_quarter));
                $issues[] = array('issue' => 'Events are maintained', 'status' => $this->events_are_maintained($active_quarter));
				
            } elseif($qt == 'LN') {

            } else { 
                // educational (E)
                $this->loadModel('EduDay');
                $this->loadModel('EduAttendanceRecord');
                $this->loadModel('EduAssessment');
                $this->loadModel('EduEvaluation');
                $this->loadModel('EduRegistration');
                $this->loadModel('EduRegistrationEvaluation');

                // check for attendances
                $days = $this->EduDay->find('count', array(
                        'conditions' => array(
                            'EduDay.edu_quarter_id' => $active_quarter['EduQuarter']['id']
                        )
                    ));
                $attendance_status = 'OK';
                
                foreach ($sections as $section) {
                    $attendance_record_count = $this->EduAttendanceRecord->find('count', array(
                        'conditions' => array(
                                'EduAttendanceRecord.edu_section_id' => $section['EduSection']['id'],
                                'EduAttendanceRecord.edu_quarter_id' => $active_quarter['EduQuarter']['id'],
                                'EduAttendanceRecord.status' => 'S' // S=Submitted, N-Not submitted
                            )
                        ));
					
                    if($days > $attendance_record_count) {
						$this->log('Error: There is a day for which attendance is not taken for the section ' . $section['EduClass']['name'] . '-' . $section['EduSection']['name'], 'eoq');
                        $attendance_status = 'NOK';
						//return false;
                    }
                }
				$this->log('Attendances are take', 'eoq');
                $issues[] = array('issue' => 'Attendances are taken', 'status' => $attendance_status);
                
                // Check for Evaluations
                $evaluation_status = 'OK';
                
                foreach ($sections as $section) {
                    $evaluations_count = $this->EduEvaluation->find('count', array('conditions' => array(
                            'EduEvaluation.edu_class_id' => $section['EduClass']['id']
                        )));
                    $student_count = $this->EduRegistration->find('count', array('conditions' => array(
                            'edu_section_id' => $section['EduSection']['id'],
							'EduStudent.deleted' => false
                        )));
                    $expected_count = $evaluations_count * $student_count;

                    $evaluation_record_count = $this->EduRegistrationEvaluation->find('count', array(
                        'conditions' => array(
                                'EduRegistration.edu_section_id' => $section['EduSection']['id'],
                                'EduRegistrationEvaluation.edu_quarter_id' => $active_quarter['EduQuarter']['id']
                            )
                        ));
                    if($expected_count > $evaluation_record_count) {
						$this->log('Expected no of evaluation records is less in ' . $section['EduClass']['name'] . '-' . $section['EduSection']['name'], 'eoq');
                        $evaluation_status = 'NOK';
						//return false;
                    }
                }
				$this->log('Evaluations maintained', 'eoq');
                
                $issues[] = array('issue' => 'Evaluations maintained', 'status' => $evaluation_status);
				
                // check for assessments if the classes are not evaluation_value
                $unsubmitted_assessments = $this->EduAssessment->find('count', array(
                        'conditions' => array(
                                'EduAssessment.status' => 'S',
                                'EduAssessment.edu_quarter_id' => $active_quarter['EduQuarter']['id']
                            )
                    ));
                $submitted_assessments = $this->EduAssessment->find('count', array(
                        'conditions' => array(
                                'EduAssessment.status' => 'SB',
                                'EduAssessment.edu_quarter_id' => $active_quarter['EduQuarter']['id']
                            )
                    ));
                if($unsubmitted_assessments + $submitted_assessments > 0){
                    $submitted_assessments_status = 'OK';
                    if($submitted_assessments == 0) {
                        $submitted_assessments_status = 'NOK';
						//return false;
                    }
					if($unsubmitted_assessments > 0 || $submitted_assessments == 0) {
                        $submitted_assessments_status = 'NOK';
					    //return false;
                    }
                    $issues[] = array('issue' => 'Assessments are maintaned', 'status' => $submitted_assessments_status);

                }
                
                // for each class (collect the sections if the class is not evaluation_value)
                // read(count assessments that holds the sections specified)

                foreach ($sections as $section) {
                    if($section['EduClass']['grading_type'] != 'G' && count($section['EduAssessment']) == 0){
                        return false;  //???????
                    }
                    if($section['EduClass']['grading_type'] == 'G') {
                        $reg_count = $this->EduRegistration->find('count', array(
                                'conditions' => array(
                                        'EduRegistration.edu_section_id' => $section['EduSection']['id'],
                                        'EduRegistration.status_id' => 1,
										'EduStudent.deleted' => false
                                    )
                            ));
                        if($reg_count > 0 && $last_educational_quarter) {
                            return false;
                        }

                        $regs = $this->EduRegistration->find('all', array(
                                'conditions' => array(
                                        'EduRegistration.edu_section_id' => $section['EduSection']['id'],
										'EduStudent.deleted' => false
                                    )
                            ));

                        $found = false;
                        foreach ($regs as $reg) {
                            if(count($reg['EduRegistrationEvaluation']) == 0){
                                $found = true;
                                break;
                            }
                        }
                        if($found) {
							$this->log('EduRegistrationEvaluation is not done for ' . $section['EduClass']['name'] . '-' . $section['EduSection']['name'], 'eoq');
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    function all_days_occupied($active_quarter){
        $edu_academic_year = $this->EduQuarter->EduAcademicYear->read(null, $active_quarter['EduAcademicYear']['id']);
        $empty_start = $edu_academic_year['EduAcademicYear']['start_date'];
        $empty_end = $edu_academic_year['EduAcademicYear']['end_date'];
        $quarters =  $this->EduQuarter->find('all', array('conditions' => array('EduQuarter.edu_academic_year_id' => $active_quarter['EduAcademicYear']['id']), 'order' => 'EduQuarter.start_date'));
        $this->log($quarters, 'occupied');
		//pr($active_quarter['EduAcademicYear']['id']);
        foreach($quarters as $q) {
            if($q['EduQuarter']['start_date'] == $empty_start){
                $empty_start = date('Y-m-d', strtotime($q['EduQuarter']['end_date'] . ' +1 day'));
            } else {
                // there is day gap here
                $empty_end = date('Y-m-d', strtotime($q['EduQuarter']['start_date'] . ' -1 day'));
                break;
            }
        }
        //pr('End: ' . $empty_end . ' Start: ' . $empty_start);

        return ($empty_end <= $empty_start)? 'OK': 'NOT_OK';
    }

    function classes_are_maintained() {
        $this->loadModel('EduClass');
        $classes = $this->EduClass->find('count');

        return $classes > 0? 'OK': 'NOT_OK';
    }

    function events_are_maintained($active_quarter) {
        $edu_academic_year = $this->EduQuarter->EduAcademicYear->read(null, $active_quarter['EduAcademicYear']['id']);
        $quarters = $this->EduQuarter->find('all', array('conditions' => array('EduQuarter.edu_academic_year_id' => $active_quarter['EduAcademicYear']['id']), 'order' => 'EduQuarter.start_date'));
        foreach ($quarters as $quarter) {
            if(count($quarter['EduCalendarEvent']) == 0) {
				pr($quarter);
                return 'NOT_OK';
            }
        }
		
        return 'OK';
    }

    function classes_with_sections($active_quarter){
        $this->loadModel('EduClass');
        $this->loadModel('EduSection');
		$this->EduClass->recursive = -1;
        $classes = $this->EduClass->find('all');
		
		$edu_academic_year = $this->EduQuarter->EduAcademicYear->read(null, $active_quarter['EduAcademicYear']['id']);
        foreach ($classes as $class) {
			if($class['EduClass']['id'] > 15) continue;
            $sections = $this->EduSection->find('count', array('conditions' => array(
                    'EduSection.edu_class_id' => $class['EduClass']['id'],
                    'EduSection.edu_academic_year_id' => $edu_academic_year['EduAcademicYear']['id'])
                ));
            if($sections == 0) {
                return 'NOT_OK';
            }
        }
        return 'OK';

    }

    function sections_with_students($active_quarter){
        $this->loadModel('EduClass');
        $this->loadModel('EduSection');
        $edu_academic_year = $this->EduQuarter->EduAcademicYear->read(null, $active_quarter['EduAcademicYear']['id']);
        $sections = $this->EduSection->find('all', array('conditions' => array('EduSection.edu_academic_year_id' => $edu_academic_year['EduAcademicYear']['id'])));
        if(empty($sections)){
            return 'NOT_OK';
        }
        foreach ($sections as $section) {
            if(count($section['EduRegistration']) == 0) {
                return 'NOT_OK';
            }
        }
        return 'OK';

    }

    function list_data($id = null) {
        $start = filter_input(INPUT_GET, 'start') !== FALSE ? filter_input(INPUT_GET, 'start') : 0;
        $limit = filter_input(INPUT_GET, 'limit') !== FALSE ? filter_input(INPUT_GET, 'limit') : 5;
        $edu_academic_year_id = filter_input(INPUT_GET, 'edu_academic_year_id') !== FALSE ? filter_input(INPUT_GET, 'edu_academic_year_id') : -1;
        if ($id) {
            $edu_academic_year_id = ($id) ? $id : -1;
        }
        $conditions = filter_input(INPUT_GET, 'conditions') !== FALSE ? filter_input(INPUT_GET, 'conditions') : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_academic_year_id != -1) {
            $conditions['EduQuarter.edu_academic_year_id'] = $edu_academic_year_id;
        }
        $quarters = $this->EduQuarter->find('all', array('conditions' => $conditions, 'order' => 'EduQuarter.start_date ASC'));

        $openable_quarter = $this->EduQuarter->find('first', array(
            'conditions' => array(
                    'EduQuarter.status_id' => 9, // 9 = Created
                    'EduQuarter.edu_academic_year_id' => $edu_academic_year_id), 
            'order' => 'EduQuarter.start_date ASC'
        ));
		$q = $this->EduQuarter->getActiveQuarter();
		
		$this->loadModel('EduAssessment');
		$assessment_count = $this->EduAssessment->find('count', array(
                                'conditions' => array(
                                    'edu_quarter_id' => $q['EduQuarter']['id'],
                                    'status' => 'S'
                                )
                            ));
		$all_assessment_count = $this->EduAssessment->find('count', array(
                                'conditions' => array(
                                    'edu_quarter_id' => $q['EduQuarter']['id']
                                )
                            ));
		$summarizable = 1; //($assessment_count > 0? 0: ($all_assessment_count > 0? 1: 0)); // 1;
		$nonsummarizable = ($assessment_count > 0? 1: 0);
		
        $openable_quarter_id = 0;
        if (isset($openable_quarter['EduQuarter']['id'])) {
            $openable_quarter_id = $openable_quarter['EduQuarter']['id'];
        }

        foreach($quarters as &$quarter){
            if($quarter['EduQuarter']['status_id'] == 1)
                $openable_quarter_id = 0;
			if($quarter['EduQuarter']['id'] == $q['EduQuarter']['id'] && $q['EduQuarter']['quarter_type'] == 'E')
				$quarter['EduQuarter']['summarizable'] = $summarizable;
			else 
				$quarter['EduQuarter']['summarizable'] = $nonsummarizable;
        }

        $this->set('openable_quarter_id', $openable_quarter_id);
        $this->set('edu_quarters', $quarters);
        $this->set('results', $this->EduQuarter->find('count', array('conditions' => $conditions)));
    }

    // called for correction of marks
    function list_data_combo() {
        $this->loadModel('Edu.EduSection');
        $edu_section_id = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : -1;
        $quarter_type = (isset($_REQUEST['quarter_type'])) ? $_REQUEST['quarter_type'] : 'E';

        $section = $this->EduSection->read(null, $edu_section_id);
        $edu_academic_year_id = $section['EduSection']['edu_academic_year_id'];

        $conditions = '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_academic_year_id != -1) {
            $conditions['EduQuarter.edu_academic_year_id'] = $edu_academic_year_id;
        }
        $conditions['EduQuarter.quarter_type'] = 'E'; //$quarter_type;
        $conditions['EduQuarter.status_id'] = 8; // closed quarters

        $this->log($conditions, 'quarter_combo');

        $quarters = $this->EduQuarter->find('all', array('conditions' => $conditions, 'order' => 'EduQuarter.start_date ASC'));
		$this->log($quarters, 'quarter_combo');

        $this->set('edu_quarters', $quarters);
        $this->set('results', $this->EduQuarter->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Quarter', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduQuarter->recursive = 2;
        $this->set('edu_quarter', $this->EduQuarter->read(null, $id));
    }

    /**
     * id - quarter_id int
     * 
     */
    function maintain_education_days($id = null, $regenerate = true) {
        $edu_quarter = $this->EduQuarter->read(null, $id);
        $this->loadModel('Edu.EduDay');

        $days = $this->EduDay->find('all', array(
                'conditions' => array(
                        'EduDay.edu_quarter_id' => $id
                    )
            ));
		if($regenerate) {
			foreach ($days as $day) {
				$this->EduDay->delete($day['EduDay']['id']);
			}
		} elseif(count($days) > 0) {
			return 'Days are already maintained'; 
		}

        $date = $edu_quarter['EduQuarter']['start_date'];
        while($date <= $edu_quarter['EduQuarter']['end_date']) {
            // if the date is not a weekend, add it to the list
            if(date('N', strtotime($date)) <= 5) {
                $edu_day = array('EduDay' => array(
                        'date' => $date,
                        'week_day' => date('N', strtotime($date)),
                        'edu_quarter_id' => $id,
                        'is_active' => !$this->isHoliday($date) // setting the status whether the date
                        // is active or holiday. Day of holiday may be changed to educational 
                        // in the process of Holiday Maintenance. 
                    ));
                $this->EduDay->create();
                $this->EduDay->save($edu_day); 
            }
            //$this->log($date, 'debug');
            $date = date('Y-m-d', strtotime($date . ' +1 day'));
            //$this->log($date, 'debug');
        }
        return true;
    }

    function maintain_edu_days($id = null, $regenerate = true) {
        $this->autoRender = false;

        $return = $this->maintain_education_days($id, $regenerate);
        if($return === true) {
            $this->Session->setFlash(__('Quarter Education Days are maintained successfully.', true), '');
            $this->render('/elements/success');
        } else {
            $this->cakeError('cannotSaveRecord', array(
                    'message' => 'Operation not successful. ' . $return,
                    'helpcode' => 'ERR-201-1234'));
        }
    }

    function is_holyday($date) {
        // TODO: Doing the check from settings
        if(date('N', strtotime($date)) > 5) // that is Saturday 6 and Sunday 7
            return true;
        return false;
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->data['EduQuarter']['status_id'] = 9; // 9=Created, 1=Active/Open, 8=Closed
            $this->data['EduQuarter']['user_id'] = $this->Session->read('Auth.User.id');
            $this->EduQuarter->create();
            $this->autoRender = false;
            $ay = $this->EduQuarter->EduAcademicYear->read(null, $this->data['EduQuarter']['edu_academic_year_id']);
            $first = true;
            if(count($ay['EduQuarter']) > 0){
                $first = false;
            }
            Configure::write('audit_desc', $this->data['EduQuarter']['name'] . ' is created on ' . date('F j, Y'));
            
			// TODO: Make sure that the second Quarter must always be 'Educational'
            
			if ($this->EduQuarter->save($this->data)) {
                if($first) {
                    $this->open_this_quarter($this->EduQuarter->id);
                    $this->Session->setFlash(__('The Term created and opened successfully', true), '');
                } else {
                    $this->Session->setFlash(__('The Term created successfully', true), '');
                }
                
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The quarter could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id) {
            $this->set('parent_id', $id);
			$ay = $this->EduQuarter->EduAcademicYear->read(null, $id);
            $this->set('edu_academic_year', $ay);
			$quarters =  $this->EduQuarter->find('all', array(
				'conditions' => array(
					'EduQuarter.edu_academic_year_id' => $ay['EduAcademicYear']['id']), 
				'order' => 'EduQuarter.start_date'));
			$this->set('edu_quarters', $quarters);
        }
        $edu_academic_years = $this->EduQuarter->EduAcademicYear->find('list');
        $this->set(compact('edu_academic_years'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Quarter', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            Configure::write('audit_desc', $this->data['EduQuarter']['name'] . ' is updated on ' . date('F j, Y'));
            if ($this->EduQuarter->save($this->data)) {
                $this->Session->setFlash($this->data['EduQuarter']['name'] . __(' is saved successfully.', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The Quarter could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_quarter', $this->EduQuarter->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
            $ay = $this->EduQuarter->EduAcademicYear->read(null, $parent_id);
            $this->set('edu_academic_year', $ay);
			$quarters =  $this->EduQuarter->find('all', array(
				'conditions' => array(
					'EduQuarter.edu_academic_year_id' => $ay['EduAcademicYear']['id']), 
				'order' => 'EduQuarter.start_date'));
			$this->set('edu_quarters', $quarters);
        }
        $edu_academic_years = $this->EduQuarter->EduAcademicYear->find('list');
        $this->set(compact('edu_academic_years'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Quarter', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduQuarter->delete($i);
                }
                $this->Session->setFlash(__('Quarter deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Quarter was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduQuarter->delete($id)) {
                $this->Session->setFlash(__('Quarter deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Quarter was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}
