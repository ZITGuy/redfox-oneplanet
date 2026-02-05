<?php

class EduEventTasksController extends EduAppController {

    var $name = 'EduEventTasks';

    function index() {
        $edu_calendar_event_types = $this->EduEventTask->EduCalendarEventType->find('all');
        $this->set(compact('edu_calendar_event_types'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
        
        $this->set('edu_calendar_event_type', $this->EduEventTask->EduCalendarEventType->read(null, $id));
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;

        $edu_calendar_event_type_id = (isset($_REQUEST['edu_calendar_event_type_id'])) ? $_REQUEST['edu_calendar_event_type_id'] : -1;
        if ($id) {
            $edu_calendar_event_type_id = ($id) ? $id : -1;
        }
        $conditions = isset($_REQUEST['conditions']) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_calendar_event_type_id != -1) {
            $conditions['EduEventTask.edu_calendar_event_type_id'] = $edu_calendar_event_type_id;
        }

        $this->set('edu_event_tasks', $this->EduEventTask->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduEventTask->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu event task', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduEventTask->recursive = 2;
        $this->set('edu_event_task', $this->EduEventTask->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->EduEventTask->create();
            $this->autoRender = false;
            if ($this->EduEventTask->save($this->data)) {
                $this->Session->setFlash(__('The edu event task has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu event task could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id) {
            $this->set('parent_id', $id);
        }
        $edu_calendar_event_types = $this->EduEventTask->EduCalendarEventType->find('list');
        $this->set(compact('edu_calendar_event_types'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu event task', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduEventTask->save($this->data)) {
                $this->Session->setFlash(__('The edu event task has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu event task could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_event_task', $this->EduEventTask->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $edu_calendar_event_types = $this->EduEventTask->EduCalendarEventType->find('list');
        $this->set(compact('edu_calendar_event_types'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for edu event task', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                $this->delete_these($ids);

                $this->Session->setFlash(__('Edu event task deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Edu event task was not deleted', true) . ' Error: ' . $e->getMessage(), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduEventTask->delete($id)) {
                $this->Session->setFlash(__('Edu event task deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Edu event task was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    private function delete_these($ids) {
        try {
            foreach ($ids as $i) {
                $this->EduEventTask->delete($i);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

}
