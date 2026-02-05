<?php

class EduCalendarEventTypesController extends EduAppController {

    var $name = 'EduCalendarEventTypes';

    function index() {
        
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");

        $this->set('edu_calendar_event_types', $this->EduCalendarEventType->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduCalendarEventType->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu calendar event type', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduCalendarEventType->recursive = 2;
        $this->set('edu_calendar_event_type', $this->EduCalendarEventType->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $tasks = explode(",", $this->data['EduCalendarEventType']['Task']);
            
            $this->data['Task'] = array('Task' => $tasks);

            $this->EduCalendarEventType->create();
            $this->autoRender = false;
            if ($this->EduCalendarEventType->save($this->data)) {
                $this->Session->setFlash(__('The edu calendar event type has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu calendar event type could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $tasks = $this->EduCalendarEventType->Task->find('list');
        $this->set(compact('tasks'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu calendar event type', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;

            $tasks = explode(",", $this->data['EduCalendarEventType']['Task']);
            
            $this->data['Task'] = array('Task' => $tasks);
            
            if ($this->EduCalendarEventType->save($this->data)) {
                $this->Session->setFlash(__('The edu calendar event type has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu calendar event type could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_calendar_event_type', $this->EduCalendarEventType->read(null, $id));

        $tasks = $this->EduCalendarEventType->Task->find('list');
        $this->set(compact('tasks'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for edu calendar event type', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduCalendarEventType->delete($i);
                }
                $this->Session->setFlash(__('Edu calendar event type deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Edu calendar event type was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduCalendarEventType->delete($id)) {
                $this->Session->setFlash(__('Edu calendar event type deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Edu calendar event type was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}

?>