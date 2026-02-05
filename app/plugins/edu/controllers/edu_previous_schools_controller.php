<?php

class EduPreviousSchoolsController extends EduAppController {

    var $name = 'EduPreviousSchools';

    function index() {
        
    }

    function list_data() {
		$prev_schools = array();
		if($this->Session->check('prev_schools')) {
			$prev_schools = $this->Session->read('prev_schools');
		} else {
			$this->Session->write('prev_schools', $prev_schools);
		}
        $this->set('prev_schools', $prev_schools);
        $this->set('results', count($prev_schools));
    }

    function add() {
        if (!empty($this->data)) {
			$this->autoRender = false;
            $prev_schools = $this->Session->read('prev_schools');
			
			$prev_school = array();
			$prev_school['country'] = $this->data['EduPreviousSchool']['country'];
			$prev_school['year_attended'] = $this->data['EduPreviousSchool']['year_attended'];
			$prev_school['grade_levels'] = $this->data['EduPreviousSchool']['grade_levels'];
			$prev_school['languages'] = $this->data['EduPreviousSchool']['languages'];
			
			$prev_schools[time()] = $prev_school;
			
			$this->Session->write('prev_schools', $prev_schools);
			
			$this->Session->setFlash(__('Data saved successfully', true));
			$this->render('/elements/success');
        }
    }

    function delete($id = null) {
        $this->autoRender = false;
		
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->deleteRecord($i);
                }
                $this->Session->setFlash(__('Data deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Data was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->deleteRecord($id)) {
                $this->Session->setFlash(__('Data deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Data was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }
	
	function deleteRecord($id) {
		$prev_schools = $this->Session->read('prev_schools');
		$found = false;
		foreach($prev_schools as $k => $v) {
			if($k == $id) {
				unset($prev_schools[$k]);
				$this->Session->write('prev_schools', $prev_schools);
				$found = true;
				break;
			}
		}
		
		return $found;
	}

}
