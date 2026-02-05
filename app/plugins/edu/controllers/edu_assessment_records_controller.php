<?php
class EduAssessmentRecordsController extends EduAppController {

	public $name = 'EduAssessmentRecords';
	
	public function index()
	{
		$students = $this->EduAssessmentRecord->EduStudent->find('all');
		$this->set(compact('students'));
	}
	
	public function index2($id = null)
	{
		$this->set('parent_id', $id);
	}

	public function search()
	{
		// empty body
	}
	
	public function list_data2($id = null)
	{
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
		$assessmentId = (isset($_REQUEST['assessment_id'])) ? $_REQUEST['assessment_id'] : -1;
		if ($id) {
			$assessmentId = ($id) ? $id : -1;
		}
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($assessmentId != -1) {
            $conditions['EduAssessmentRecord.assessment_id'] = $assessmentId;
        }
		$assessmentRecords = $this->EduAssessmentRecord->find('all', array(
			'conditions' => $conditions, 'limit' => $limit, 'offset' => $start));
		$this->set('assessment_records', $assessmentRecords);
		$this->set('results', $this->EduAssessmentRecord->find('count', array('conditions' => $conditions)));
	}
	
	public function list_data($id = null)
	{
		// comment $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		// comment $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
		// comment $assessmentId = (isset($_REQUEST['assessment_id'])) ? $_REQUEST['assessment_id'] : -1;
		//if ($id) {
		//	$assessmentId = ($id) ? $id : -1;
		//}
		$asses = $this->EduAssessmentRecord->EduAssessment->read(null, $id);
		$conditionsy['EduRegistration.edu_section_id'] =
			$asses['EduAssessment']['edu_section_id'];
		$this->loadModel('EduRegistration');
		$students = $this->EduRegistration->find('all', array('conditions'=>$conditionsy));
		$i = 0;
		$results = array();
		foreach ($students as $student) {
			$results[$i]['id']=$i;
			$results[$i]['student_id']=$student['EduStudent']['id'];
			$results[$i]['first_name']=$student['EduStudent']['name'];
			$results[$i]['assessment_id']=$id;
			$conditionsx['EduAssessmentRecord.edu_student_id']=$student['EduStudent']['id'];
			$conditionsx['EduAssessmentRecord.edu_assessment_Id']=$id;
			$res = $this->EduAssessmentRecord->find('first', array('conditions'=>$conditionsx));
			if (!empty($res)) {
				$results[$i]['rank'] = $res['EduAssessmentRecord']['rank'];
			} else {
				$results[$i]['rank']='';
			}
			$i++;
		}
		$this->set('assessment_records', $results);
		$this->set('results', $this->EduAssessmentRecord->find('count'));
	}

	public function list_data_combo() {
		$edu_assessment_id = (isset($_REQUEST['edu_assessment_id'])) ? $_REQUEST['edu_assessment_id'] : -1;
		$edu_registration_id = (isset($_REQUEST['edu_registration_id'])) ? $_REQUEST['edu_registration_id'] : -1;
		
		$conditions = array();
		$conditions['EduAssessmentRecord.edu_assessment_id'] = $edu_assessment_id;
		$conditions['EduAssessmentRecord.edu_registration_id'] = $edu_registration_id;
		
		$this->set('edu_assessment_records', $this->EduAssessmentRecord->find('all', array('conditions' => $conditions)));
		$this->set('results', $this->EduAssessmentRecord->find('count'));
	}
	
	public function view($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid assessment record', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->EduAssessmentRecord->recursive = 2;
		$this->set('assessment_record', $this->EduAssessmentRecord->read(null, $id));
	}

	public function add2($id = null)
	{
		if (!empty($this->data)) {
			$this->EduAssessmentRecord->create();
			$this->autoRender = false;
			if ($this->EduAssessmentRecord->save($this->data)) {
				$this->Session->setFlash(__('The assessment record has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The assessment record could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if ($id) {
			$this->set('parent_id', $id);
		}
		$students = $this->EduAssessmentRecord->EduStudent->find('list');
		$assessments = $this->EduAssessmentRecord->EduAssessment->find('list');
		$this->set(compact('students', 'assessments'));
	}
	
	public  function add($id = null)
	{
		$this->autoRender = false;
		foreach ($this->data as $record) {
			$studentId = $record['student_id'];
			$assessmentId = $record['assessment'];
			$mark = $record['mark'];
			
			$assessmentId = str_replace('"', '', $assessmentId);
			$studentId = str_replace('"', '', $studentId);
			$mark = str_replace('"', '', $mark);
			
			if ($mark !=='') {
				$conditionx['EduAssessmentRecord.edu_assessment_id'] = $assessmentId;
				$conditionx['EduAssessmentRecord.edu_student_id'] = $studentId;
				$this->EduAssessmentRecord->deleteAll($conditionx);
				
				$this->data2['EduAssessmentRecord']['edu_assessment_id'] = $assessmentId;
				$this->data2['EduAssessmentRecord']['edu_student_id'] = $studentId;
				$this->data2['EduAssessmentRecord']['mark'] = $mark;
				$this->EduAssessmentRecord->create();
				$this->EduAssessmentRecord->save($this->data2);
			}
		}
	}

	public function edit($id = null, $parentId = null)
	{
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid assessment record', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->EduAssessmentRecord->save($this->data)) {
				$this->Session->setFlash(__('The assessment record has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The assessment record could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('assessment__record', $this->EduAssessmentRecord->read(null, $id));
		
		if ($parentId) {
			$this->set('parent_id', $parentId);
		}
			
		$students = $this->EduAssessmentRecord->EduStudent->find('list');
		$assessments = $this->EduAssessmentRecord->EduAssessment->find('list');
		$this->set(compact('students', 'assessments'));
	}

	public function delete($id = null)
	{
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for assessment record', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->EduAssessmentRecord->delete($i);
                }
				$this->Session->setFlash(__('Assessment record deleted', true), '');
				$this->render('/elements/success');
            } catch (Exception $e) {
				$this->Session->setFlash(__('Assessment record was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->EduAssessmentRecord->delete($id)) {
				$this->Session->setFlash(__('Assessment record deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Assessment record was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
