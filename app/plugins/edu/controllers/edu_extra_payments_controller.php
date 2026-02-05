<?php

class EduExtraPaymentsController extends EduAppController {

    var $name = 'EduExtraPayments';

    function index() {
        $edu_extra_payment_settings = $this->EduExtraPayment->EduExtraPaymentSetting->find('all');
        $this->set(compact('edu_extra_payment_settings'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_extra_payment_setting_id = (isset($_REQUEST['edu_extra_payment_setting_id'])) ? $_REQUEST['edu_extra_payment_setting_id'] : -1;
        if ($id)
            $edu_extra_payment_setting_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_extra_payment_setting_id != -1) {
            $conditions['EduExtraPayment.edu_extra_payment_setting_id'] = $edu_extra_payment_setting_id;
        }

        $this->set('edu_extra_payments', $this->EduExtraPayment->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduExtraPayment->find('count', array('conditions' => $conditions)));
    }
    
    function list_data_payments() {
        $selected_student_id_number = (isset($_REQUEST['selected_student_id_number'])) ? $_REQUEST['selected_student_id_number'] : -1;

        $conditions = array();
        $this->loadModel('Edu.EduStudent');
        $student = $this->EduStudent->getStudent($selected_student_id_number);

        if ($student === FALSE) {
            $this->log('Student is null in the edu_payments.list_data_payments function', 'debug');
            $this->set('edu_extra_payments', array());
            $this->set('results', 0);
            return;
        }
        $conditions['EduExtraPayment.edu_student_id'] = $student['EduStudent']['id'];
        $conditions['EduExtraPayment.edu_extra_payment_setting_id >'] = 0;
        $extra_payments = $this->EduExtraPayment->find('all', array('conditions' => $conditions));
		
        //if (count($extra_payments) == 0) {
            // payment schedules are not set for the student
            $last_reg = 0;
            
            $max = 0;
			$current_reg_id = 0;
            foreach ($student['EduRegistration'] as $reg) {
                if ($reg['id'] > $max) {
                    $last_reg = $reg['edu_class_id'];
					$current_reg_id = $reg['id'];
                    $max = $reg['id'];
                }
            }
            $this->loadModel('Edu.EduAcademicYear');
            $this->loadModel('Edu.EduRegistrationPreference');
            $this->loadModel('Edu.EduExtraPaymentSetting');
            $ay = $this->EduAcademicYear->getActiveAcademicYear();

			$eptypes = array();
			$prefs = $this->EduRegistrationPreference->find('all', array(
				'conditions' => array(
					'EduRegistrationPreference.edu_registration_id' => $current_reg_id, 
					'EduRegistrationPreference.is_applicable' => 1
				)));
			foreach($prefs as $pref) {
				$eptypes[] = $pref['EduRegistrationPreference']['edu_extra_payment_type_id'];
			}
			
            $con = array(
                'EduExtraPaymentSetting.edu_class_id' => $last_reg,
                'EduExtraPaymentSetting.edu_academic_year_id' => $ay['EduAcademicYear']['id'],
				'EduExtraPaymentSetting.edu_extra_payment_type_id' => $eptypes
            );
            $ep_settings = $this->EduExtraPaymentSetting->find('all', array('conditions' => $con));

			//$this->log($ep_settings, 'dudu');
			
            foreach ($ep_settings as $ep_setting) {
				$p = $this->EduExtraPayment->find('first', array(
					'conditions' => array(
						'EduExtraPayment.edu_student_id' => $student['EduStudent']['id'],
						'EduExtraPayment.edu_extra_payment_setting_id' => $ep_setting['EduExtraPaymentSetting']['id']
					))
				);
				if(empty($p) || !$p) {
					$this->EduExtraPayment->create();
					$payment_data = array('EduExtraPayment' => array(
							'edu_extra_payment_setting_id' => $ep_setting['EduExtraPaymentSetting']['id'],
							'edu_student_id' => $student['EduStudent']['id'],
							'is_paid' => false,
							'date_paid' => date('Y-m-d'),
							'paid_amount' => '0.00',
							'cheque_number' => 'Not Set',
							'cheque_amount' => '0.00',
							'invoice' => 'Not Set',
							'transaction_ref' => 'Not Set'
					));
					if (!$this->EduExtraPayment->save($payment_data)) {
						echo 'Cannot save' . pr($this->EduExtraPayment->validationErrors, true);
					}
				}
            }
        //}
        $conditions['EduExtraPayment.is_paid'] = false;
        $ex_payments = $this->EduExtraPayment->find('all', array('conditions' => $conditions));

        $this->set('edu_extra_payments', $ex_payments);
        $this->set('results', $this->EduExtraPayment->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu extra payment', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduExtraPayment->recursive = 2;
        $this->set('eduExtraPayment', $this->EduExtraPayment->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->EduExtraPayment->create();
            $this->autoRender = false;
            if ($this->EduExtraPayment->save($this->data)) {
                $this->Session->setFlash(__('The edu extra payment has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu extra payment could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
        $edu_extra_payment_settings = $this->EduExtraPayment->EduExtraPaymentSetting->find('list');
        $edu_students = $this->EduExtraPayment->EduStudent->find('list');
        $this->set(compact('edu_extra_payment_settings', 'edu_students'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu extra payment', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduExtraPayment->save($this->data)) {
                $this->Session->setFlash(__('The edu extra payment has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu extra payment could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_extra_payment', $this->EduExtraPayment->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $edu_extra_payment_settings = $this->EduExtraPayment->EduExtraPaymentSetting->find('list');
        $edu_students = $this->EduExtraPayment->EduStudent->find('list');
        $this->set(compact('edu_extra_payment_settings', 'edu_students'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for edu extra payment', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduExtraPayment->delete($i);
                }
                $this->Session->setFlash(__('Edu extra payment deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Extra payment was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduExtraPayment->delete($id)) {
                $this->Session->setFlash(__('Edu extra payment deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Edu extra payment was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }
}
