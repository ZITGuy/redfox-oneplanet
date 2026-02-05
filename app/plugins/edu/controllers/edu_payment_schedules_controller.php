<?php

class EduPaymentSchedulesController extends EduAppController {

    var $name = 'EduPaymentSchedules';

    function index() {
        $edu_classes = $this->EduPaymentSchedule->EduClass->find('all');
        $this->set(compact('edu_classes'));
        $this->set('payment_schedule_method', $this->getSystemSetting('PAYMENT_SCHEDULE_METHOD'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
        $this->set('edu_class', $this->EduPaymentSchedule->EduClass->read(null, $id));
        $this->set('payment_schedule_method', $this->getSystemSetting('PAYMENT_SCHEDULE_METHOD'));
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_class_id = (isset($_REQUEST['edu_class_id'])) ? $_REQUEST['edu_class_id'] : -1;
        if ($id) {
            $edu_class_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_class_id != -1) {
            $conditions['EduPaymentSchedule.edu_class_id'] = $edu_class_id;
        }

        $this->set('payment_schedule_method', $this->getSystemSetting('PAYMENT_SCHEDULE_METHOD'));
        $this->set('edu_payment_schedules', $this->EduPaymentSchedule->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduPaymentSchedule->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu payment schedule', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduPaymentSchedule->recursive = 2;
        $this->set('edu_payment_schedule', $this->EduPaymentSchedule->read(null, $id));
    }

    function add($id = null) {
        /**
         * Active academic year
         */
        $ay = $this->EduPaymentSchedule->EduAcademicYear->getActiveAcademicYear();
        if (!empty($this->data)) {
            $this->autoRender = false;

            if ($ay === FALSE) {
                $this->Session->setFlash(__('The payment schedule could not be saved, because there is no active academic year. Please, try again.', true), '');
                $this->render('/elements/failure');
            } else {
                $ds = $this->EduPaymentSchedule->getDataSource();
                $ds->begin($this->EduPaymentSchedule);

                $this->data['EduPaymentSchedule']['edu_academic_year_id'] = $ay['EduAcademicYear']['id'];
                $this->EduPaymentSchedule->create();
                if ($this->EduPaymentSchedule->save($this->data)) {
                    $the_id = $this->EduPaymentSchedule->id;
                    //$ds->commit($this->EduPaymentSchedule);
                    $ds->rollback($this->EduPaymentSchedule);
                    $this->Session->setFlash(__('The edu payment schedule has been saved', true) . $the_id, '');
                    $this->render('/elements/success');
                } else {
                    $this->Session->setFlash(__('The edu payment schedule could not be saved. Please, try again.', true), '');
                    $this->render('/elements/failure');
                }
            }
        }
        if ($id)
            $this->set('parent_id', $id);

        $setting = $this->getSystemSetting('PAYMENT_SCHEDULE_METHOD');

        $ps_options = array();
        if ($ay !== FALSE) {
            $schedules = $this->EduPaymentSchedule->find('all', array('conditions' => array(
                    'EduPaymentSchedule.edu_academic_year_id' => $ay['EduAcademicYear']['id'],
                    'EduPaymentSchedule.edu_class_id' => $id,
            )));
            foreach ($schedules as $schedule) {
                $ps_options[] = $schedule['EduPaymentSchedule']['month'];
            }
        }

        $edu_classes = $this->EduPaymentSchedule->EduClass->find('list');
        $this->set(compact('edu_classes', 'ay', 'setting', 'ps_options'));
    }

    function schedule($id = null) {
        if (!empty($this->data)) {
            $ay = $this->EduPaymentSchedule->EduAcademicYear->getActiveAcademicYear();
            if ($ay === FALSE) {
                $this->Session->setFlash(__('The payment schedule could not be saved, because there is no active academic year. Please, try again.', true), '');
                $this->render('/elements/failure');
            } else {
                $terms = 0;
				$days = $this->getSystemSetting('DUE_DAY_AFTER_START_DATE');
                $term_due_dates = array();
                foreach ($ay['EduQuarter'] as $quarter) {
                    if($quarter['quarter_type'] == 'E') {
                        $terms++;
                        $term_due_dates[$terms] = date('Y-m-d', strtotime($quarter['start_date'] . ' +' . ($days - 1) . ' days'));    // ????
                    }
                }
                $payment_method = $this->getSystemSetting('PAYMENT_SCHEDULE_METHOD');
                $m = ($payment_method == 'M')? 10: $terms;
                $months = array(1 => 'September', 'October', 'November', 'December', 'January', 
                    'February', 'March', 'April', 'May', 'June');
                for ($i = 1; $i <= $m; $i++) {
                    $this->EduPaymentSchedule->create();
                    $schedule = array('EduPaymentSchedule' => array(
                            'edu_class_id' => $this->data['EduPaymentSchedule']['edu_class_id'],
                            'month' => $i,
                            'edu_academic_year_id' => $ay['EduAcademicYear']['id'],
                            'due_date' => ($m == 10? date('Y-m-01', strtotime('next ' . $months[$i] . ' +' . ($days - 1) . ' days')): $term_due_dates[$i])
                        )
                    );

                    $this->EduPaymentSchedule->save($schedule);
                }
                $this->Session->setFlash(__('The payment schedules have been saved', true), '');
                $this->render('/elements/success');
            }
        }
        if ($id){
            $this->set('parent_id', $id);
        }
        $class_id = $id;

        $ay = $this->EduPaymentSchedule->EduAcademicYear->getActiveAcademicYear();
        $academic_year_id = 0;
        if ($ay === FALSE) {
            $academic_year_id = 0;
        } else {
            $academic_year_id = $ay['EduAcademicYear']['id'];
        }
        $all_payment_Schedules = $this->EduPaymentSchedule->find('all', array('conditions' => array('EduPaymentSchedule.edu_academic_year_id' => $academic_year_id)));
        $completed_class_ids = array();
        foreach ($all_payment_Schedules as $ps) {
            if (!in_array($ps['EduPaymentSchedule']['edu_class_id'], $completed_class_ids)) {
                $completed_class_ids[] = $ps['EduPaymentSchedule']['edu_class_id'];
            }
        }

        if (in_array($class_id, $completed_class_ids)) {
            $this->cakeError('cannotRedefineRecord', array(
                'message' => 'Cannot make the payment schedules for the current class. It is already scheduled. (ERR-1404)',
                'helpcode' => 'ERR-1404'));
        }
		$this->loadModel('Edu.EduClassPayment');
		$ays = $this->EduPaymentSchedule->EduAcademicYear->find('count');
		$cps = $this->EduClassPayment->find('count', array('conditions' => array('EduClassPayment.edu_class_id' => $class_id)));
		if($ays != $cps) {
			$this->cakeError('cannotSaveRecord', array(
                'message' => 'Cannot create payment schedules for the current class. Its batch payment is not fully defined. (ERR-1404)',
                'helpcode' => 'ERR-1404'));
		}
        $edu_classes = $this->EduPaymentSchedule->EduClass->find('list', array('conditions' => array("NOT" => array('EduClass.id' => $completed_class_ids))));

        $this->set(compact('edu_classes'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu payment schedule', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            unset($this->data['EduPaymentSchedule']['month']);
            if ($this->EduPaymentSchedule->save($this->data)) {
                $this->Session->setFlash(__('The payment schedule has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu payment schedule could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_payment_schedule', $this->EduPaymentSchedule->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $edu_classes = $this->EduPaymentSchedule->EduClass->find('list');
        $this->set(compact('edu_classes'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->cakeError('cannotDeleteRecord', array(
                'message' => 'Invalid id for Payment Schedule. (ERR-1602)',
                'helpcode' => 'ERR-1602'));
        }
        $payment_schedule = $this->EduPaymentSchedule->read(null, $id);
        if (count($payment_schedule['EduPayment']) > 0) {
            $this->cakeError('cannotDeleteRecord', array(
                'message' => 'Payment Schedule has related records in other locations. (ERR-1601)',
                'helpcode' => 'ERR-1601'));
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduPaymentSchedule->delete($i);
                }
                $this->Session->setFlash(__('Payment Schedules successfully deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->cakeError('cannotDeleteRecord', array(
                    'message' => 'Payment Schedule cannot be deleted. (' .$e->getMessage() . '). (ERR-1603)',
                    'helpcode' => 'ERR-1603'));
            }
        } else {
            if ($this->EduPaymentSchedule->delete($id)) {
                $this->Session->setFlash(__('Payment Schedule successfully deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotDeleteRecord', array(
                    'message' => 'Payment Schedulet cannot be deleted. (ERR-1603)',
                    'helpcode' => 'ERR-1603'));
            }
        }
    }

}