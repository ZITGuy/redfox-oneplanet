<?php
class EduClassPaymentsController extends EduAppController {

	var $name = 'EduClassPayments';
	
	function index() {
		$edu_classes = $this->EduClassPayment->EduClass->find('all');
		$this->set(compact('edu_classes'));
	}
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
		$edu_class_id = (isset($_REQUEST['edu_class_id'])) ? $_REQUEST['edu_class_id'] : -1;
		if($id)
			$edu_class_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($edu_class_id != -1) {
            $conditions['EduClassPayment.edu_class_id'] = $edu_class_id;
        }
		
		$this->set('edu_class_payments', $this->EduClassPayment->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->EduClassPayment->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid class payment', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EduClassPayment->recursive = 2;
		$this->set('edu_class_payment', $this->EduClassPayment->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->autoRender = false;
			$this->loadModel('Edu.EduClass');
			if($this->data['EduClassPayment']['edu_class_id'] == 0) {
				$cps = $this->EduClassPayment->find('all', array(
					'conditions' => array('EduClassPayment.edu_academic_year_id' => $this->data['EduClassPayment']['edu_academic_year_id'])
				));
				foreach($cps as $cp) {
					$this->EduClassPayment->delete($cp['EduClassPayment']['id']);
				}
				$classes = $this->EduClass->find('all');
				foreach($classes as $cl) {
					$this->EduClassPayment->create();
					$this->data['EduClassPayment']['edu_class_id'] = $cl['EduClass']['id'];
					$this->EduClassPayment->save($this->data);
				}
				$this->Session->setFlash(__('The class payment saved successfully', true), '');
				$this->render('/elements/success');
			} else {
				$this->EduClassPayment->create();
				$this->autoRender = false;
				if ($this->EduClassPayment->save($this->data)) {
					$this->Session->setFlash(__('The edu class payment has been saved', true), '');
					$this->render('/elements/success');
				} else {
					$this->Session->setFlash(__('The edu class payment could not be saved. Please, try again.', true), '');
					$this->render('/elements/failure');
				}
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$edu_classes = $this->EduClassPayment->EduClass->find('list');
		$edu_academic_years = $this->EduClassPayment->EduAcademicYear->find('list');
		$this->set(compact('edu_classes', 'edu_academic_years'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid edu class payment', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EduClassPayment->save($this->data)) {
				$this->Session->setFlash(__('The edu class payment has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The class payment could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$edu_class_payment = $this->EduClassPayment->read(null, $id);
		$this->set('edu_class_payment', $edu_class_payment);
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
		
		$edu_classes = $this->EduClassPayment->EduClass->find('list');
		$edu_academic_years = $this->EduClassPayment->EduAcademicYear->find('list');
		$this->set(compact('edu_classes', 'edu_academic_years'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for edu class payment', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EduClassPayment->delete($i);
                }
				$this->Session->setFlash(__('Edu class payment deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Edu class payment was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EduClassPayment->delete($id)) {
				$this->Session->setFlash(__('Edu class payment deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Edu class payment was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>