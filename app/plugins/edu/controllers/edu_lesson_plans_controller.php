<?php

class EduLessonPlansController extends EduAppController {

    var $name = 'EduLessonPlans';

    function index() {
        $edu_sections = $this->EduLessonPlan->EduSection->find('all');
        $this->set(compact('edu_sections'));
    }
    
    function index_maker() {
        $edu_sections = $this->EduLessonPlan->EduSection->find('all');
        $this->set(compact('edu_sections'));
    }
    
    function index_checker() {
        $edu_sections = $this->EduLessonPlan->EduSection->find('all');
        $this->set(compact('edu_sections'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
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
            $conditions['EduLessonPlan.edu_section_id'] = $edu_section_id;
        }

        $this->set('edu_lesson_plans', $this->EduLessonPlan->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduLessonPlan->find('count', array('conditions' => $conditions)));
    }
    
    function list_data_checker($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_section_id = (isset($_REQUEST['edu_section_id'])) ? $_REQUEST['edu_section_id'] : -1;
        if ($id) {
            $edu_section_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_section_id != -1) {
            $conditions['EduLessonPlan.edu_section_id'] = $edu_section_id;
        }
        $conditions['EduLessonPlan.status NOT'] = array('Created', 'Returned');

        $this->set('edu_lesson_plans', $this->EduLessonPlan->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduLessonPlan->find('count', array('conditions' => $conditions)));
    }

    function list_data_lesson_plan_items() {
        $edu_section_id = (isset($_REQUEST['selected_section'])) ? $_REQUEST['selected_section'] : -1;
        $edu_course_id = (isset($_REQUEST['selected_course'])) ? $_REQUEST['selected_course'] : -1;

        $this->EduLessonPlan->recursive = 2;
        $lesson_plan = $this->EduLessonPlan->find('first', array(
            'conditions' => array(
                'EduLessonPLan.edu_section_id' => $edu_section_id,
                'EduLessonPLan.edu_course_id' => $edu_course_id
            )
        ));
        $lesson_plan_items = array();
        if ($lesson_plan) {
            $count = 1;
            foreach($lesson_plan['EduLessonPlanItem'] as $item) {
                $lesson_plan_items[] = array(
                    'id' => $item['id'],
                    'date' => date('l M. d, Y', strtotime($item['EduDay']['date'])),
                    'period' => $item['EduPeriod']['period'],
                    'outline' => ($item['edu_outline_id'] == 0? '-': $count++ . '. ' . $item['EduOutline']['name']),
                    'activity' => $item['activity'],
                    'materials_needed' => $item['materials_needed']
                );
            }
        } else {
            $this->loadModel('Edu.EduAcademicYear');
            $this->loadModel('Edu.EduDay');
            $this->loadModel('Edu.EduPeriod');

            $periods = $this->EduPeriod->find('all', array(
                'conditions' => array(
                    'EduPeriod.edu_course_id' => $edu_course_id,
                    'EduPeriod.edu_section_id' => $edu_section_id
                )
            ));
            $periods_per_day = array();
            foreach ($periods as $period) {
                if (!isset($periods_per_day[$period['EduPeriod']['day']])) {
                    $periods_per_day[$period['EduPeriod']['day']] = array();
                }
                $periods_per_day[$period['EduPeriod']['day']][] = $period['EduPeriod']['period'];
            }

            $ay = $this->EduAcademicYear->getActiveAcademicYear();
            $quarters = array();
            foreach ($ay['EduQuarter'] as $quarter) {
                $quarters[] = $quarter['id'];
            }

            $days = $this->EduDay->find('all', array(
                'conditions' => array(
                    'edu_quarter_id' => $quarters
                )
            ));

            $count = 1;
            foreach ($days as $day) {
                if (isset($periods_per_day[$day['EduDay']['week_day']])) { // if the course is given this day?
                    foreach ($periods_per_day[$day['EduDay']['week_day']] as $p) {
                        $lesson_plan_items[] = array(
                            'id' => $count++,
                            'date' => date('l M. d, Y', strtotime($day['EduDay']['date'])),
                            'period' => $p,
                            'outline' => '-',
                            'activity' => '-',
                            'materials_needed' => '-'
                        );
                    }
                }
            }
        }

        $this->set('lesson_plan_items', $lesson_plan_items);
        $this->set('results', count($lesson_plan_items));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu lesson plan', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduLessonPlan->recursive = 2;
        $this->set('edu_lesson_plan', $this->EduLessonPlan->read(null, $id));
    }

    function maintain($id = null) {
        if (!empty($this->data)) {
            $this->EduLessonPlan->create();
            $this->autoRender = false;
            if ($this->EduLessonPlan->save($this->data)) {
                $this->Session->setFlash(__('The edu lesson plan has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu lesson plan could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id) {
            $this->set('parent_id', $id);
        }
        $this->loadModel('Edu.EduClass');
        $edu_courses = $this->EduLessonPlan->EduCourse->find('list');
        $edu_sections = $this->EduLessonPlan->EduSection->find('list');
        $edu_classes = $this->EduClass->find('list');

        $this->set(compact('edu_courses', 'edu_sections', 'edu_classes'));
    }

    function save_lesson_plan() {
        $this->autoRender = false;
        
        $this->loadModel('EduPeriod');
        $this->loadModel('EduDay');
        $this->loadModel('EduOutline');
        
        $edu_section_id = str_replace('"', '', $this->data[0]['section_id']);
        $edu_course_id = str_replace('"', '', $this->data[0]['course_id']);
        
        $lesson_plan = $this->EduLessonPlan->find('first', array(
            'conditions' => array(
                'EduLessonPLan.edu_section_id' => $edu_section_id,
                'EduLessonPLan.edu_course_id' => $edu_course_id
            )
        ));
        if ($lesson_plan) {
            $this->EduLessonPlan->delete($lesson_plan['EduLessonPlan']['id']);
        }
        
        $lesson_plan = array('EduLessonPlan' => array());
        
        foreach ($this->data as $record) {
            $lesson_plan['EduLessonPlan']['edu_course_id'] = str_replace('"', '', $record['course_id']);
            $lesson_plan['EduLessonPlan']['edu_section_id'] = str_replace('"', '', $record['section_id']);
            $lesson_plan['EduLessonPlan']['maker_id'] = $this->Session->read('Auth.User.id');
            $lesson_plan['EduLessonPlan']['checker_id'] = 0;
            $lesson_plan['EduLessonPlan']['is_posted'] = false;
            $lesson_plan['EduLessonPlan']['posts'] = 0;
            $lesson_plan['EduLessonPlan']['status'] = 'Created';
            $lesson_plan['EduLessonPlan']['reason'] = 'Created';

            break;
        }
        $this->EduLessonPlan->create();
        $this->EduLessonPlan->save($lesson_plan);
        $lesson_plan_id = $this->EduLessonPlan->id;
        
        $this->log($this->data, 'debug');
        foreach ($this->data as $record) {
            $day_name = str_replace('"', '', $record['date']);
            $period_name = str_replace('"', '', $record['period']);
            $outline_name = str_replace('"', '', $record['outline']);
            $activity = str_replace('"', '', $record['activity']);
            $materials_needed = str_replace('"', '', $record['materials_needed']);

            $day = $this->EduDay->find('first', array('conditions' => array(
                    'date' => date('Y-m-d', strtotime($day_name))
            )));
            $this->log($day, 'debug');
            
            $edu_day_id = $day['EduDay']['id'];
            
            $week_day = date('N', strtotime($day_name));
            $period = $this->EduPeriod->find('first', array(
                'conditions' => array(
                    'period' => $period_name,
                    'day' => $week_day,
                    'edu_section_id' => str_replace('"', '', $record['section_id']),
                    'edu_course_id' => str_replace('"', '', $record['course_id']),
                )
            ));
            $this->log($period, 'debug');
            $edu_period_id = $period['EduPeriod']['id'];
            
            $edu_outline_id = 0;
            $outline_name_parts = explode('. ', $outline_name);
            if (count($outline_name_parts) > 1) {
                $outline = $this->EduOutline->find('first', array('conditions' => array(
                        'name' => $outline_name_parts[1],
                        'edu_course_id' => str_replace('"', '', $record['course_id'])
                )));
                $this->log($outline, 'debug');
                $edu_outline_id = $outline['EduOutline']['id'];
            }
            
            $edu_lesson_plan_item = array('EduLessonPlanItem' => array(
                'edu_lesson_plan_id' => $lesson_plan_id,
                'edu_period_id' => $edu_period_id,
                'edu_day_id' => $edu_day_id,
                'edu_outline_id' => $edu_outline_id,
                'activity' => $activity,
                'materials_needed' => $materials_needed
            ));
            $this->log($edu_lesson_plan_item, 'debug');
            $this->EduLessonPlan->EduLessonPlanItem->create();
            $this->EduLessonPlan->EduLessonPlanItem->save($edu_lesson_plan_item);
        }

        $this->Session->setFlash(__('Lesson Plan created successfully', true), '');
        $this->render('/elements/success');
    }
    
    function post_lesson_plan($id = null){
        
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for lesson plan', true), '');
            $this->render('/elements/failure');
        }
        $lp = $this->EduLessonPlan->read(null, $id);
        if($lp['EduLessonPlan']['is_posted']) {
            $this->log(__('Lesson plan is already posted.', true), 'debug');
            $this->Session->setFlash(__('Lesson plan is already posted.', true), '');
            $this->render('/elements/failure');
        }
        if(!in_array($lp['EduLessonPlan']['status'], array('Created', 'Returned'))) {
            $this->log(__('Lesson plan should be in Created or Retuned state.', true), 'debug');
            $this->Session->setFlash(__('Lesson plan should be in Created or Retuned state.', true), '');
            $this->render('/elements/failure');
        }
        
        $this->EduLessonPlan->read(null, $id);
        $this->EduLessonPlan->set(array(
            'is_posted' => true,
            'posts' => $lp['EduLessonPlan']['posts']+1,
            'status' => 'Posted'
        ));
        $this->EduLessonPlan->save();
        
        $this->Session->setFlash(__('Edu lesson plan deleted', true), '');
        $this->render('/elements/success');
    }
    
    function approve_lesson_plan($id = null){
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for lesson plan', true), '');
            $this->render('/elements/failure');
        }
        $lp = $this->EduLessonPlan->read(null, $id);
        if(!in_array($lp['EduLessonPlan']['status'], array('Posted'))) {
            $this->log(__('Lesson plan should be in Posted state.', true), 'debug');
            $this->Session->setFlash(__('Lesson plan should be in Posted state.', true), '');
            $this->render('/elements/failure');
        }
        
        $this->EduLessonPlan->read(null, $id);
        $this->EduLessonPlan->set(array(
            'checker_id' => $this->Session->read('Auth.User.id'),
            'status' => 'Approved'
        ));
        $this->EduLessonPlan->save();
        
        $this->Session->setFlash(__('Edu lesson plan deleted', true), '');
        $this->render('/elements/success');
    }
    
    function reject_lesson_plan($id = null){
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Lesson Plan', true), '');
            $this->render('/elements/failure');
        }
        if(!empty($this->data)){
            $this->autoRender = false;
            if (isset($this->data['EduLessonPlan']['returned'])) {
                unset($this->data['EduLessonPlan']['returned']);
                $this->data['EduLessonPlan']['status'] = 'Returned';
            } else {
                $this->data['EduLessonPlan']['status'] = 'Rejected';
            }
            $this->data['EduLessonPlan']['checker_id'] = $this->Session->read('Auth.User.id');
            
            if ($this->EduLessonPlan->save($this->data)) {
                if($this->data['EduLessonPlan']['status'] == 'Returned'){
                    $this->Session->setFlash(__('The lesson plan is returnd', true), '');
                } else {
                    $this->Session->setFlash(__('The lesson plan is rejected', true), '');
                }
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu period could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $edu_lesson_plan = $this->EduLessonPlan->read(null, $id);
        
        $this->set('edu_lesson_plan', $edu_lesson_plan);
    }
    
    function print_lesson_plan($id = null) {
        $this->loadModel('Edu.EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $this->EduLessonPlan->recursive = 4;
        $lesson_plan = $this->EduLessonPlan->read(null, $id);
        $this->set('company_url', Configure::read('company_url'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('academic_year', $ay['EduAcademicYear']['name']);
        $this->set('lesson_plan', $lesson_plan);
        
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for edu lesson plan', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduLessonPlan->delete($i);
                }
                $this->Session->setFlash(__('Edu lesson plan deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Edu lesson plan was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduLessonPlan->delete($id)) {
                $this->Session->setFlash(__('Edu lesson plan deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Edu lesson plan was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}
