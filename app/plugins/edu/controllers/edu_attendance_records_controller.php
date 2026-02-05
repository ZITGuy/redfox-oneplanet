<?php

class EduAttendanceRecordsController extends EduAppController {

    var $name = 'EduAttendanceRecords';
	
	// Attendance per Section
    function index() {
		$this->loadModel('EduAcademicYear');
		$this->loadModel('EduSection');
		$this->loadModel('EduTeacher');
		
		$ay = $this->EduAcademicYear->getActiveAcademicYear();
        $edu_academic_year_id = $ay['EduAcademicYear']['id'];
		
        $teacher = $this->EduTeacher->find('first', array(
                'conditions' => array('EduTeacher.user_id' => $this->Session->read('Auth.User.id'))
            ));
		$conditions = array();
		if($teacher) {
			$teacher_id = $teacher['EduTeacher']['id'];
			// if the teacher is homeroom for self contained classes
			$sections = $this->EduSection->find('all', array('conditions' => array(
				'EduSection.edu_teacher_id' => $teacher_id, // homeroom
				'EduSection.edu_academic_year_id' => $edu_academic_year_id,
				'EduSection.edu_campus_id' => $this->Session->read('Auth.User.edu_campus_id')
			)));
			$sec_ids = array();
			foreach($sections as $sec) {
				$sec_ids[] = $sec['EduSection']['id'];
			}
			$conditions['EduSection.id'] = $sec_ids;
		}
		$conditions['EduSection.edu_campus_id'] = $this->Session->read('Auth.User.edu_campus_id');
		$conditions['EduSection.edu_academic_year_id'] = $edu_academic_year_id;
        $sections = $this->EduAttendanceRecord->EduSection->find('all', array('conditions' => $conditions, 'order' => 'EduClass.cvalue'));
        $this->set(compact('sections'));
    }
	
	function index_manager_o() {
		$this->loadModel('EduAcademicYear');
		$this->loadModel('EduSection');
		$this->loadModel('EduTeacher');
		
		$ay = $this->EduAcademicYear->getActiveAcademicYear();
        $edu_academic_year_id = $ay['EduAcademicYear']['id'];
		
		// TODO: manager classes .....
        $teacher = $this->EduTeacher->find('first', array(
                'conditions' => array('EduTeacher.user_id' => $this->Session->read('Auth.User.id'))
            ));
		$conditions = array();
		if($teacher) {
			$teacher_id = $teacher['EduTeacher']['id'];
			// if the teacher is homeroom for self contained classes
			$sections = $this->EduSection->find('all', array('conditions' => array(
				'edu_teacher_id' => $teacher_id, // homeroom
				'edu_academic_year_id' => $edu_academic_year_id
			)));
			$sec_ids = array();
			foreach($sections as $sec) {
				$sec_ids[] = $sec['EduSection']['id'];
			}
			$conditions['EduSection.id'] = $sec_ids;
		}
		//-----------------------
		
		$conditions['EduSection.edu_academic_year_id'] = $edu_academic_year_id;
        $sections = $this->EduAttendanceRecord->EduSection->find('all', array('conditions' => $conditions, 'order' => 'EduClass.cvalue'));
        $this->set(compact('sections'));
    }

	function return_attendance_record($id) {
		$this->autoRender = false;
		$ar = $this->EduAttendanceRecord->read(null, $id);
		$this->EduAttendanceRecord->read(null, $id);
		$this->EduAttendanceRecord->set('status', 'N');
		$this->EduAttendanceRecord->set('return_count', ++$ar['EduAttendanceRecord']['return_count']);
		$this->EduAttendanceRecord->save();
		
		$this->Session->setFlash(__('Attendance record returned', true), '');
        $this->render('/elements/success');
	}
	
    function take_attendance_sec() {
        $this->loadModel('EduClass');
        $classes = $this->EduClass->find('list');
        
        $this->set('classes', $classes);
    }
	
	function take_attendance_by_teacher() {
        $this->loadModel('Edu.EduClass');
        $this->loadModel('Edu.EduSection');
		$this->loadModel('EduTeacher');
		$this->loadModel('EduAcademicYear');
		
		$ay = $this->EduAcademicYear->getActiveAcademicYear();
        $edu_academic_year_id = $ay['EduAcademicYear']['id'];
		
        $teacher = $this->EduTeacher->find('first', array(
                'conditions' => array('EduTeacher.user_id' => $this->Session->read('Auth.User.id'))
            ));
		$teacher_id = $teacher['EduTeacher']['id'];
        // if the teacher is homeroom for self contained classes
		$sections = $this->EduSection->find('all', array('conditions' => array(
			'edu_teacher_id' => $teacher_id, // homeroom
			'edu_academic_year_id' => $edu_academic_year_id
		)));
		
		$class_ids = array();
		foreach($sections as $sec) {
			$class_ids[] = $sec['EduSection']['edu_class_id'];
		}
		
		
        $classes = $this->EduClass->find('list', array('conditions' => array(
			'id' => $class_ids
		)));
        
        $this->set('classes', $classes);
    }

    function list_of_dates() {
        $edu_section_id = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : 0;
        $this->loadModel('EduQuarter');
        $this->loadModel('EduDay');
        $edu_quarter = $this->EduQuarter->getActiveQuarter();
        
        $edu_days = $this->EduDay->find('all', array('conditions' => array(
            'EduDay.edu_quarter_id' => $edu_quarter['EduQuarter']['id'], 
            'EduDay.is_active' => true)));
        
        $attendance_records = $this->EduAttendanceRecord->find('all', array(
                'conditions' => array(
                        'EduAttendanceRecord.edu_section_id' => $edu_section_id,
                        'EduAttendanceRecord.edu_quarter_id' => $edu_quarter['EduQuarter']['id'],
                        'EduAttendanceRecord.status' => 'S'
                    )
            ));
		$attendance_records2 = $this->EduAttendanceRecord->find('all', array(
                'conditions' => array(
                        'EduAttendanceRecord.edu_section_id' => $edu_section_id,
                        'EduAttendanceRecord.edu_quarter_id' => $edu_quarter['EduQuarter']['id'],
                        'EduAttendanceRecord.status' => 'N'
                    )
            ));
        $used_dates = array();
        
		
        foreach($attendance_records as $ar) {
            $used_dates[] = $ar['EduAttendanceRecord']['edu_day_id'];
        }
		$saved_dates = array();
		foreach($attendance_records2 as $ar) {
            $saved_dates[] = $ar['EduAttendanceRecord']['edu_day_id'];
        }

        foreach ($edu_days as $key => $value) {
            if(in_array($value['EduDay']['id'], $used_dates) || $this->today() < $value['EduDay']['date']){
                unset($edu_days[$key]);
            } elseif(in_array($value['EduDay']['id'], $saved_dates)) {
				$edu_days[$key]['EduDay']['date'] = $edu_days[$key]['EduDay']['date'] . ' [Taken]';
			}
        }
        $this->set('edu_days', $edu_days);
        $this->set('results', count($edu_days));   
    }
    
    function list_data_students() {
        $edu_section_id = (isset($_REQUEST['selsection'])) ? $_REQUEST['selsection'] : 0;
        $edu_day_id = (isset($_REQUEST['seldate'])) ? $_REQUEST['seldate'] : 0;

        $this->loadModel('Edu.EduRegistration');
        
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_section_id != -1) {
            $conditions['EduRegistration.edu_section_id'] = $edu_section_id;
        }
        
        $this->loadModel('Edu.EduAcademicYear');
        
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $eduAcademicYearId = $ay['EduAcademicYear']['id'];
        $conditions['EduRegistration.edu_academic_year_id'] = $eduAcademicYearId;
        
        
        $registrations = $this->EduRegistration->find('all', array('conditions' => $conditions, 'order' => 'EduRegistration.name'));
        $students = array();

        $attendance_record = $this->EduAttendanceRecord->find('first', array('conditions' => array(
                    'EduAttendanceRecord.edu_section_id' => $edu_section_id,
                    'EduAttendanceRecord.edu_day_id' => $edu_day_id
        )));
        $absentees = array();
        $statuses = array(
            'A' => 'Absent',
            'L' => 'Late Comer',
            'S' => 'Sick',
            'P' => 'Permission'
        );

        if($attendance_record != null && !empty($attendance_record) && !empty($attendance_record['EduAbsentee'])) {
            foreach($attendance_record['EduAbsentee'] as $absentee) {
				if(!$absentee['deleted'])
					$absentees[$absentee['edu_student_id']] = array('status' => $statuses[$absentee['status']], 'remark' => $absentee['reason']);
            }
        }

        foreach($registrations as $reg) {
            $sid = $reg['EduRegistration']['edu_student_id'];
            $students[] = array(
                'EduStudent' => array(
                    'id' => $reg['EduRegistration']['edu_student_id'],
                    'name' => $reg['EduStudent']['name'],
                    'status' => (isset($absentees[$sid])? $absentees[$sid]['status']: 'Present'),
                    'remark' => (isset($absentees[$sid])? $absentees[$sid]['remark']: '-')
                )
            );
        }
        
        $this->set('students', $students);
        $this->set('results', count($students));
    }
    
    function save_attendance() {
        $this->autoRender = false;
        
        $this->loadModel('Edu.EduAbsentee');
        //$this->loadModel('TextMessage');
        
        $edu_day_id = $this->data[0]['edu_day_id'];
        $edu_day_id = str_replace('"', '', $edu_day_id);
        
        $action = $this->data[0]['action'];
        $section_id = $this->data[0]['section_id'];
        
        $action = str_replace('"', '', $action);
        $section_id = str_replace('"', '', $section_id);
        $edu_section_id = $section_id;

        $attendance_record = $this->EduAttendanceRecord->find('first', array(
            'conditions' => array(
                'edu_section_id' => $section_id,
                'edu_day_id' => $edu_day_id
            )
        ));
        
        $attendance_record_id = 0;
        if(!empty($attendance_record)){
            $attendance_record_id = $attendance_record['EduAttendanceRecord']['id'];
            
            $this->EduAttendanceRecord->read(null, $attendance_record_id);
            $this->EduAttendanceRecord->set('status', substr($action, 0, 1));  //????  N or S
            $this->EduAttendanceRecord->save();
        } else {
            $this->loadModel('Edu.EduQuarter');
            $edu_quarter = $this->EduQuarter->getActiveQuarter();

            $attendance_record = array('EduAttendanceRecord' => array(
                'user_id' => $this->Session->read('Auth.User.id'),
                'edu_section_id' => $section_id,
                'edu_quarter_id' => $edu_quarter['EduQuarter']['id'],
                'edu_day_id' => $edu_day_id,
                'status' => substr($action, 0, 1)   //???? N or S
            ));
            
            $this->EduAttendanceRecord->create();
            $this->EduAttendanceRecord->save($attendance_record);
            $attendance_record_id = $this->EduAttendanceRecord->id;
        }

        $statuses = array(
            'Absent' => 'A',
            'Late Comer' => 'L',
            'Sick' => 'S',
            'Permission' => 'P'
        );
        
        foreach ($this->data as $record) {
            $id = $record['id'];
            $status = $record['status'];
            $remark = $record['remark'];
            
            $id = str_replace('"', '', $id);
            $status = str_replace('"', '', $status);
            $remark = str_replace('"', '', $remark);
            
            $absentee = $this->EduAbsentee->find('first', array(
                'conditions' => array(
                    'edu_student_id' => $id,
                    'edu_attendance_record_id' => $attendance_record_id
                )
            ));
            //$this->log($absentee, 'debug');
            
            if(!empty($absentee) && $absentee != null){
                if($status == 'P' || $status == 'Present'){
                    $this->log('Status: ' . $status, 'debugg');
                    
                    if(!$this->EduAbsentee->delete($absentee['EduAbsentee']['id'])) {
                        $this->log('Cannot delete record ' . $absentee['EduAbsentee']['id'], 'debug');
                    } else {
                        $this->log('Item with id [' . $absentee['EduAbsentee']['id'] . '] deleted.', 'debug');
                    }
                } else {
                    $this->EduAbsentee->read(null, $absentee['EduAbsentee']['id']);
                    $this->EduAbsentee->set('status', $statuses[$status]); // update 
                    $this->EduAbsentee->save();

                    // queue sms here to the parent if substr($action, 0, 1) == 'S'
                    $this->loadModel('TextMessage');
                    
                    // use the EduStudent Model to get the primary parent's phone of student $id
                    // TODO:
                    $to = '';
                    $msg = '';
                    $this->TextMessage->queueSMSMessage($to, $msg);
                }
            } else {
                if($status == 'P' || $status == 'Present'){
                    // Do nothing
                } else { // create
                    $absentee2 = array('EduAbsentee' => array(
                        'edu_attendance_record_id' => $attendance_record_id,
                        'edu_student_id' => $id,
                        'status' => $statuses[$status],
                        'reason' => $remark
                    ));
                    $this->EduAbsentee->create();
                    $this->EduAbsentee->save($absentee2);

                    // queue sms here to the parent if substr($action, 0, 1) == 'S'
                    // use the EduStudent Model to get the primary parent's phone of student $id
                    // TODO:
                    //$to = '';
                    //$msg = '';
                    //$this->TextMessage->queueSMSMessage($to, $msg);
                }
            }
            
        }

        if($action == 'SA') {
            $this->loadModel('Edu.EduQuarter');
            $quarter = $this->EduQuarter->getActiveQuarter();
            $attendance_records = $this->EduAttendanceRecord->find('all', array(
                'conditions' => array(
                        'EduAttendanceRecord.edu_section_id' => $edu_section_id,
                        'EduAttendanceRecord.edu_quarter_id' => $quarter['EduQuarter']['id'],
                        'EduAttendanceRecord.status' => 'N'
                    )
            ));
            foreach($attendance_records as $ar) {
                $this->EduAttendanceRecord->read(null, $ar['EduAttendanceRecord']['id']);
                $this->EduAttendanceRecord->set('status', 'S');
                $this->EduAttendanceRecord->save();
            }

        }
    }

    function search() {
        // no data
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_section_id = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : -1;
        if ($id) {
            $edu_section_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_section_id != -1) {
            $conditions['EduAttendanceRecord.edu_section_id'] = $edu_section_id;
			$this->Session->write('edu_section_id', $edu_section_id);
        } else {
			if($this->Session->check('edu_section_id')) {
				$conditions['EduAttendanceRecord.edu_section_id'] = $this->Session->read('edu_section_id');
			} else {
				$conditions['EduAttendanceRecord.edu_section_id'] = $edu_section_id;
			}
		}
		
        $this->set('attendance_records', $this->EduAttendanceRecord->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduAttendanceRecord->find('count', array('conditions' => $conditions)));
    }
	
	function list_data_for_manager($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_section_id = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : -1;
        if ($id) {
            $edu_section_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_section_id != -1) {
            $conditions['EduAttendanceRecord.edu_section_id'] = $edu_section_id;
        }
		$this->loadModel('EduQuarter');
		$q = $this->EduQuarter->getActiveQuarter();
		
		$conditions['EduDay.date >= '] = $q['EduQuarter']['start_date'];
		$conditions['EduDay.date <= '] = $q['EduQuarter']['end_date'];
		$conditions['EduAttendanceRecord.status'] = 'S';
		
		$attendance_records = $this->EduAttendanceRecord->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start));
		$attendance_records_count = $this->EduAttendanceRecord->find('count', array('conditions' => $conditions));
		
        $this->set('attendance_records', $attendance_records);
        $this->set('results', $attendance_records_count);
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid attendance record', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduAttendanceRecord->recursive = 2;
        $this->set('edu_attendance_record', $this->EduAttendanceRecord->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->loadModel('EduHomeroom');
            $conditions['EduHomeroom.status'] = 'active';
            $conditions['EduHomeroom.edu_section_id'] = $this->data['EduAttendanceRecord']['edu_section_id'];
            $teach = $this->EduHomeroom->find('first', array('conditions' => $conditions));
            if (!empty($teach)) {
                $this->data['EduAttendanceRecord']['edu_teacher_id'] = $teach['EduTeacher']['id'];

                $conditionsy['EduAttendanceRecord.date'] = $this->data['EduAttendanceRecord']['date'];
                $conditionsy['EduAttendanceRecord.edu_section_id'] = $this->data['EduAttendanceRecord']['edu_section_id'];
                $alrd = $this->EduAttendanceRecord->find('count', array('conditions' => $conditionsy));
                if ($alrd <= 0) {
                    $this->EduAttendanceRecord->create();
                    $this->autoRender = false;
                    if ($this->EduAttendanceRecord->save($this->data)) {
                        $this->Session->setFlash(__('The attendance record has been saved', true), '');
                        $this->render('/elements/success');
                    } else {
                        $this->Session->setFlash(__('The attendance record could not be saved. Please, try again.', true), '');
                        $this->render('/elements/failure');
                    }
                } else {
                    $this->Session->setFlash(__('Attendance already taken for this class on the selected date', true), '');
                    $this->render('/elements/failure');
                }
            } else {
                $this->Session->setFlash(__('No homeroom Assigned for this class.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
        $teachers = $this->EduAttendanceRecord->EduTeacher->find('list');
        $sections = $this->EduAttendanceRecord->EduSection->find('all');
        $this->set(compact('teachers', 'sections'));
    }

    function print_attendance_sheet($id = null) {
        $this->layout = 'ajax';
        $this->EduAttendanceRecord->recursive = 2;
        $att_record = $this->EduAttendanceRecord->read(null, $id);
        $teacher_user_id = $att_record['EduSection']['EduTeacher']['user_id'];
        $this->loadModel('User');
        $teacher_user = $this->User->read(null, $teacher_user_id);

        $dt = $att_record['EduDay']['date'];
        $week_day = date('N', strtotime($dt));

        $week_start = date('Y-m-d', strtotime($dt . ' -' . ($week_day - 1) . ' days'));
        $week_end = date('Y-m-d', strtotime($week_start . ' +4 days'));

        $this->set('week_start', $week_start);
        $this->set('week_end', $week_end);
        $this->set('teacher_user', $teacher_user);

        $days = $this->EduAttendanceRecord->EduDay->find('all', array(
            'conditions' => array(
                'EduDay.date >=' => $week_start, 
                'EduDay.date <=' => $week_end
            )
        ));

        $day_ids = array();
        foreach($days as $d) {
            $day_ids[] = $d['EduDay']['id'];
        }
        
        $att_records = $this->EduAttendanceRecord->find('all', array(
            'conditions' => array('EduAttendanceRecord.edu_day_id' => $day_ids, 'EduAttendanceRecord.edu_section_id' => $att_record['EduSection']['id'])
        ));

        $this->set('att_records', $att_records);
    }

    function takequick($id = null) {
        if (!empty($this->data)) {
            $conditionsy['EduAttendanceRecord.date'] = $this->data['EduAttendanceRecord']['date'];
            $conditionsy['EduAttendanceRecord.edu_section_id'] = $this->data['EduAttendanceRecord']['edu_section_id'];
            $alrd = $this->EduAttendanceRecord->find('count', array('conditions' => $conditionsy));
            if ($alrd <= 0) {
                $this->EduAttendanceRecord->create();
                $this->autoRender = false;
                if ($this->EduAttendanceRecord->save($this->data)) {
                    $attendance_id = $this->EduAttendanceRecord->getLastInsertID();
                    $this->data2['EduAbsentee']['edu_attendance_record_id'] = $attendance_id;
                    $absentees = explode(',', $this->data['AttendanceRecord']['absentees']);
                    $this->loadModel('EduAbsentee');
                    foreach ($absentees as $absentee) {
                        if ($absentee != '') {
                            $this->data2['EduAbsentee']['edu_student_id'] = $absentee;
                            $this->EduAbsentee->create();
                            $this->EduAbsentee->save($this->data2);
                        }
                    }
                    $this->Session->setFlash(__('The attendance record has been saved', true), '');
                    $this->render('/elements/success');
                }
            } else {
                $this->Session->setFlash(__('Attendance already taken for this class on the selected date', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id) {
            $section_id = $id;

            $this->loadModel('EduHomeroom');
            $conditionsx['EduHomeroom.status'] = 'active';
            $conditionsx['EduHomeroom.edu_section_id'] = $section_id;
            $teach = $this->EduHomeroom->find('first', array('conditions' => $conditionsx));
            if (!empty($teach)) {
                $teacher_id = $teach['EduTeacher']['id'];
                $this->loadModel('EduAbsentee');
                $conditions['edujoin.edu_section_id'] = $section_id;
                $students = $this->EduAbsentee->EduStudent->find('all', array('joins' => array(
                        array(
                            'table' => 'edu_registrations',
                            'alias' => 'edujoin',
                            'type' => 'INNER',
                            'conditions' => array(
                                'edujoin.id = EduStudent.id'
                            )
                        )
                    ), 'conditions' => $conditions));
                $filteredstudents = array();
                foreach ($students as $key => $student) {
                    $filteredstudents[$student['EduStudent']['id']] = $student['EduStudent']['name'];
                }
                $students = array();
                $students = $filteredstudents;

                if (!empty($students)) {
                    $this->set('students', $students);
                    $this->set('teacher', $teacher_id);
                    $this->set('section_id', $section_id);
                    $this->set('date', date('Y-m-d'));
                } else {
                    $this->autoRender = false;
                    echo "alert('No Students Assigned for this Section');";
                }
            } else {
                $this->autoRender = false;
                echo "alert('No Homeroom Assigned for this class');";
            }
        }
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid attendance record', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduAttendanceRecord->save($this->data)) {
                $this->Session->setFlash(__('The attendance record has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The attendance record could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('attendance_record', $this->EduAttendanceRecord->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $teachers = $this->EduAttendanceRecord->EduTeacher->find('list');
        $sections = $this->EduAttendanceRecord->EduSection->find('list');
        $this->set(compact('teachers', 'sections'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for attendance record', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduAttendanceRecord->delete($i);
                }
                $this->Session->setFlash(__('Attendance record deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Attendance record was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduAttendanceRecord->delete($id)) {
                $this->Session->setFlash(__('Attendance record deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Attendance record was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

	function build_attendance($quarter_id) {
		$this->loadModel('Edu.EduSection');
		$this->loadModel('Edu.EduDay');
		$this->loadModel('Edu.EduAttendanceRecord');
		$sections = $this->EduSection->find('all', array('conditions' => array('edu_academic_year_id' => 14))); 
		$days = $this->EduDay->find('all', array('conditions' => array('edu_quarter_id' => $quarter_id, 'is_active' => 1)));
		
		foreach($sections as $section) {
			foreach($days as $day) {
				$att_record = array('EduAttendanceRecord' => array(
						'user_id' => 0,
						'edu_section_id' => $section['EduSection']['id'],
						'edu_quarter_id' => $quarter_id,
						'edu_day_id' => $day['EduDay']['id'],
						'status' => 'S',
						'deleted' => 0
					));
				
				$this->EduAttendanceRecord->create();
				$this->EduAttendanceRecord->save($att_record);
			}
		}
	}
}
