<?php
class ReportCategoriesController extends AppController {

	var $name = 'ReportCategories';
	
	function index() {
	}
	

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		
		$this->set('report_categories', $this->ReportCategory->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->ReportCategory->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid report category', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->ReportCategory->recursive = 2;
		$this->set('report_category', $this->ReportCategory->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->ReportCategory->create();
			$this->autoRender = false;
			if ($this->ReportCategory->save($this->data)) {
				$this->Session->setFlash(__('The report category has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The report category could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid report category', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->ReportCategory->save($this->data)) {
				$this->Session->setFlash(__('The report category has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The report category could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$report_category = $this->ReportCategory->read(null, $id);
		$this->set('report_category', $report_category);
	
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for report category', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->ReportCategory->delete($i);
                }
				$this->Session->setFlash(__('Report category deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Report category was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->ReportCategory->delete($id)) {
				$this->Session->setFlash(__('Report category deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Report category was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>