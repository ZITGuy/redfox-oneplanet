<?php

class EduPeriodsController extends AppController {

    var $name = 'EduPeriods';
    var $day_names = array('1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday');

    function index() {
        $edu_sections = $this->EduPeriod->EduSection->find('all');
        $this->set(compact('edu_sections'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
        $schedule = $this->EduPeriod->EduSchedule->read(null, $id);
        $this->set('schedule', $schedule);
        $this->set('day_names', $this->day_names);

        $sections = $this->EduPeriod->EduSection->find('all');
        $this->set(compact('sections'));

        $subjects = $this->EduPeriod->EduCourse->EduSubject->find('all');

        $this->set(compact('subjects'));
    }

    function index3($id = null) {
        $this->set('parent_id', $id);
        $schedule = $this->EduPeriod->EduSchedule->read(null, $id);
        $this->set('schedule', $schedule);
        $this->set('day_names', $this->day_names);

        $sections = $this->EduPeriod->EduSection->find('all');
        $this->set(compact('sections'));

        $subjects = $this->EduPeriod->EduCourse->EduSubject->find('all');

        $this->set(compact('subjects'));
    }

    function search() {
        
    }

    function list_data($id = null) {
        $section_id = (isset($_REQUEST['section_id'])) ? $_REQUEST['section_id'] : -1;
        $schedule_id = (isset($_REQUEST['schedule_id'])) ? $_REQUEST['schedule_id'] : -1;
        $schedule = $this->EduPeriod->EduSchedule->read(null, $schedule_id);
        $this->set('schedule', $schedule);
        $results = array();
        for ($i = 1; $i <= $schedule['EduSchedule']['periods']; $i++) {
            $results[$i]['period'] = $i;
            $results[$i]['section_id'] = $section_id;
            $results[$i]['schedule_id'] = $schedule_id;
            $results[$i]['id'] = $i;
            for ($j = 1; $j <= $schedule['EduSchedule']['days']; $j++) {
                $conditions['EduPeriod.period'] = $i;
                $conditions['EduPeriod.day'] = $j;
                $conditions['EduPeriod.edu_schedule_id'] = $schedule_id;
                $conditions['EduPeriod.edu_section_id'] = $section_id;
                $conditions['EduPeriod.actor'] = 'user';
                $this->EduPeriod->recursive = 2;
                $check = $this->EduPeriod->find('first', array('conditions' => $conditions));
                if (!empty($check)) {
                    $results[$i]['days'][$this->day_names[$j]] = $check['EduCourse']['EduSubject']['name'];
                } else {
                    $results[$i]['days'][$this->day_names[$j]] = '-';
                }
            }
        }
        $this->set('results', $results);
    }

    function list_data3($id = null) {
        $section_id = (isset($_REQUEST['section_id'])) ? $_REQUEST['section_id'] : -1;
        $schedule_id = (isset($_REQUEST['schedule_id'])) ? $_REQUEST['schedule_id'] : -1;
        $schedule = $this->EduPeriod->EduSchedule->read(null, $schedule_id);
        $this->set('schedule', $schedule);
        $results = array();
        for ($i = 1; $i <= $schedule['EduSchedule']['periods']; $i++) {
            $results[$i]['period'] = $i;
            $results[$i]['section_id'] = $section_id;
            $results[$i]['schedule_id'] = $schedule_id;
            $results[$i]['id'] = $i;
            for ($j = 1; $j <= $schedule['EduSchedule']['days']; $j++) {
                $conditions['EduPeriod.period'] = $i;
                $conditions['EduPeriod.day'] = $j;
                $conditions['EduPeriod.edu_schedule_id'] = $schedule_id;
                $conditions['EduPeriod.edu_section_id'] = $section_id;
                $this->EduPeriod->recursive = 2;
                $check = $this->EduPeriod->find('first', array('conditions' => $conditions));
                if (!empty($check)) {
                    $results[$i]['days'][$this->day_names[$j]] = $check['EduCourse']['EduSubject']['name'];
                } else {
                    $results[$i]['days'][$this->day_names[$j]] = '-';
                }
            }
        }
        $this->set('results', $results);
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu period', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduPeriod->recursive = 2;
        $this->set('eduPeriod', $this->EduPeriod->read(null, $id));
    }

    function add($id = null) {
        $this->autoRender = false;
        if (!empty($this->data)) {

            foreach ($this->data as $record) {

                $period = $record['period'];
                $section_id = $record['edu_section'];
                $schedule_id = $record['edu_schedule'];

                $period = str_replace('"', '', $period);
                $section_id = str_replace('"', '', $section_id);
                $schedule_id = str_replace('"', '', $schedule_id);

                foreach ($this->day_names as $key => $day) {
                    if (isset($record[$day])) {
                        $record[$day] = str_replace('"', '', $record[$day]);
                        if ($record[$day] != '-') {
                            $conditionx['EduPeriod.edu_section_id'] = $section_id;
                            $conditionx['EduPeriod.edu_schedule_id'] = $schedule_id;
                            $conditionx['EduPeriod.day'] = $key;
                            $conditionx['EduPeriod.period'] = $period;
                            $this->EduPeriod->deleteAll($conditionx);

                            $this->data2['EduPeriod']['period'] = $period;
                            $this->data2['EduPeriod']['day'] = $key;
                            $this->data2['EduPeriod']['edu_schedule_id'] = $schedule_id;
                            $this->data2['EduPeriod']['edu_section_id'] = $section_id;

                            $sec = $this->EduPeriod->EduSection->read(null, $section_id);
                            $sub = $this->EduPeriod->EduCourse->EduSubject->find('first', array('conditions' => array('EduSubject.name' => $record[$day])));
                            $cor = $this->EduPeriod->EduCourse->find('first', array('conditions' => array('EduCourse.edu_class_id' => $sec['EduClass']['id'], 'EduCourse.edu_subject_id' => $sub['EduSubject']['id'])));
                            if (!empty($cor)) {
                                $this->data2['EduPeriod']['edu_course_id'] = $cor['EduCourse']['id'];
                                $this->data2['EduPeriod']['actor'] = 'user';
                                $this->EduPeriod->create();
                                $this->EduPeriod->save($this->data2);
                            }
                        } else {
                            $conditionx['EduPeriod.edu_section_id'] = $section_id;
                            $conditionx['EduPeriod.edu_schedule_id'] = $schedule_id;
                            $conditionx['EduPeriod.day'] = $key;
                            $conditionx['EduPeriod.period'] = $period;
                            $this->EduPeriod->deleteAll($conditionx);
                        }
                    }
                }
            }
        }
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu period', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduPeriod->save($this->data)) {
                $this->Session->setFlash(__('The edu period has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu period could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu__period', $this->EduPeriod->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $edu_sections = $this->EduPeriod->EduSection->find('list');
        $edu_schedules = $this->EduPeriod->EduSchedule->find('list');
        $this->set(compact('edu_sections', 'edu_schedules'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for edu period', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduPeriod->delete($i);
                }
                $this->Session->setFlash(__('Edu period deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Edu period was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduPeriod->delete($id)) {
                $this->Session->setFlash(__('Edu period deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Edu period was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}

?>