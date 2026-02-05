<?php

class EduCalendarEventsController extends EduAppController {

    var $name = 'EduCalendarEvents';

    function index() {
        $edu_quarters = $this->EduCalendarEvent->EduQuarter->find('all');
        $this->set(compact('edu_quarters'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
        $quarter = $this->EduCalendarEvent->EduQuarter->read(null, $id);
        $this->loadModel('EduAcademicYear');
        $ay = $this->EduAcademicYear->read(null, $quarter['EduQuarter']['edu_academic_year_id']);
        $quarter['EduAcademicYear'] = $ay['EduAcademicYear'];

        $this->set('quarter', $quarter);
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_quarter_id = (isset($_REQUEST['edu_quarter_id'])) ? $_REQUEST['edu_quarter_id'] : -1;
        if ($id) {
            $edu_quarter_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_quarter_id != -1) {
            $conditions['EduCalendarEvent.edu_quarter_id'] = $edu_quarter_id;
        }

        $this->set('edu_calendar_events', $this->EduCalendarEvent->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduCalendarEvent->find('count', array('conditions' => $conditions)));
    }

    function list_data2($id = null) {
        $start = 0;
        $limit = 500;

        $quarter_ids = array();
        if ($id) {
            $quarters = $this->EduCalendarEvent->EduQuarter->find('all', array(
                'conditions' => array('EduQuarter.edu_academic_year_id' => $id)));

            foreach ($quarters as $quarter) {
                $quarter_ids[] = $quarter['EduQuarter']['id'];
            }
        }
        $conditions = array();
        $conditions['EduCalendarEvent.edu_quarter_id'] = $quarter_ids;

        $this->set('edu_calendar_events', $this->EduCalendarEvent->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduCalendarEvent->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Calendar Event', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduCalendarEvent->recursive = 2;
        $this->set('edu_calendar_event', $this->EduCalendarEvent->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->EduCalendarEvent->create();
            $this->autoRender = false;
            if ($this->EduCalendarEvent->save($this->data)) {
                // create the education days
                // if the calendar event type is Educational (Education)
                $this->Session->setFlash(__('The Calendar Event saved successfully', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The Calendar Event could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $cet_conditions = array();
        if ($id) {
            $this->set('parent_id', $id);
            $quarter = $this->EduCalendarEvent->EduQuarter->read(null, $id);
            $this->set('edu_quarter', $quarter);
            if($quarter['EduQuarter']['quarter_type'] == 'E'){
                $cet_conditions['EduCalendarEventType.applicable'] = array('E', 'B');
            } else {
                $cet_conditions['EduCalendarEventType.applicable'] = array('N', 'B');
            }
        }
        $edu_calendar_event_types = $this->EduCalendarEvent->EduCalendarEventType->find('list', array('conditions' => $cet_conditions));
        $edu_quarters = $this->EduCalendarEvent->EduQuarter->find('list');
        $edu_campuses = $this->EduCalendarEvent->EduCampus->find('list');
        $this->set(compact('edu_calendar_event_types', 'edu_quarters', 'edu_campuses'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu calendar event', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduCalendarEvent->save($this->data)) {
                // create the education days
                // if the calendar event type is Educational (Education)
                $this->Session->setFlash(__('The Calendar Event updated successfully', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The Calendar Event could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_calendar_event', $this->EduCalendarEvent->read(null, $id));
		$cet_conditions = array();
        if ($parent_id) {
            $this->set('parent_id', $parent_id);
            $quarter = $this->EduCalendarEvent->EduQuarter->read(null, $parent_id);
            $this->set('edu_quarter', $quarter);
            
            if($quarter['EduQuarter']['quarter_type'] == 'E'){
                $cet_conditions['EduCalendarEventType.applicable'] = array('E', 'B');
            } else {
                $cet_conditions['EduCalendarEventType.applicable'] = array('N', 'B');
            }
        }

        $edu_calendar_event_types = $this->EduCalendarEvent->EduCalendarEventType->find('list', array('conditions' => $cet_conditions));
        $edu_quarters = $this->EduCalendarEvent->EduQuarter->find('list');
		$edu_campuses = $this->EduCalendarEvent->EduCampus->find('list');
        $this->set(compact('edu_calendar_event_types', 'edu_quarters', 'edu_campuses'));
    }
	
    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Calendar Event', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduCalendarEvent->delete($i);
                }
                $this->Session->setFlash(__('Calendar Events deleted successfully', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Calendar Event was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduCalendarEvent->delete($id)) {
                $this->Session->setFlash(__('Calendar Event deleted successfully', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Calendar Event was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}
