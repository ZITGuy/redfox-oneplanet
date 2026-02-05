<?php

class EduPaymentsController extends EduAppController
{

    var $name = 'EduPayments';

    function index()
    {
        $edu_payment_schedules = $this->EduPayment->EduPaymentSchedule->find('all');
        $this->set(compact('edu_payment_schedules'));
    }

    function index2($id = null)
    {
        $this->set('parent_id', $id);
    }

    function due_payments()
    {}

    function completed_payments()
    {}

    function payment_collections()
    {}

    function generate_collections_report($start_dt, $end_dt, $opt, $ord)
    {
        $order_by = ($ord == 0 ? 'EduStudent.name' : ($ord == 1 ? 'EduStudent.identity_number' : 'EduPayment.date_paid'));
        $payments = $this->EduPayment->find('all', array(
            'conditions' => array(
                'EduPayment.date_paid >=' => $start_dt,
                'EduPayment.date_paid <=' => $end_dt,
                'EduPayment.is_paid' => 1
            ),
            'order' => $order_by
        ));

        $this->set('payments', $payments);
        $this->set('opt', $opt);
        $this->set('start_dt', $start_dt);
        $this->set('end_dt', $end_dt);
    }

    function uncollected_payments()
    {
        $this->loadModel('EduClass');

        $edu_classes = $this->EduClass->find('list', array(
            'order' => 'EduClass.cvalue'
        ));

        $this->set('edu_classes', $edu_classes);
    }

    function generate_uncollected_payments_report($class_id, $opt, $ord) {
        $this->layout = 'ajax';
		
		$order_by = ($ord == 0 ? 'EduStudent.name' : ($ord == 1 ? 'EduStudent.identity_number' : 'EduPayment.date_paid'));

        // read the students in the class
        $this->loadModel('EduAcademicYear');
        $this->loadModel('EduSection');
        $this->loadModel('EduRegistration');
        $this->loadModel('EduQuarter');
        $this->loadModel('EduClassPayment');
        $this->loadModel('EduStudent');

        // get the sections
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
		$this->EduSection->recursive = -1;
        $sections = $this->EduSection->find('all', array(
            'conditions' => array(
                'EduSection.edu_academic_year_id' => $ay['EduAcademicYear']['id'],
                'EduSection.edu_class_id' => $class_id
            )
        ));
        $secs = array();
        foreach ($sections as $section) {
            $secs[] = $section['EduSection']['id'];
        }
		$this->EduRegistration->recursive = 0;
		$students = $this->EduRegistration->find('all', array(
            'conditions' => array(
                'EduRegistration.edu_section_id' => $secs
            )
        ));
		
		$past_payments = array();

        // calculate uncollecteds for each of students. Refer Student Payment Form function
        foreach ($students as $student) {
            $conditions = array();
            $conditions['EduPayment.edu_student_id'] = $student['EduRegistration']['edu_student_id'];
            $conditions['EduPayment.edu_payment_schedule_id >'] = 0; // ?????
            $payments = $this->EduPayment->find('all', array(
                'conditions' => $conditions
            ));

			//pr($student);
			//pr($payments);
			
            // payment schedules are not set for the student
            $ps_conditions = array(
                'EduPaymentSchedule.edu_class_id' => $class_id,
                'EduPaymentSchedule.edu_academic_year_id' => $ay['EduAcademicYear']['id']
            );
			
			$this->EduPayment->EduPaymentSchedule->recursive = 0;
			
            $schedules = $this->EduPayment->EduPaymentSchedule->find('all', array(
                'conditions' => $ps_conditions
            ));

            foreach ($schedules as $schedule) {
                $p = $this->EduPayment->find('first', array(
                    'conditions' => array(
                        'EduPayment.edu_student_id' => $student['EduRegistration']['edu_student_id'],
                        'EduPayment.edu_payment_schedule_id' => $schedule['EduPaymentSchedule']['id']
                    )
                ));
                if (empty($p) || !$p) {
                    $this->EduPayment->create();
                    $payment_data = array(
                        'EduPayment' => array(
                            'edu_payment_schedule_id' => $schedule['EduPaymentSchedule']['id'],
                            'edu_student_id' => $student['EduStudent']['id'],
                            'is_paid' => false,
                            'date_paid' => date('Y-m-d'),
                            'paid_amount' => '0.00',
                            'cheque_number' => 'Not Set',
                            'cheque_amount' => '0.00',
                            'invoice' => 'Not Set',
                            'transaction_ref' => 'Not Set'
                        )
                    );
                    if (! $this->EduPayment->save($payment_data)) {
                        echo 'Cannot save' . pr($this->EduPayment->validationErrors, true);
                    }
                }
            }

            $siblings = $this->EduStudent->find('all', array(
                'conditions' => array(
                    'EduStudent.edu_parent_id' => $student['EduStudent']['edu_parent_id']
                ),
                'order' => 'EduStudent.registration_date'
            ));

            $student_order = 1;
            foreach ($siblings as $sibling) {
                if ($sibling['EduStudent']['id'] == $student['EduStudent']['id']) {
                    break;
                } else {
                    $student_order ++;
                }
            }
			
            $conditions['EduPayment.is_paid'] = false; // other fields are set above
            $payments = $this->EduPayment->find('all', array(
                'conditions' => $conditions
            ));
			
			$this->EduQuarter->recursive = 0;
			
            $quarters = $this->EduQuarter->find('all', array(
                'conditions' => array(
                    'EduQuarter.quarter_type' => 'E'
                ),
                'order' => 'EduQuarter.start_date ASC'
            ));

            foreach ($payments as &$payment) {
                $penalty = 15;  // this is the maximum penalty rate. NOTE: All payments have penalty
                if ($payment['EduPaymentSchedule']['due_date'] < $this->today()) {
                    $datetime1 = new DateTime($payment['EduPaymentSchedule']['due_date']);
                    $datetime2 = new DateTime($this->today());
                    $interval = $datetime1->diff($datetime2);

                    $days = $interval->format('%R%a');
                    // TODO: percent based like 7=5%,14=7%,21=9%,28=11%,35=13%
                    // ie if upto 7 due dates then payment * 5% is added on the payment amount
                    $penalty_setting = $this->getSystemSetting('PENALTY');
                    $ps = explode(',', $penalty_setting);
                    $penalty_settings = array();
                    if ($penalty_setting != "") {
                        foreach ($ps as $part) {
                            $parts = explode('=', $part);
                            $penalty_settings[$parts[0]] = str_replace('%', '', $parts[1]);
                        }
                        foreach ($penalty_settings as $k => $v) {
                            if ($days <= $k) {
                                $penalty = $v;
                                break;
                            }
                        }
                    }
					
                    $payment['EduPayment']['penalty'] = $penalty;
                    $ay_id = $student['EduStudent']['edu_academic_year_id'];
                    $class_payment = $this->EduClassPayment->find('first', array(
                        'conditions' => array(
                            'EduClassPayment.edu_academic_year_id' => $ay_id,
                            'EduClassPayment.edu_class_id' => $payment['EduPaymentSchedule']['edu_class_id']
                        )
                    ));
                    if ($class_payment && ! empty($class_payment)) {
                        $payment['EduPayment']['paid_amount'] = $class_payment['EduClassPayment']['tuition_fee'];
                    } else {
                        $payment['EduPayment']['paid_amount'] = 0;
                    }
					
                    // For Sibling Discount
                    $sibling_discount = $student_order > 1? 12: 0;

                    // TODO: percent based like 1=0%,2=10%,3=12%
                    $sibling_discount_setting = $this->getSystemSetting('SIBLING_DISCOUNT');
                    $sd = explode(',', $sibling_discount_setting);
                    $sibling_discount_settings = array();
                    foreach ($sd as $part) {
                        $parts = explode('=', $part);
                        // $sibling_discount_settings[$parts[0]] = str_replace('%', '', $parts[1]);
                    }

                    foreach ($sibling_discount_settings as $k => $v) {
                        if ($student_order <= $k) {
                            $sibling_discount = $v;
                            break;
                        }
                    }
					
                    $payment['EduPayment']['sibling_discount'] = $sibling_discount;
                    
                    $past_payments[] = $payment;
                }
            }
        }

        // if the student has uncollected payments, include him in the list

        // else do not include him.

        $this->set('payments', $past_payments);
        $this->set('opt', $opt);
        $this->set('today', $this->today());
    }

    function search()
    {}

    function list_data($id = null)
    {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_payment_schedule_id = (isset($_REQUEST['edu_payment_schedule_id'])) ? $_REQUEST['edu_payment_schedule_id'] : - 1;
        if ($id) {
            $edu_payment_schedule_id = ($id) ? $id : - 1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_payment_schedule_id != - 1) {
            $conditions['EduPayment.edu_payment_schedule_id'] = $edu_payment_schedule_id;
        }

        $this->set('edu_payments', $this->EduPayment->find('all', array(
            'conditions' => $conditions,
            'limit' => $limit,
            'offset' => $start
        )));
        $this->set('results', $this->EduPayment->find('count', array(
            'conditions' => $conditions
        )));
    }

    function list_data_payments()
    {
        $selected_student_id_number = (isset($_REQUEST['selected_student_id_number'])) ? $_REQUEST['selected_student_id_number'] : - 1;

        $conditions = array();
        $this->loadModel('Edu.EduStudent');
        $student = $this->EduStudent->getStudent($selected_student_id_number);

        if ($student === FALSE) {
            $this->log('Student is null in the edu_payments.list_data_payments function', 'debug');
            $this->set('edu_payments', array());
            $this->set('results', 0);
            return;
        }
        $conditions['EduPayment.edu_student_id'] = $student['EduStudent']['id'];
        $conditions['EduPayment.edu_payment_schedule_id >'] = 0; // ?????
        $payments = $this->EduPayment->find('all', array(
            'conditions' => $conditions
        ));

        // if (count($payments) == 0) {
        // payment schedules are not set for the student
        $last_reg = 0;

        $max = 0;
        $registration = array();
        foreach ($student['EduRegistration'] as $reg) {
            if ($reg['id'] > $max) {
                $last_reg = $reg['edu_class_id'];
                $max = $reg['id'];
                $registration = $reg;
            }
        }
        $this->loadModel('Edu.EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();

        $ps_conditions = array(
            'EduPaymentSchedule.edu_class_id' => $last_reg,
            'EduPaymentSchedule.edu_academic_year_id' => $ay['EduAcademicYear']['id']
        );
        $schedules = $this->EduPayment->EduPaymentSchedule->find('all', array(
            'conditions' => $ps_conditions
        ));

        foreach ($schedules as $schedule) {
            $p = $this->EduPayment->find('first', array(
                'conditions' => array(
                    'EduPayment.edu_student_id' => $student['EduStudent']['id'],
                    'EduPayment.edu_payment_schedule_id' => $schedule['EduPaymentSchedule']['id']
                )
            ));
            if (empty($p) || ! $p) {
                $this->EduPayment->create();
                $payment_data = array(
                    'EduPayment' => array(
                        'edu_payment_schedule_id' => $schedule['EduPaymentSchedule']['id'],
                        'edu_student_id' => $student['EduStudent']['id'],
                        'is_paid' => false,
                        'date_paid' => date('Y-m-d'),
                        'paid_amount' => '0.00',
                        'cheque_number' => 'Not Set',
                        'cheque_amount' => '0.00',
                        'invoice' => 'Not Set',
                        'transaction_ref' => 'Not Set'
                    )
                );
                if (! $this->EduPayment->save($payment_data)) {
                    echo 'Cannot save' . pr($this->EduPayment->validationErrors, true);
                }
            }
        }
        // }
        $this->loadModel('EduQuarter');
        $this->loadModel('EduClassPayment');

        $siblings = $this->EduStudent->find('all', array(
            'conditions' => array(
                'EduStudent.edu_parent_id' => $student['EduStudent']['edu_parent_id']
            ),
            'order' => 'EduStudent.registration_date'
        ));

        $student_order = 1;
        foreach ($siblings as $sibling) {
            if ($sibling['EduStudent']['id'] == $student['EduStudent']['id']) {
                break;
            } else {
                $student_order ++;
            }
        }
        $conditions['EduPayment.is_paid'] = false;
        $payments = $this->EduPayment->find('all', array(
            'conditions' => $conditions
        ));
        $quarters = $this->EduQuarter->find('all', array(
            'conditions' => array(
                'EduQuarter.quarter_type' => 'E'
            ),
            'order' => 'EduQuarter.start_date ASC'
        ));

        foreach ($payments as &$payment) {
            $penalty = 15;
            if ($payment['EduPaymentSchedule']['due_date'] < $this->today()) {
                $datetime1 = new DateTime($payment['EduPaymentSchedule']['due_date']);
                $datetime2 = new DateTime($this->today());
                $interval = $datetime1->diff($datetime2);

                $days = $interval->format('%R%a');
                // TODO: percent based like 7=5%,14=7%,21=9%,28=11%,35=13%
                // ie if upto 7 due dates then payment * 5% is added on the payment amount
                $penalty_setting = $this->getSystemSetting('PENALTY');
                $ps = explode(',', $penalty_setting);
                $penalty_settings = array();
                if ($penalty_setting != "") {
                    foreach ($ps as $part) {
                        $parts = explode('=', $part);
                        $penalty_settings[$parts[0]] = str_replace('%', '', $parts[1]);
                    }
                    foreach ($penalty_settings as $k => $v) {
                        if ($days <= $k) {
                            $penalty = $v;
                            break;
                        }
                    }
                }
            } else {
                $penalty = 0;
            }
            $payment['penalty'] = $penalty;
            $ay_id = $student['EduStudent']['edu_academic_year_id'];
            $class_payment = $this->EduClassPayment->find('first', array(
                'conditions' => array(
                    'EduClassPayment.edu_academic_year_id' => $ay_id,
                    'EduClassPayment.edu_class_id' => $payment['EduPaymentSchedule']['edu_class_id']
                )
            ));
            if ($class_payment && ! empty($class_payment)) {
                $payment['amount'] = $class_payment['EduClassPayment']['tuition_fee'];
            } else {
                $payment['amount'] = 0;
            }
            // For Sibling Discount
            $sibling_discount = 12;

            // TODO: percent based like 1=0%,2=10%,3=12%
            $sibling_discount_setting = $this->getSystemSetting('SIBLING_DISCOUNT');
            $sd = explode(',', $sibling_discount_setting);
            $sibling_discount_settings = array();
            foreach ($sd as $part) {
                $parts = explode('=', $part);
                // $sibling_discount_settings[$parts[0]] = str_replace('%', '', $parts[1]);
            }

            foreach ($sibling_discount_settings as $k => $v) {
                if ($student_order <= $k) {
                    $sibling_discount = $v;
                    break;
                }
            }
            $payment['sibling_discount'] = $sibling_discount;
        }

        $this->set('quarters', $quarters);
        $this->set('payment_schedule_method', $this->getSystemSetting('PAYMENT_SCHEDULE_METHOD'));
        $this->set('registration', $registration);
        $this->set('edu_payments', $payments);
        $this->set('results', $this->EduPayment->find('count', array(
            'conditions' => $conditions
        )));
    }

    function view($id = null)
    {
        if (! $id) {
            $this->Session->setFlash(__('Invalid edu payment', true));
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->EduPayment->recursive = 2;
        $this->set('edu_payment', $this->EduPayment->read(null, $id));
    }

    function make_payments()
    {
        if ($this->Session->check('edu_student_id')) {
            $edu_student_id = $this->Session->read('edu_student_id');
            $this->set('edu_student_id', $edu_student_id);
            $this->loadModel('Edu.EduStudent');
            $student = $this->EduStudent->read(null, $edu_student_id);
            $this->set('student', $student);
            // other settings
            $this->set('term_name', $this->getSystemSetting('TERM_NAME'));
            $this->Session->delete('edu_student_id');
        }
        if ($this->Session->check('edu_receipt_id')) {
            $this->set('edu_receipt_id', $this->Session->read('edu_receipt_id'));
        }
        $this->loadModel('EduSection');
        $this->loadModel('EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();

        $conditions = array(
            'EduSection.edu_academic_year_id' => $ay['EduAcademicYear']['id']
        );
        $edu_sections = $this->EduSection->find('all', array(
            'conditions' => $conditions
        ));
        $sections = array();
        foreach ($edu_sections as $edu_section) {
            $sections[$edu_section['EduSection']['id']] = $edu_section['EduClass']['name'] . ' - ' . $edu_section['EduSection']['name'];
        }
        $this->set(compact('sections'));
    }

    function save_changes()
    {
        $this->autoRender = false;
        $additionals = $this->data['additionals'];
        unset($this->data['additionals']);

        $dr_side = array();
        // $additionals['total_amount'] -= $additionals['penalty_amount'];
        // $additionals['total_amount'] += $additionals['discount_amount'];
        // the total is to be sent to acct is total + penalty - discount
        $net_total = $additionals['total_amount'] + $additionals['penalty_amount'] - $additionals['discount_amount'];

        if ($additionals['cheque_amount'] > 0) { // if cheque payment is involved
            $dr_side[$this->getSystemSetting('RECEIVABLE_GL_ACCOUNT')] = $additionals['cheque_amount']; // the cheque DR side trans
            if ($additionals['cheque_amount'] == ($additionals['total_amount'] - $additionals['discount_amount'])) {
                // all payment is done by cheque
            } else {
                if ((($additionals['total_amount'] - $additionals['discount_amount']) - $additionals['cheque_amount']) < 0) {
                    $dr_side[$this->getSystemSetting('CASH_GL_ACCOUNT')] = 0;
                    $dr_side[$this->getSystemSetting('RECEIVABLE_GL_ACCOUNT')] = $additionals['total_amount'];
                } else {
                    $dr_side[$this->getSystemSetting('CASH_GL_ACCOUNT')] = (($additionals['total_amount'] - $additionals['discount_amount']) - $additionals['cheque_amount']); // the Cash DR side trans
                }
            }
        } else { // if all payment is in cash
            $dr_side[$this->getSystemSetting('CASH_GL_ACCOUNT')] = $net_total;
        }
        $cr_side = array();
        $cr_side[$this->getSystemSetting('TUITION_GL_ACCOUNT')] = $net_total;

        // TODO: here we have so many fields.
        $transaction = array(
            'dr_side' => $dr_side,
            'cr_side' => $cr_side, // TODO: Configurable item
            'cheque_number' => $additionals['cheque_number'],
            'invoice_number' => $additionals['invoice'],
            'description' => 'Student Payment ... ',
            'return' => ''
        );
        // put the request data to session
        $this->Session->write('transaction', $transaction);

        // call the acct module to save the transaction using the session data
        $ret = $this->requestAction(array(
            'controller' => 'acct_transactions',
            'action' => 'save_transaction_v2',
            'plugin' => 'acct'
        ), array(
            'pass' => $transaction
        ));
        // $ret is the reference number of the transaction

        $this->loadModel('Edu.EduStudent');
        $this->loadModel('Edu.EduReceipt');
        $this->loadModel('Edu.EduAcademicYear');
        $this->loadModel('Edu.EduExtraPayment');

        $payments = array();

        // If receipt is not in session, create receipt object and set the receipt_id var here.
        $edu_receipt_id = 0;
        if ($this->Session->check('edu_receipt_id')) {
            $edu_receipt_id = $this->Session->read('edu_receipt_id');
        } else {
            $this->EduStudent->recursive = 3;
            $student = $this->EduStudent->getStudent($additionals['selected_student']);

            // Restart Invoice Number for new Academic year
            $ay = $this->EduAcademicYear->getActiveAcademicYear();
            $cond = array(
                'EduReceipt.invoice_date >=' => $ay['EduAcademicYear']['start_date'],
                'EduReceipt.invoice_date <=' => $ay['EduAcademicYear']['end_date']
            );
            $re = $this->EduReceipt->find('first', array(
                'conditions' => $cond,
                'order' => 'EduReceipt.reference_number DESC'
            ));
            $reference_number = 1;
            if (! empty($re)) {
                $reference_number = $re['EduReceipt']['reference_number'] + 1;
            }

            if ($additionals['crm_number'] == '') {
                $additionals['crm_number'] = 'AUTO-' . time();
            }
            $receipt = array(
                'EduReceipt' => array(
                    'name' => $this->Session->read('transaction.return'),
                    'reference_number' => $reference_number,
                    'invoice_date' => date('Y-m-d'),
                    'crm_number' => $additionals['crm_number'],
                    'parent_name' => $student['EduParent']['authorized_person'],
                    'parent_address' => $student['EduParent']['EduParentDetail'][0]['work_address'] . '<br>' . $student['EduParent']['EduParentDetail'][0]['telephone'],
                    'edu_student_id' => $student['EduStudent']['id'],
                    'student_name' => $student['EduStudent']['name'],
                    'student_number' => $student['EduStudent']['identity_number'],
                    'student_class' => $student['EduRegistration'][0]['EduClass']['name'],
                    'student_section' => (isset($student['EduRegistration'][0]['EduSection']['name']) ? $student['EduRegistration'][0]['EduSection']['name'] : 'Not Set'),
                    'student_academic_year' => $ay['EduAcademicYear']['name'],
                    'total_before_tax' => $net_total,
                    'total_after_tax' => $net_total,
                    'VAT' => 0,
                    'TOT' => 0
                )
            );

            $this->EduReceipt->create();
            if ($this->EduReceipt->save($receipt)) {
                $edu_receipt_id = $this->EduReceipt->id;
                $this->Session->write('edu_receipt_id', $edu_receipt_id);
            }
        }

        // ???

        $this->loadModel('EduQuarter');

        $quarters = $this->EduQuarter->find('all', array(
            'conditions' => array(
                'EduQuarter.quarter_type' => 'E'
            ),
            'order' => 'EduQuarter.start_date ASC'
        ));

        $payment_schedule_method = $this->getSystemSetting('PAYMENT_SCHEDULE_METHOD');

        $months = array();
        if ($payment_schedule_method == 'M') {
            $months = array(
                1 => 'September',
                'October',
                'November',
                'December',
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August'
            );
        } else {
            $i = 1;
            foreach ($quarters as $quarter) {
                $months[$i ++] = $quarter['EduQuarter']['name'];
            }
        }

        if (isset($this->data['payments'])) {
            foreach ($this->data['payments'] as $record) {
                $re_id = str_replace('"', '', $record['id']);
                $amount = str_replace('"', '', $record['amount']);
                $cheque_number = str_replace('"', '', $additionals['cheque_number']);
                $cheque_amount = str_replace('"', '', $additionals['cheque_amount']);
                $invoice = str_replace('"', '', $additionals['invoice']);

                $transaction = $this->Session->read('transaction');

                $payment = $this->EduPayment->read(null, $re_id);
                $penalty = 0;
                if ($payment['EduPaymentSchedule']['due_date'] < date('Y-m-d')) {
                    $datetime1 = new DateTime($payment['EduPaymentSchedule']['due_date']);
                    $datetime2 = new DateTime(date('Y-m-d'));
                    $interval = $datetime1->diff($datetime2);

                    $days = $interval->format('%R%a');
                    $penalty_setting = $this->getSystemSetting('PENALTY_PER_DAY');
                    $penalty = $days * $penalty_setting;
                }
                // $payment['penalty'] = $penalty;
                $discount = ($amount / $additionals['total_amount']) * $additionals['discount_amount'];
                $sibling_discount = ($amount / $additionals['total_amount']) * $additionals['sibling_discount_amount'];

                $this->EduPayment->set(array(
                    'is_paid' => 1,
                    'date_paid' => date('Y-m-d'),
                    'paid_amount' => $amount,
                    'penalty' => $penalty,
                    'discount' => $discount,
                    'sibling_discount' => $sibling_discount,
                    'cheque_number' => $cheque_number,
                    'cheque_amount' => $cheque_amount,
                    'invoice' => $invoice,
                    'transaction_ref' => $transaction['return']
                ));

                $this->EduPayment->save();

                $payments[] = $re_id;

                // Save the payment as a receipt item with the above receipt_id
                $item = array(
                    'name' => 'Payment for ' . $months[$payment['EduPaymentSchedule']['month']],
                    'amount' => $amount,
                    'edu_receipt_id' => $edu_receipt_id
                );
                $this->EduReceipt->EduReceiptItem->create();
                $this->EduReceipt->EduReceiptItem->save($item);
            }
        }
        if (isset($this->data['extra_payments'])) {
            foreach ($this->data['extra_payments'] as $record) {
                $re_id = str_replace('"', '', $record['id']);
                $name = str_replace('"', '', $record['name']);
                $amount = str_replace('"', '', $record['amount']);
                $cheque_number = str_replace('"', '', $additionals['cheque_number']);
                $cheque_amount = str_replace('"', '', $additionals['cheque_amount']);
                $invoice = str_replace('"', '', $additionals['invoice']);

                $payment = $this->EduExtraPayment->read(null, $re_id);
                $this->EduExtraPayment->set(array(
                    'is_paid' => 1,
                    'date_paid' => date('Y-m-d'),
                    'paid_amount' => $amount,
                    'cheque_number' => $cheque_number,
                    'cheque_amount' => $cheque_amount,
                    'invoice' => $invoice
                ));

                $this->EduExtraPayment->save();

                $payments[] = 'ex-' . $re_id;

                // Save the payment as a receipt item with the above receipt_id
                $item = array(
                    'name' => 'Extra payment paid for ' . $name,
                    'amount' => $amount,
                    'edu_receipt_id' => $edu_receipt_id
                );
                $this->EduReceipt->EduReceiptItem->create();
                $this->EduReceipt->EduReceiptItem->save($item);
            }
        }

        // Save the payment penalty and discount as receipt items with the above receipt_id
        $item = array(
            'name' => 'Penalty paid',
            'amount' => $additionals['penalty_amount'],
            'edu_receipt_id' => $edu_receipt_id
        );
        $this->EduReceipt->EduReceiptItem->create();
        $this->EduReceipt->EduReceiptItem->save($item);

        // discount
        $item = array(
            'name' => 'Discount/Deductions',
            'amount' => $additionals['discount_amount'] * (- 1),
            'edu_receipt_id' => $edu_receipt_id
        );

        $this->EduReceipt->EduReceiptItem->create();
        $this->EduReceipt->EduReceiptItem->save($item);

        // sibling discount
        $item = array(
            'name' => 'Sibling Discount',
            'amount' => $additionals['sibling_discount_amount'] * (- 1),
            'edu_receipt_id' => $edu_receipt_id
        );

        $this->EduReceipt->EduReceiptItem->create();
        $this->EduReceipt->EduReceiptItem->save($item);

        $this->Session->save('payments', $payments);
    }

    function print_receipt()
    {
        $payments = $this->Session->read('payments');

        $this->set('payments', $payments);
    }

    function add($id = null)
    {
        if (! empty($this->data)) {
            $this->EduPayment->create();
            $this->autoRender = false;
            if ($this->EduPayment->save($this->data)) {
                $this->Session->setFlash(__('The edu payment has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu payment could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id) {
            $this->set('parent_id', $id);
        }
        $edu_payment_schedules = $this->EduPayment->EduPaymentSchedule->find('list');
        $edu_students = $this->EduPayment->EduStudent->find('list');
        $this->set(compact('edu_payment_schedules', 'edu_students'));
    }

    function edit($id = null, $parent_id = null)
    {
        if (! $id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu payment', true), '');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        if (! empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduPayment->save($this->data)) {
                $this->Session->setFlash(__('The edu payment has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu payment could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_payment', $this->EduPayment->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $edu_payment_schedules = $this->EduPayment->EduPaymentSchedule->find('list');
        $edu_students = $this->EduPayment->EduStudent->find('list');
        $this->set(compact('edu_payment_schedules', 'edu_students'));
    }

    function delete($id = null)
    {
        $this->autoRender = false;
        if (! $id) {
            $this->Session->setFlash(__('Invalid id for edu payment', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduPayment->delete($i);
                }
                $this->Session->setFlash(__('Edu payment deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Edu payment was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduPayment->delete($id)) {
                $this->Session->setFlash(__('Edu payment deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Edu payment was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }
}
