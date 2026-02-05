<?php

class EduExtraPaymentSettingsController extends EduAppController {

    var $name = 'EduExtraPaymentSettings';

    function index() {
        $edu_classes = $this->EduExtraPaymentSetting->EduClass->find('all');
        $this->set(compact('edu_classes'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
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
            $conditions['EduExtraPaymentSetting.edu_class_id'] = $edu_class_id;
        }

        $this->set('edu_extra_payment_settings', $this->EduExtraPaymentSetting->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduExtraPaymentSetting->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid extra payment setting', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduExtraPaymentSetting->recursive = 2;
        $this->set('edu_extra_payment_setting', $this->EduExtraPaymentSetting->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->EduExtraPaymentSetting->create();
            $this->autoRender = false;
            $this->loadModel('Edu.EduAcademicYear');
            $ay = $this->EduAcademicYear->getActiveAcademicYear();
            
            $this->data['EduExtraPaymentSetting']['edu_academic_year_id'] = $ay['EduAcademicYear']['id'];
            
            if ($this->EduExtraPaymentSetting->save($this->data)) {
                $this->Session->setFlash(__('The extra payment setting has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The extra payment setting could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
        $edu_classes = $this->EduExtraPaymentSetting->EduClass->find('list');
        $edu_extra_payment_types = $this->EduExtraPaymentSetting->EduExtraPaymentType->find('list');
        $edu_academic_years = $this->EduExtraPaymentSetting->EduAcademicYear->find('list');
        $this->set(compact('edu_classes', 'edu_academic_years', 'edu_extra_payment_types'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid extra payment setting', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->EduExtraPaymentSetting->save($this->data)) {
                $this->Session->setFlash(__('The extra payment setting has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The extra payment setting could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('edu_extra_payment_setting', $this->EduExtraPaymentSetting->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $edu_classes = $this->EduExtraPaymentSetting->EduClass->find('list');
        $edu_academic_years = $this->EduExtraPaymentSetting->EduAcademicYear->find('list');
        $this->set(compact('edu_classes', 'edu_academic_years'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->cakeError('cannotDeleteRecord', array(
                'message' => 'Invalid id for Extra Payment Setting. (ERR-1602)',
                'helpcode' => 'ERR-1602'));
        }
        $extra_payment_setting = $this->EduExtraPaymentSetting->read(null, $id);
        if (count($extra_payment_setting['EduExtraPayment']) > 0) {
            $this->cakeError('cannotDeleteRecord', array(
                'message' => 'Extra Payment Setting has related records in other locations. (ERR-1601)',
                'helpcode' => 'ERR-1601'));
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduExtraPaymentSetting->delete($i);
                }
                $this->Session->setFlash(__('Extra Payment Setting successfully deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->cakeError('cannotDeleteRecord', array(
                    'message' => 'Extra Payment Setting cannot be deleted. (' .$e->getMessage() . '). (ERR-1603)',
                    'helpcode' => 'ERR-1603'));
            }
        } else {
            if ($this->EduExtraPaymentSetting->delete($id)) {
                $this->Session->setFlash(__('Extra Payment Setting successfully deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->cakeError('cannotDeleteRecord', array(
                    'message' => 'Extra Payment Setting cannot be deleted. (ERR-1603)',
                    'helpcode' => 'ERR-1603'));
            }
        }
    }

}
