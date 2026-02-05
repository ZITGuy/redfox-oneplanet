<?php

class EduQuarterSummariesController extends EduAppController {

    var $name = 'EduQuarterSummaries';

    function index2($id = null) {
        $this->set('parent_id', $id);

        $edu_quarter = $this->EduQuarterSummary->EduQuarter->read(null, $id);
        $this->set('edu_quarter', $edu_quarter);
		
		$today = $this->today();
		$this->set('today', $today);
    }

    function summarize_quarter_automatic($queued_job) {
        $this->autoRender = false;
        // considering $queued_job['QueuedJob']['content'] is like '{"edu_quarter_summary_id":1}'
        // based on the content in $queued_job, summarize the quarter
        // or call the summarize_the_quarter function
        $content = json_decode($queued_job['QueuedJob']['content'], true);
        $id = $content['edu_quarter_summary_id'];
        $this->summarize_the_quarter($id);
    }
	
	// id is the Quarter Summary id
    public function summarize_the_quarter($id = null) {
		$this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid Quarter Summary', true));
            $this->redirect(array('action' => 'index'));
        }
		$this->loadModel('EduClass');
		$this->loadModel('EduQuarter');
		
        $this->EduQuarterSummary->recursive = 2;

        $qs = $this->EduQuarterSummary->read(null, $id);
        $this->EduQuarterSummary->set('status', 'RUNNING');
        if ($this->EduQuarterSummary->save()) {
            // call to the quarter summary function
			$class = $this->EduClass->read(null, $qs['EduQuarterSummary']['edu_class_id']);
			$quarter = $this->EduQuarter->getActiveQuarter();
			
			if ($this->summarize_quarter($quarter, $class) == 'T') {
				$this->EduQuarterSummary->read(null, $id);
				$this->EduQuarterSummary->set('status', 'COMPLETED');
				$this->EduQuarterSummary->save();
				
                $this->Session->setFlash(__('Quarter Class summarized Successfully', true), '');
                $this->render('/elements/success');
            } else {
				$this->EduQuarterSummary->read(null, $id);
				$this->EduQuarterSummary->set('status', 'PENDING');
				$this->EduQuarterSummary->save();
				
                $this->Session->setFlash(__('Quarter Class is not summarized. Please try again.', true), '');
                $this->render('/elements/failure');
            }
        } else {
            $this->Session->setFlash(__('The quarter summary cannot be done', true), '');
            $this->render('/elements/failure');
        }
    }
	
	public function do_summarize_quarter() {
		$this->autoRender = false;
        
		$quarter = $this->EduQuarter->getActiveQuarter();
        if (!empty($quarter)) {
            if($this->summarize_quarter($quarter) == 'T') {
                $this->Session->setFlash(__('Quarter summarized Successfully', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Quarter was not summarized Successfully', true), '');
                $this->render('/elements/failure');
            }
        } else {
			$this->Session->setFlash(__('No active Quarter', true), '');
			$this->render('/elements/failure');
		}
    }

    public function copyAssessments($sections, $quarterId) {
        // find all assessments from all the sections the current section
        // and copy them to the next quarter.
        $this->loadModel('EduAssessment');
        $this->loadModel('EduQuarter');
        
        $nq = $this->EduQuarter->getNextEducationalQuarter($quarterId);

        if ($nq !== false) {
            $assessmentCounts = $this->EduAssessment->find('count', array(
                'conditions' => array(
                    'EduAssessment.edu_section_id' => $sections[0]['EduSection']['id'],
                    'EduAssessment.edu_quarter_id' => $nq['EduQuarter']['id']
                )
            ));

            if ($assessmentCounts > 0) {
                // it is already maintained
            } else {
                $secionIds = array();
                foreach ($sections as $s) {
                    $secionIds[] = $s['EduSection']['id'];
                }
                $assessments = $this->EduAssessment->find('count', array(
                    'conditions' => array(
                        'EduAssessment.edu_section_id' => $secionIds,
                        'EduAssessment.edu_quarter_id' => $quarterId
                    )
                ));

                foreach ($assessments as $a) {
                    // create another with the new quarter id having the values of $a
                    $assessment = array('EduAsssessment' => array(
                        'edu_section_id' => $a['EduAssessment']['edu_section_id'],
                        'edu_quarter_id' => $nq['EduQuarter']['id'],
                        'edu_assessment_type_id' => $a['EduAssessment']['edu_assessment_type_id'],
                        'edu_teacher_id' => $a['EduAssessment']['edu_teacher_id'],
                        'max_value' => $a['EduAssessment']['max_value'],
                        'date' => $nq['EduQuarter']['start_date'],
                        'status' => 'S',
                        'submitted_at' => $nq['EduQuarter']['start_date'],
                        'checked_by_id' => 0,
                        'checked_at' => $nq['EduQuarter']['start_date'],
                        'approved_by_id' => 0,
                        'approved_at' => $nq['EduQuarter']['start_date'],
                        'detail' => '',
                        'edu_course_id' => $a['EduAssessment']['edu_course_id'],
                        'return_count' => 0,
                        'return_count_curr' => 0,
                        'user_id' => $a['EduAssessment']['user_id']
                    ));

                    $this->EduAssessment->create();
                    $this->EduAssessment->save($assessment);
                }
                
            }
        }
    }
	
	/**
	 * Objects: Quarter and Class as parameter
	 */
	public function summarize_quarter($quarter, $cl) {
        try {
            // block the system in busy state, then do the processing
			$this->setSystemSetting('SYSTEM_HEALTH', 'B');
            $id = $quarter['EduQuarter']['id'];
			
            $this->loadModel('EduClass');
            $this->loadModel('EduQuarter');
            $this->loadModel('EduSection');
            $this->loadModel('EduCourse');
            $this->loadModel('EduAssessment');
            $this->loadModel('EduExemption');
            $this->loadModel('EduAttendanceRecord');
            $this->loadModel('EduRegistration');
            $this->loadModel('EduRegistrationQuarter');
            $this->loadModel('EduRegistrationQuarterResult');

            $classes = $this->EduClass->find('all', array(
				'conditions' => array('EduClass.id' => $cl['EduClass']['id'])
			));
            
            foreach ($classes as $class) {
                $sections = $this->EduSection->find('all', array(
                    'conditions' => array(
                        'EduSection.edu_academic_year_id' => $quarter['EduQuarter']['edu_academic_year_id'],
                        'EduSection.edu_class_id' => $class['EduClass']['id']
                    )
                ));

                //$this->copyAssessments($sections, $quarter['EduQuarter']['id']);

				$section_averages = array();

                foreach ($sections as $section) {
                    $courses = $this->EduCourse->find('all', array(
                        'conditions' => array('EduCourse.edu_class_id' => $section['EduSection']['edu_class_id'])
                    ));

                    if ($class['EduClass']['grading_type'] <> 'G') {
						foreach ($courses as $course) {
                            $assessments = $this->EduAssessment->find('all', array(
                                'conditions' => array(
                                    'edu_quarter_id' => $id,
                                    'edu_section_id' => $section['EduSection']['id'],
                                    'edu_course_id' => $course['EduCourse']['id']
                                )
                            ));

                            $reg_quarter_results = array();
                            $total_assessment = 0;
                            foreach ($assessments as $assessment) {
                                foreach ($assessment['EduAssessmentRecord'] as $ar) {
									$mark = $ar['mark'];
									// if negative value -> change it to zero
									$mark = ($mark > 0? $mark: 0);
									// TODO: What about exempted courses for a student?
                                    if (isset($reg_quarter_results[$ar['edu_registration_id']])) {
                                        $reg_quarter_results[$ar['edu_registration_id']] += $mark;
                                    } else {
                                        $reg_quarter_results[$ar['edu_registration_id']] = $mark;
                                    }
                                }
                                $total_assessment += $assessment['EduAssessment']['max_value'];
                            }

                            arsort($reg_quarter_results);

                            $rank = 0;
							$rank_value = 0;
                            foreach ($reg_quarter_results as $r_id => $value) {
                                $rqs = $this->EduRegistrationQuarter->find('first', array(
                                        'conditions' => array(
                                            'edu_registration_id' => $r_id
                                        )
                                    )
                                );
                                if(count($rqs) < 3) {
                                    $this->log($r_id . ' reg id has missing reg quarters', 'reg_quarters');
                                    $this->saveRegistrationQuarters($r_id);
                                }
                                $rqs = $this->EduRegistrationQuarter->find('first', array(
                                        'conditions' => array(
                                            'edu_registration_id' => $r_id
                                        )
                                    )
                                );
                                $rq_ids_avaiable = array();
                                foreach($rqs as $x) {
                                    $rq_ids_avaiable[] = $x['EduRegistrationQuarter']['id'];
                                }

                                // read registration quarters
                                $rq = $this->EduRegistrationQuarter->find('first', array(
                                        'conditions' => array(
                                            'edu_quarter_id' => $id,
                                            'edu_registration_id' => $r_id
                                        )
                                    )
                                );

                                $course_result = (($value * 100)/$total_assessment);
                                $result_indicator = 'N';
                                if ($course_result >= $course['EduCourse']['min_for_pass']) {
                                    $result_indicator = 'P';
                                } else {
                                    $result_indicator = 'F';
                                }

                                $rqr = $this->EduRegistrationQuarterResult->find('first', array(
                                    'conditions' => array(
                                        'edu_registration_quarter_id' => $rq['EduRegistrationQuarter']['id'],
                                        'edu_course_id' => $course['EduCourse']['id']
                                    )
                                ));

                                // added to correct the missed rqr
                                if(empty($rqr)) {
                                    $rqr['EduRegistrationQuarterResult'] = array(
                                        'edu_registration_quarter_id' => $rq['EduRegistrationQuarter']['id'],
                                        'edu_course_id' => $course['EduCourse']['id'],
                                        'course_result' => 0,
                                        'scale_result' => '-',
                                        'course_rank' => 0,
                                        'result_indicator' => 'N',
                                        'teacher_comment' => '-'
                                    );
                                    $this->EduRegistrationQuarterResult->create();
                                    $this->EduRegistrationQuarterResult->save($rqr);

                                    //$rqr = array();
                                    $rqr = $this->EduRegistrationQuarterResult->find('first', array(
                                            'conditions' => array(
                                                'edu_registration_quarter_id' => $rq['EduRegistrationQuarter']['id'],
                                                'edu_course_id' => $course['EduCourse']['id']
                                            )
                                        ));
                                }

                                // fix if the rest of the quarter_results are missing
                                $rqrs = $this->EduRegistrationQuarterResult->find('all', array(
                                    'conditions' => array(
                                        'edu_registration_quarter_id' => $rq_ids_avaiable,
                                        'edu_course_id' => $course['EduCourse']['id']
                                    )
                                ));
                                $includeds = array();
                                foreach ($rqrs as $rqr_for_course) {
                                    $includeds[] = $rqr_for_course['EduRegistrationQuarterResult']['edu_registration_quarter_id'];
                                }

                                if(count($rqrs) < 3) {
                                    foreach($rq_ids_avaiable as $rq_id) {
                                        if(!in_array($rq_id, $includeds)) {
                                            $rqrx['EduRegistrationQuarterResult'] = array(
                                                'edu_registration_quarter_id' => $rq_id,
                                                'edu_course_id' => $course['EduCourse']['id'],
                                                'course_result' => 0,
                                                'scale_result' => '-',
                                                'course_rank' => 0,
                                                'result_indicator' => 'N',
                                                'teacher_comment' => '-'
                                            );
                                            $this->EduRegistrationQuarterResult->create();
                                            $this->EduRegistrationQuarterResult->save($rqrx);
                                        }
                                    }
                                }

                                // end fix

								if ($rank == 0) {
									$rank = 1;
									$rank_value = $course_result;
								} else {
									if ($rank_value > $course_result) {
										$rank++;
									}
								}
								
                                $this->EduRegistrationQuarterResult->read(
                                    null,
                                    $rqr['EduRegistrationQuarterResult']['id']
                                );

                                $scale = $this->getScale($course_result);
                                $this->EduRegistrationQuarterResult->set('course_result', $course_result);
                                $this->EduRegistrationQuarterResult->set('scale_result', $scale);
                                $this->EduRegistrationQuarterResult->set('course_rank', $rank);
                                $this->EduRegistrationQuarterResult->set('result_indicator', $result_indicator);
                                $this->EduRegistrationQuarterResult->save();
                            }
							
							//  0 and the course is exemped
							$rqrs = $this->EduRegistrationQuarterResult->find('all', array(
								'conditions' => array(
									'EduRegistrationQuarter.edu_quarter_id' => $id,
									'EduRegistrationQuarterResult.edu_course_id' => $course['EduCourse']['id']
								)
							));
							
							foreach ($rqrs as $my_rqr) {
								if ($my_rqr['EduRegistrationQuarterResult']['course_result'] == 0) {
									// check if the sudent is exempted for the course
									$my_reg = $this->EduRegistration->read(null, $my_rqr['EduRegistrationQuarter']['edu_registration_id']);
									$exemptions = $this->EduExemption->find('count', array(
										'conditions' => array(
											'EduExemption.edu_student_id' => $my_reg['EduRegistration']['edu_student_id'],
											'EduExemption.edu_course_id' => $my_rqr['EduRegistrationQuarterResult']['edu_course_id'],
											'EduExemption.edu_academic_year_id' => $section['EduSection']['edu_academic_year_id'],
                                            'EduExemption.edu_quarter_id' => array($id, 0)
										)
									));
									if ($exemptions > 0) {
										$this->EduRegistrationQuarterResult->read(null, $my_rqr['EduRegistrationQuarterResult']['id']);
										$this->EduRegistrationQuarterResult->set('result_indicator', 'NA');
										$this->EduRegistrationQuarterResult->save();
									}
								}
							}
                        }
                    }
					
                    $attendance_records = $this->EduAttendanceRecord->find('all', array(
                        'conditions' => array(
                            'EduAttendanceRecord.edu_section_id' => $section['EduSection']['id'],
                            'EduAttendanceRecord.edu_quarter_id' => $id
                        )
                    ));

                    $student_absentees = array();
                    foreach ($attendance_records as $ar) {
                        foreach ($ar['EduAbsentee'] as $absentee) {
                            if ($absentee['status'] == 'A') {
                                if (isset($student_absentees[$absentee['edu_student_id']])) {
                                    $student_absentees[$absentee['edu_student_id']] += 1;
                                } else {
                                    $student_absentees[$absentee['edu_student_id']] = 1;
                                }
                            }
                        }
                    }

                    $registrations = $this->EduRegistration->find('all', array(
                        'conditions' => array(
                            'EduRegistration.edu_section_id' => $section['EduSection']['id'],
                            'EduStudent.deleted' => false
                        )
                    ));
                    
                    // traverse over the list of students in a section
                    //  to calculate the absentees
                    // But for the time-being since this data is maintained manually,
                    // better make it off.
                    /*
                    foreach ($registrations as $registration) {
                        if (isset($student_absentees[$registration['EduRegistration']['edu_student_id']])){
                            $reg_quarter = $this->EduRegistrationQuarter->find('first', array(
                                'conditions' => array(
                                    'EduRegistrationQuarter.edu_registration_id' =>
                                        $registration['EduRegistration']['id'],
                                    'EduRegistrationQuarter.edu_quarter_id' => $id
                                )
                            ));

                            if ($reg_quarter) {
                                $this->EduRegistrationQuarter->read(null, $reg_quarter['EduRegistrationQuarter']['id']);
                                $this->EduRegistrationQuarter->set(
                                    'absentees',
                                    $student_absentees[$registration['EduRegistration']['edu_student_id']]);
                                $this->EduRegistrationQuarter->save();
                            }
                        }
                    }*/

                    // if the class is NOT a KG type,
                    if ($class['EduClass']['grading_type'] <> 'G') {
                        $averages = array();
                        foreach ($registrations as $registration) {
                            $reg_quarters = $this->EduRegistrationQuarter->find('all', array(
                                'conditions' => array(
                                    'EduRegistrationQuarter.edu_registration_id' =>
                                        $registration['EduRegistration']['id'],
                                    'EduRegistrationQuarter.edu_quarter_id' => $id
                                )
                            ));

                            // i.e, the 3 quarters of a student
                            foreach ($reg_quarters as $reg_quarter) {
                                $average = 0;
								$c_count = 0;
                                foreach ($reg_quarter['EduRegistrationQuarterResult'] as $rqr) {
                                    // exclude course results with value of -1
                                    $average += ($rqr['course_result'] > 0? $rqr['course_result']: 0);
									if ($rqr['result_indicator'] != 'NA') { // to exclude the exempted courses
										$c_count++;
                                    }
                                }
								// save the quarter total
								$this->EduRegistrationQuarter->read(null, $reg_quarter['EduRegistrationQuarter']['id']);
								$this->EduRegistrationQuarter->set('quarter_total', $average);
								$this->EduRegistrationQuarter->save();
								
                                $averages[$reg_quarter['EduRegistrationQuarter']['id']] = $average / $c_count;
                                $section_averages[$reg_quarter['EduRegistrationQuarter']['id']] = $average ;
                            }
                        }
                        arsort($averages);

                        // consider having equal result would result in equal rank
                        $ranks = array();
                        $rcount = 1;
                        foreach ($averages as $k => $v) {
                            if(!in_array($v, $ranks)) {
                                $ranks[$v] = $rcount++;
                            }
                        }

                        foreach ($averages as $k => $v) {
                            $this->EduRegistrationQuarter->read(null, $k);
                            $this->EduRegistrationQuarter->set('quarter_average', $v);
                            $this->EduRegistrationQuarter->set('quarter_rank', $ranks[$v]);
                            $this->EduRegistrationQuarter->save();
                        }
                    }
                }

                // arrange the quarter records for all students
                arsort($section_averages);

                $section_ranks = array();
                $section_rcount = 1;
                foreach ($section_averages as $k => $v) {
                    if(!in_array($v, $section_ranks)) {
                        $section_ranks[$v] = $section_rcount++;
                    }
                }

                foreach ($section_averages as $k => $v) {
                    $this->EduRegistrationQuarter->read(null, $k);
                    $this->EduRegistrationQuarter->set('class_rank', $section_ranks[$v]);
                    $this->EduRegistrationQuarter->save();
                }
            }
			// call summerize year if the qurter is the last educational
			// identify the quarter by reading all edu quarters from the ay
			// and sort by start_date and if the last item is equal to the
			// current one, then it is the last educational quarter
			$quarters = $this->EduQuarter->find('all', array(
				'conditions' => array(
					'EduQuarter.edu_academic_year_id' => $quarter['EduQuarter']['edu_academic_year_id'],
					'EduQuarter.quarter_type' => 'E' // educational
				), 'order' => 'EduQuarter.start_date'
			));

			$last_id = 0;
			foreach ($quarters as $q) {
				$last_id = $q['EduQuarter']['id'];
			}
			if ($quarter['EduQuarter']['id'] == $last_id) {
				$this->summarize_year($cl['EduClass']['id']);
			}
			
			// back the system in to normal state
			$this->setSystemSetting('SYSTEM_HEALTH', 'H');
            return 'T';
        } catch (Exception $ex) {
            $this->log($ex->getMessage(), "summary_error");
            return $ex->getMessage();
        }
    }

    public function saveRegistrationQuarters($id) {
        $reg = $this->EduRegistration->read(null, $id);
        $this->loadModel('Edu.EduQuarter');
        $this->loadModel('Edu.EduAcademicYear');
        $this->loadModel('Edu.EduRegistration');
        $this->loadModel('Edu.EduRegistrationQuarter');
        $this->loadModel('Edu.EduRegistrationQuarterResult');

        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $ay_id = $ay['EduAcademicYear']['id'];

        $quarters = $this->EduQuarter->find('all', array('conditions' => array(
                'EduQuarter.edu_academic_year_id' => $ay_id,
                'EduQuarter.quarter_type' => 'E')));
        
        foreach ($quarters as $quarter) {
            $rq = $this->EduRegistrationQuarter->find('first', array(
                'conditions' => array(
                    'EduRegistrationQuarter.edu_registration_id' => $id,
                    'EduRegistrationQuarter.edu_quarter_id' => $quarter['EduQuarter']['id']
                )
            ));
            $rq_id = 0;
            if (empty($rq)) {
                $rq_id = $this->createRegistrationQuarters($id, $quarter['EduQuarter']['id']);
            } else {
                $rq_id = $rq['EduRegistrationQuarter']['id'];
            }
            
            $class = $this->EduRegistration->EduClass->read(null, $reg['EduRegistration']['edu_class_id']);
            foreach ($class['EduCourse'] as $course) {
                $rqrs = $this->EduRegistrationQuarterResult->find('all',
                    array(
                        'conditions' => array(
                            'edu_registration_quarter_id' => $rq_id,
                            'edu_course_id' => $course['id']
                        )
                    )
                );
                if (empty($rqrs)) {
                    $rqr = array('EduRegistrationQuarterResult' => array(
                            'edu_registration_quarter_id' => $rq_id,
                            'edu_course_id' => $course['id'],
                            'course_result' => 0,
                            'course_rank' => 0,
                            'result_indicator' => 'N'
                    ));
                    $this->EduRegistrationQuarterResult->create();
                    if ($this->EduRegistrationQuarterResult->save($rqr)) {
                        $this->log($this->EduRegistrationQuarterResult->id, 'rqr_ids');
                    } else {
                        $this->log(pr($this->EduRegistrationQuarterResult->validationErrors, true), 'rqr_ids2');
                    }
                }
            }
        }
    }

    function createRegistrationQuarters($reg_id, $quarter_id) {
        $this->loadModel('EduRegistrationQuarter');

        $reg_q = array('EduRegistrationQuarter' => array(
            'edu_registration_id' => $reg_id,
            'edu_quarter_id' => $quarter_id,
            'quarter_average' => 0,
            'quarter_rank' => 0,
            'class_rank' => 0
        ));
        $this->EduRegistrationQuarter->create();
        if ($this->EduRegistrationQuarter->save($reg_q)) {
            return $this->EduRegistrationQuarter->id;
        }
        return 0;
    }
	
	public function summarize_year($class_id) {
        try {
            $this->log('In summarize_year try', 'yclose');

            $this->loadModel('EduAcademicYear');
			$this->loadModel('EduQuarter');
            $this->loadModel('EduClass');
            $this->loadModel('EduSection');
            $this->loadModel('EduRegistration');
            $this->loadModel('EduRegistrationQuarter');
            $this->loadModel('EduRegistrationResult');

            $ay = $this->EduAcademicYear->getActiveAcademicYear();
            $classes = $this->EduClass->find('all', array(
				'conditions' => array(
					'EduClass.id' => $class_id
				)
			));

            foreach ($classes as $class) {
                if ($class['EduClass']['grading_type'] == 'G') {
                    continue;
                }
                $this->log('In class foreach', 'yclose');
                $sections = $this->EduSection->find('all', array(
                    'conditions' => array(
                        'EduSection.edu_academic_year_id' => $ay['EduAcademicYear']['id'],
                        'EduSection.edu_class_id' => $class['EduClass']['id']
                        )
                ));

                $section_averages = array();

                foreach ($sections as $section) {
                    $registrations = $this->EduRegistration->find('all', array(
                            'conditions' => array(
                                'EduRegistration.edu_section_id' => $section['EduSection']['id'],
                                'EduStudent.deleted' => false
                            )
                        )
                    );
                    $mandatory_failures = array();
                    $course_failures = array();
                    $reg_averages = array();
                    foreach ($registrations as $registration) {
                        $this->log('In registrations foreach', 'yclose');
                        $reg_average = 0;
                        $course_averages = array();
                        $reg_counts = array();
                        $course_failures[$registration['EduRegistration']['id']] = 0;

                        foreach ($registration['EduRegistrationQuarter'] as $reg_quarter) {
                            $reg_average += $reg_quarter['quarter_average'];

                            $reg_quarter = $this->EduRegistrationQuarter->read(null,  $reg_quarter['id']);
                            $reg_count = 0;
                            foreach ($reg_quarter['EduRegistrationQuarterResult'] as $rqr) {
                                if(isset($course_averages[$rqr['edu_course_id']])){
                                    $course_averages[$rqr['edu_course_id']] += $rqr['course_result'];
                                    $reg_counts[$rqr['edu_course_id']] += ($rqr['course_result'] + 0 == 0? 0: 1);
                                }
                                else {
                                    $course_averages[$rqr['edu_course_id']] = $rqr['course_result'];
                                    $reg_counts[$rqr['edu_course_id']] = ($rqr['course_result'] + 0 == 0? 0: 1);
                                }
                            }
                        }

                        foreach ($course_averages as $k => $v) {
                            $course = $this->EduCourse->read(null, $k);
                            $av = ($v / $reg_counts[$k]);
                            $status = ($course['EduCourse']['min_for_pass'] > $av? 'F': 'P');
                            
							// TODO: consider exempted courses for the student
							//    and set the status to 'NA'
							
							if($status == 'F'){
                                $course_failures[$registration['EduRegistration']['id']] += 1;
                            }
                            if($status == 'F' && $course['EduCourse']['is_mandatory'] == 1){
                                $mandatory_failures[$registration['EduRegistration']['id']] = 1;
                            }
                            $reg_result = array('EduRegistrationResult' => array(
                                    'edu_registration_id' => $registration['EduRegistration']['id'],
                                    'edu_course_id' => $k,
                                    'average' => $av,
                                    'scale_result' => $this->getScale($av),   //??????????????
                                    'status' => $status
                                ));
                            $this->EduRegistrationResult->create();
                            $this->EduRegistrationResult->save($reg_result);
                        }

                        $grand_total_average = ($reg_average / count($registration['EduRegistrationQuarter']));
                        $reg_averages[$registration['EduRegistration']['id']] = $grand_total_average;
                        $section_averages[$registration['EduRegistration']['id']] = $grand_total_average;
                    }

                    arsort($reg_averages);
                    $rank = 1;
                    foreach ($reg_averages as $k => $v) {
                        $fails_to_dismissal = $this->getSystemSetting('FAILS_TO_DISMISSAL');
                        $status_id = 13;  // Promoted
                        $failure_count = 0;
                        $r = $this->EduRegistration->read(null, $k);
                        if($class['EduClass']['min_for_promotion'] > $v || isset($mandatory_failures[$r['EduRegistration']['id']]) || $class['EduClass']['max_failure_for_promotion'] < $course_failures[$r['EduRegistration']['id']]) {
                            $status_id = 14;  // Not Promoted
                            $failure_count = 1;
                        }
                        $allowed = 'A';
                        if($r['EduRegistration']['failure_count'] + $failure_count >= $fails_to_dismissal) {
                            $allowed = 'N';
                        }
                        $this->EduRegistration->read(null, $k);
                        $this->EduRegistration->set('grand_total_average', $v);
                        $this->EduRegistration->set('rank', $rank++);  // ?????????????????????
                        $this->EduRegistration->set('status_id', $status_id);
                        $this->EduRegistration->set('failure_count', $r['EduRegistration']['failure_count'] + $failure_count);
                        $this->EduRegistration->set('allowed', $allowed);
                        $this->EduRegistration->save();
                    }
                }
                arsort($section_averages);
                $rank = 1;
                foreach ($section_averages as $k => $v) {
                    $this->EduRegistration->read(null, $k);
                    $this->EduRegistration->set('class_rank', $rank++);
                    $this->EduRegistration->save();
                }
            }
            return 'T';
        } catch(Exception $ex){
            return $ex->getMessage();
        }
    }
	
	var $scales = array();
	
	function getScale($mark = 0) {
		if(count($this->scales) == 0) {
			// load the scales
			$this->loadModel('EduScale');
			$scs = $this->EduScale->find('all');
			
			foreach($scs as $sc) {
				$this->scales[$sc['EduScale']['scale']] = array('min' => $sc['EduScale']['min'], 'max' => $sc['EduScale']['max']);
			}
		}
		$scale = '-';
		foreach($this->scales as $v => $s) {
			if($s['max'] > $mark && $s['min'] <= $mark) {
				$scale = $v;
			}
		}
		return $scale;
	}
	
    function list_data($id = null) {
		$quarter_id = 0;
		if($id) {
			$quarter_id = $id;
		}
        
        $quarter_summaries = $this->EduQuarterSummary->find('all', array(
			'conditions' => array('EduQuarterSummary.edu_quarter_id' => $quarter_id), 
			'order' => 'EduQuarterSummary.edu_class_id ASC')
		);
		
		if(count($quarter_summaries) == 0 && $quarter_id > 0) {
			$classes = $this->EduQuarterSummary->EduClass->find('all', 
				array('conditions' => array('EduClass.grading_type <>' => 'G')));
			foreach($classes as $class) {
				$quarter_summary = array('EduQuarterSummary' => array(
					'edu_quarter_id' => $quarter_id,
					'edu_class_id' => $class['EduClass']['id'],
					'status' => 'PENDING'
				));
				$this->EduQuarterSummary->create();
				$this->EduQuarterSummary->save($quarter_summary);
			}
			
			$quarter_summaries = $this->EduQuarterSummary->find('all', array(
				'conditions' => array('EduQuarterSummary.edu_quarter_id' => $quarter_id), 
				'order' => 'EduQuarterSummary.edu_class_id ASC')
			);
		}

        $this->set('edu_quarter_summaries', $quarter_summaries);
        $this->set('results', count($quarter_summaries));
    }

}
