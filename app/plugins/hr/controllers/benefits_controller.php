<?php

class BenefitsController extends HrAppController {

    var $name = 'Benefits';

    function index() {
        $grades = $this->Benefit->Grade->find('all');
        $this->set(compact('grades'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function index22($id = null) {
        $this->set('parent_id', $id);
    }
    
    function index_ot($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $grade_id = (isset($_REQUEST['grade_id'])) ? $_REQUEST['grade_id'] : -1;
        if ($id)
            $grade_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($grade_id != -1) {
            $conditions['Benefit.grade_id'] = $grade_id;
        }
        $conditions['Benefit.payroll_id'] = $this->Session->read('Auth.User.payroll_id');
        $conditions['Benefit.status'] = 'active';
        $this->set('benefits', $this->Benefit->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->Benefit->find('count', array('conditions' => $conditions)));
    }

    function list_data2($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $employee_id = (isset($_REQUEST['employee_id'])) ? $_REQUEST['employee_id'] : -1;
        if ($id)
            $employee_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($employee_id != -1) {
            $conditions['Benefit.employee_id'] = $employee_id;
        }
        $conditions['Benefit.payroll_id'] = $this->Session->read('Auth.User.payroll_id');
        $conditions['Benefit.status'] = 'active';
        $this->set('benefits', $this->Benefit->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->Benefit->find('count', array('conditions' => $conditions)));
    }
    
    function list_data_ot($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $employee_id = (isset($_REQUEST['employee_id'])) ? $_REQUEST['employee_id'] : -1;
        if ($id)
            $employee_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($employee_id != -1) {
            $conditions['Benefit.employee_id'] = $employee_id;
        }
        $conditions['Benefit.payroll_id'] = $this->Session->read('Auth.User.payroll_id');
        $conditions['Benefit.status'] = 'active';
        $conditions['Benefit.name'] = 'OT';
        $conditions['Benefit.end_date >='] = date('Y-m-d');
        $this->set('benefits', $this->Benefit->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->Benefit->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid benefit', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Benefit->recursive = 2;
        $this->set('benefit', $this->Benefit->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->Benefit->create();
            $this->autoRender = false;
            if ($this->Benefit->save($this->data)) {
                $this->Session->setFlash(__('The benefit has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The benefit could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
        $grades = $this->Benefit->Grade->find('list');
        $payrolls = $this->Benefit->Payroll->find('list');
        $this->set(compact('grades', 'payrolls'));
    }
    
    function add_ot($id = null) {
        if (!empty($this->data)) {
            $this->Benefit->create();
            $this->data['Benefit']['name'] = 'OT';
            $this->data['Benefit']['Measurement'] = 'Birr';
            $this->data['Benefit']['over'] = 0;
            $this->data['Benefit']['apply_to'] = 'employee';
            $this->data['Benefit']['employee_id'] = $id;
            $this->data['Benefit']['taxable'] = 0;
            $this->data['Benefit']['status'] = 'active';
            $this->data['Benefit']['payroll_id'] = $this->Session->read('Auth.User.payroll_id');
            
            $employee = $this->Benefit->Employee->read(null, $id);
            $salary = 0;
            foreach ($employee['EmployeeDetail'] as $detail) {
                if($detail['end_date'] == '0000-00-00')
                    $salary = $detail['salary'];
            }
            
            $hourly_salary = ($salary / 30) / 8; //assuming basic salary is 1000
            $amount = $hourly_salary * $this->data['Benefit']['ot_reverance'];
            
            $this->data['Benefit']['amount'] = $amount;
            $this->data['Benefit']['taxable'] = 'on';
            $this->data['Benefit']['start_date'] = date('Y-m') . '-01';
            $this->data['Benefit']['end_date'] = date('Y-m-t');
            
            $this->autoRender = false;
            if ($this->Benefit->save($this->data)) {
                $this->Session->setFlash(__('The benefit has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The benefit could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
        $grades = $this->Benefit->Grade->find('list');
        $payrolls = $this->Benefit->Payroll->find('list');
        $this->set(compact('grades', 'payrolls'));
    }

    function add2($id = null) {
        if (!empty($this->data)) {
            $this->Benefit->create();
            $this->autoRender = false;
            if ($this->Benefit->save($this->data)) {
                $this->Session->setFlash(__('The benefit has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The benefit could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
        $employees = $this->Benefit->Employee->find('list');
        $payrolls = $this->Benefit->Payroll->find('list');
        $this->set(compact('grades', 'payrolls'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid benefit', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->Benefit->save($this->data)) {
                $this->Session->setFlash(__('The benefit has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The benefit could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('benefit', $this->Benefit->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $grades = $this->Benefit->Grade->find('list');
        $this->set(compact('grades'));
    }

    function remove($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid benefit', true), '');
            $this->redirect(array('action' => 'index'));
        }

        $this->data['Benefit']['status'] = 'removed';
        $this->data['Benefit']['id'] = $id;
        if ($this->Benefit->save($this->data)) {
            $this->Session->setFlash(__('The benefit has been removed', true), '');
            $this->render('/elements/success');
        } else {
            $this->Session->setFlash(__('The benefit could not be removed. Please, try again.', true), '');
            $this->render('/elements/failure');
        }
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for benefit', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->Benefit->delete($i);
                }
                $this->Session->setFlash(__('Benefit deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Benefit was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->Benefit->delete($id)) {
                $this->Session->setFlash(__('Benefit deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Benefit was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}

?>