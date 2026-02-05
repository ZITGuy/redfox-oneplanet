<?php

class EduCorrectionsController extends EduAppController {

    public $name = 'EduCorrections';

    public function index_m()
    {
        // empty body for index_m
    }
   
    public function index_o()
    {
        // empty body for index_o
    }

    public function search()
    {
        // empty body for search
    }

    public function list_data_m() {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';
        eval("\$conditions = array( " . $conditions . " );");

        $conditions['EduCorrection.status'] = array('CREATED', 'SUBMITTED', 'REJECTED');
        $this->EduCorrection->recursive = 2;
        
        $this->EduCorrection->EduSection->unbindModel(array(
            'belongsTo' => array('EduAcademicYear', 'EduTeacher', 'EduCampus'),
            'hasMany' => array('EduAssessment', 'EduAssignment', 'EduRegistration')
        ));
        $this->EduCorrection->EduCourse->unbindModel(array(
            'belongsTo' => array('EduClass', 'EduSubject'),
            'hasMany' => array('EduAssessment', 'EduPeriod', 'EduOutline', 'EduCourseItem')
        ));
        $this->EduCorrection->EduRegistration->unbindModel(array(
            'belongsTo' => array('EduClass', 'EduStudent', 'EduSection', 'EduCampus', 'Status'),
            'hasMany' => array('EduRegistrationResult', 'EduRequiredDocument',
                'EduRegistrationQuarter', 'EduRegistrationEvaluation')
        ));
        $this->EduCorrection->EduAssessment->unbindModel(array(
            'belongsTo' => array('EduTeacher', 'EduSection',
                'EduCourse', 'EduQuarter', 'User', 'CheckedBy', 'ApprovedBy'),
            'hasMany' => array('EduAssessmentRecord')
        ));
        $this->EduCorrection->unbindModel(array(
            'hasMany' => array('EduAssessmentRecord')
        ));

        $corrections = $this->EduCorrection->find('all', array(
            'conditions' => $conditions, 'limit' => $limit, 'offset' => $start));
        $this->set('edu_corrections', $corrections);
        $this->set('results', $this->EduCorrection->find('count', array('conditions' => $conditions)));
    }

    public function list_data_o() {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';
        eval("\$conditions = array( " . $conditions . " );");

        $conditions['EduCorrection.status'] = array('SUBMITTED');
        $this->EduCorrection->recursive = 2;
        
        $this->EduCorrection->EduSection->unbindModel(array(
            'belongsTo' => array('EduAcademicYear', 'EduTeacher', 'EduCampus'),
            'hasMany' => array('EduAssessment', 'EduAssignment', 'EduRegistration')
        ));
        $this->EduCorrection->EduCourse->unbindModel(array(
            'belongsTo' => array('EduClass', 'EduSubject'),
            'hasMany' => array('EduAssessment', 'EduPeriod', 'EduOutline', 'EduCourseItem')
        ));
        $this->EduCorrection->EduRegistration->unbindModel(array(
            'belongsTo' => array('EduClass', 'EduStudent', 'EduSection', 'EduCampus', 'Status'),
            'hasMany' => array('EduRegistrationResult', 'EduRequiredDocument',
                'EduRegistrationQuarter', 'EduRegistrationEvaluation')
        ));
        $this->EduCorrection->EduAssessment->unbindModel(array(
            'belongsTo' => array('EduTeacher', 'EduSection',
                'EduCourse', 'EduQuarter', 'User', 'CheckedBy', 'ApprovedBy'),
            'hasMany' => array('EduAssessmentRecord')
        ));
        $this->EduCorrection->unbindModel(array(
            'hasMany' => array('EduAssessmentRecord')
        ));

        $corrections = $this->EduCorrection->find('all', array(
            'conditions' => $conditions, 'limit' => $limit, 'offset' => $start));
        $this->set('edu_corrections', $corrections);
        $this->set('results', $this->EduCorrection->find('count', array('conditions' => $conditions)));
    }

    public function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid correction', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduCorrection->recursive = 2;
        $this->set('correction', $this->EduCorrection->read(null, $id));
    }

    public function add($id = null) {
        if (!empty($this->data)) {
            $reg_id = $this->data['EduCorrection']['edu_registration_id'];
            $ass_id = $this->data['EduCorrection']['edu_assessment_id'];
            $parts = explode('-', $reg_id);
            if(count($parts) > 0) {
                $reg_id = $parts[0];
                $this->data['EduCorrection']['edu_registration_id'] = $reg_id;
            }
            $this->loadModel('EduAssessmentRecord');
            $ar = $this->EduAssessmentRecord->find('first', array('conditions' => array(
                'edu_registration_id' => $reg_id,
                'edu_assessment_id' => $ass_id
            )));
            if(!empty($ar)) {
                $this->data['EduCorrection']['edu_assessment_record_id'] = $ar['EduAssessmentRecord']['id'];
            }
            // status
            $this->data['EduCorrection']['status'] = 'CREATED';
            // rejection_reason
            $this->data['EduCorrection']['rejection_reason'] = '-';
            
            $this->EduCorrection->create();
            $this->autoRender = false;
            if ($this->EduCorrection->save($this->data)) {
                $this->Session->setFlash(__('The correction has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The correction could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->loadModel('EduClass');
        $this->EduClass->recursive = 1;
        
        $edu_classes = $this->EduClass->find('list');
        $this->set(compact('edu_classes'));
    }

    public function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid correction', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduCorrection->save($this->data)) {
                $this->Session->setFlash(__('The correction has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The correction could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('correction', $this->EduCorrection->read(null, $id));
    }

    public function submit_correction($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid correction', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->data['EduCorrection']['status'] = 'SUBMITTED';
            $this->autoRender = false;
            if ($this->EduCorrection->save($this->data)) {
                $this->Session->setFlash(__('The correction has been submitted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The correction could not be submitted. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('correction', $this->EduCorrection->read(null, $id));
    }

    public function approve_correction($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid correction', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->data['EduCorrection']['status'] = 'APPROVED';
            $this->autoRender = false;
            if ($this->EduCorrection->save($this->data)) {
                // what about changing the actual values
                // load required models
                $this->loadModel('EduAssessmentRecord');
                $this->loadModel('EduAssessment');
                $this->loadModel('EduRegistrationQuarter');
                $this->loadModel('EduRegistrationQuarterResult');
                $this->loadModel('EduRegistrationResult');
                $this->loadModel('EduCourse');

                // 1. Update the Assessment Record
                $correction = $this->EduCorrection->read(null, $this->data['EduCorrection']['id']);

                $this->EduAssessmentRecord->read(null, $correction['EduCorrection']['edu_assessment_record_id']);
                $this->EduAssessmentRecord->set('mark', $correction['EduCorrection']['new_value']);
                $this->EduAssessmentRecord->save();

                //    --- FROM SUMMARIZE RESULT ---
                // 2. Collect all ARs of the student for the course and
                $assessments = $this->EduAssessment->find('all', array('conditions' => array(
                    'EduAssessment.edu_section_id' => $correction['EduCorrection']['edu_section_id'],
                    'EduAssessment.edu_quarter_id' => $correction['EduCorrection']['edu_quarter_id'],
                    'EduAssessment.edu_course_id' => $correction['EduCorrection']['edu_course_id']
                )));
                $ass_ids = array();
                foreach ($assessments as $assessment) {
                    $ass_ids[] = $assessment['EduAssessment']['id'];
                }
                $ars = $this->EduAssessmentRecord->find('all', array('conditions' => array(
                    'EduAssessmentRecord.edu_assessment_id' => $ass_ids,
                    'EduAssessmentRecord.edu_registration_id' => $correction['EduCorrection']['edu_registration_id'],
                )));
                $out_of_total = 0;
                $total_mark = 0;
                $out_of_100 = 0;

                foreach ($ars as $ar) {
                    $out_of_total += $ar['EduAssessment']['max_value'];
                    $total_mark += $ar['EduAssessmentRecord']['mark'] + $ar['EduAssessmentRecord']['bonus'];
                }
                $out_of_100 = $total_mark;
                if($out_of_total <> 100) {
                    $out_of_100 = ($total_mark * 100) / $out_of_total;
                }
                //    reflect the summarized result to edu_registartion_quarter_results table
                $rq = $this->EduRegistrationQuarter->find('first', array('conditions' => array(
                    'EduRegistrationQuarter.edu_registration_id' => $correction['EduCorrection']['edu_registration_id'],
                    'EduRegistrationQuarter.edu_quarter_id' => $correction['EduCorrection']['edu_quarter_id']
                )));
                $rqr = $this->EduRegistrationQuarterResult->find('first', array('conditions' => array(
                    'EduRegistrationQuarterResult.edu_registration_quarter_id' => $rq['EduRegistrationQuarter']['id'],
                    'EduRegistrationQuarterResult.edu_course_id' => $correction['EduCorrection']['edu_course_id']
                )));

                $course = $this->EduCourse->read(null, $correction['EduCorrection']['edu_course_id']);

                $result_indicator = 'N';
                if ($out_of_100 >= $course['EduCourse']['min_for_pass']) {
                    $result_indicator = 'P';
                } else {
                    $result_indicator = 'F';
                }

                $this->EduRegistrationQuarterResult->read(null, $rqr['EduRegistrationQuarterResult']['id']);
                $this->EduRegistrationQuarterResult->set('course_result', $out_of_100);
                $this->EduRegistrationQuarterResult->set('scale_result', $this->getScale($out_of_100));
                $this->EduRegistrationQuarterResult->set('result_indicator', $result_indicator);
                $this->EduRegistrationQuarterResult->save();

                // 3. Reflect this on the registration_quarters 
                $rqrs = $this->EduRegistrationQuarterResult->find('all', array('conditions' => array(
                    'EduRegistrationQuarterResult.edu_registration_quarter_id' => $rq['EduRegistrationQuarter']['id']
                )));
                $results_count = 0;
                $quarter_total = 0;
                $quarter_average = 0;
                $total_gpas = 0;
                $cgpa = 0;
                foreach ($rqrs as $single_rqr) {
                    $quarter_total += $single_rqr['EduRegistrationQuarterResult']['course_result'];
                    $results_count++;
                    $total_gpas += $this->getGPA($single_rqr['EduRegistrationQuarterResult']['scale_result']);
                }
                $cgpa = $total_gpas / $results_count;
                $quarter_average = $quarter_total / $results_count;

                // update the record now
                $this->EduRegistrationQuarter->read(null, $rq['EduRegistrationQuarter']['id']);
                $this->EduRegistrationQuarter->set('quarter_total', $quarter_total);
                $this->EduRegistrationQuarter->set('quarter_average', $quarter_average);
                $this->EduRegistrationQuarter->set('cgpa', $cgpa);
                $this->EduRegistrationQuarter->save();

                // 4. Reflect this on the registration_results
                // find rqs 
                $rqs = $this->EduRegistrationQuarter->find('all', array('conditions' => array(
                    'EduRegistrationQuarter.edu_registration_id' => $correction['EduCorrection']['edu_registration_id']
                )));
                $reg_quarter_ids = array();
                foreach ($rqs as $rq_single) {
                    // collect rq_ids
                    $reg_quarter_ids[] = $rq_single['EduRegistrationQuarter']['id'];
                }
                // read all RQR of reg_quarter_ids and the course at hand
                $course_id = $course['EduCourse']['id'];
                $rqrs = $this->EduRegistrationQuarterResult->find('all', array(
                    'conditions' => array(
                        'edu_course_id' => $course_id,
                        'edu_registration_quarter_id' => $reg_quarter_ids
                    )
                ));

                // calculate values: average, new_scale, status
                $average = 0;
                foreach ($rqrs as $rqr) {
                    $average += $rqr['EduRegistrationQuarterResult']['course_result'];
                }
                $new_average = $average / count($rqrs);
                $new_scale = $this->getScale($average);
                $new_status = $average >= $course['EduCourse']['min_for_pass'] ? 'P': 'F';

                $rr = $this->EduRegistrationResult->find('first', array(
                    'conditions' => array(
                        'EduRegistrationResult.edu_registration_id' => $correction['EduCorrection']['edu_registration_id'],
                        'EduRegistrationResult.edu_course_id' => $course_id
                    )
                ));

                $this->EduRegistrationResult->read(null, $rr['EduRegistrationResult']['id']);
                $this->EduRegistrationResult->set('average', $new_average);
                $this->EduRegistrationResult->set('scale_result', $new_scale);
                $this->EduRegistrationResult->set('status', $new_status);
                $this->EduRegistrationResult->save();
                
                $this->Session->setFlash(__('The correction has been approved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The correction could not be submitted. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('correction', $this->EduCorrection->read(null, $id));
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

    function getGPA($scale = 'F') {
		$gpa = 0;
        switch ($scale) {
            case 'A': 
                $gpa = 4; break;
            case 'B':
                $gpa = 3; break;
            case 'C':
                $gpa = 2; break;
            case 'D':
                $gpa = 1; break;
            default:
                $gpa = 0; break;
        }
		return $gpa;
	}

    public function reject_correction($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid correction', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->data['EduCorrection']['status'] = 'REJECTED';
            $this->autoRender = false;
            if ($this->EduCorrection->save($this->data)) {
                $this->Session->setFlash(__('The correction has been rejected', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The correction could not be submitted. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('correction', $this->EduCorrection->read(null, $id));
    }

    public function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for correction', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduCorrection->delete($i);
                }
                $this->Session->setFlash(__('Correction deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Correction was not deleted', true) . ' ' . $e->getMessage(), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduCorrection->delete($id)) {
                $this->Session->setFlash(__('Correction deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Correction was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }
}
