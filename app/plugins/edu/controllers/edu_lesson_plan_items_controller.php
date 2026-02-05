<?php

class EduLessonPlanItemsController extends EduAppController {

    var $name = 'EduLessonPlanItems';

    function index() {
        $edu_lesson_plans = $this->EduLessonPlanItem->EduLessonPlan->find('all');
        $this->set(compact('edu_lesson_plans'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 30;
        $edu_lesson_plan_id = (isset($_REQUEST['edu_lesson_plan_id'])) ? $_REQUEST['edu_lesson_plan_id'] : -1;
        if ($id) {
            $edu_lesson_plan_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_lesson_plan_id != -1) {
            $conditions['EduLessonPlanItem.edu_lesson_plan_id'] = $edu_lesson_plan_id;
        }

        $this->set('edu_lesson_plan_items', $this->EduLessonPlanItem->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduLessonPlanItem->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu lesson plan item', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduLessonPlanItem->recursive = 2;
        $this->set('edu_lesson_plan_item', $this->EduLessonPlanItem->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->EduLessonPlanItem->create();
            $this->autoRender = false;
            if ($this->EduLessonPlanItem->save($this->data)) {
                $this->Session->setFlash(__('The edu lesson plan item has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu lesson plan item could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id) {
            $this->set('parent_id', $id);
        }
        $edu_lesson_plans = $this->EduLessonPlanItem->EduLessonPlan->find('list');
        $edu_periods = $this->EduLessonPlanItem->EduPeriod->find('list');
        $edu_days = $this->EduLessonPlanItem->EduDay->find('list');
        $edu_outlines = $this->EduLessonPlanItem->EduOutline->find('list');
        $this->set(compact('edu_lesson_plans', 'edu_periods', 'edu_days', 'edu_outlines'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu lesson plan item', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduLessonPlanItem->save($this->data)) {
                $this->Session->setFlash(__('The edu lesson plan item has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu lesson plan item could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_lesson_plan_item', $this->EduLessonPlanItem->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $edu_lesson_plans = $this->EduLessonPlanItem->EduLessonPlan->find('list');
        $edu_periods = $this->EduLessonPlanItem->EduPeriod->find('list');
        $edu_days = $this->EduLessonPlanItem->EduDay->find('list');
        $edu_outlines = $this->EduLessonPlanItem->EduOutline->find('list');
        $this->set(compact('edu_lesson_plans', 'edu_periods', 'edu_days', 'edu_outlines'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for edu lesson plan item', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduLessonPlanItem->delete($i);
                }
                $this->Session->setFlash(__('Edu lesson plan item deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Edu lesson plan item was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduLessonPlanItem->delete($id)) {
                $this->Session->setFlash(__('Edu lesson plan item deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Edu lesson plan item was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }
}
