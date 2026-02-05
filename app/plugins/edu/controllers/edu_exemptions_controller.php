<?php
class EduExemptionsController extends EduAppController {

	var $name = 'EduExemptions';
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
		$edu_student_id = (isset($_REQUEST['edu_student_id'])) ? $_REQUEST['edu_student_id'] : -1;
		if($id)
			$edu_student_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($edu_student_id != -1) {
            $conditions['EduExemption.edu_student_id'] = $edu_student_id;
        }
		
		$this->set('edu_exemptions', $this->EduExemption->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->EduExemption->find('count', array('conditions' => $conditions)));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->EduExemption->create();
			$this->autoRender = false;
			if ($this->EduExemption->save($this->data)) {
				$this->Session->setFlash(__('The course exempted successfully', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The exemption could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$edu_students = $this->EduExemption->EduStudent->find('list');
		$edu_academic_years = $this->EduExemption->EduAcademicYear->find('list');
		$current_ay = $this->EduExemption->EduAcademicYear->getActiveAcademicYear();
		$current_ay_id = $current_ay['EduAcademicYear']['id'];

		$this->loadModel('EduQuarter');
		$edu_quarters = $this->EduQuarter->find('list', array('conditions' => array(
			'EduQuarter.edu_academic_year_id' => $current_ay_id,
			'EduQuarter.quarter_type' => 'E'
		)));
		$edu_quarters[0] = 'All';
		
		$this->loadModel('EduRegistration');
		$last_reg = $this->EduRegistration->find('first', array('conditions' => array('EduRegistration.edu_student_id' => $id), 'order' => 'EduRegistration.created DESC'));
		
		$student = $this->EduExemption->EduStudent->read(null, $id);
		// get current student class
		
		$class_id = $last_reg['EduRegistration']['edu_class_id'];
		$courses = array();
		$edu_courses = $this->EduExemption->EduCourse->find('all', array('conditions' => array('EduCourse.edu_class_id' => $class_id )));
		foreach($edu_courses as $course) {
			$courses[$course['EduCourse']['id']] = $course['EduCourse']['description'];
		}
		$edu_courses = $courses;
		
		$this->set(compact('edu_students', 'edu_courses', 'edu_academic_years', 'current_ay_id', 'edu_quarters'));
	}

	function add_for_section($id = null) {
		if (!empty($this->data)) {
			$this->autoRender = false;
			$this->loadModel('EduRegistration');
			$section_id = $this->data['EduExemption']['edu_section_id'];
			$regs = $this->EduRegistration->find('all', array(
				'conditions' => array('EduRegistration.edu_section_id' => $section_id)
			));

			$stud_count = 0;
			foreach ($regs as $reg) {
				$already_exempteds = $this->EduExemption->find('count', array('conditions' => array(
					'EduExemption.edu_student_id' => $reg['EduRegistration']['edu_student_id'],
					'EduExemption.edu_course_id' => $this->data['EduExemption']['edu_course_id'],
					'EduExemption.edu_quarter_id' => $this->data['EduExemption']['edu_quarter_id'],
					'EduExemption.edu_academic_year_id' => $this->data['EduExemption']['edu_academic_year_id']
				)));

				if($already_exempteds > 0) {
					// if the student is already exempted for the course for the selected course
					// do nothing
				} else {
					$this->EduExemption->create();
					$exemption = array('EduExemption' => array(
						'edu_student_id' => $reg['EduRegistration']['edu_student_id'],
						'edu_course_id' => $this->data['EduExemption']['edu_course_id'],
						'edu_academic_year_id' => $this->data['EduExemption']['edu_academic_year_id'],
						'edu_quarter_id' => $this->data['EduExemption']['edu_quarter_id']
					));
					if (!$this->EduExemption->save($exemption)) {
						$this->Session->setFlash(__('The exemption could not be saved. Please, try again.', true), '');
						$this->render('/elements/failure');
					}
					$stud_count++;
				}
			}

			$this->Session->setFlash(__('The course exempted successfully for ' . $stud_count . ' students.', true), '');
			$this->render('/elements/success');
		}
		if($id)
			$this->set('parent_id', $id);
		$edu_academic_years = $this->EduExemption->EduAcademicYear->find('list');
		$current_ay = $this->EduExemption->EduAcademicYear->getActiveAcademicYear();
		$current_ay_id = $current_ay['EduAcademicYear']['id'];

		$this->loadModel('EduQuarter');
		$edu_quarters = $this->EduQuarter->find('list', array('conditions' => array(
			'EduQuarter.edu_academic_year_id' => $current_ay_id,
			'EduQuarter.quarter_type' => 'E'
		)));
		$edu_quarters[0] = 'All';
		
		$this->loadModel('EduSection');
		$edu_sections = $this->EduSection->find('all', array(
			'conditions' => array('EduSection.edu_academic_year_id' => $current_ay_id)));
		
		//$class_id = $last_reg['EduRegistration']['edu_class_id'];
		$courses = array();
		$conditions = array();
		if($id) {
			$section = $this->EduSection->read(null, $id);

			$conditions['EduCourse.edu_class_id'] = $section['EduSection']['edu_class_id'];
		}
		$edu_courses = $this->EduExemption->EduCourse->find('all', array('conditions' => $conditions));

		foreach($edu_courses as $course) {
			$courses[$course['EduCourse']['id']] = $course['EduCourse']['description'];
		}
		$edu_courses = $courses;
		
		$this->set(compact('edu_sections', 'edu_courses', 'edu_academic_years', 'current_ay_id', 'edu_quarters'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for edu exemption', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->EduExemption->read(null, $i);
                    $this->EduExemption->set('deleted', true);
                    $this->EduExemption->save();
                }
			 $this->Session->setFlash(__('Edu exemption deleted', true), '');
			 $this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Edu exemption was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            $this->EduExemption->read(null, $id);
            $this->EduExemption->set('deleted', true);
            if ($this->EduExemption->save()) {
				$this->Session->setFlash(__('Edu exemption deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Edu exemption was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>
