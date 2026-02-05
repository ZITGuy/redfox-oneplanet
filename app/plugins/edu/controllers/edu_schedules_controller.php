<?php

class EduSchedulesController extends AppController {

    var $name = 'EduSchedules';
    var $day_names = array('1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday');

    function index() {
        
    }

    function search() {
        
    }

    function print_schedule() {
        $this->set('edu_schedules', $this->EduSchedule->find('list')); // TODO: filter out the workable schedules
    }

    function print_schedule_pdf($id = null, $waterMark = '') {
        $this->layout = 'ajax';
		
		$num_periods = $this->getSystemSetting('NUMBER_OF_PERIODS_PER_DAY');
        $this->EduSchedule->recursive = 4;
        $edu_schedule = $this->EduSchedule->read(null, $id);
        $this->EduSchedule->EduPeriod->recursive = 3;
        $this->loadModel('User');
        foreach($edu_schedule['EduPeriod'] as &$period) {
            //$p = $this->EduSchedule->EduPeriod->read(null, $period['id']);
            $period['course'] = $period['EduCourse']['EduSubject']['name'];
            $u = $this->User->read(null, $period['EduTeacher']['user_id']);
            $period['teacher'] = $period['EduTeacher']['User']['Person']['first_name'] . ' ' . substr($period['EduTeacher']['User']['Person']['middle_name'], 0, 1) . '.';
            unset($period['EduSection']);
            unset($period['EduCourse']);
            unset($period['EduSchedule']);
            unset($period['EduTeacher']);
        }
        //pr($edu_schedule);
        //exit();
        $this->set('num_periods', $num_periods);
        $this->set('waterMark', $waterMark);
        $this->set('company_url', $this->getSystemSetting('COMPANY_URL'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('edu_schedule', $edu_schedule);
    }
	
	function print_schedule_teacher_pdf($id = null, $waterMark = '') {
        $this->layout = 'ajax';
        
		$num_periods = $this->getSystemSetting('NUMBER_OF_PERIODS_PER_DAY');
		$schedule_ids = array();
		$schedules = $this->EduSchedule->find('all'); // TODO: filter only the current schedules
		foreach($schedules as $schedule) {
			$schedule_ids[] = $schedule['EduSchedule']['id'];
		}
		$this->loadModel('EduPeriod');
		$this->EduPeriod->unbindModel(
			array('belongsTo' => array('EduSchedule'))
		);
		$this->EduPeriod->recursive = 2;
		$periods = $this->EduPeriod->find('all', array('conditions' => array('EduPeriod.edu_schedule_id' => $schedule_ids, 'EduPeriod.edu_teacher_id' => $id)));
		$this->loadModel('User');
		$this->loadModel('EduTeacher');
		
        foreach($periods as &$period) {
            $period['EduPeriod']['course'] = $period['EduCourse']['EduSubject']['name'];
            $u = $this->User->read(null, $period['EduTeacher']['user_id']);
            $period['EduPeriod']['teacher'] = $u['User']['Person']['first_name'] . ' ' . substr($u['User']['Person']['middle_name'], 0, 1) . '.';
			
			$this->EduSchedule->recursive = 2;
			$schedule = $this->EduSchedule->read(null, $period['EduPeriod']['edu_schedule_id']);
			$period['EduPeriod']['section'] = $schedule['EduSection']['EduClass']['name'] . ' ' . $schedule['EduSection']['name'];
			
            unset($period['EduSection']);
            unset($period['EduCourse']);
            unset($period['EduSchedule']);
            unset($period['EduTeacher']);
        }
		//pr($periods);
		//exit();
		$this->EduTeacher->recursive = 2;
		$this->set('teacher', $this->EduTeacher->read(null, $id));
        $this->set('num_periods', $num_periods);
        $this->set('waterMark', $waterMark);
        $this->set('company_url', $this->getSystemSetting('COMPANY_URL'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('periods', $periods);
    }
	
	
	function print_schedule_teacher() {
		$this->loadModel('EduTeacher');
		$conditions['EduTeacher.deleted'] = 0;
        
		$this->EduTeacher->unbindModel(
			array('hasMany' => array('EduSection'))
		);
		$this->EduTeacher->unbindModel(
			array('hasMany' => array('EduAssignment'))
		);
		$this->EduTeacher->unbindModel(
			array('hasAndBelongsToMany' => array('EduSubject'))
		);
		$this->EduTeacher->unbindModel(
			array('hasAndBelongsToMany' => array('EduClass'))
		);
		
        $this->EduTeacher->recursive = 2;
		
		$edu_teachers = $this->EduTeacher->find('all', array('conditions' => $conditions));
		$teachers = array();
		
		foreach($edu_teachers as $teacher) {
			$p = $teacher['User']['Person'];
			$teachers[$teacher['EduTeacher']['id']] = $p['first_name'] . ' ' . $p['middle_name'] . ' ' . $p['last_name'];
		}
        $this->set('edu_teachers', $teachers);
    }
	
    function manual_schedule() {
        $num_periods = $this->getSystemSetting('NUMBER_OF_PERIODS_PER_DAY');
        $this->loadModel('EduClass');
        $this->loadModel('EduCourse');

        $classes = $this->EduClass->find('list', array('order' => 'cvalue', 'conditions' => array('EduClass.grading_type <>' => 'G')));
        $courses = $this->EduCourse->find('all');
        $all_classes = $this->EduClass->find('all');

        $this->set('num_periods', $num_periods);
        $this->set('classes', $classes);
        $this->set('courses', $courses);
        $this->set('all_classes', $all_classes);
    }

    function manual_schedule2() {
        $num_periods = $this->getSystemSetting('NUMBER_OF_PERIODS_PER_DAY');
        $this->loadModel('EduClass');
        $this->loadModel('EduCourse');
        
        $classes = $this->EduClass->find('list', array('order' => 'cvalue', 'conditions' => array('EduClass.grading_type <>' => 'G')));
        $courses = $this->EduCourse->find('all');
        $all_classes = $this->EduClass->find('all');

        $this->set('num_periods', $num_periods);
        $this->set('classes', $classes);
        $this->set('courses', $courses);
        $this->set('all_classes', $all_classes);
    }

    function get_class_courses($id = null) {
        $this->layout = 'ajax';
        $this->loadModel('EduCourse');
        $num_periods = $this->getSystemSetting('NUMBER_OF_PERIODS_PER_DAY');

        $courses = $this->EduCourse->find('all', array('conditions' => array('EduCourse.edu_class_id' => $id)));
        $this->set('num_periods', $num_periods);
        $this->set('courses', $courses);
    }

    function save_manual_schedules() {
        $this->autoRender = false;
        $schedule = null;
        $this->loadModel('EduSection');
        $this->loadModel('EduSubject');
        $this->loadModel('EduCourse');
        $this->loadModel('EduTeacher');
        $this->loadModel('Person');
		
		$selteacher = $this->data['Teacher'];
		unset($this->data['Teacher']);
		
        $teachers = array();    // 
        // records of id 1 - 5 are subjects
        // while id of 6 - 10 are teachers
        foreach ($this->data as $record) {
            $id = $record['id'];
            $periods = $record['periods'];   // periods is also an array [1=>English],[2=>Mathematics]...
			                                 //  [5=>Abebe Bekele Kebede],[6=>Hailu Abebe Balcha]...
            $id = str_replace('"', '', $id);
            if ($id > 5) {
                $teachers[$id - 5] = $periods; // i.e., teachers[0] = [1=>Kebede Lemma],[2=>Dereje Belew Yibel],...
            }
        }

        // go through every record of course periods not of the teachers
        foreach ($this->data as $record) {
            $id = $record['id'];
            $section_id = $record['section_id'];
            $periods = $record['periods']; // an array

            $id = str_replace('"', '', $id);
			// if the record is about teachers - just leave it.
            if ($id > 5) {
                continue;
            }
            $section_id = str_replace('"', '', $section_id);
            $section = $this->EduSection->read(null, $section_id);
            
            // The $schedule should be created on the first
            // iteration.
            if ($schedule == null) {
                // ps -> periods of the section
                $ps = $this->EduSchedule->EduPeriod->find('all', array(
                    'conditions' => array('EduPeriod.edu_section_id' => $section_id)
                ));
                if (count($ps) > 0) {
                    // it is already available and the user is to edit records.
                    $schedule = $this->EduSchedule->read(null, $ps[0]['EduPeriod']['edu_schedule_id']);
                } else {
                    // the schedule is about to be created first time
                    $schedule = array('EduSchedule' => array(
                            'name' => 'Schedule for Grade ' . $section['EduClass']['name'] . ' - Section ' . $section['EduSection']['name'],
                            'periods' => $this->getSystemSetting('NUMBER_OF_PERIODS_PER_DAY'),
                            'edu_section_id' => $section['EduSection']['id'],
                            'days' => 5,
                            'status' => 'Created',
							'deleted' => 0
                    ));
                    $this->EduSchedule->create();
                    $this->EduSchedule->save($schedule);
                    $schedule['EduSchedule']['id'] = $this->EduSchedule->id;
                }
            }
			// now we have the schedule object...
			// lets go through each period object from the array $periods
            foreach ($periods as $key => $subject_name) { // e.g., 1 =>  Amharic
                $subject_name = str_replace('"', '', $subject_name);
                $subject = $this->EduSubject->find('first', array(
                    'conditions' => array('EduSubject.name' => $subject_name)
                ));
				
				// NOTE: Course is the result of Subject + Class
                $course = $this->EduCourse->find('first', array(
                    'conditions' => array(
                        'EduCourse.edu_subject_id' => $subject['EduSubject']['id'],
                        'EduCourse.edu_class_id' => $section['EduSection']['edu_class_id']
                    )
                ));
                
				// lets find the teacher id now
				$edu_teacher_id = 0;
                //$this->log($teachers[$id][$key] . ' as teacher', 'debug');
				// eg. teacher[1][1] is Abebe Kebede Lemma
                $teachers[$id][$key] = str_replace('"', '', $teachers[$id][$key]);
                $names = explode(' ', $teachers[$id][$key]);
                $person = array();
                if (count($names) > 1) {
                    $person = $this->Person->find('first', array(
                        'conditions' => array(
                            'Person.first_name' => $names[0],
                            'Person.middle_name' => $names[1],
                            'Person.last_name' => $names[2]
                        )
                    ));
                }
                if (!empty($person)) {
                    $teacher = $this->EduTeacher->find('first', array(
                        'conditions' => array(
                            'EduTeacher.user_id' => $person['User']['id']
                        )
                    ));

                    if (!empty($teacher)) {
                        $edu_teacher_id = $teacher['EduTeacher']['id'];
                    }
                }
                
                // now a record of schedule (7 periods) is identified
                // and we should save the current period with the 
                // proper values
				
				// if the class is governed by uni-teacher and in case the 
				// $edu_teacher_id is 0 then we need to get the teacher of the section
				if($edu_teacher_id == 0) {
					$edu_teacher_id = $section['EduSection']['edu_teacher_id'];
				}

                // $p is the new period record under the schedule
                $p = array('EduPeriod' => array(
                        'edu_section_id' => $section_id,
                        'edu_course_id' => $course['EduCourse']['id'],
                        'edu_schedule_id' => $schedule['EduSchedule']['id'],
                        'day' => $id,
                        'period' => $key,
                        'actor' => 'System', // ?? what is this? may be to be replaced by current user.
                        'edu_teacher_id' => $edu_teacher_id,
						'deleted' => 0
                ));

                // may be the old record? if it is just supply the id to the new record
				//  in ths case the operation will be record UPDATE
                $pp = $this->EduSchedule->EduPeriod->find('all', array(
                    'conditions' => array(
                        'edu_schedule_id' => $schedule['EduSchedule']['id'],
                        'day' => $id,
                        'period' => $key//,
                        //'actor' => 'System' // it can be system/System so leave it for now. TODO:
                    )
                ));
                if (count($pp) > 0) {
                    $p['EduPeriod']['id'] = $pp[0]['EduPeriod']['id'];
                } else {
                    $this->EduSchedule->EduPeriod->create(); // because for UPDATE no need the create record
                }
                $this->EduSchedule->EduPeriod->save($p);
            } // completed loop for periods of a day
        } // loop for all days
		
        $this->Session->setFlash(__('Records maintained successfully', true), '');
        $this->render('/elements/success');
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");

        $this->set('edu_schedules', $this->EduSchedule->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduSchedule->find('count', array('conditions' => $conditions)));
    }

    function list_data_periods() {
        $selected_section_id = (isset($_REQUEST['selsection'])) ? $_REQUEST['selsection'] : 0;
        $selected_teacher_id = (isset($_REQUEST['selteacher'])) ? $_REQUEST['selteacher'] : 0;
        $periods = array();
        $num_periods = $this->getSystemSetting('NUMBER_OF_PERIODS_PER_DAY');

        $this->loadModel('EduTeacher');
        $this->loadModel('EduSection');

        $this->EduSchedule->EduPeriod->recursive = 3;
        $ps = $this->EduSchedule->EduPeriod->find('all', array('conditions' =>
            array(
                'EduPeriod.edu_section_id' => $selected_section_id
            )
        ));
        $section = $this->EduSection->read(null, $selected_section_id);

        $teacher = '';
        if ($section['EduClass']['uni_teacher'] == 1) {
            $this->EduTeacher->recursive = 3;
            if($selected_teacher_id == 0) {
                $selected_teacher_id = $section['EduSection']['edu_teacher_id'];
            }
            $t = $this->EduTeacher->read(null, $selected_teacher_id);
            $pers = $t['User']['Person'];
            $teacher = $pers['first_name'] . ' ' . $pers['middle_name'] . ' ' . $pers['last_name'];
        }
        $the_periods = array();
        foreach ($ps as $p) {
            $per = array();
            if (isset($p['EduTeacher']['User'])) {
                $per = $p['EduTeacher']['User']['Person'];
            } else {
                $per = array('first_name' => '', 'middle_name' => '', 'last_name' => '');
            }
            $teacher2 = ($per['first_name'] == '') ? '-' : $per['first_name'] . ' ' . $per['middle_name'] . ' ' . $per['last_name'];
            $the_periods[$p['EduPeriod']['day'] . '-' . $p['EduPeriod']['period']] = array(
                'subject' => $p['EduCourse']['EduSubject']['name'],
                'teacher' => ($teacher == '') ? $teacher2 : $teacher
            );
        }

        $days = array(1 => 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');
        for ($i = 1; $i <= 5; $i++) { // assuming there are 5 education days per week
            $period = array('id' => $i, 'day' => $days[$i]);
            for ($j = 1; $j <= $num_periods; $j++) {
                $period['period' . $j] = array('subject' => '-', 'teacher' => ($teacher == '') ? '-' : $teacher);

                if (isset($the_periods[$i . '-' . $j])) {
                    $period['period' . $j] = $the_periods[$i . '-' . $j];
                }
            }
            $periods[] = $period;
        }

        $this->set('periods', $periods);
        $this->set('num_periods', $num_periods);
        $this->set('results', count($periods));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu schedule', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduSchedule->recursive = 2;
        $this->set('eduSchedule', $this->EduSchedule->read(null, $id));
    }

    function compileit($days, $periods, $teachers, $subjects, $subject_allocate, $class, $teacher_class_subject, $class_period, $teacher_period, $section_course) {
        $out = '<?xml version="1.0" encoding="UTF-8"?><fet version="5.21.3"><Institution_Name>ERP</Institution_Name><Comments>Default comments</Comments>';
        $out.='<Hours_List><Number>' . $periods . '</Number>';
        $x_pds = '';
        for ($iii = 1; $iii <= $periods; $iii++) {
            $x_pds.='<Name>' . $iii . '</Name>';
        }
        $out.=$x_pds . '</Hours_List>';
        $out.='<Days_List><Number>' . $days . '</Number>';
        $x_pds = '';
        for ($iii = 1; $iii <= $days; $iii++) {
            $x_pds.='<Name>' . $iii . '</Name>';
        }
        $out.=$x_pds . '</Days_List>';

        $out.='<Students_List>';
        foreach ($class as $cl) {
            $out.= '<Year> <Name>' . $cl . '</Name><Number_of_Students>0</Number_of_Students></Year>';
        }
        $out.='</Students_List>';
        $out.='<Teachers_List>';
        foreach ($teachers as $teacher) {
            $out.='<Teacher> <Name>' . $teacher . '</Name> </Teacher> ';
        }
        $out.='</Teachers_List>';
        $out.='<Subjects_List>';
        foreach ($subjects as $subject) {
            $out.='<Subject> <Name>' . $subject . '</Name> </Subject>';
        }
        $out.='</Subjects_List> ';
        $out.='<Activity_Tags_List></Activity_Tags_List>';
        $cons = '';
        $out.='<Activities_List>';
        $allowed = $days;
        $i = 1; //activ id
        $gi = 0; //group id
        foreach ($teacher_class_subject as $keyc => $classes) {
            $teacher = $teachers[$keyc];
            foreach ($classes as $key => $subjectsx) {
                $students = $class[$key];
                foreach ($subjectsx as $subj) {
                    $subjname = $subjects[$subj];
                    $gi = $i;
                    $alloc_final = $subject_allocate[$subj];
                    if (isset($section_course[$key][$subj]))
                        $alloc_final = $subject_allocate[$subj] - $section_course[$key][$subj];
                    $np = $alloc_final;
                    if ($np > $allowed)
                        $total_duration = $allowed;
                    else
                        $total_duration = $np;
                    $left = 0;
                    $cons.='<ConstraintMinDaysBetweenActivities>
                            <Weight_Percentage>95</Weight_Percentage>
                            <Consecutive_If_Same_Day>true</Consecutive_If_Same_Day>
                            <Number_of_Activities>' . $total_duration . '</Number_of_Activities>';
                    for ($y = 0; $y < $alloc_final; $y++) {
                        $out.='<Activity>
                            <Teacher>' . $teacher . '</Teacher>
                            <Subject>' . $subjname . '</Subject>
                            <Students>' . $students . '</Students>
                            <Duration>1</Duration>
                            <Total_Duration>' . $total_duration . '</Total_Duration>
                            <Id>' . $i . '</Id>
                            <Activity_Group_Id>' . $gi . '</Activity_Group_Id>
                            <Active>true</Active>
                            <Comments></Comments>
                    </Activity>';

                        $cons.='<Activity_Id>' . $i . '</Activity_Id>';

                        $i++;
                        $left++;
                        if ($left == $allowed) {
                            $gi = $i;
                            $np = $np - $left;
                            if ($np > $allowed)
                                $total_duration = $allowed;
                            else
                                $total_duration = $np;
                            $left = 0;
                            $cons.='<MinDays>1</MinDays>
                                <Active>true</Active>
                                <Comments></Comments>
                        </ConstraintMinDaysBetweenActivities>
                        <ConstraintMinDaysBetweenActivities>
                                <Weight_Percentage>95</Weight_Percentage>
                                <Consecutive_If_Same_Day>true</Consecutive_If_Same_Day>
                                <Number_of_Activities>' . $total_duration . '</Number_of_Activities>';
                        }
                    }//end for subject
                    if ($left < $allowed)
                        $cons.='<MinDays>1</MinDays>
                                <Active>true</Active>
                                <Comments></Comments>
                        </ConstraintMinDaysBetweenActivities>';
                }
            }
        }
        $out.='</Activities_List>';

        $out.='<Buildings_List></Buildings_List><Rooms_List></Rooms_List>
                    <Time_Constraints_List>
                    <ConstraintBasicCompulsoryTime>
                            <Weight_Percentage>100</Weight_Percentage>
                            <Active>true</Active>
                            <Comments></Comments>
                    </ConstraintBasicCompulsoryTime>';
        $not_av_t = '';
        foreach ($teacher_period as $tperk => $tper) {
            $iv = 0;
            $not_av_t_tmp = '';
            foreach ($tper as $perk => $tpe) {
                foreach ($tpe as $dayk => $te) {
                    if ($te !== 'F') {
                        $not_av_t_tmp.='<Not_Available_Time>
		<Day>' . $dayk . '</Day>
		<Hour>' . $perk . '</Hour>
	</Not_Available_Time>';
                        $iv++;
                    }
                }
            }
            if ($iv > 0) {
                $not_av_t_tmp = '<ConstraintTeacherNotAvailableTimes>
                    <Weight_Percentage>100</Weight_Percentage>
                    <Teacher>' . $teachers[$tperk] . '</Teacher>
                    <Number_of_Not_Available_Times>' . $iv . '</Number_of_Not_Available_Times>' . $not_av_t_tmp;
                $not_av_t_tmp.='<Active>true</Active>
                    <Comments></Comments>
            </ConstraintTeacherNotAvailableTimes>';
                $not_av_t.=$not_av_t_tmp;
            }
        }

        foreach ($class_period as $tperk => $tper) {
            $iv = 0;
            $not_av_t_tmp = '';
            foreach ($tper as $perk => $tpe) {
                foreach ($tpe as $dayk => $te) {
                    if ($te !== 'F') {
                        $not_av_t_tmp.='<Not_Available_Time>
                                <Day>' . $dayk . '</Day>
                                <Hour>' . $perk . '</Hour>
                        </Not_Available_Time>';
                        $iv++;
                    }
                }
            }
            if ($iv > 0) {
                $not_av_t_tmp = '<ConstraintStudentsSetNotAvailableTimes>
                        <Weight_Percentage>100</Weight_Percentage>
                        <Students>' . $class[$tperk] . '</Students>
                        <Number_of_Not_Available_Times>' . $iv . '</Number_of_Not_Available_Times>' . $not_av_t_tmp;
                $not_av_t_tmp.='<Active>true</Active>
                        <Comments></Comments>
                </ConstraintStudentsSetNotAvailableTimes>';
                $not_av_t.=$not_av_t_tmp;
            }
        }
        $out.=$not_av_t;
        $out.=$cons;
        $out.='</Time_Constraints_List>

            <Space_Constraints_List>
            <ConstraintBasicCompulsorySpace>
                    <Weight_Percentage>100</Weight_Percentage>
                    <Active>true</Active>
                    <Comments></Comments>
            </ConstraintBasicCompulsorySpace>
            </Space_Constraints_List>';

        $out.='</fet>';
        return $out;
    }

    function process($sections, $schedule_id) {
        $schedule = $this->EduSchedule->read(null, $schedule_id);
        // $class = array('Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12');
        $class = array();
        $class_period = array();
        foreach ($sections as $section) {
            $class[$section] = $section;
            $c_period = array();
            for ($i = 1; $i <= $schedule['EduSchedule']['periods']; $i++) {
                for ($j = 1; $j <= $schedule['EduSchedule']['days']; $j++) {
                    $conditionsc['EduPeriod.edu_schedule_id'] = $schedule['EduSchedule']['id'];
                    $conditionsc['EduPeriod.edu_section_id'] = $section;
                    $conditionsc['EduPeriod.day'] = $j;
                    $conditionsc['EduPeriod.period'] = $i;
                    $conditionsc['EduPeriod.actor'] = 'user';
                    $tp_check_cls = $this->EduSchedule->EduPeriod->find('count', array('conditions' => $conditionsc));
                    if ($tp_check_cls > 0)
                        $c_period[$i][$j] = 'N';
                    else
                        $c_period[$i][$j] = 'F';
                }
            }
            $class_period[$section] = $c_period;
        }
        // $teachers = array('Sintayehu', 'Gashaw', 'Senayit', 'Habtish');
        $this->loadModel('EduTeacherAllocation');
        $teachers = array();
        $teacher_class_subject = array();
        $teacher_period = array();
        $conditions1['EduTeacherAllocation.edu_section_id'] = $sections;
        $conditions1['EduTeacherAllocation.status'] = 'active';
        $teachs = $this->EduTeacherAllocation->find('all', array('conditions' => $conditions1, 'group' => array('EduTeacherAllocation.edu_teacher_id')));
        foreach ($teachs as $teach) {
            $teachers[$teach['EduTeacher']['id']] = $teach['EduTeacher']['id'];

            $assignment_array = array();
            $conditions5['EduTeacherAllocation.edu_teacher_id'] = $teach['EduTeacher']['id'];
            $conditions5['EduTeacherAllocation.status'] = 'active';
            $a_classes = $this->EduTeacherAllocation->find('all', array('conditions' => $conditions5, 'group' => array('EduTeacherAllocation.edu_section_id')));
            foreach ($a_classes as $a_class) {
                if (in_array($a_class['EduSection']['id'], $sections)) {
                    $conditions6['EduTeacherAllocation.edu_section_id'] = $a_class['EduSection']['id'];
                    $conditions6['EduTeacherAllocation.edu_teacher_id'] = $teach['EduTeacher']['id'];
                    $conditions6['EduTeacherAllocation.status'] = 'active';
                    $a_subs = $this->EduTeacherAllocation->find('all', array('conditions' => $conditions6));
                    foreach ($a_subs as $a_sub) {
                        $assignment_array[$a_class['EduSection']['id']][] = $a_sub['EduCourse']['id'];
                    }
                }
            }
            $teacher_class_subject[$teach['EduTeacher']['id']] = $assignment_array;

            $t_period = array();
            for ($i = 1; $i <= $schedule['EduSchedule']['periods']; $i++) {
                for ($j = 1; $j <= $schedule['EduSchedule']['days']; $j++) {
                    $conditions7['EduNonavailablePeriod.edu_schedule_id'] = $schedule['EduSchedule']['id'];
                    $conditions7['EduNonavailablePeriod.edu_teacher_id'] = $teach['EduTeacher']['id'];
                    $conditions7['EduNonavailablePeriod.day'] = $j;
                    $conditions7['EduNonavailablePeriod.period'] = $i;
                    $tp_check = $this->EduSchedule->EduNonavailablePeriod->find('count', array('conditions' => $conditions7));
                    if ($tp_check > 0)
                        $t_period[$i][$j] = 'N';
                    else {
                        $t_period[$i][$j] = 'F';
                        foreach ($teacher_class_subject[$teach['EduTeacher']['id']] as $key => $t_clls) {
                            foreach ($t_clls as $t_subj) {
                                $conditions8['EduPeriod.edu_schedule_id'] = $schedule['EduSchedule']['id'];
                                $conditions8['EduPeriod.edu_section_id'] = $key;
                                $conditions8['EduPeriod.edu_course_id'] = $t_subj;
                                $conditions8['EduPeriod.day'] = $j;
                                $conditions8['EduPeriod.period'] = $i;
                                $conditions8['EduPeriod.actor'] = 'user';
                                $tp_check_sub = $this->EduSchedule->EduPeriod->find('count', array('conditions' => $conditions8));
                                if ($tp_check_sub > 0) {
                                    $t_period[$i][$j] = 'N';
                                    break 2;
                                }
                            }
                        }

                        $conditions9['EduTeacherAllocation.edu_teacher_id'] = $teach['EduTeacher']['id'];
                        $conditions9['EduTeacherAllocation.status'] = 'active';
                        $a_classes_all = $this->EduTeacherAllocation->find('all', array('conditions' => $conditions5));
                        foreach ($a_classes_all as $a_c_a) {
                            $a_sec = $a_c_a['EduTeacherAllocation']['edu_section_id'];
                            $a_cor = $a_c_a['EduTeacherAllocation']['edu_course_id'];
                            if (in_array($a_sec, $sections) === FALSE) {
                                $conditions8['EduPeriod.edu_schedule_id'] = $schedule['EduSchedule']['id'];
                                $conditions8['EduPeriod.edu_section_id'] = $a_sec;
                                $conditions8['EduPeriod.edu_course_id'] = $a_cor;
                                $conditions8['EduPeriod.day'] = $j;
                                $conditions8['EduPeriod.period'] = $i;
                                //$conditions8['EduPeriod.actor'] = 'system';
                                $tp_check_sub = $this->EduSchedule->EduPeriod->find('count', array('conditions' => $conditions8));
                                if ($tp_check_sub > 0) {
                                    $t_period[$i][$j] = 'N';
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            $teacher_period[$teach['EduTeacher']['id']] = $t_period;
        }
        // $subjects = array('Amharic 7', 'English 7', 'Mathimatics 7');
        $subjects = array();
        $subject_allocate = array();
        $section_course = array();
        $this->loadModel('EduSection');
        $conditions2['EduSection.id'] = $sections;
        $mainclasses = $this->EduSection->find('all', array('conditions' => $conditions2, 'group' => array('EduSection.edu_class_id')));
        $this->loadModel('EduCourse');
        foreach ($mainclasses as $clls) {
            $conditions3['EduCourse.edu_class_id'] = $clls['EduClass']['id'];
            $courses = $this->EduCourse->find('all', array('conditions' => $conditions3));
            foreach ($courses as $course) {
                $subjects[$course['EduCourse']['id']] = $course['EduCourse']['id'];
                $conditions4['EduTeacherAllocation.edu_course_id'] = $course['EduCourse']['id'];
                $conditions4['EduTeacherAllocation.status'] = 'active';
                $alloc = $this->EduTeacherAllocation->find('first', array('conditions' => $conditions4));
                $subject_allocate[$course['EduCourse']['id']] = $alloc['EduTeacherAllocation']['weekly'];
                foreach ($sections as $sec_cor) {
                    $cond_sec_cor['EduPeriod.edu_schedule_id'] = $schedule['EduSchedule']['id'];
                    $cond_sec_cor['EduPeriod.edu_section_id'] = $sec_cor;
                    $cond_sec_cor['EduPeriod.edu_course_id'] = $course['EduCourse']['id'];
                    $cond_sec_cor['EduPeriod.actor'] = 'user';
                    $tp_check_sec_cor = $this->EduSchedule->EduPeriod->find('count', array('conditions' => $cond_sec_cor));
                    if ($tp_check_sec_cor > 0)
                        $section_course[$sec_cor][$course['EduCourse']['id']] = $tp_check_sec_cor;
                }
            }
        }
        return $this->compileit($schedule['EduSchedule']['days'], $schedule['EduSchedule']['periods'], $teachers, $subjects, $subject_allocate, $class, $teacher_class_subject, $class_period, $teacher_period, $section_course);
    }

    function generate($id = null) {
        if (!empty($this->data)) {
            $this->autoRender = false;
            $sched_id = $this->data['EduSchedule']['edu_schedule_id'];
            $sections = explode(',', $this->data['EduSchedule']['list']);
            unset($sections[0]);
            $xmlout = $this->process($sections, $this->data['EduSchedule']['edu_schedule_id']);
            $myfile = fopen("SMIS.fet", "w");
            fwrite($myfile, $xmlout);
            fclose($myfile);

            $output = '';
            $ret = exec("/wamp/www/SMIS/app/vendors/shells/fet/fet-cl --inputfile=c:\\wamp\\www\\SMIS\\app\\webroot\\SMIS.fet &", $output);
            //echo strpos($output,'s');	
            if (strpos($ret, 'Simulation successful') !== false) {
                $cl_list = '';
                foreach ($sections as $sec) {
                    $secc = $this->EduSchedule->EduPeriod->EduSection->read(null, $sec);
                    $cl_list .= ' ' . $secc['EduClass']['name'] . ' ' . $secc['EduSection']['name'] . '<br>';
                }

                $this->Session->setFlash(__('TimeTable Successfully Generated for:- </br></br>' . $cl_list, true), '');
                $this->render('/elements/success');
            } else {

                $sections = explode(',', $this->data['EduSchedule']['list']);
                unset($sections[0]);
                $additionals = array();
                $this->loadModel('EduTeacherAllocation');
                foreach ($sections as $section) {
                    $conditions['EduTeacherAllocation.edu_section_id'] = $section;
                    $conditions['EduTeacherAllocation.status'] = 'active';
                    $teachs = $this->EduTeacherAllocation->find('all', array('conditions' => $conditions));
                    foreach ($teachs as $teach) {
                        $conditions2['EduTeacherAllocation.edu_teacher_id'] = $teach['EduTeacherAllocation']['edu_teacher_id'];
                        $conditions2['EduTeacherAllocation.status'] = 'active';
                        $secs = $this->EduTeacherAllocation->find('all', array('conditions' => $conditions));
                        foreach ($secs as $sec) {
                            if (in_array($sec['EduTeacherAllocation']['edu_section_id'], $sections) === FALSE) {
                                if (in_array($sec['EduTeacherAllocation']['edu_section_id'], $additionals) === FALSE)
                                    $additionals[] = $sec['EduTeacherAllocation']['edu_section_id'];
                            }
                        }
                    }
                }
                array_merge($sections, $additionals);
                $xmlout = $this->process($sections, $this->data['EduSchedule']['edu_schedule_id']);
                $myfile = fopen("SMIS.fet", "w");
                fwrite($myfile, $xmlout);
                fclose($myfile);

                $output = '';
                $ret = exec("/wamp/www/SMIS/app/vendors/shells/fet/fet-cl --inputfile=c:\\wamp\\www\\SMIS\\app\\webroot\\SMIS.fet", $output);
                //echo strpos($output,'s');	
                if (strpos($ret, 'Simulation successful') !== false) {
                    $cl_list = '';
                    foreach ($sections as $sec) {
                        $secc = $this->EduSchedule->EduPeriod->EduSection->read(null, $sec);
                        $cl_list .= ' ' . $secc['EduClass']['name'] . ' ' . $secc['EduSection']['name'] . '<br>';
                    }

                    $this->Session->setFlash(__('TimeTable Successfully Generated for:- </br></br>' . $cl_list, true), '');
                } else {
                    $this->Session->setFlash(__('Error Generating TimeTable, Please correct preference settings', true), '');
                    $this->render('/elements/failure');
                    $this->rrmdir('timetables');
                }
            }

            //unlink('SMIS.fet');
        } else {
            $this->loadModel('EduSection');
            $this->EduSection->recursive = 2;
            $classes = $this->EduSection->find('all');
            $this->set(compact('classes'));
            $this->set('schedule_id', $id);
        }
    }

    function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        $this->rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    function apply($id = null) {
        $sched_id = $id;
        $this->autoRender = false;
        $fetout = simplexml_load_file('timetables/SMIS/SMIS_subgroups.xml');
        foreach ($fetout->Subgroup as $class) {
            $sec_id = str_replace(' Automatic Group Automatic Subgroup', '', $class['name']);
            $conditionxx['EduPeriod.edu_section_id'] = $sec_id;
            $conditionxx['EduPeriod.actor'] = 'system';
            $this->EduSchedule->EduPeriod->deleteAll($conditionxx);
            foreach ($class as $day) {
                $day_id = $day['name'];
                foreach ($day as $hour) {
                    $period_id = $hour['name'];
                    if (isset($hour->Teacher)) {
                        if (isset($hour->Subject)) {
                            $teach_id = $hour->Teacher['name'];
                            $sub_id = $hour->Subject['name'];
                            $this->datax['EduPeriod']['edu_section_id'] = $sec_id;
                            $this->datax['EduPeriod']['edu_teacher_id'] = $teach_id;
                            $this->datax['EduPeriod']['edu_course_id'] = $sub_id;
                            $this->datax['EduPeriod']['edu_schedule_id'] = $sched_id;
                            $this->datax['EduPeriod']['day'] = $day_id;
                            $this->datax['EduPeriod']['period'] = $period_id;
                            $this->datax['EduPeriod']['actor'] = 'system';
                            $this->EduSchedule->EduPeriod->create();
                            $this->EduSchedule->EduPeriod->save($this->datax);
                        }
                    }
                }
            }
        }
        $this->rrmdir('timetables');
    }

    function display($id = null) {
        $this->autoRender = false;
        $sched_id = $id;
        $fetout = simplexml_load_file('timetables/SMIS/SMIS_subgroups.xml');
        $class_p = array();
        foreach ($fetout->Subgroup as $class) {
            $sec_id = str_replace(' Automatic Group Automatic Subgroup', '', $class['name']);
            foreach ($class as $day) {
                $day_id = $day['name'];
                foreach ($day as $hour) {
                    $period_id = $hour['name'];
                    if (isset($hour->Teacher)) {
                        if (isset($hour->Subject)) {
                            $teach_id = $hour->Teacher['name'];
                            $sub_id = $hour->Subject['name'];
                            $class_p[(int) $sec_id][(int) $day_id][(int) $period_id] = (int) $sub_id;

                            $this->condx['EduPeriod.edu_section_id'] = (int) $sec_id;
                            $this->condx['EduPeriod.edu_schedule_id'] = (int) $sched_id;
                            $this->condx['EduPeriod.day'] = (int) $day_id;
                            $this->condx['EduPeriod.period'] = (int) $period_id;
                            $this->condx['EduPeriod.actor'] = 'user';
                            $perr = $this->EduSchedule->EduPeriod->find('first', array('conditions' => $this->condx));
                            if (!empty($perr))
                                $class_p[(int) $sec_id][(int) $day_id][(int) $period_id] = 'Conflict User:(' . $perr['EduCourse']['id'] . ') with ' . (int) $sub_id;
                        }
                    }else {

                        $this->condx['EduPeriod.edu_section_id'] = (int) $sec_id;
                        $this->condx['EduPeriod.edu_schedule_id'] = (int) $sched_id;
                        $this->condx['EduPeriod.day'] = (int) $day_id;
                        $this->condx['EduPeriod.period'] = (int) $period_id;
                        $this->condx['EduPeriod.actor'] = 'user';
                        $perr = $this->EduSchedule->EduPeriod->find('first', array('conditions' => $this->condx));
                        if (!empty($perr))
                            $class_p[(int) $sec_id][(int) $day_id][(int) $period_id] = $perr['EduCourse']['id'];
                        else
                            $class_p[(int) $sec_id][(int) $day_id][(int) $period_id] = '-';
                    }
                }
            }
        }
//print
        $schedule = $this->EduSchedule->read(null, $sched_id);
        foreach ($class_p as $key => $clss) {
            $secc = $this->EduSchedule->EduPeriod->EduSection->read(null, $key);
            echo '<table cellpadding="5px" border="0" style="width: 646px; border: 1px solid;"><tr><td colspan=' . ($schedule['EduSchedule']['days'] + 1) . '>' . $secc['EduClass']['name'] . ' ' . $secc['EduSection']['name'] . '</td></tr>';
            echo '<tr style="background-color:lightblue"><td> </td>';
            for ($j = 1; $j <= $schedule['EduSchedule']['days']; $j++) {
                echo '<td>' . $this->day_names[$j] . '</td>';
            }
            echo '</tr>';

            for ($i = 1; $i <= $schedule['EduSchedule']['periods']; $i++) {
                echo '<tr><td>' . $i . '</td>';
                for ($j = 1; $j <= $schedule['EduSchedule']['days']; $j++) {
                    $cor = $this->EduSchedule->EduPeriod->EduCourse->read(null, $class_p[$key][$j][$i]);
                    if ($cor['EduSubject']['name'])
                        echo '<td>' . $cor['EduSubject']['name'] . '</td>';
                    else
                        echo '<td>' . $class_p[$key][$j][$i] . '</td>';
                }
                echo '</tr>';
            }
            echo '</table><br><br>';
        }
    }

    function cancel() {
        $this->autoRender = false;
        $this->rrmdir('timetables');
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->EduSchedule->create();
            $this->autoRender = false;
            if ($this->EduSchedule->save($this->data)) {
                $this->Session->setFlash(__('The edu schedule has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu schedule could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu schedule', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduSchedule->save($this->data)) {
                $this->Session->setFlash(__('The edu schedule has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu schedule could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu__schedule', $this->EduSchedule->read(null, $id));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for edu schedule', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduSchedule->delete($i);
                }
                $this->Session->setFlash(__('Edu schedule deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Edu schedule was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduSchedule->delete($id)) {
                $this->Session->setFlash(__('Edu schedule deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Edu schedule was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}
