<?php

class EduAbsenteesController extends EduAppController {

    var $name = 'EduAbsentees';

    function index() {
        $attendance_records = $this->EduAbsentee->EduAttendanceRecord->find('all');
        $this->set(compact('attendance_records'));
    }
   
    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_attendance_record_id = (isset($_REQUEST['edu_attendance_record_id'])) ? $_REQUEST['edu_attendance_record_id'] : -1;
        if ($id) {
            $edu_attendance_record_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_attendance_record_id != -1) {
            $conditions['EduAbsentee.edu_attendance_record_id'] = $edu_attendance_record_id;
        }

        $this->set('edu_absentees', $this->EduAbsentee->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduAbsentee->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid absentee', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduAbsentee->recursive = 2;
        $this->set('absentee', $this->EduAbsentee->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->EduAbsentee->create();
            $this->autoRender = false;
            if ($this->EduAbsentee->save($this->data)) {
                $this->Session->setFlash(__('The absentee has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The absentee could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id) {
            $this->set('parent_id', $id);
        }

        //$attendance_records = $this->Absentee->AttendanceRecord->find('list');
        $this->EduAbsentee->EduAttendanceRecord->recursive = 1;
        $attendance_recordsy = $this->EduAbsentee->EduAttendanceRecord->read(null, $id);
        //$students = $this->Absentee->Student->find('list');
        $conditions['edujoin.edu_section_id'] = $attendance_recordsy['EduSection']['id'];
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
        $filtered_students = array();
        foreach ($students as $key => $student) {
            $filtered_students[$student['EduStudent']['id']] = $student['EduStudent']['name'];
        }
        $students = $filtered_students;
        $this->set(compact('attendance_records', 'students'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid absentee', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduAbsentee->save($this->data)) {
                $this->Session->setFlash(__('The absentee has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The absentee could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('absentee', $this->EduAbsentee->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $attendance_records = $this->EduAbsentee->EduAttendanceRecord->find('list');
        $students = $this->EduAbsentee->EduStudent->find('list');
        $this->set(compact('attendance_records', 'students'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for absentee', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduAbsentee->delete($i);
                }
                $this->Session->setFlash(__('Absentee deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Absentee was not deleted', true) . ' ' . $e->getMessage(), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduAbsentee->delete($id)) {
                $this->Session->setFlash(__('Absentee deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Absentee was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}
