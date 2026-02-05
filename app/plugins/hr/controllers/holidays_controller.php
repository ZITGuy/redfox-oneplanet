<?php

class HolidaysController extends HrAppController {

    var $name = 'Holidays';

    function index() {
        //$this->autoRender = false;
        $employees = $this->Holiday->Employee->find('all');
        $this->set(compact('employees'));
        $emp = $this->Holiday->Employee->findByuser_id($this->Session->read('Auth.User.id'));
        $tot = 0;
        if (!empty($emp)) {
            $balance = $this->Holiday->LeaveType->LeaveRule->find('all', array('conditions' => array('LeaveRule.employee_id' => $emp['Employee']['id'], 'LeaveRule.leave_type_id' => 1)));
            if (!empty($balance)) {

                $this->data['LeaveRule']['total'] = $this->calculate_annual_leave($emp['Employee']['id']);
                $this->data['LeaveRule']['balance'] = $this->data['LeaveRule']['total'] - $balance[0]['LeaveRule']['taken'];
                $this->data['LeaveRule']['id'] = $balance[0]['LeaveRule']['id'];
                $this->Holiday->LeaveType->LeaveRule->save($this->data);

                $tot = $this->data['LeaveRule']['balance'];

                $halfbalance = $this->Holiday->LeaveType->LeaveRule->find('all', array('conditions' => array('LeaveRule.employee_id' => $emp['Employee']['id'], 'LeaveRule.leave_type_id' => 2)));
                $this->data['LeaveRule']['total'] = $this->data['LeaveRule']['total'] * 2;
                $this->data['LeaveRule']['balance'] = $this->data['LeaveRule']['total'] - $halfbalance[0]['LeaveRule']['taken'];
                $this->data['LeaveRule']['id'] = $halfbalance[0]['LeaveRule']['id'];
                $this->Holiday->LeaveType->LeaveRule->save($this->data);
            } else {
                $this->initializeleave($emp['Employee']['id']);
                $balance = $this->Holiday->LeaveType->LeaveRule->find('all', array('conditions' => array('LeaveRule.employee_id' => $emp['Employee']['id'], 'LeaveRule.leave_type_id' => 1)));

                $this->data['LeaveRule']['total'] = $this->calculate_annual_leave($emp['Employee']['id']);
                $this->data['LeaveRule']['balance'] = $this->data['LeaveRule']['total'] - $balance[0]['LeaveRule']['taken'];
                $this->data['LeaveRule']['id'] = $balance[0]['LeaveRule']['id'];
                $this->Holiday->LeaveType->LeaveRule->save($this->data);

                $tot = $this->data['LeaveRule']['balance'];

                $halfbalance = $this->Holiday->LeaveType->LeaveRule->find('all', array('conditions' => array('LeaveRule.employee_id' => $emp['Employee']['id'], 'LeaveRule.leave_type_id' => 2)));
                $this->data['LeaveRule']['total'] = $this->data['LeaveRule']['total'] * 2;
                $this->data['LeaveRule']['balance'] = $this->data['LeaveRule']['total'] - $halfbalance[0]['LeaveRule']['taken'];
                $this->data['LeaveRule']['id'] = $halfbalance[0]['LeaveRule']['id'];
                $this->Holiday->LeaveType->LeaveRule->save($this->data);
            }
        }
        $this->set('balance', $tot);
    }

    function index3() {
        $employees = $this->Holiday->Employee->find('all');
        $this->set(compact('employees'));
    }

    function supervised($emp_id) {
        $supervised = $this->Supervisor->find('all', array('conditions' => array('sup_emp_id' => $emp_id)));

        $empls = array();
        $conditions = array();
        foreach ($supervised as $sups) {
            $empls = array_merge(array($sups['Supervisor']['emp_id']), $empls);
            $this->loadModel("Holiday");
            $cond['Holiday.employee_id'] = $sups['Supervisor']['emp_id'];
            $cond['Holiday.from_date <='] = date('Y-m-d');
            $cond['Holiday.to_date >='] = date('Y-m-d');
            $cond['Holiday.status'] = 'On Leave';
            $onleave = $this->Holiday->find('count', array('conditions' => $cond));
            if ($onleave > 0)
                $empls = array_merge($this->supervised($sups['Supervisor']['emp_id']), $empls);
        }
        return $empls;
    }

    function list_data3() {
        $this->requestAction('/holidays/startleave');
        $this->requestAction('/holidays/endleave');
        $this->loadModel('Employee');
        $emp = $this->Employee->findByuser_id($this->Session->read('Auth.User.id'));
        $this->loadModel('Supervisor');
        //$supervised = $this->Supervisor->find('all', array('conditions' => array('sup_emp_id' => $emp['Employee']['id'])));
        $empls = $this->supervised($emp['Employee']['id']);
        /*
          $empls = array();
          $conditions = array();
          foreach ($supervised as $sups) {
          $empls = array_merge(array($sups['Supervisor']['emp_id']), $empls);
          $this->loadModel("Holiday");
          $cond['Holiday.employee_id'] = $sups['Supervisor']['emp_id'];
          $cond['Holiday.from_date <='] = date('Y-m-d');
          $cond['Holiday.to_date >='] = date('Y-m-d');
          $cond['Holiday.status'] = 'On Leave';
          $onleave = $this->Holiday->find('count',array('conditions' => $cond));
          }
         * 
         */
        //print_r($empls);
        $conditions = array();
        $statarr = array('Pending Approval', 'Scheduled', 'On Leave');
        $conditions = array_merge(array("OR" => array("Holiday.status" => $statarr)), $conditions);
        $empcond = array("OR " => array("Holiday.employee_id" => $empls));
        $conditions = array_merge($empcond, $conditions);
        //  print_r($conditions);
        $this->Holiday->recursive = 3;
        //$this->set('holidays', $this->Holiday->find('all', array('conditions' => $conditions)));
        $holidays = $this->Holiday->find('all', array('conditions' => array("AND" => $conditions)));
        $i = 0;
        foreach ($holidays As $holiday) {
            if ($holiday['Holiday']['leave_type_id'] == 2) {
                $holidays[$i]['Holiday']['no_of_dates'] = $this->calculate($holiday['Holiday']['from_date'], $holiday['Holiday']['to_date'], 1);
                $holidays[$i]['Holiday']['no_of_dates'] = $holidays[$i]['Holiday']['no_of_dates'] / 2;
            }else
                $holidays[$i]['Holiday']['no_of_dates'] = $this->calculate($holiday['Holiday']['from_date'], $holiday['Holiday']['to_date'], 0);
            $i++;
        }
        $this->set('holidays', $holidays);
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }

    function list_data($id = null) {
        $this->requestAction('/holidays/startleave');
        $this->requestAction('/holidays/endleave');
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $employee_id = (isset($_REQUEST['employee_id'])) ? $_REQUEST['employee_id'] : -1;
        if ($id)
            $employee_id = ($id) ? $id : -1;
        $emp = $this->Holiday->Employee->findByuser_id($this->Session->read('Auth.User.id'));
        $employee_id = $emp['Employee']['id'];
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($employee_id != -1) {
            $conditions['Holiday.employee_id'] = $employee_id;
        }

        $holidays = $this->Holiday->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start));
        $i = 0;
        foreach ($holidays As $holiday) {
            if ($holiday['Holiday']['leave_type_id'] == 2) {
                $holidays[$i]['Holiday']['no_of_dates'] = $this->calculate($holiday['Holiday']['from_date'], $holiday['Holiday']['to_date'], 1);
                $holidays[$i]['Holiday']['no_of_dates'] = $holidays[$i]['Holiday']['no_of_dates'] / 2;
            }else
                $holidays[$i]['Holiday']['no_of_dates'] = $this->calculate($holiday['Holiday']['from_date'], $holiday['Holiday']['to_date'], 0);
            $i++;
        }
        $this->set('holidays', $holidays);
        //print_r($holidays);
        $this->set('results', $this->Holiday->find('count', array('conditions' => $conditions)));
    }

    function startleave() {
        $this->autoRender = false;
        $holidays = $this->Holiday->find('all', array('conditions' => array('Holiday.from_date <=' => date('Y-m-d'), 'Holiday.status' => 'Scheduled')));
        $this->data['Holiday']['status'] = 'On Leave';

        foreach ($holidays As $holiday) {
            $this->data['Holiday']['id'] = $holiday['Holiday']['id'];
            $this->Holiday->save($this->data);
            $balance = $this->Holiday->LeaveType->LeaveRule->find('first', array('conditions' => array('LeaveRule.employee_id' => $holiday['Holiday']['employee_id'], 'LeaveRule.leave_type_id' => $holiday['Holiday']['leave_type_id'])));
            if ($balance['LeaveType']['id'] == 2)
                $ldays = $this->calculate($holiday['Holiday']['from_date'], $holiday['Holiday']['to_date'], 1);
            else
                $ldays = $this->calculate($holiday['Holiday']['from_date'], $holiday['Holiday']['to_date'], 0);

            $this->data['LeaveRule']['balance'] = $balance['LeaveRule']['balance'] - $ldays;
            $this->data['LeaveRule']['taken'] = $balance['LeaveRule']['taken'] + $ldays;
            $this->data['LeaveRule']['id'] = $balance['LeaveRule']['id'];
            $this->Holiday->LeaveType->LeaveRule->save($this->data);

            if ($balance['LeaveType']['id'] == 2) {
                $balancex = $this->Holiday->LeaveType->LeaveRule->find('first', array('conditions' => array('LeaveRule.employee_id' => $holiday['Holiday']['employee_id'], 'LeaveRule.leave_type_id' => 1)));
                $this->data['LeaveRule']['balance'] = $balancex['LeaveRule']['balance'] - ($ldays * 0.5);
                $this->data['LeaveRule']['taken'] = $balancex['LeaveRule']['taken'] + ($ldays * 0.5);
                $this->data['LeaveRule']['id'] = $balancex['LeaveRule']['id'];
                $this->Holiday->LeaveType->LeaveRule->save($this->data);
            }
            if ($balance['LeaveType']['id'] == 1) {
                $balancex = $this->Holiday->LeaveType->LeaveRule->find('first', array('conditions' => array('LeaveRule.employee_id' => $holiday['Holiday']['employee_id'], 'LeaveRule.leave_type_id' => 2)));
                $this->data['LeaveRule']['balance'] = $balancex['LeaveRule']['balance'] - ($ldays * 2);
                $this->data['LeaveRule']['taken'] = $balancex['LeaveRule']['taken'] + ($ldays * 2);
                $this->data['LeaveRule']['id'] = $balancex['LeaveRule']['id'];
                $this->Holiday->LeaveType->LeaveRule->save($this->data);
            }
        }
    }

    function endleave() {
        $this->autoRender = false;
        $holidays = $this->Holiday->find('all', array('conditions' => array('Holiday.to_date <' => date('Y-m-d'), 'Holiday.status' => 'On Leave')));
        $this->data['Holiday']['status'] = 'Taken';
        foreach ($holidays As $holiday) {
            $this->data['Holiday']['id'] = $holiday['Holiday']['id'];
            $this->Holiday->save($this->data);
        }
    }

    function calculate_annual_leave($id) {
        // $this->autoRender = false;
        $increment = 16;
        if ($id) {
            $total = 0;
            // $this->autoRender = false;
            $this->Holiday->Employee->recursive = 2;
            $emp = $this->Holiday->Employee->read(null, $id);
            $this->array_sort_by_column($emp['EmployeeDetail'], "start_date");
            $empdate = $emp['EmployeeDetail'][0]['start_date'];
            if ($emp['EmployeeDetail'][0]['Position']['is_managerial'] == 0) {
                $ration = $increment / 12;
            } else {
                $increment = 20;
                $ration = $increment / 12;
            }

            $this->loadModel('BudgetYear');
            $query = $this->BudgetYear->find('first', array('conditions' =>
                array('BudgetYear.from_date <= ' => $empdate,
                    'BudgetYear.to_date >= ' => $empdate
                    )));
            if (is_array($query)) {
                $from = $query['BudgetYear']['from_date'];
                $to = $query['BudgetYear']['to_date'];
            }else
                return 0;

            $empdate = strtotime($empdate);

            while (date('Y-m-d', $empdate) < date('Y-m-d')) {
                //increment every bugdet year
                if (date('Y-m-d', $empdate) >= $from && date('Y-m-d', $empdate) <= $to) {
                    //do nothing
                } else {
                    $this->loadModel('BudgetYear');
                    $query = $this->BudgetYear->find('first', array('conditions' =>
                        array('BudgetYear.from_date <= ' => date('Y-m-d', $empdate),
                            'BudgetYear.to_date >= ' => date('Y-m-d', $empdate)
                            )));

                    if (is_array($query)) {
                        $from = $query['BudgetYear']['from_date'];
                        $to = $query['BudgetYear']['to_date'];
                    }else
                        return 0;

                    $increment++;
                    $ration = $increment / 12;
                }
                //check if hired to be managerial
                foreach ($emp['EmployeeDetail'] as $empdetail) {

                    if (date('Y-m', strtotime($empdetail['start_date'])) == date('Y-m', $empdate)) {
                        if ($empdetail['Position']['is_managerial'] == 1 && $total < 20) {
                            $increment = 20;
                            $ration = $increment / 12;
                        }
                    }
                }



                $total = $ration + $total;

                $empdate = strtotime("+1 month", $empdate);
            }
            return $total;
        }
    }

    function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }

    function initializeleave($id) {
        if ($id) {
            // $this->autoRender = false;
            $this->Holiday->Employee->recursive = 2;
            $emp = $this->Holiday->Employee->read(null, $id);
            //print_r($emp);
            // exit();
            if ($emp['EmployeeDetail'][count($emp['EmployeeDetail']) - 1]['Position']['is_managerial'] == 0) {
                $totalx = $this->calculate_annual_leave($id);
                $this->data['LeaveRule']['employee_id'] = $id;
                $this->data['LeaveRule']['leave_type_id'] = 1;
                $this->data['LeaveRule']['total'] = $totalx;
                $this->data['LeaveRule']['balance'] = $totalx;
                $this->Holiday->LeaveType->LeaveRule->create();
                $this->Holiday->LeaveType->LeaveRule->save($this->data);

                $this->data['LeaveRule']['employee_id'] = $id;
                $this->data['LeaveRule']['leave_type_id'] = 2;
                $this->data['LeaveRule']['total'] = $totalx * 2;
                $this->data['LeaveRule']['balance'] = $totalx * 2;
                $this->Holiday->LeaveType->LeaveRule->create();
                $this->Holiday->LeaveType->LeaveRule->save($this->data);

                $this->data['LeaveRule']['employee_id'] = $id;
                $this->data['LeaveRule']['leave_type_id'] = 3;
                $this->data['LeaveRule']['total'] = 180;
                $this->data['LeaveRule']['balance'] = 180;
                $this->Holiday->LeaveType->LeaveRule->create();
                $this->Holiday->LeaveType->LeaveRule->save($this->data);

                $this->data['LeaveRule']['employee_id'] = $id;
                $this->data['LeaveRule']['leave_type_id'] = 4;
                if ($emp['User']['Person']['sex'] == 'F') {
                    $this->data['LeaveRule']['total'] = 90;
                    $this->data['LeaveRule']['balance'] = 90;
                } else {
                    $this->data['LeaveRule']['total'] = 3;
                    $this->data['LeaveRule']['balance'] = 3;
                }
                $this->Holiday->LeaveType->LeaveRule->create();
                $this->Holiday->LeaveType->LeaveRule->save($this->data);

                $this->data['LeaveRule']['employee_id'] = $id;
                $this->data['LeaveRule']['leave_type_id'] = 5;
                $this->data['LeaveRule']['total'] = 300;
                $this->data['LeaveRule']['balance'] = 300;
                $this->Holiday->LeaveType->LeaveRule->create();
                $this->Holiday->LeaveType->LeaveRule->save($this->data);

                $this->data['LeaveRule']['employee_id'] = $id;
                $this->data['LeaveRule']['leave_type_id'] = 6;
                $this->data['LeaveRule']['total'] = 300;
                $this->data['LeaveRule']['balance'] = 300;
                $this->Holiday->LeaveType->LeaveRule->create();
                $this->Holiday->LeaveType->LeaveRule->save($this->data);

                $this->data['LeaveRule']['employee_id'] = $id;
                $this->data['LeaveRule']['leave_type_id'] = 7;
                $this->data['LeaveRule']['total'] = 300;
                $this->data['LeaveRule']['balance'] = 300;
                $this->Holiday->LeaveType->LeaveRule->create();
                $this->Holiday->LeaveType->LeaveRule->save($this->data);
            }

            if ($emp['EmployeeDetail'][count($emp['EmployeeDetail']) - 1]['Position']['is_managerial'] == 1) {
                $totalx = $this->calculate_annual_leave($id);
                $this->data['LeaveRule']['employee_id'] = $id;
                $this->data['LeaveRule']['leave_type_id'] = 1;
                $this->data['LeaveRule']['total'] = $totalx;
                $this->data['LeaveRule']['balance'] = $totalx;
                $this->Holiday->LeaveType->LeaveRule->create();
                $this->Holiday->LeaveType->LeaveRule->save($this->data);

                $this->data['LeaveRule']['employee_id'] = $id;
                $this->data['LeaveRule']['leave_type_id'] = 2;
                $this->data['LeaveRule']['total'] = $totalx * 2;
                $this->data['LeaveRule']['balance'] = $totalx * 2;
                $this->Holiday->LeaveType->LeaveRule->create();
                $this->Holiday->LeaveType->LeaveRule->save($this->data);

                $this->data['LeaveRule']['employee_id'] = $id;
                $this->data['LeaveRule']['leave_type_id'] = 3;
                $this->data['LeaveRule']['total'] = 180;
                $this->data['LeaveRule']['balance'] = 180;
                $this->Holiday->LeaveType->LeaveRule->create();
                $this->Holiday->LeaveType->LeaveRule->save($this->data);

                $this->data['LeaveRule']['employee_id'] = $id;
                $this->data['LeaveRule']['leave_type_id'] = 4;
                if ($emp['User']['Person']['sex'] == 'F') {
                    $this->data['LeaveRule']['total'] = 90;
                    $this->data['LeaveRule']['balance'] = 90;
                } else {
                    $this->data['LeaveRule']['total'] = 3;
                    $this->data['LeaveRule']['balance'] = 3;
                }
                $this->Holiday->LeaveType->LeaveRule->create();
                $this->Holiday->LeaveType->LeaveRule->save($this->data);

                $this->data['LeaveRule']['employee_id'] = $id;
                $this->data['LeaveRule']['leave_type_id'] = 5;
                $this->data['LeaveRule']['total'] = 300;
                $this->data['LeaveRule']['balance'] = 300;
                $this->Holiday->LeaveType->LeaveRule->create();
                $this->Holiday->LeaveType->LeaveRule->save($this->data);

                $this->data['LeaveRule']['employee_id'] = $id;
                $this->data['LeaveRule']['leave_type_id'] = 6;
                $this->data['LeaveRule']['total'] = 300;
                $this->data['LeaveRule']['balance'] = 300;
                $this->Holiday->LeaveType->LeaveRule->create();
                $this->Holiday->LeaveType->LeaveRule->save($this->data);

                $this->data['LeaveRule']['employee_id'] = $id;
                $this->data['LeaveRule']['leave_type_id'] = 7;
                $this->data['LeaveRule']['total'] = 300;
                $this->data['LeaveRule']['balance'] = 300;
                $this->Holiday->LeaveType->LeaveRule->create();
                $this->Holiday->LeaveType->LeaveRule->save($this->data);
            }
        }
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid holiday', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Holiday->recursive = 2;
        $this->set('holiday', $this->Holiday->read(null, $id));
    }

    function calculate($from, $to, $sat) {
        //$this->autoRender = false;
        // $from='2013-04-05';
        // $to='2013-04-5';
        $frm = strtotime($from);
        $td = strtotime($to);
        $ndate = $td - $frm;
        $numdate = floor($ndate / (60 * 60 * 24)) + 1;
        $this->loadModel('BudgetYear');
        $query = $this->BudgetYear->find('first', array('conditions' => array(
                'and' => array(
                    array('BudgetYear.from_date <= ' => date('Y-m-d', $frm),
                        'BudgetYear.to_date >= ' => date('Y-m-d', $frm)
                    ),
                    array('BudgetYear.from_date <= ' => date('Y-m-d', $td),
                        'BudgetYear.to_date >= ' => date('Y-m-d', $td)
                    )
            ))));
//print_r($query);
        if (is_array($query)) {
            $byid = $query['BudgetYear']['id'];
            for ($i = 0; $i > -1; $i++) {
                if ($frm > $td)
                    break;

                $chkhol = $this->BudgetYear->CelebrationDay->find('count', array('conditions' => array('CelebrationDay.day' => date('Y-m-d', $frm), 'CelebrationDay.budget_year_id' => $byid)));
                if ($chkhol >= 1) {
                    $numdate--;
                } else {

                    if (date('l', $frm) == 'Sunday')
                        $numdate--;
                    if (date('l', $frm) == 'Saturday' && $sat != 1)
                        $numdate = $numdate - 0.5;
                }

                // echo date('Y-m-d', $frm);
                $frm = strtotime("+1 day", $frm);
            }
        }else {
            for ($i = 0; $i > -1; $i++) {
                if ($frm > $td)
                    break;



                if (date('l', $frm) == 'Sunday')
                    $numdate--;
                if (date('l', $frm) == 'Saturday' && $sat != 1)
                    $numdate = $numdate - 0.5;


                // echo date('Y-m-d', $frm);
                $frm = strtotime("+1 day", $frm);
            }
        }
        //echo $numdate;
        //exit();
        return $numdate;
    }

    function set_leave($id = null, $parent_id = null) {

        if (!empty($this->data)) {
            $this->autoRender = false;
            //print_r($this->data);
            $balancex = $this->Holiday->LeaveType->LeaveRule->find('first', array('conditions' => array('LeaveRule.employee_id' => $this->data['Holiday']['employee_id'], 'LeaveRule.leave_type_id' => 1)));
            $this->data['LeaveRule']['taken'] = $this->data['Holiday']['Taken'];
            //$this->data['LeaveRule']['total'] = $this->data['Holiday']['Total'];
            $this->data['LeaveRule']['balance'] = $balancex['LeaveRule']['total'] - $this->data['LeaveRule']['taken'];
            $this->data['LeaveRule']['id'] = $balancex['LeaveRule']['id'];
            $this->Holiday->LeaveType->LeaveRule->save($this->data);

            $balancey = $this->Holiday->LeaveType->LeaveRule->find('first', array('conditions' => array('LeaveRule.employee_id' => $this->data['Holiday']['employee_id'], 'LeaveRule.leave_type_id' => 2)));
            $this->data['LeaveRule']['taken'] = $this->data['Holiday']['Taken'] * 2;
            // $this->data['LeaveRule']['total'] = $this->data['Holiday']['Total']*2;
            $this->data['LeaveRule']['balance'] = $balancey['LeaveRule']['total'] - $this->data['LeaveRule']['taken'];
            $this->data['LeaveRule']['id'] = $balancey['LeaveRule']['id'];
            $this->Holiday->LeaveType->LeaveRule->save($this->data);
            $this->Session->setFlash(__('Annual Leave configured for employee', true), '');
            $this->render('/elements/success');
        } else {

            if ($id) {
                $this->set('parent_id', $id);
            }
            $employees = $this->Holiday->Employee->find('list');
            $this->set('employees', $employees);
            //$emp=$this->Holiday->Employee->findByuser_id($this->Session->read('Auth.User.id'));
            //if(!empty($emp))
            $balance = $this->Holiday->LeaveType->LeaveRule->find('all', array('conditions' => array('LeaveRule.employee_id' => $id, 'LeaveRule.leave_type_id' => '1')));
            //print_r($balance);
            if (!empty($balance)) {
                $total = $balance[0]['LeaveRule']['total'];
                $taken = $balance[0]['LeaveRule']['taken'];
                $this->set('total', $total);
                $this->set('taken', $taken);
            } else {
                $this->initializeleave($id);
                $balance = $this->Holiday->LeaveType->LeaveRule->find('all', array('conditions' => array('LeaveRule.employee_id' => $id, 'LeaveRule.leave_type_id' => '1')));

                $total = $balance[0]['LeaveRule']['total'];
                $taken = $balance[0]['LeaveRule']['taken'];
                $this->set('total', $total);
                $this->set('taken', $taken);
            }
        }
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $balance = $this->Holiday->LeaveType->LeaveRule->find('first', array('conditions' => array('LeaveRule.employee_id' => $this->data['Holiday']['employee_id'], 'LeaveRule.leave_type_id' => $this->data['Holiday']['leave_type_id'])));

            $tempdate = explode('/', $this->data['Holiday']['from_date']);
            if (count($tempdate) >= 3)
                $from_date = $tempdate[2] . '-' . $tempdate[1] . '-' . $tempdate[0];

            $tempdate = explode('/', $this->data['Holiday']['to_date']);
            if (count($tempdate) >= 3)
                $to_date = $tempdate[2] . '-' . $tempdate[1] . '-' . $tempdate[0];

            if (is_array($balance)) {
                if ($this->calculate($from_date, $to_date, 0) > $balance['LeaveRule']['balance']) {
                    $this->Session->setFlash(__('You have only ' . $balance['LeaveRule']['balance'] . ' ' . $balance['LeaveType']['name'] . ' left.', true), '');
                    $this->render('/elements/failure');
                }
                //if annual half day
                elseif ($this->calculate($from_date, $to_date, 1) > $balance['LeaveRule']['balance'] && $this->data['Holiday']['leave_type_id'] == 2) {
                    $this->Session->setFlash(__('You have only ' . $balance['LeaveRule']['balance'] . ' ' . $balance['LeaveType']['name'] . ' left.', true), '');
                    $this->render('/elements/failure');
                } else {
                    $this->Holiday->create();
                    $this->autoRender = false;
                    if ($this->Holiday->save($this->data)) {
                        $this->Session->setFlash(__('Leave Applied Succesfully.', true), '');
                        $this->render('/elements/success');
                    } else {
                        $this->Session->setFlash(__('Error Found with your entry. Please, try again.', true), '');
                        $this->render('/elements/failure');
                    }
                }
            } else {
                $this->Session->setFlash(__('The leave you are requesting is not configured for you. Please contact Administrators.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);

        $employees = $this->Holiday->Employee->find('list');
        $leave_types = $this->Holiday->LeaveType->find('list');
        $this->set(compact('employees', 'leave_types'));
        $this->set('employee', $this->Holiday->Employee->findByuser_id($this->Session->read('Auth.User.id')));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid holiday', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->Holiday->save($this->data)) {
                $this->Session->setFlash(__('The holiday has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The holiday could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('holiday', $this->Holiday->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $employees = $this->Holiday->Employee->find('list');
        $leave_types = $this->Holiday->LeaveType->find('list');
        $this->set(compact('employees', 'leave_types'));
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
                    $this->Holiday->delete($i);
                }
                $this->Session->setFlash(__('Holiday deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Holiday was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->Holiday->delete($id)) {
                $this->Session->setFlash(__('Holiday deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Holiday was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function cancel($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid holiday', true), '');
            $this->redirect(array('action' => 'index'));
        }
        $this->data['Holiday']['status'] = 'Canceled';
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->Holiday->save($this->data)) {
                $this->Session->setFlash(__('The Leave has been Canceled', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The Leave could not be canceled. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function approve($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid holiday', true), '');
            $this->redirect(array('action' => 'index'));
        }
        $emp = $this->Holiday->Employee->findByuser_id($this->Session->read('Auth.User.id'));
        $this->data['Holiday']['approved_by'] = $emp['Employee']['id'];
        $this->data['Holiday']['status'] = 'Scheduled';
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->Holiday->save($this->data)) {
                $this->Session->setFlash(__('The Leave has been Approved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The Leave could not be Approved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function reject($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid holiday', true), '');
            $this->redirect(array('action' => 'index'));
        }
        $emp = $this->Holiday->Employee->findByuser_id($this->Session->read('Auth.User.id'));
        $this->data['Holiday']['approved_by'] = $emp['Employee']['id'];
        $this->data['Holiday']['status'] = 'Rejected';
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->Holiday->save($this->data)) {
                $this->Session->setFlash(__('The Leave has been Rejected', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The Leave could not be Rejected. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}

?>