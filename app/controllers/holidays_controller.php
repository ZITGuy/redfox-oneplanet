<?php

class HolidaysController extends AppController {

    var $name = 'Holidays';

    function index() {
        
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");

        $this->set('holidays', $this->Holiday->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->Holiday->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid holiday', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Holiday->recursive = 2;
        $this->set('holiday', $this->Holiday->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->Holiday->create();
            $this->autoRender = false;
            if ($this->Holiday->save($this->data)) {
                // make amendments to education days (if any)
                $this->maintainEduDays($this->data['Holiday']['from_date'], $this->data['Holiday']['to_date']);

                $this->Session->setFlash(__('Date Range is set as holiday ' . $this->data['Holiday']['name'] . '.', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The holiday could not be set. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid holiday', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            $old_record = $this->Holiday->read(null, $this->data['Holiday']['id']);
            if ($this->Holiday->save($this->data)) {
                // make ammendments to education days (if any)
                $this->maintainEduDays($old_record['Holiday']['from_date'], $old_record['Holiday']['to_date']);
                $this->maintainEduDays($this->data['Holiday']['from_date'], $this->data['Holiday']['to_date']);

                $this->Session->setFlash(__('Date Range is set as holiday ' . $this->data['Holiday']['name'] . '.', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The holiday could not be set. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('holiday', $this->Holiday->read(null, $id));
    }

    function maintainEduDays($start_date, $end_date) {
        if($start_date <= $end_date) {
            $this->loadModel('Edu.EduDay');

            while($start_date <= $end_date) {
                $is_holiday = $this->isHoliday($start_date);
                $edu_day = $this->EduDay->find('first', array('conditions' => array('EduDay.date' => $start_date)));
                if(!empty($edu_day)){
                    $this->EduDay->read(null, $edu_day['EduDay']['id']);
                    $this->EduDay->set('is_active', !$is_holiday);
                    $this->EduDay->save();
                }
                $start_date = date('Y-m-d', strtotime($start_date . ' +1 day'));
            }
        }
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for holiday', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $old_hd = $this->Holiday->read(null, $i);
                    $this->Holiday->delete($i);
                    $this->maintainEduDays($old_hd['Holiday']['from_date'], $old_hd['Holiday']['to_date']);
                }
                $this->Session->setFlash(__('Holiday deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Holiday was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            $old_hd = $this->Holiday->read(null, $id);
            if ($this->Holiday->delete($id)) {
                $this->maintainEduDays($old_hd['Holiday']['from_date'], $old_hd['Holiday']['to_date']);

                $this->Session->setFlash(__('Holiday deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Holiday was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }
}
