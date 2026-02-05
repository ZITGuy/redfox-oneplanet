<?php

class EduReceiptsController extends EduAppController {

    var $name = 'EduReceipts';

    function index() {
        $edu_students = $this->EduReceipt->EduStudent->find('all');
        $this->set(compact('edu_students'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }

    function print_receipt($id = null) {
        $this->layout = 'ajax';
        $edu_receipt_id = 0;
        if (!$id) {
            $edu_receipt_id = $this->Session->read('edu_receipt_id'); // at the time of receipt creation
            $this->Session->delete('edu_receipt_id');
        } else {
            $edu_receipt_id = $id; // at any time whenever print is needed
        }
        $receipt = $this->EduReceipt->read(null, $edu_receipt_id);

        $this->set('company_url', $this->getSystemSetting('COMPANY_URL'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_tin', $this->getSystemSetting('COMPANY_TIN'));
        $this->set('company_address', $this->getSystemSetting('COMPANY_ADDRESS'));
        $this->set('receipt', $receipt);
        
        $base_url = 'http://' . Configure::read('domain') . Configure::read('localhost_string');
        $this->set('base_url', $base_url);
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_student_id = (isset($_REQUEST['edu_student_id'])) ? $_REQUEST['edu_student_id'] : -1;
        if ($id)
            $edu_student_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_student_id != -1) {
            $conditions['EduReceipt.edu_student_id'] = $edu_student_id;
        }

        $this->set('edu_receipts', $this->EduReceipt->find('all', array(
			'conditions' => $conditions, 
			'limit' => $limit, 
			'offset' => $start,
			'order' => 'EduReceipt.created DESC'
			)
		));
        $this->set('results', $this->EduReceipt->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu receipt', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduReceipt->recursive = 2;
        $this->set('eduReceipt', $this->EduReceipt->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->EduReceipt->create();
            $this->autoRender = false;
            if ($this->EduReceipt->save($this->data)) {
                $this->Session->setFlash(__('The edu receipt has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu receipt could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
        $edu_students = $this->EduReceipt->EduStudent->find('list');
        $this->set(compact('edu_students'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu receipt', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduReceipt->save($this->data)) {
                $this->Session->setFlash(__('The edu receipt has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The edu receipt could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_receipt', $this->EduReceipt->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $edu_students = $this->EduReceipt->EduStudent->find('list');
        $this->set(compact('edu_students'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for edu receipt', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduReceipt->delete($i);
                }
                $this->Session->setFlash(__('Edu receipt deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Edu receipt was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->EduReceipt->delete($id)) {
                $this->Session->setFlash(__('Edu receipt deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Edu receipt was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}
