<?php

App::import('Vendor', 'PHPExcel/PHPExcel/IOFactory', array('file' => 'IOFactory.php'));

class MigratorController extends AppController {

    public $uses = array();

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('*');
    }
	
    public function index() {
        
    }

    public function result() {
		$file_name = IMAGES . 'migration_2013.xlsx';
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel = $objReader->load($file_name);
		$results = '';
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			echo $worksheet->getTitle() . '<br/>';
			if($worksheet->getTitle() == 'kg x') {
				$this->migrate_kg($worksheet);
				
				break;
			} 
			elseif($worksheet->getTitle() == 'pre-kg x') {
				$this->migrate_pre_kg($worksheet);
				
				break;
			} 
			elseif($worksheet->getTitle() == 'Preparatory 2 x') {
				$this->migrate_preparatory($worksheet);
				
				break;
			}
			elseif($worksheet->getTitle() == 'G1B x') {
				$this->migrate_g($worksheet);
				
				break;
			}
			elseif($worksheet->getTitle() == 'G2 x') {
				$this->migrate_g2($worksheet);
				
				break;
			}
			elseif($worksheet->getTitle() == 'G3B x') {
				$this->migrate_g3($worksheet);
				
				break;
			}
			elseif($worksheet->getTitle() == 'G3 a') {
				$this->migrate_g3_ammend($worksheet);
				
				break;
			}
			elseif($worksheet->getTitle() == 'G4B x') {
				$this->migrate_g4($worksheet);
				
				break;
			}
			elseif($worksheet->getTitle() == 'G5 x') {
				$this->migrate_g5($worksheet);
				
				break;
			}
			elseif($worksheet->getTitle() == 'G6 X') {
				$this->migrate_g6($worksheet);
				
				break;
			}
			elseif($worksheet->getTitle() == 'G7 x') {
				$this->migrate_g7($worksheet);
				
				break;
			}
			elseif($worksheet->getTitle() == 'G8 x') {
				$this->migrate_g8($worksheet);
				
				break;
			}
			elseif($worksheet->getTitle() == 'G8 a') {
				$this->migrate_g8_ammend($worksheet);
				
				break;
			}
			elseif($worksheet->getTitle() == 'G9 x') {
				$this->migrate_g9($worksheet);
				
				break;
			}
			elseif($worksheet->getTitle() == 'G10') {
				$this->migrate_g10($worksheet);
				
				break;
			}
		}
		$this->autoRender = false;
		$this->render('/migrator/index');
    }
	
	public function migrate_kg($worksheet){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => 1)));
		
		foreach($gs as $g) {
			$guidelines[$g['EduGuideline']['name']] = $g['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		// input into the db
		$edu_section_id = 17; // (17 => A) => KG
		$edu_class_id = 2; // KG
		$birth_date = '2010-06-01';
		$registration_date = '2014-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {
			/*if($row->getRowIndex() < 1) {
				$records++;
				continue;
			}*/

			if($row->getRowIndex() == 1) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$this->loadModel('Edu.EduEvaluation');
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						//echo 'CV: ' . $cv . ' (' . $co . ')<br/>';
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)));
							//pr($ea);
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							}
						}
					}
				}
				pr($evaluation_ids);
			} else {
				$index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// clear it
						$cv = ereg_replace("\r",'',$cv);
						$cv = ereg_replace("\n\n",'',$cv);
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						if(($index - 2) % 4 == 0) { // this is a new student record start
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								
								$student['registration_date'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['quarters'] = array();
								$student['quarters'][(($index - 2) % 4) + 1] = array();
							}
							
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['quarters'][(($index - 2) % 4) + 1])) {
								$student['quarters'][(($index - 2) % 4) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							$student['quarters'][(($index - 2) % 4) + 1][$evaluation_ids[$key]] = $guidelines[$cv];
						}
					}
					
				}
				if((($index - 2) % 4) == 3) {
					$students[] = $student;
					$student = array();
				}
			}
			/*
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);
			
			
			foreach ($cellIterator as $cell) {
				if (!is_null($cell)) {
					$cv = trim(strtoupper($cell->getCalculatedValue()));
					$co = $cell->getCoordinate();
					
					
				}
			}*/
		}
		//$this->Session->write('results', $results);
		//$this->redirect(array('controller' => 'migrator', 'action' => 'result_display'));
		pr($students);
		
		
		
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduRegistrationQuarter');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		
		
		foreach($students as $s){
			// 1. save EduStudent
			$student = null;
			$student = $this->EduStudent->find('first', array('conditions' => array('EduStudent.identity_number' => $s['identity_number'])));
			if($student) {
				// regular student
				 
			} else {
				$student = array('EduStudent' => array());
				$student['EduStudent']['name'] = $s['name'];
				$student['EduStudent']['identity_number'] = $s['identity_number'];
				$student['EduStudent']['birth_date'] = $birth_date;
				$student['EduStudent']['registration_date'] = $registration_date;
				$student['EduStudent']['gender'] = $s['sex'];
				$student['EduStudent']['nationality'] = $nationality;
				$student['EduStudent']['edu_parent_id'] = $edu_parent_id;
				$student['EduStudent']['photo_file_name'] = $photo_file_name;
				$student['EduStudent']['maker_id'] = $maker_id;
				$student['EduStudent']['status'] = $status;
				$student['EduStudent']['id'] = null;
				$this->EduStudent->create();
				if($this->EduStudent->save($student)){
					$student['EduStudent']['id'] = $this->EduStudent->id;
				}
			}
			
			if($student['EduStudent']['id']){
				// 2. save EduRegistration
				$edu_student_id = $student['EduStudent']['id'];
				$registration = array('EduRegistration' => array());
				$registration['EduRegistration']['name'] = $s['name'];
				$registration['EduRegistration']['edu_student_id'] = $edu_student_id;
				$registration['EduRegistration']['edu_class_id'] = $edu_class_id;
				$registration['EduRegistration']['edu_section_id'] = $edu_section_id;
				$registration['EduRegistration']['edu_campus_id'] = $edu_campus_id;
				$registration['EduRegistration']['grand_total_average'] = 0;
				$registration['EduRegistration']['rank'] = 0;
				$registration['EduRegistration']['class_rank'] = 0;
				$registration['EduRegistration']['status'] = 'P';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = '-';
				
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 15, 16, 17, 18);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = 0;
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
						
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						foreach($s['quarters'][$ki] as $k => $v){
							// 4. save EduRegistrationEvaluation
							$r_ev = array('EduRegistrationEvaluation' => array());
							$r_ev['EduRegistrationEvaluation']['edu_registration_id'] = $edu_registration_id;
							$r_ev['EduRegistrationEvaluation']['edu_evaluation_id'] = $k;
							$r_ev['EduRegistrationEvaluation']['edu_quarter_id'] = $q;
							$r_ev['EduRegistrationEvaluation']['edu_guideline_id'] = $v;
							
							$this->EduRegistrationEvaluation->create();
							$this->EduRegistrationEvaluation->save($r_ev);
						}
					}
				}
			}
		}
	}
	
	public function migrate_pre_kg($worksheet){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => 2)));
		
		$na_id = 0;
		foreach($gs as $g) {
			$guidelines[$g['EduGuideline']['name']] = $g['EduGuideline']['id'];
			if($g['EduGuideline']['name'] == '-'){
				$na_id = $g['EduGuideline']['id'];
			}
		}
		$guidelines[''] = $na_id;
		
		pr($guidelines);
		// input into the db
		$edu_section_id = 16; // (1 => A)
		$edu_class_id = 1; // Pre-KG
		$birth_date = '2010-06-01';
		$registration_date = '2014-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {
			/*if($row->getRowIndex() < 1) {
				$records++;
				continue;
			}*/

			if($row->getRowIndex() == 1) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$this->loadModel('Edu.EduEvaluation');
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = str_replace("  ", ' ',$cv);
						$cv = str_replace("+", '', $cv);
						$cv = str_replace("AA", 'A',$cv);
						$cv = strtoupper($cv);
						
						$co = $cell->getCoordinate();
						//echo 'CV: ' . $cv . ' (' . $co . ')<br/>';
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)));
							//pr($ea);
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							}
						}
					}
				}
				pr($evaluation_ids);
			} else {
				$index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// clear it
						$cv = ereg_replace("\r",'',$cv);
						$cv = ereg_replace("\n\n",'',$cv);
						$cv = ereg_replace("\n",'',$cv);
						$cv = str_replace("  ", ' ',$cv);
						$cv = str_replace("+", '', $cv);
						$cv = str_replace("AA", 'A',$cv);
						$cv = strtoupper($cv);
						
						$co = $cell->getCoordinate();
						if(($index - 2) % 4 == 0) { // this is a new student record start
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								
								$student['registration_date'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['quarters'] = array();
								$student['quarters'][(($index - 2) % 4) + 1] = array();
							}
							
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['quarters'][(($index - 2) % 4) + 1])) {
								$student['quarters'][(($index - 2) % 4) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							$student['quarters'][(($index - 2) % 4) + 1][$evaluation_ids[$key]] = $guidelines[$cv];
						}
					}
					
				}
				if((($index - 2) % 4) == 3) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		//$this->Session->write('results', $results);
		//$this->redirect(array('controller' => 'migrator', 'action' => 'result_display'));
		pr($students);
		
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduRegistrationQuarter');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		
		
		foreach($students as $s){
			// 1. save EduStudent
			$student = array('EduStudent' => array());
			$student['EduStudent']['name'] = $s['name'];
			$student['EduStudent']['identity_number'] = $s['identity_number'];
			$student['EduStudent']['birth_date'] = $birth_date;
			$student['EduStudent']['registration_date'] = $registration_date;
			$student['EduStudent']['gender'] = $s['sex'];
			$student['EduStudent']['nationality'] = $nationality;
			$student['EduStudent']['edu_parent_id'] = $edu_parent_id;
			$student['EduStudent']['photo_file_name'] = $photo_file_name;
			$student['EduStudent']['maker_id'] = $maker_id;
			$student['EduStudent']['status'] = $status;
			
			$this->EduStudent->create();
			if($this->EduStudent->save($student)){
				// 2. save EduRegistration
				$edu_student_id = $this->EduStudent->id;
				$registration = array('EduRegistration' => array());
				$registration['EduRegistration']['name'] = $s['name'];
				$registration['EduRegistration']['edu_student_id'] = $edu_student_id;
				$registration['EduRegistration']['edu_class_id'] = $edu_class_id;
				$registration['EduRegistration']['edu_section_id'] = $edu_section_id;
				$registration['EduRegistration']['edu_campus_id'] = $edu_campus_id;
				$registration['EduRegistration']['grand_total_average'] = 0;
				$registration['EduRegistration']['rank'] = 0;
				$registration['EduRegistration']['class_rank'] = 0;
				$registration['EduRegistration']['status'] = 'P';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = '-';
				
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 15, 16, 17, 18);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = 0;
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
						
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						foreach($s['quarters'][$ki] as $k => $v){
							// 4. save EduRegistrationEvaluation
							$r_ev = array('EduRegistrationEvaluation' => array());
							$r_ev['EduRegistrationEvaluation']['edu_registration_id'] = $edu_registration_id;
							$r_ev['EduRegistrationEvaluation']['edu_evaluation_id'] = $k;
							$r_ev['EduRegistrationEvaluation']['edu_quarter_id'] = $q;
							$r_ev['EduRegistrationEvaluation']['edu_guideline_id'] = $v;
							
							$this->EduRegistrationEvaluation->create();
							$this->EduRegistrationEvaluation->save($r_ev);
						}
					}
				}
			}
		}
	}
	
	public function migrate_preparatory($worksheet){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => 3)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		// input into the db
		$edu_section_id = 19; // (18 => A, 19 => B)
		$edu_class_id = 3; // PREP
		$birth_date = '2008-06-01';
		$registration_date = '2014-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {

			if($row->getRowIndex() == 1) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$this->loadModel('Edu.EduEvaluation');
				$this->loadModel('Edu.EduCourse');
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						echo 'CV: ' . $cv . ' (' . $co . ')<br/>';
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)
								));
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							} else {
								echo 'Not Evaluation<br/>';
							}
							
							$course = $this->EduCourse->find('first', array('conditions' => array(
									'EduSubject.name' => $cv, 'EduCourse.edu_class_id' => $edu_class_id)
								));
							if($course) {
								$key = str_replace('1', '', $co);
								$course_ids[$key] = $course['EduCourse']['id'];
							} else {
								echo 'Not Course<br/>';
							}
						}
					}
				}
				pr($evaluation_ids);
				pr($course_ids);
			} else {
				$index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// clear it
						$cv = ereg_replace("\r",'',$cv);
						$cv = ereg_replace("\n\n",'',$cv);
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						if(($index - 2) % 3 == 0) { // this is a new student record start
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								
								$student['registration_date'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['semesters'] = array();
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['semesters'][(($index - 2) % 3) + 1])) {
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							if(isset($evaluation_ids[$key])){
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['E']))
									$student['semesters'][(($index - 2) % 3) + 1]['E'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['E'][$evaluation_ids[$key]] = $guidelines[$cv];
							} elseif(isset($course_ids[$key])) {
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['C']))
									$student['semesters'][(($index - 2) % 3) + 1]['C'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['C'][$course_ids[$key]] = $cv;
							}
							
							if($co == 'N'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['total'] = $cv;
							} elseif($co == 'O'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['average'] = $cv;
							} elseif($co == 'P'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['rank'] = $cv;
							} elseif($co == 'R'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['remark'] = $cv;
							}
						}
					}
				}
				if((($index - 2) % 3) == 2) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		//$this->Session->write('results', $results);
		//$this->redirect(array('controller' => 'migrator', 'action' => 'result_display'));
		pr($students);
		
		
		
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduRegistrationQuarter');
		$this->loadModel('Edu.EduRegistrationQuarterResult');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		$this->loadModel('Edu.EduRegistrationResult');
		
		
		foreach($students as $s){
			$student = null;
			$student = $this->EduStudent->find('first', array('conditions' => array('EduStudent.identity_number' => $s['identity_number'])));
			if($student) {
				// regular student
				 
			} else {
				$student = array('EduStudent' => array());
				$student['EduStudent']['name'] = $s['name'];
				$student['EduStudent']['identity_number'] = $s['identity_number'];
				$student['EduStudent']['birth_date'] = $birth_date;
				$student['EduStudent']['registration_date'] = $registration_date;
				$student['EduStudent']['gender'] = $s['sex'];
				$student['EduStudent']['nationality'] = $nationality;
				$student['EduStudent']['edu_parent_id'] = $edu_parent_id;
				$student['EduStudent']['photo_file_name'] = $photo_file_name;
				$student['EduStudent']['maker_id'] = $maker_id;
				$student['EduStudent']['status'] = $status;
				$student['EduStudent']['id'] = null;
				$this->EduStudent->create();
				if($this->EduStudent->save($student)){
					$student['EduStudent']['id'] = $this->EduStudent->id;
				}
			}
			
			if($student['EduStudent']['id']){
				// 2. save EduRegistration
				$edu_student_id = $student['EduStudent']['id'];
				$registration = array('EduRegistration' => array());
				$registration['EduRegistration']['name'] = $s['name'];
				$registration['EduRegistration']['edu_student_id'] = $edu_student_id;
				$registration['EduRegistration']['edu_class_id'] = $edu_class_id;
				$registration['EduRegistration']['edu_section_id'] = $edu_section_id;
				$registration['EduRegistration']['edu_campus_id'] = $edu_campus_id;
				$registration['EduRegistration']['grand_total_average'] = ($s['semesters'][3]['average'] == ''? 0: $s['semesters'][3]['average']);
				$registration['EduRegistration']['rank'] = ($s['semesters'][3]['rank'] == ''? 0: $s['semesters'][3]['rank']);
				$registration['EduRegistration']['class_rank'] = 0;
				$registration['EduRegistration']['status'] = 'P';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = $s['semesters'][3]['remark'];
		
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 15, 16, 17, 18);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$qindex = ($ki > 2)? 2: 1;
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = ($s['semesters'][$qindex]['total'] == ''? 0: $s['semesters'][$qindex]['total']);
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = ($s['semesters'][$qindex]['average'] == ''? 0: $s['semesters'][$qindex]['average']);
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = ($s['semesters'][$qindex]['rank'] == ''? 0: $s['semesters'][$qindex]['rank']);
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
					
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						$edu_registration_quarter_id = $this->EduRegistrationQuarter->id;
						// Course
						foreach($s['semesters'][$qindex]['C'] as $k => $v){
							// 4. save EduRegistrationQuarterResult
							if($k == '') {
								continue;
							}
							$r_ev = array('EduRegistrationQuarterResult' => array());
							$r_ev['EduRegistrationQuarterResult']['edu_registration_quarter_id'] = $edu_registration_quarter_id;
							$r_ev['EduRegistrationQuarterResult']['edu_course_id'] = $k;
							$r_ev['EduRegistrationQuarterResult']['course_result'] = is_numeric($v)? $v: 0;
							$r_ev['EduRegistrationQuarterResult']['scale_result'] = is_numeric($v)? '-': $v;
							$r_ev['EduRegistrationQuarterResult']['course_rank'] = 0;
							$r_ev['EduRegistrationQuarterResult']['result_indicator'] = 'P';
							
							$this->EduRegistrationQuarterResult->create();
							$this->EduRegistrationQuarterResult->save($r_ev);
						}
						
						// Evaluation
						foreach($s['semesters'][$qindex]['E'] as $k => $v){
							// 4. save EduRegistrationEvaluation
							$r_ev = array('EduRegistrationEvaluation' => array());
							$r_ev['EduRegistrationEvaluation']['edu_registration_id'] = $edu_registration_id;
							$r_ev['EduRegistrationEvaluation']['edu_evaluation_id'] = $k;
							$r_ev['EduRegistrationEvaluation']['edu_quarter_id'] = $q;
							$r_ev['EduRegistrationEvaluation']['edu_guideline_id'] = $v;
							
							$this->EduRegistrationEvaluation->create();
							$this->EduRegistrationEvaluation->save($r_ev);
						}
					}
					foreach($s['semesters'][3]['C'] as $k => $v){
						if($k == '') {
							continue;
						}
						$r_r = array('EduRegistrationResult' => array());
						$r_r['EduRegistrationResult']['edu_registration_id'] = $edu_registration_id;
						$r_r['EduRegistrationResult']['edu_course_id'] = $k;
						$r_r['EduRegistrationResult']['average'] = is_numeric($v)? $v: 0;
						$r_r['EduRegistrationResult']['scale_result'] = is_numeric($v)? '-': $v;
						$r_r['EduRegistrationResult']['status'] = 'P';
						
						$this->EduRegistrationResult->create();
						$this->EduRegistrationResult->save($r_r);
					}
				}
			}
		}
	}
	
	public function migrate_g($worksheet){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => 3)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		// input into the db
		$edu_section_id = 31; // (30 => A, 31 => B)
		$edu_class_id = 4; // G1
		$birth_date = '2006-06-01';
		$registration_date = '2014-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {

			if($row->getRowIndex() == 1) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$this->loadModel('Edu.EduEvaluation');
				$this->loadModel('Edu.EduCourse');
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						echo 'CV: ' . $cv . ' (' . $co . ')<br/>';
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)
								));
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							} else {
								echo 'Not Evaluation<br/>';
							}
							
							$course = $this->EduCourse->find('first', array('conditions' => array(
									'EduSubject.name' => $cv, 'EduCourse.edu_class_id' => $edu_class_id)
								));
							if($course) {
								$key = str_replace('1', '', $co);
								$course_ids[$key] = $course['EduCourse']['id'];
							} else {
								echo 'Not Course<br/>';
							}
						}
					}
				}
				pr($evaluation_ids);
				pr($course_ids);
			} else {
				$index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// clear it
						$cv = ereg_replace("\r",'',$cv);
						$cv = ereg_replace("\n\n",'',$cv);
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						if(($index - 2) % 3 == 0) { // this is a new student record start
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								
								$student['registration_date'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['semesters'] = array();
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['semesters'][(($index - 2) % 3) + 1])) {
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							if(isset($evaluation_ids[$key])){
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['E']))
									$student['semesters'][(($index - 2) % 3) + 1]['E'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['E'][$evaluation_ids[$key]] = $guidelines[$cv];
							} elseif(isset($course_ids[$key])) {
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['C']))
									$student['semesters'][(($index - 2) % 3) + 1]['C'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['C'][$course_ids[$key]] = $cv;
							}
							
							if($co == 'O'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['total'] = $cv;
							} elseif($co == 'P'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['average'] = $cv;
							} elseif($co == 'Q'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['rank'] = $cv;
							} elseif($co == 'S'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['remark'] = $cv;
							}
						}
					}
				}
				if((($index - 2) % 3) == 2) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		//$this->Session->write('results', $results);
		//$this->redirect(array('controller' => 'migrator', 'action' => 'result_display'));
		pr($students);
		
		
		
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduRegistrationQuarter');
		$this->loadModel('Edu.EduRegistrationQuarterResult');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		$this->loadModel('Edu.EduRegistrationResult');
		
		
		foreach($students as $s){
			$student = null;
			$student = $this->EduStudent->find('first', array('conditions' => array('EduStudent.identity_number' => $s['identity_number'])));
			if($student) {
				// regular student
				 
			} else {
				$student = array('EduStudent' => array());
				$student['EduStudent']['name'] = $s['name'];
				$student['EduStudent']['identity_number'] = $s['identity_number'];
				$student['EduStudent']['birth_date'] = $birth_date;
				$student['EduStudent']['registration_date'] = $registration_date;
				$student['EduStudent']['gender'] = $s['sex'];
				$student['EduStudent']['nationality'] = $nationality;
				$student['EduStudent']['edu_parent_id'] = $edu_parent_id;
				$student['EduStudent']['photo_file_name'] = $photo_file_name;
				$student['EduStudent']['maker_id'] = $maker_id;
				$student['EduStudent']['status'] = $status;
				$student['EduStudent']['id'] = null;
				$this->EduStudent->create();
				if($this->EduStudent->save($student)){
					$student['EduStudent']['id'] = $this->EduStudent->id;
				}
			}
			
			if($student['EduStudent']['id']){
				// 2. save EduRegistration
				$edu_student_id = $student['EduStudent']['id'];
				$registration = array('EduRegistration' => array());
				$registration['EduRegistration']['name'] = $s['name'];
				$registration['EduRegistration']['edu_student_id'] = $edu_student_id;
				$registration['EduRegistration']['edu_class_id'] = $edu_class_id;
				$registration['EduRegistration']['edu_section_id'] = $edu_section_id;
				$registration['EduRegistration']['edu_campus_id'] = $edu_campus_id;
				$registration['EduRegistration']['grand_total_average'] = ($s['semesters'][3]['average'] == ''? 0: $s['semesters'][3]['average']);
				$registration['EduRegistration']['rank'] = ($s['semesters'][3]['rank'] == ''? 0: $s['semesters'][3]['rank']);
				$registration['EduRegistration']['class_rank'] = 0;
				$registration['EduRegistration']['status'] = 'P';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = $s['semesters'][3]['remark'];
		
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 15, 16, 17, 18);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$qindex = ($ki > 2)? 2: 1;
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = ($s['semesters'][$qindex]['total'] == ''? 0: $s['semesters'][$qindex]['total']);
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = ($s['semesters'][$qindex]['average'] == ''? 0: $s['semesters'][$qindex]['average']);
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = ($s['semesters'][$qindex]['rank'] == ''? 0: $s['semesters'][$qindex]['rank']);
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
					
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						$edu_registration_quarter_id = $this->EduRegistrationQuarter->id;
						// Course
						foreach($s['semesters'][$qindex]['C'] as $k => $v){
							// 4. save EduRegistrationQuarterResult
							if($k == '') {
								continue;
							}
							$r_ev = array('EduRegistrationQuarterResult' => array());
							$r_ev['EduRegistrationQuarterResult']['edu_registration_quarter_id'] = $edu_registration_quarter_id;
							$r_ev['EduRegistrationQuarterResult']['edu_course_id'] = $k;
							$r_ev['EduRegistrationQuarterResult']['course_result'] = is_numeric($v)? $v: 0;
							$r_ev['EduRegistrationQuarterResult']['scale_result'] = is_numeric($v)? '-': $v;
							$r_ev['EduRegistrationQuarterResult']['course_rank'] = 0;
							$r_ev['EduRegistrationQuarterResult']['result_indicator'] = 'P';
							
							$this->EduRegistrationQuarterResult->create();
							$this->EduRegistrationQuarterResult->save($r_ev);
						}
						
						// Evaluation
						foreach($s['semesters'][$qindex]['E'] as $k => $v){
							// 4. save EduRegistrationEvaluation
							$r_ev = array('EduRegistrationEvaluation' => array());
							$r_ev['EduRegistrationEvaluation']['edu_registration_id'] = $edu_registration_id;
							$r_ev['EduRegistrationEvaluation']['edu_evaluation_id'] = $k;
							$r_ev['EduRegistrationEvaluation']['edu_quarter_id'] = $q;
							$r_ev['EduRegistrationEvaluation']['edu_guideline_id'] = $v;
							
							$this->EduRegistrationEvaluation->create();
							$this->EduRegistrationEvaluation->save($r_ev);
						}
					}
					foreach($s['semesters'][3]['C'] as $k => $v){
						if($k == '') {
							continue;
						}
						$r_r = array('EduRegistrationResult' => array());
						$r_r['EduRegistrationResult']['edu_registration_id'] = $edu_registration_id;
						$r_r['EduRegistrationResult']['edu_course_id'] = $k;
						$r_r['EduRegistrationResult']['average'] = is_numeric($v)? $v: 0;
						$r_r['EduRegistrationResult']['scale_result'] = is_numeric($v)? '-': $v;
						$r_r['EduRegistrationResult']['status'] = 'P';
						
						$this->EduRegistrationResult->create();
						$this->EduRegistrationResult->save($r_r);
					}
				}
			}
		}
	}
	
	public function migrate_g_b($worksheet){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => 3)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		// input into the db
		$edu_section_id = 31; // (31 => B)
		$edu_class_id = 4; // G1
		$birth_date = '2006-06-01';
		$registration_date = '2014-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {

			if($row->getRowIndex() == 1) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$this->loadModel('Edu.EduEvaluation');
				$this->loadModel('Edu.EduCourse');
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						echo 'CV: ' . $cv . ' (' . $co . ')<br/>';
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)
								));
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							} else {
								echo 'Not Evaluation<br/>';
							}
							
							$course = $this->EduCourse->find('first', array('conditions' => array(
									'EduSubject.name' => $cv, 'EduCourse.edu_class_id' => $edu_class_id)
								));
							if($course) {
								$key = str_replace('1', '', $co);
								$course_ids[$key] = $course['EduCourse']['id'];
							} else {
								echo 'Not Course<br/>';
							}
						}
					}
				}
				pr($evaluation_ids);
				pr($course_ids);
			} else {
				$index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// clear it
						$cv = ereg_replace("\r",'',$cv);
						$cv = ereg_replace("\n\n",'',$cv);
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						if(($index - 2) % 3 == 0) { // this is a new student record start
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								
								$student['registration_date'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['semesters'] = array();
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['semesters'][(($index - 2) % 3) + 1])) {
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							if(isset($evaluation_ids[$key])){
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['E']))
									$student['semesters'][(($index - 2) % 3) + 1]['E'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['E'][$evaluation_ids[$key]] = $guidelines[$cv];
							} elseif(isset($course_ids[$key])) {
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['C']))
									$student['semesters'][(($index - 2) % 3) + 1]['C'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['C'][$course_ids[$key]] = $cv;
							}
							
							if($co == 'O'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['total'] = $cv;
							} elseif($co == 'P'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['average'] = $cv;
							} elseif($co == 'Q'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['rank'] = $cv;
							} elseif($co == 'S'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['remark'] = $cv;
							}
						}
					}
				}
				if((($index - 2) % 3) == 2) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		//$this->Session->write('results', $results);
		//$this->redirect(array('controller' => 'migrator', 'action' => 'result_display'));
		pr($students);
		
		
		
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduRegistrationQuarter');
		$this->loadModel('Edu.EduRegistrationQuarterResult');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		$this->loadModel('Edu.EduRegistrationResult');
		
		
		foreach($students as $s){
			$student = null;
			$student = $this->EduStudent->find('first', array('conditions' => array('EduStudent.identity_number' => $s['identity_number'])));
			if($student) {
				// regular student
				 
			} else {
				$student = array('EduStudent' => array());
				$student['EduStudent']['name'] = $s['name'];
				$student['EduStudent']['identity_number'] = $s['identity_number'];
				$student['EduStudent']['birth_date'] = $birth_date;
				$student['EduStudent']['registration_date'] = $registration_date;
				$student['EduStudent']['gender'] = $s['sex'];
				$student['EduStudent']['nationality'] = $nationality;
				$student['EduStudent']['edu_parent_id'] = $edu_parent_id;
				$student['EduStudent']['photo_file_name'] = $photo_file_name;
				$student['EduStudent']['maker_id'] = $maker_id;
				$student['EduStudent']['status'] = $status;
				$student['EduStudent']['id'] = null;
				$this->EduStudent->create();
				if($this->EduStudent->save($student)){
					$student['EduStudent']['id'] = $this->EduStudent->id;
				}
			}
			
			if($student['EduStudent']['id']){
				// 2. save EduRegistration
				$edu_student_id = $student['EduStudent']['id'];
				$registration = array('EduRegistration' => array());
				$registration['EduRegistration']['name'] = $s['name'];
				$registration['EduRegistration']['edu_student_id'] = $edu_student_id;
				$registration['EduRegistration']['edu_class_id'] = $edu_class_id;
				$registration['EduRegistration']['edu_section_id'] = $edu_section_id;
				$registration['EduRegistration']['edu_campus_id'] = $edu_campus_id;
				$registration['EduRegistration']['grand_total_average'] = ($s['semesters'][3]['average'] == ''? 0: $s['semesters'][3]['average']);
				$registration['EduRegistration']['rank'] = ($s['semesters'][3]['rank'] == ''? 0: $s['semesters'][3]['rank']);
				$registration['EduRegistration']['class_rank'] = 0;
				$registration['EduRegistration']['status'] = 'P';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = $s['semesters'][3]['remark'];
		
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 15, 16, 17, 18);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$qindex = ($ki > 2)? 2: 1;
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = ($s['semesters'][$qindex]['total'] == ''? 0: $s['semesters'][$qindex]['total']);
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = ($s['semesters'][$qindex]['average'] == ''? 0: $s['semesters'][$qindex]['average']);
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = ($s['semesters'][$qindex]['rank'] == ''? 0: $s['semesters'][$qindex]['rank']);
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
					
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						$edu_registration_quarter_id = $this->EduRegistrationQuarter->id;
						// Course
						foreach($s['semesters'][$qindex]['C'] as $k => $v){
							// 4. save EduRegistrationQuarterResult
							if($k == '') {
								continue;
							}
							$r_ev = array('EduRegistrationQuarterResult' => array());
							$r_ev['EduRegistrationQuarterResult']['edu_registration_quarter_id'] = $edu_registration_quarter_id;
							$r_ev['EduRegistrationQuarterResult']['edu_course_id'] = $k;
							$r_ev['EduRegistrationQuarterResult']['course_result'] = is_numeric($v)? $v: 0;
							$r_ev['EduRegistrationQuarterResult']['scale_result'] = is_numeric($v)? '-': $v;
							$r_ev['EduRegistrationQuarterResult']['course_rank'] = 0;
							$r_ev['EduRegistrationQuarterResult']['result_indicator'] = 'P';
							
							$this->EduRegistrationQuarterResult->create();
							$this->EduRegistrationQuarterResult->save($r_ev);
						}
						
						// Evaluation
						foreach($s['semesters'][$qindex]['E'] as $k => $v){
							// 4. save EduRegistrationEvaluation
							$r_ev = array('EduRegistrationEvaluation' => array());
							$r_ev['EduRegistrationEvaluation']['edu_registration_id'] = $edu_registration_id;
							$r_ev['EduRegistrationEvaluation']['edu_evaluation_id'] = $k;
							$r_ev['EduRegistrationEvaluation']['edu_quarter_id'] = $q;
							$r_ev['EduRegistrationEvaluation']['edu_guideline_id'] = $v;
							
							$this->EduRegistrationEvaluation->create();
							$this->EduRegistrationEvaluation->save($r_ev);
						}
					}
					foreach($s['semesters'][3]['C'] as $k => $v){
						if($k == '') {
							continue;
						}
						$r_r = array('EduRegistrationResult' => array());
						$r_r['EduRegistrationResult']['edu_registration_id'] = $edu_registration_id;
						$r_r['EduRegistrationResult']['edu_course_id'] = $k;
						$r_r['EduRegistrationResult']['average'] = is_numeric($v)? $v: 0;
						$r_r['EduRegistrationResult']['scale_result'] = is_numeric($v)? '-': $v;
						$r_r['EduRegistrationResult']['status'] = 'P';
						
						$this->EduRegistrationResult->create();
						$this->EduRegistrationResult->save($r_r);
					}
				}
			}
		}
	}
	
	public function migrate_g2($worksheet){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => 3)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		// input into the db
		$edu_section_id = 32; // (21 => A)
		$edu_class_id = 5; // G2
		$birth_date = '2006-06-01';
		$registration_date = '2014-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {

			if($row->getRowIndex() == 1) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$this->loadModel('Edu.EduEvaluation');
				$this->loadModel('Edu.EduCourse');
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						echo 'CV: ' . $cv . ' (' . $co . ')<br/>';
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)
								));
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							} else {
								echo 'Not Evaluation<br/>';
							}
							
							$course = $this->EduCourse->find('first', array('conditions' => array(
									'EduSubject.name' => $cv, 'EduCourse.edu_class_id' => $edu_class_id)
								));
							if($course) {
								$key = str_replace('1', '', $co);
								$course_ids[$key] = $course['EduCourse']['id'];
							} else {
								echo 'Not Course<br/>';
							}
						}
					}
				}
				pr($evaluation_ids);
				pr($course_ids);
			} else {
				$index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// clear it
						$cv = ereg_replace("\r",'',$cv);
						$cv = ereg_replace("\n\n",'',$cv);
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						if(($index - 2) % 3 == 0) { // this is a new student record start
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								
								$student['registration_date'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['semesters'] = array();
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['semesters'][(($index - 2) % 3) + 1])) {
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							if(isset($evaluation_ids[$key])){
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['E']))
									$student['semesters'][(($index - 2) % 3) + 1]['E'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['E'][$evaluation_ids[$key]] = $guidelines[$cv];
							} elseif(isset($course_ids[$key])) {
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['C']))
									$student['semesters'][(($index - 2) % 3) + 1]['C'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['C'][$course_ids[$key]] = $cv;
							}
							
							if($co == 'O'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['total'] = $cv;
							} elseif($co == 'P'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['average'] = $cv;
							} elseif($co == 'Q'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['rank'] = $cv;
							} elseif($co == 'S'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['remark'] = $cv;
							}
						}
					}
				}
				if((($index - 2) % 3) == 2) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		//$this->Session->write('results', $results);
		//$this->redirect(array('controller' => 'migrator', 'action' => 'result_display'));
		pr($students);
		
		
		
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduRegistrationQuarter');
		$this->loadModel('Edu.EduRegistrationQuarterResult');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		$this->loadModel('Edu.EduRegistrationResult');
		
		
		foreach($students as $s){
			$student = null;
			$student = $this->EduStudent->find('first', array('conditions' => array('EduStudent.identity_number' => $s['identity_number'])));
			if($student) {
				// regular student
				 
			} else {
				$student = array('EduStudent' => array());
				$student['EduStudent']['name'] = $s['name'];
				$student['EduStudent']['identity_number'] = $s['identity_number'];
				$student['EduStudent']['birth_date'] = $birth_date;
				$student['EduStudent']['registration_date'] = $registration_date;
				$student['EduStudent']['gender'] = $s['sex'];
				$student['EduStudent']['nationality'] = $nationality;
				$student['EduStudent']['edu_parent_id'] = $edu_parent_id;
				$student['EduStudent']['photo_file_name'] = $photo_file_name;
				$student['EduStudent']['maker_id'] = $maker_id;
				$student['EduStudent']['status'] = $status;
				$student['EduStudent']['id'] = null;
				$this->EduStudent->create();
				if($this->EduStudent->save($student)){
					$student['EduStudent']['id'] = $this->EduStudent->id;
				}
			}
			
			if($student['EduStudent']['id']){
				// 2. save EduRegistration
				$edu_student_id = $student['EduStudent']['id'];
				$registration = array('EduRegistration' => array());
				$registration['EduRegistration']['name'] = $s['name'];
				$registration['EduRegistration']['edu_student_id'] = $edu_student_id;
				$registration['EduRegistration']['edu_class_id'] = $edu_class_id;
				$registration['EduRegistration']['edu_section_id'] = $edu_section_id;
				$registration['EduRegistration']['edu_campus_id'] = $edu_campus_id;
				$registration['EduRegistration']['grand_total_average'] = ($s['semesters'][3]['average'] == ''? 0: $s['semesters'][3]['average']);
				$registration['EduRegistration']['rank'] = ($s['semesters'][3]['rank'] == ''? 0: $s['semesters'][3]['rank']);
				$registration['EduRegistration']['class_rank'] = 0;
				$registration['EduRegistration']['status'] = 'P';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = $s['semesters'][3]['remark'];
		
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 15, 16, 17, 18);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$qindex = ($ki > 2)? 2: 1;
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = ($s['semesters'][$qindex]['total'] == ''? 0: $s['semesters'][$qindex]['total']);
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = ($s['semesters'][$qindex]['average'] == ''? 0: $s['semesters'][$qindex]['average']);
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = ($s['semesters'][$qindex]['rank'] == ''? 0: $s['semesters'][$qindex]['rank']);
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
					
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						$edu_registration_quarter_id = $this->EduRegistrationQuarter->id;
						// Course
						foreach($s['semesters'][$qindex]['C'] as $k => $v){
							// 4. save EduRegistrationQuarterResult
							if($k == '') {
								continue;
							}
							$r_ev = array('EduRegistrationQuarterResult' => array());
							$r_ev['EduRegistrationQuarterResult']['edu_registration_quarter_id'] = $edu_registration_quarter_id;
							$r_ev['EduRegistrationQuarterResult']['edu_course_id'] = $k;
							$r_ev['EduRegistrationQuarterResult']['course_result'] = is_numeric($v)? $v: 0;
							$r_ev['EduRegistrationQuarterResult']['scale_result'] = is_numeric($v)? '-': $v;
							$r_ev['EduRegistrationQuarterResult']['course_rank'] = 0;
							$r_ev['EduRegistrationQuarterResult']['result_indicator'] = 'P';
							
							$this->EduRegistrationQuarterResult->create();
							$this->EduRegistrationQuarterResult->save($r_ev);
						}
						
						// Evaluation
						foreach($s['semesters'][$qindex]['E'] as $k => $v){
							// 4. save EduRegistrationEvaluation
							$r_ev = array('EduRegistrationEvaluation' => array());
							$r_ev['EduRegistrationEvaluation']['edu_registration_id'] = $edu_registration_id;
							$r_ev['EduRegistrationEvaluation']['edu_evaluation_id'] = $k;
							$r_ev['EduRegistrationEvaluation']['edu_quarter_id'] = $q;
							$r_ev['EduRegistrationEvaluation']['edu_guideline_id'] = $v;
							
							$this->EduRegistrationEvaluation->create();
							$this->EduRegistrationEvaluation->save($r_ev);
						}
					}
					foreach($s['semesters'][3]['C'] as $k => $v){
						if($k == '') {
							continue;
						}
						$r_r = array('EduRegistrationResult' => array());
						$r_r['EduRegistrationResult']['edu_registration_id'] = $edu_registration_id;
						$r_r['EduRegistrationResult']['edu_course_id'] = $k;
						$r_r['EduRegistrationResult']['average'] = is_numeric($v)? $v: 0;
						$r_r['EduRegistrationResult']['scale_result'] = is_numeric($v)? '-': $v;
						$r_r['EduRegistrationResult']['status'] = 'P';
						
						$this->EduRegistrationResult->create();
						$this->EduRegistrationResult->save($r_r);
					}
				}
			}
		}
	}
	
	public function migrate_g3($worksheet){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => 3)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		// input into the db
		$edu_section_id = 34; // (22 => A)
		$edu_class_id = 6; // G3
		$birth_date = '2005-06-01';
		$registration_date = '2014-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {

			if($row->getRowIndex() == 1) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$this->loadModel('Edu.EduEvaluation');
				$this->loadModel('Edu.EduCourse');
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						echo 'CV: ' . $cv . ' (' . $co . ')<br/>';
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)
								));
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							} else {
								echo 'Not Evaluation<br/>';
							}
							
							$course = $this->EduCourse->find('first', array('conditions' => array(
									'EduSubject.name' => $cv, 'EduCourse.edu_class_id' => $edu_class_id)
								));
							if($course) {
								$key = str_replace('1', '', $co);
								$course_ids[$key] = $course['EduCourse']['id'];
							} else {
								echo 'Not Course<br/>';
							}
						}
					}
				}
				pr($evaluation_ids);
				pr($course_ids);
			} else {
				$index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// clear it
						$cv = ereg_replace("\r",'',$cv);
						$cv = ereg_replace("\n\n",'',$cv);
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						if(($index - 2) % 3 == 0) { // this is a new student record start
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								
								$student['registration_date'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['semesters'] = array();
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['semesters'][(($index - 2) % 3) + 1])) {
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							if(isset($evaluation_ids[$key])){
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['E']))
									$student['semesters'][(($index - 2) % 3) + 1]['E'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['E'][$evaluation_ids[$key]] = $guidelines[$cv];
							} elseif(isset($course_ids[$key])) {
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['C']))
									$student['semesters'][(($index - 2) % 3) + 1]['C'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['C'][$course_ids[$key]] = $cv;
							}
							
							if($co == 'O'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['total'] = $cv;
							} elseif($co == 'P'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['average'] = $cv;
							} elseif($co == 'Q'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['rank'] = $cv;
							} elseif($co == 'S'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['remark'] = $cv;
							}
						}
					}
				}
				if((($index - 2) % 3) == 2) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		//$this->Session->write('results', $results);
		//$this->redirect(array('controller' => 'migrator', 'action' => 'result_display'));
		pr($students);
		
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduRegistrationQuarter');
		$this->loadModel('Edu.EduRegistrationQuarterResult');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		$this->loadModel('Edu.EduRegistrationResult');
		
		
		foreach($students as $s){
			$student = null;
			$student = $this->EduStudent->find('first', array('conditions' => array('EduStudent.identity_number' => $s['identity_number'])));
			if($student) {
				// regular student
				 
			} else {
				$student = array('EduStudent' => array());
				$student['EduStudent']['name'] = $s['name'];
				$student['EduStudent']['identity_number'] = $s['identity_number'];
				$student['EduStudent']['birth_date'] = $birth_date;
				$student['EduStudent']['registration_date'] = $registration_date;
				$student['EduStudent']['gender'] = $s['sex'];
				$student['EduStudent']['nationality'] = $nationality;
				$student['EduStudent']['edu_parent_id'] = $edu_parent_id;
				$student['EduStudent']['photo_file_name'] = $photo_file_name;
				$student['EduStudent']['maker_id'] = $maker_id;
				$student['EduStudent']['status'] = $status;
				$student['EduStudent']['id'] = null;
				$this->EduStudent->create();
				if($this->EduStudent->save($student)){
					$student['EduStudent']['id'] = $this->EduStudent->id;
				}
			}
			
			if($student['EduStudent']['id']){
				// 2. save EduRegistration
				$edu_student_id = $student['EduStudent']['id'];
				$registration = array('EduRegistration' => array());
				$registration['EduRegistration']['name'] = $s['name'];
				$registration['EduRegistration']['edu_student_id'] = $edu_student_id;
				$registration['EduRegistration']['edu_class_id'] = $edu_class_id;
				$registration['EduRegistration']['edu_section_id'] = $edu_section_id;
				$registration['EduRegistration']['edu_campus_id'] = $edu_campus_id;
				$registration['EduRegistration']['grand_total_average'] = ($s['semesters'][3]['average'] == ''? 0: $s['semesters'][3]['average']);
				$registration['EduRegistration']['rank'] = ($s['semesters'][3]['rank'] == ''? 0: $s['semesters'][3]['rank']);
				$registration['EduRegistration']['class_rank'] = 0;
				$registration['EduRegistration']['status'] = 'P';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = $s['semesters'][3]['remark'];
		
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 15, 16, 17, 18);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$qindex = ($ki > 2)? 2: 1;
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = ($s['semesters'][$qindex]['total'] == ''? 0: $s['semesters'][$qindex]['total']);
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = ($s['semesters'][$qindex]['average'] == ''? 0: $s['semesters'][$qindex]['average']);
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = ($s['semesters'][$qindex]['rank'] == ''? 0: $s['semesters'][$qindex]['rank']);
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
					
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						$edu_registration_quarter_id = $this->EduRegistrationQuarter->id;
						// Course
						foreach($s['semesters'][$qindex]['C'] as $k => $v){
							// 4. save EduRegistrationQuarterResult
							if($k == '') {
								continue;
							}
							$r_ev = array('EduRegistrationQuarterResult' => array());
							$r_ev['EduRegistrationQuarterResult']['edu_registration_quarter_id'] = $edu_registration_quarter_id;
							$r_ev['EduRegistrationQuarterResult']['edu_course_id'] = $k;
							$r_ev['EduRegistrationQuarterResult']['course_result'] = is_numeric($v)? $v: 0;
							$r_ev['EduRegistrationQuarterResult']['scale_result'] = is_numeric($v)? '-': $v;
							$r_ev['EduRegistrationQuarterResult']['course_rank'] = 0;
							$r_ev['EduRegistrationQuarterResult']['result_indicator'] = 'P';
							
							$this->EduRegistrationQuarterResult->create();
							$this->EduRegistrationQuarterResult->save($r_ev);
						}
						
						// Evaluation
						foreach($s['semesters'][$qindex]['E'] as $k => $v){
							// 4. save EduRegistrationEvaluation
							$r_ev = array('EduRegistrationEvaluation' => array());
							$r_ev['EduRegistrationEvaluation']['edu_registration_id'] = $edu_registration_id;
							$r_ev['EduRegistrationEvaluation']['edu_evaluation_id'] = $k;
							$r_ev['EduRegistrationEvaluation']['edu_quarter_id'] = $q;
							$r_ev['EduRegistrationEvaluation']['edu_guideline_id'] = $v;
							
							$this->EduRegistrationEvaluation->create();
							$this->EduRegistrationEvaluation->save($r_ev);
						}
					}
					foreach($s['semesters'][3]['C'] as $k => $v){
						if($k == '') {
							continue;
						}
						$r_r = array('EduRegistrationResult' => array());
						$r_r['EduRegistrationResult']['edu_registration_id'] = $edu_registration_id;
						$r_r['EduRegistrationResult']['edu_course_id'] = $k;
						$r_r['EduRegistrationResult']['average'] = is_numeric($v)? $v: 0;
						$r_r['EduRegistrationResult']['scale_result'] = is_numeric($v)? '-': $v;
						$r_r['EduRegistrationResult']['status'] = 'P';
						
						$this->EduRegistrationResult->create();
						$this->EduRegistrationResult->save($r_r);
					}
				}
			}
		}
	}
	
	public function migrate_g3_ammend($worksheet){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => 3)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		// input into the db
		$edu_section_id = 8; // (8 => A)
		$edu_class_id = 6; // G3
		$birth_date = '2004-06-01';
		$registration_date = '2014-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {

			if($row->getRowIndex() == 1) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$this->loadModel('Edu.EduEvaluation');
				$this->loadModel('Edu.EduCourse');
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						echo 'CV: ' . $cv . ' (' . $co . ')<br/>';
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)
								));
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							} else {
								echo 'Not Evaluation<br/>';
							}
							
							$course = $this->EduCourse->find('first', array('conditions' => array(
									'EduSubject.name' => $cv, 'EduCourse.edu_class_id' => $edu_class_id)
								));
							if($course) {
								$key = str_replace('1', '', $co);
								$course_ids[$key] = $course['EduCourse']['id'];
							} else {
								echo 'Not Course<br/>';
							}
						}
					}
				}
				pr($evaluation_ids);
				pr($course_ids);
			} else {
				$index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// clear it
						$cv = ereg_replace("\r",'',$cv);
						$cv = ereg_replace("\n\n",'',$cv);
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						if(($index - 2) % 3 == 0) { // this is a new student record start
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								
								$student['registration_date'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['semesters'] = array();
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['semesters'][(($index - 2) % 3) + 1])) {
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							if(isset($evaluation_ids[$key])){
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['E']))
									$student['semesters'][(($index - 2) % 3) + 1]['E'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['E'][$evaluation_ids[$key]] = $guidelines[$cv];
							} elseif(isset($course_ids[$key])) {
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['C']))
									$student['semesters'][(($index - 2) % 3) + 1]['C'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['C'][$course_ids[$key]] = $cv;
							}
							
							if($co == 'O'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['total'] = $cv;
							} elseif($co == 'P'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['average'] = $cv;
							} elseif($co == 'Q'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['rank'] = $cv;
							} elseif($co == 'S'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['remark'] = $cv;
							}
						}
					}
				}
				if((($index - 2) % 3) == 2) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		//$this->Session->write('results', $results);
		//$this->redirect(array('controller' => 'migrator', 'action' => 'result_display'));
		pr($students);
		
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduRegistrationQuarter');
		$this->loadModel('Edu.EduRegistrationQuarterResult');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		$this->loadModel('Edu.EduRegistrationResult');
		
		
		foreach($students as $s){
			// 1. save EduStudent
			
			//$this->EduStudent->create();
			$stu = $this->EduStudent->find('first', array('conditions' => array('EduStudent.identity_number' => $s['identity_number'])));
			//pr($stu);
			//continue;
			
			if(!empty($stu) || $stu){
				// 2. save EduRegistration
				$edu_student_id = $stu['EduStudent']['id'];
				$registration = $stu['EduRegistration'][0];
				
				if($registration){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 15, 16, 17, 18);
					$edu_registration_id = $registration['id'];
					
					foreach($qs as $ki => $q) {
						$qindex = ($ki > 2)? 2: 1;
						
						if(strpos($s['semesters'][$qindex]['rank'], '/') === FALSE){
							continue;
						}
						$rparts = explode('/', $s['semesters'][$qindex]['rank']);
						$rank = $rparts[0];
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = ($s['semesters'][$qindex]['total'] == ''? 0: $s['semesters'][$qindex]['total']);
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = ($s['semesters'][$qindex]['average'] == ''? 0: $s['semesters'][$qindex]['average']);
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = $rank;
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
					
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						$edu_registration_quarter_id = $this->EduRegistrationQuarter->id;
						// Course
						foreach($s['semesters'][$qindex]['C'] as $k => $v){
							// 4. save EduRegistrationQuarterResult
							if($k == '') {
								continue;
							}
							$r_ev = array('EduRegistrationQuarterResult' => array());
							$r_ev['EduRegistrationQuarterResult']['edu_registration_quarter_id'] = $edu_registration_quarter_id;
							$r_ev['EduRegistrationQuarterResult']['edu_course_id'] = $k;
							$r_ev['EduRegistrationQuarterResult']['course_result'] = is_numeric($v)? $v: 0;
							$r_ev['EduRegistrationQuarterResult']['scale_result'] = is_numeric($v)? '-': $v;
							$r_ev['EduRegistrationQuarterResult']['course_rank'] = 0;
							$r_ev['EduRegistrationQuarterResult']['result_indicator'] = 'P';
							
							$this->EduRegistrationQuarterResult->create();
							$this->EduRegistrationQuarterResult->save($r_ev);
						}
						
						// Evaluation
						foreach($s['semesters'][$qindex]['E'] as $k => $v){
							// 4. save EduRegistrationEvaluation
							$r_ev = array('EduRegistrationEvaluation' => array());
							$r_ev['EduRegistrationEvaluation']['edu_registration_id'] = $edu_registration_id;
							$r_ev['EduRegistrationEvaluation']['edu_evaluation_id'] = $k;
							$r_ev['EduRegistrationEvaluation']['edu_quarter_id'] = $q;
							$r_ev['EduRegistrationEvaluation']['edu_guideline_id'] = $v;
							
							$this->EduRegistrationEvaluation->create();
							$this->EduRegistrationEvaluation->save($r_ev);
						}
					}
					/*foreach($s['semesters'][3]['C'] as $k => $v){
						if($k == '') {
							continue;
						}
						$r_r = array('EduRegistrationResult' => array());
						$r_r['EduRegistrationResult']['edu_registration_id'] = $edu_registration_id;
						$r_r['EduRegistrationResult']['edu_course_id'] = $k;
						$r_r['EduRegistrationResult']['average'] = is_numeric($v)? $v: 0;
						$r_r['EduRegistrationResult']['scale_result'] = is_numeric($v)? '-': $v;
						$r_r['EduRegistrationResult']['status'] = 'P';
						
						$this->EduRegistrationResult->create();
						$this->EduRegistrationResult->save($r_r);
					}*/
				}
			}
		}
	}
	
	public function migrate_g4($worksheet){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => 3)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		// input into the db
		$edu_section_id = 36; // (35 => A)
		$edu_class_id = 7; // G4
		$birth_date = '2004-06-01';
		$registration_date = '2014-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {

			if($row->getRowIndex() == 1) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$this->loadModel('Edu.EduEvaluation');
				$this->loadModel('Edu.EduCourse');
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						echo 'CV: ' . $cv . ' (' . $co . ')<br/>';
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)
								));
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							} else {
								echo 'Not Evaluation<br/>';
							}
							
							$course = $this->EduCourse->find('first', array('conditions' => array(
									'EduSubject.name' => $cv, 'EduCourse.edu_class_id' => $edu_class_id)
								));
							if($course) {
								$key = str_replace('1', '', $co);
								$course_ids[$key] = $course['EduCourse']['id'];
							} else {
								echo 'Not Course<br/>';
							}
						}
					}
				}
				pr($evaluation_ids);
				pr($course_ids);
			} else {
				$index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// clear it
						$cv = ereg_replace("\r",'',$cv);
						$cv = ereg_replace("\n\n",'',$cv);
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						if(($index - 2) % 3 == 0) { // this is a new student record start
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								
								$student['registration_date'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['semesters'] = array();
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['semesters'][(($index - 2) % 3) + 1])) {
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							if(isset($evaluation_ids[$key])){
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['E']))
									$student['semesters'][(($index - 2) % 3) + 1]['E'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['E'][$evaluation_ids[$key]] = $guidelines[$cv];
							} elseif(isset($course_ids[$key])) {
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['C']))
									$student['semesters'][(($index - 2) % 3) + 1]['C'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['C'][$course_ids[$key]] = $cv;
							}
							
							if($co == 'O'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['total'] = $cv;
							} elseif($co == 'P'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['average'] = $cv;
							} elseif($co == 'Q'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['rank'] = $cv;
							} elseif($co == 'S'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['remark'] = $cv;
							}
						}
					}
				}
				if((($index - 2) % 3) == 2) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		//$this->Session->write('results', $results);
		//$this->redirect(array('controller' => 'migrator', 'action' => 'result_display'));
		pr($students);
		
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduRegistrationQuarter');
		$this->loadModel('Edu.EduRegistrationQuarterResult');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		$this->loadModel('Edu.EduRegistrationResult');
		
		
		foreach($students as $s){
			$student = null;
			$student = $this->EduStudent->find('first', array('conditions' => array('EduStudent.identity_number' => $s['identity_number'])));
			if($student) {
				// regular student
				 
			} else {
				$student = array('EduStudent' => array());
				$student['EduStudent']['name'] = $s['name'];
				$student['EduStudent']['identity_number'] = $s['identity_number'];
				$student['EduStudent']['birth_date'] = $birth_date;
				$student['EduStudent']['registration_date'] = $registration_date;
				$student['EduStudent']['gender'] = $s['sex'];
				$student['EduStudent']['nationality'] = $nationality;
				$student['EduStudent']['edu_parent_id'] = $edu_parent_id;
				$student['EduStudent']['photo_file_name'] = $photo_file_name;
				$student['EduStudent']['maker_id'] = $maker_id;
				$student['EduStudent']['status'] = $status;
				$student['EduStudent']['id'] = null;
				$this->EduStudent->create();
				if($this->EduStudent->save($student)){
					$student['EduStudent']['id'] = $this->EduStudent->id;
				}
			}
			
			if($student['EduStudent']['id']){
				// 2. save EduRegistration
				$edu_student_id = $student['EduStudent']['id'];
				$registration = array('EduRegistration' => array());
				$registration['EduRegistration']['name'] = $s['name'];
				$registration['EduRegistration']['edu_student_id'] = $edu_student_id;
				$registration['EduRegistration']['edu_class_id'] = $edu_class_id;
				$registration['EduRegistration']['edu_section_id'] = $edu_section_id;
				$registration['EduRegistration']['edu_campus_id'] = $edu_campus_id;
				$registration['EduRegistration']['grand_total_average'] = ($s['semesters'][3]['average'] == ''? 0: $s['semesters'][3]['average']);
				$registration['EduRegistration']['rank'] = ($s['semesters'][3]['rank'] == ''? 0: $s['semesters'][3]['rank']);
				$registration['EduRegistration']['class_rank'] = 0;
				$registration['EduRegistration']['status'] = 'P';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = $s['semesters'][3]['remark'];
		
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 15, 16, 17, 18);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$qindex = ($ki > 2)? 2: 1;
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = ($s['semesters'][$qindex]['total'] == ''? 0: $s['semesters'][$qindex]['total']);
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = ($s['semesters'][$qindex]['average'] == ''? 0: $s['semesters'][$qindex]['average']);
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = ($s['semesters'][$qindex]['rank'] == ''? 0: $s['semesters'][$qindex]['rank']);
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
					
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						$edu_registration_quarter_id = $this->EduRegistrationQuarter->id;
						// Course
						foreach($s['semesters'][$qindex]['C'] as $k => $v){
							// 4. save EduRegistrationQuarterResult
							if($k == '') {
								continue;
							}
							$r_ev = array('EduRegistrationQuarterResult' => array());
							$r_ev['EduRegistrationQuarterResult']['edu_registration_quarter_id'] = $edu_registration_quarter_id;
							$r_ev['EduRegistrationQuarterResult']['edu_course_id'] = $k;
							$r_ev['EduRegistrationQuarterResult']['course_result'] = is_numeric($v)? $v: 0;
							$r_ev['EduRegistrationQuarterResult']['scale_result'] = is_numeric($v)? '-': $v;
							$r_ev['EduRegistrationQuarterResult']['course_rank'] = 0;
							$r_ev['EduRegistrationQuarterResult']['result_indicator'] = 'P';
							
							$this->EduRegistrationQuarterResult->create();
							$this->EduRegistrationQuarterResult->save($r_ev);
						}
						
						// Evaluation
						foreach($s['semesters'][$qindex]['E'] as $k => $v){
							// 4. save EduRegistrationEvaluation
							$r_ev = array('EduRegistrationEvaluation' => array());
							$r_ev['EduRegistrationEvaluation']['edu_registration_id'] = $edu_registration_id;
							$r_ev['EduRegistrationEvaluation']['edu_evaluation_id'] = $k;
							$r_ev['EduRegistrationEvaluation']['edu_quarter_id'] = $q;
							$r_ev['EduRegistrationEvaluation']['edu_guideline_id'] = $v;
							
							$this->EduRegistrationEvaluation->create();
							$this->EduRegistrationEvaluation->save($r_ev);
						}
					}
					foreach($s['semesters'][3]['C'] as $k => $v){
						if($k == '') {
							continue;
						}
						$r_r = array('EduRegistrationResult' => array());
						$r_r['EduRegistrationResult']['edu_registration_id'] = $edu_registration_id;
						$r_r['EduRegistrationResult']['edu_course_id'] = $k;
						$r_r['EduRegistrationResult']['average'] = is_numeric($v)? $v: 0;
						$r_r['EduRegistrationResult']['scale_result'] = is_numeric($v)? '-': $v;
						$r_r['EduRegistrationResult']['status'] = 'P';
						
						$this->EduRegistrationResult->create();
						$this->EduRegistrationResult->save($r_r);
					}
				}
			}
		}
	}
	
	public function migrate_g5($worksheet){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => 3)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		// input into the db
		$edu_section_id = 37; // (24 => A)
		$edu_class_id = 8; // G5
		$birth_date = '2003-06-01';
		$registration_date = '2014-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {

			if($row->getRowIndex() == 1) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$this->loadModel('Edu.EduEvaluation');
				$this->loadModel('Edu.EduCourse');
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						echo 'CV: ' . $cv . ' (' . $co . ')<br/>';
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)
								));
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							} else {
								echo 'Not Evaluation<br/>';
							}
							
							$course = $this->EduCourse->find('first', array('conditions' => array(
									'EduSubject.name' => $cv, 'EduCourse.edu_class_id' => $edu_class_id)
								));
							if($course) {
								$key = str_replace('1', '', $co);
								$course_ids[$key] = $course['EduCourse']['id'];
							} else {
								echo 'Not Course<br/>';
							}
						}
					}
				}
				pr($evaluation_ids);
				pr($course_ids);
			} else {
				$index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// clear it
						$cv = ereg_replace("\r",'',$cv);
						$cv = ereg_replace("\n\n",'',$cv);
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						if(($index - 2) % 3 == 0) { // this is a new student record start
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								
								$student['registration_date'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['semesters'] = array();
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['semesters'][(($index - 2) % 3) + 1])) {
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							if(isset($evaluation_ids[$key])){
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['E']))
									$student['semesters'][(($index - 2) % 3) + 1]['E'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['E'][$evaluation_ids[$key]] = $guidelines[$cv];
							} elseif(isset($course_ids[$key])) {
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['C']))
									$student['semesters'][(($index - 2) % 3) + 1]['C'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['C'][$course_ids[$key]] = $cv;
							}
							
							if($co == 'P'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['total'] = $cv;
							} elseif($co == 'Q'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['average'] = $cv;
							} elseif($co == 'R'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['rank'] = $cv;
							} elseif($co == 'T'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['remark'] = $cv;
							}
						}
					}
				}
				if((($index - 2) % 3) == 2) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		//$this->Session->write('results', $results);
		//$this->redirect(array('controller' => 'migrator', 'action' => 'result_display'));
		pr($students);
		
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduRegistrationQuarter');
		$this->loadModel('Edu.EduRegistrationQuarterResult');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		$this->loadModel('Edu.EduRegistrationResult');
		
		
		foreach($students as $s){
			$student = null;
			$student = $this->EduStudent->find('first', array('conditions' => array('EduStudent.identity_number' => $s['identity_number'])));
			if($student) {
				// regular student
				 
			} else {
				$student = array('EduStudent' => array());
				$student['EduStudent']['name'] = $s['name'];
				$student['EduStudent']['identity_number'] = $s['identity_number'];
				$student['EduStudent']['birth_date'] = $birth_date;
				$student['EduStudent']['registration_date'] = $registration_date;
				$student['EduStudent']['gender'] = $s['sex'];
				$student['EduStudent']['nationality'] = $nationality;
				$student['EduStudent']['edu_parent_id'] = $edu_parent_id;
				$student['EduStudent']['photo_file_name'] = $photo_file_name;
				$student['EduStudent']['maker_id'] = $maker_id;
				$student['EduStudent']['status'] = $status;
				$student['EduStudent']['id'] = null;
				$this->EduStudent->create();
				if($this->EduStudent->save($student)){
					$student['EduStudent']['id'] = $this->EduStudent->id;
				}
			}
			
			if($student['EduStudent']['id']){
				// 2. save EduRegistration
				$edu_student_id = $student['EduStudent']['id'];
				$registration = array('EduRegistration' => array());
				$registration['EduRegistration']['name'] = $s['name'];
				$registration['EduRegistration']['edu_student_id'] = $edu_student_id;
				$registration['EduRegistration']['edu_class_id'] = $edu_class_id;
				$registration['EduRegistration']['edu_section_id'] = $edu_section_id;
				$registration['EduRegistration']['edu_campus_id'] = $edu_campus_id;
				$registration['EduRegistration']['grand_total_average'] = ($s['semesters'][3]['average'] == ''? 0: $s['semesters'][3]['average']);
				$registration['EduRegistration']['rank'] = ($s['semesters'][3]['rank'] == ''? 0: $s['semesters'][3]['rank']);
				$registration['EduRegistration']['class_rank'] = 0;
				$registration['EduRegistration']['status'] = 'P';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = $s['semesters'][3]['remark'];
		
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 15, 16, 17, 18);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$qindex = ($ki > 2)? 2: 1;
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = ($s['semesters'][$qindex]['total'] == ''? 0: $s['semesters'][$qindex]['total']);
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = ($s['semesters'][$qindex]['average'] == ''? 0: $s['semesters'][$qindex]['average']);
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = ($s['semesters'][$qindex]['rank'] == ''? 0: $s['semesters'][$qindex]['rank']);
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
					
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						$edu_registration_quarter_id = $this->EduRegistrationQuarter->id;
						// Course
						foreach($s['semesters'][$qindex]['C'] as $k => $v){
							// 4. save EduRegistrationQuarterResult
							if($k == '') {
								continue;
							}
							$r_ev = array('EduRegistrationQuarterResult' => array());
							$r_ev['EduRegistrationQuarterResult']['edu_registration_quarter_id'] = $edu_registration_quarter_id;
							$r_ev['EduRegistrationQuarterResult']['edu_course_id'] = $k;
							$r_ev['EduRegistrationQuarterResult']['course_result'] = is_numeric($v)? $v: 0;
							$r_ev['EduRegistrationQuarterResult']['scale_result'] = is_numeric($v)? '-': $v;
							$r_ev['EduRegistrationQuarterResult']['course_rank'] = 0;
							$r_ev['EduRegistrationQuarterResult']['result_indicator'] = 'P';
							
							$this->EduRegistrationQuarterResult->create();
							$this->EduRegistrationQuarterResult->save($r_ev);
						}
						
						// Evaluation
						foreach($s['semesters'][$qindex]['E'] as $k => $v){
							// 4. save EduRegistrationEvaluation
							$r_ev = array('EduRegistrationEvaluation' => array());
							$r_ev['EduRegistrationEvaluation']['edu_registration_id'] = $edu_registration_id;
							$r_ev['EduRegistrationEvaluation']['edu_evaluation_id'] = $k;
							$r_ev['EduRegistrationEvaluation']['edu_quarter_id'] = $q;
							$r_ev['EduRegistrationEvaluation']['edu_guideline_id'] = $v;
							
							$this->EduRegistrationEvaluation->create();
							$this->EduRegistrationEvaluation->save($r_ev);
						}
					}
					foreach($s['semesters'][3]['C'] as $k => $v){
						if($k == '') {
							continue;
						}
						$r_r = array('EduRegistrationResult' => array());
						$r_r['EduRegistrationResult']['edu_registration_id'] = $edu_registration_id;
						$r_r['EduRegistrationResult']['edu_course_id'] = $k;
						$r_r['EduRegistrationResult']['average'] = is_numeric($v)? $v: 0;
						$r_r['EduRegistrationResult']['scale_result'] = is_numeric($v)? '-': $v;
						$r_r['EduRegistrationResult']['status'] = 'P';
						
						$this->EduRegistrationResult->create();
						$this->EduRegistrationResult->save($r_r);
					}
				}
			}
		}
	}
	
	public function migrate_g6($worksheet){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => 3)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		// input into the db
		$edu_section_id = 38; // (11 => A)
		$edu_class_id = 9; // G6
		$birth_date = '2002-06-01';
		$registration_date = '2014-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {

			if($row->getRowIndex() == 1) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$this->loadModel('Edu.EduEvaluation');
				$this->loadModel('Edu.EduCourse');
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						echo 'CV: ' . $cv . ' (' . $co . ')<br/>';
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)
								));
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							} else {
								echo 'Not Evaluation<br/>';
							}
							
							$course = $this->EduCourse->find('first', array('conditions' => array(
									'EduSubject.name' => $cv, 'EduCourse.edu_class_id' => $edu_class_id)
								));
							if($course) {
								$key = str_replace('1', '', $co);
								$course_ids[$key] = $course['EduCourse']['id'];
							} else {
								echo 'Not Course<br/>';
							}
						}
					}
				}
				pr($evaluation_ids);
				pr($course_ids);
			} else {
				$index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// clear it
						$cv = ereg_replace("\r",'',$cv);
						$cv = ereg_replace("\n\n",'',$cv);
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						if(($index - 2) % 3 == 0) { // this is a new student record start
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								
								$student['registration_date'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['semesters'] = array();
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['semesters'][(($index - 2) % 3) + 1])) {
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							if(isset($evaluation_ids[$key])){
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['E']))
									$student['semesters'][(($index - 2) % 3) + 1]['E'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['E'][$evaluation_ids[$key]] = $guidelines[$cv];
							} elseif(isset($course_ids[$key])) {
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['C']))
									$student['semesters'][(($index - 2) % 3) + 1]['C'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['C'][$course_ids[$key]] = $cv;
							}
							
							if($co == 'P'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['total'] = $cv;
							} elseif($co == 'Q'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['average'] = $cv;
							} elseif($co == 'R'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['rank'] = $cv;
							} elseif($co == 'T'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['remark'] = $cv;
							}
						}
					}
				}
				if((($index - 2) % 3) == 2) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		//$this->Session->write('results', $results);
		//$this->redirect(array('controller' => 'migrator', 'action' => 'result_display'));
		pr($students);
		
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduRegistrationQuarter');
		$this->loadModel('Edu.EduRegistrationQuarterResult');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		$this->loadModel('Edu.EduRegistrationResult');
		
		
		foreach($students as $s){
			$student = null;
			$student = $this->EduStudent->find('first', array('conditions' => array('EduStudent.identity_number' => $s['identity_number'])));
			if($student) {
				// regular student
				 
			} else {
				$student = array('EduStudent' => array());
				$student['EduStudent']['name'] = $s['name'];
				$student['EduStudent']['identity_number'] = $s['identity_number'];
				$student['EduStudent']['birth_date'] = $birth_date;
				$student['EduStudent']['registration_date'] = $registration_date;
				$student['EduStudent']['gender'] = $s['sex'];
				$student['EduStudent']['nationality'] = $nationality;
				$student['EduStudent']['edu_parent_id'] = $edu_parent_id;
				$student['EduStudent']['photo_file_name'] = $photo_file_name;
				$student['EduStudent']['maker_id'] = $maker_id;
				$student['EduStudent']['status'] = $status;
				$student['EduStudent']['id'] = null;
				$this->EduStudent->create();
				if($this->EduStudent->save($student)){
					$student['EduStudent']['id'] = $this->EduStudent->id;
				}
			}
			
			if($student['EduStudent']['id']){
				// 2. save EduRegistration
				$edu_student_id = $student['EduStudent']['id'];
				$registration = array('EduRegistration' => array());
				$registration['EduRegistration']['name'] = $s['name'];
				$registration['EduRegistration']['edu_student_id'] = $edu_student_id;
				$registration['EduRegistration']['edu_class_id'] = $edu_class_id;
				$registration['EduRegistration']['edu_section_id'] = $edu_section_id;
				$registration['EduRegistration']['edu_campus_id'] = $edu_campus_id;
				$registration['EduRegistration']['grand_total_average'] = ($s['semesters'][3]['average'] == ''? 0: $s['semesters'][3]['average']);
				$registration['EduRegistration']['rank'] = ($s['semesters'][3]['rank'] == ''? 0: $s['semesters'][3]['rank']);
				$registration['EduRegistration']['class_rank'] = 0;
				$registration['EduRegistration']['status'] = 'P';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = $s['semesters'][3]['remark'];
		
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 15, 16, 17, 18);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$qindex = ($ki > 2)? 2: 1;
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = ($s['semesters'][$qindex]['total'] == ''? 0: $s['semesters'][$qindex]['total']);
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = ($s['semesters'][$qindex]['average'] == ''? 0: $s['semesters'][$qindex]['average']);
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = ($s['semesters'][$qindex]['rank'] == ''? 0: $s['semesters'][$qindex]['rank']);
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
					
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						$edu_registration_quarter_id = $this->EduRegistrationQuarter->id;
						// Course
						foreach($s['semesters'][$qindex]['C'] as $k => $v){
							// 4. save EduRegistrationQuarterResult
							if($k == '') {
								continue;
							}
							$r_ev = array('EduRegistrationQuarterResult' => array());
							$r_ev['EduRegistrationQuarterResult']['edu_registration_quarter_id'] = $edu_registration_quarter_id;
							$r_ev['EduRegistrationQuarterResult']['edu_course_id'] = $k;
							$r_ev['EduRegistrationQuarterResult']['course_result'] = is_numeric($v)? $v: 0;
							$r_ev['EduRegistrationQuarterResult']['scale_result'] = is_numeric($v)? '-': $v;
							$r_ev['EduRegistrationQuarterResult']['course_rank'] = 0;
							$r_ev['EduRegistrationQuarterResult']['result_indicator'] = 'P';
							
							$this->EduRegistrationQuarterResult->create();
							$this->EduRegistrationQuarterResult->save($r_ev);
						}
						
						// Evaluation
						foreach($s['semesters'][$qindex]['E'] as $k => $v){
							// 4. save EduRegistrationEvaluation
							$r_ev = array('EduRegistrationEvaluation' => array());
							$r_ev['EduRegistrationEvaluation']['edu_registration_id'] = $edu_registration_id;
							$r_ev['EduRegistrationEvaluation']['edu_evaluation_id'] = $k;
							$r_ev['EduRegistrationEvaluation']['edu_quarter_id'] = $q;
							$r_ev['EduRegistrationEvaluation']['edu_guideline_id'] = $v;
							
							$this->EduRegistrationEvaluation->create();
							$this->EduRegistrationEvaluation->save($r_ev);
						}
					}
					foreach($s['semesters'][3]['C'] as $k => $v){
						if($k == '') {
							continue;
						}
						$r_r = array('EduRegistrationResult' => array());
						$r_r['EduRegistrationResult']['edu_registration_id'] = $edu_registration_id;
						$r_r['EduRegistrationResult']['edu_course_id'] = $k;
						$r_r['EduRegistrationResult']['average'] = is_numeric($v)? $v: 0;
						$r_r['EduRegistrationResult']['scale_result'] = is_numeric($v)? '-': $v;
						$r_r['EduRegistrationResult']['status'] = 'P';
						
						$this->EduRegistrationResult->create();
						$this->EduRegistrationResult->save($r_r);
					}
				}
			}
		}
	}
	
	public function migrate_g7($worksheet){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => 3)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		// input into the db
		$edu_section_id = 39; // (12 => A)
		$edu_class_id = 10; // G7
		$birth_date = '2001-06-01';
		$registration_date = '2014-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {

			if($row->getRowIndex() == 1) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$this->loadModel('Edu.EduEvaluation');
				$this->loadModel('Edu.EduCourse');
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						echo 'CV: ' . $cv . ' (' . $co . ')<br/>';
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)
								));
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							} else {
								echo 'Not Evaluation<br/>';
							}
							
							$course = $this->EduCourse->find('first', array('conditions' => array(
									'EduSubject.name' => $cv, 'EduCourse.edu_class_id' => $edu_class_id)
								));
							if($course) {
								$key = str_replace('1', '', $co);
								$course_ids[$key] = $course['EduCourse']['id'];
							} else {
								echo 'Not Course<br/>';
							}
						}
					}
				}
				pr($evaluation_ids);
				pr($course_ids);
			} else {
				$index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// clear it
						$cv = ereg_replace("\r",'',$cv);
						$cv = ereg_replace("\n\n",'',$cv);
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						if(($index - 2) % 3 == 0) { // this is a new student record start
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								
								$student['registration_date'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['semesters'] = array();
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['semesters'][(($index - 2) % 3) + 1])) {
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							if(isset($evaluation_ids[$key])){
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['E']))
									$student['semesters'][(($index - 2) % 3) + 1]['E'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['E'][$evaluation_ids[$key]] = $guidelines[$cv];
							} elseif(isset($course_ids[$key])) {
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['C']))
									$student['semesters'][(($index - 2) % 3) + 1]['C'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['C'][$course_ids[$key]] = $cv;
							}
							
							if($co == 'R'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['total'] = $cv;
							} elseif($co == 'S'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['average'] = $cv;
							} elseif($co == 'T'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['rank'] = $cv;
							} elseif($co == 'V'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['remark'] = $cv;
							}
						}
					}
				}
				if((($index - 2) % 3) == 2) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		//$this->Session->write('results', $results);
		//$this->redirect(array('controller' => 'migrator', 'action' => 'result_display'));
		pr($students);
		
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduRegistrationQuarter');
		$this->loadModel('Edu.EduRegistrationQuarterResult');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		$this->loadModel('Edu.EduRegistrationResult');
		
		
		foreach($students as $s){
			$student = null;
			$student = $this->EduStudent->find('first', array('conditions' => array('EduStudent.identity_number' => $s['identity_number'])));
			if($student) {
				// regular student
				 
			} else {
				$student = array('EduStudent' => array());
				$student['EduStudent']['name'] = $s['name'];
				$student['EduStudent']['identity_number'] = $s['identity_number'];
				$student['EduStudent']['birth_date'] = $birth_date;
				$student['EduStudent']['registration_date'] = $registration_date;
				$student['EduStudent']['gender'] = $s['sex'];
				$student['EduStudent']['nationality'] = $nationality;
				$student['EduStudent']['edu_parent_id'] = $edu_parent_id;
				$student['EduStudent']['photo_file_name'] = $photo_file_name;
				$student['EduStudent']['maker_id'] = $maker_id;
				$student['EduStudent']['status'] = $status;
				$student['EduStudent']['id'] = null;
				$this->EduStudent->create();
				if($this->EduStudent->save($student)){
					$student['EduStudent']['id'] = $this->EduStudent->id;
				}
			}
			
			if($student['EduStudent']['id']){
				// 2. save EduRegistration
				$edu_student_id = $student['EduStudent']['id'];
				$registration = array('EduRegistration' => array());
				$registration['EduRegistration']['name'] = $s['name'];
				$registration['EduRegistration']['edu_student_id'] = $edu_student_id;
				$registration['EduRegistration']['edu_class_id'] = $edu_class_id;
				$registration['EduRegistration']['edu_section_id'] = $edu_section_id;
				$registration['EduRegistration']['edu_campus_id'] = $edu_campus_id;
				$registration['EduRegistration']['grand_total_average'] = ($s['semesters'][3]['average'] == ''? 0: $s['semesters'][3]['average']);
				$registration['EduRegistration']['rank'] = ($s['semesters'][3]['rank'] == ''? 0: $s['semesters'][3]['rank']);
				$registration['EduRegistration']['class_rank'] = 0;
				$registration['EduRegistration']['status'] = 'P';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = $s['semesters'][3]['remark'];
		
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 15, 16, 17, 18);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$qindex = ($ki > 2)? 2: 1;
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = ($s['semesters'][$qindex]['total'] == ''? 0: $s['semesters'][$qindex]['total']);
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = ($s['semesters'][$qindex]['average'] == ''? 0: $s['semesters'][$qindex]['average']);
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = ($s['semesters'][$qindex]['rank'] == ''? 0: $s['semesters'][$qindex]['rank']);
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
					
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						$edu_registration_quarter_id = $this->EduRegistrationQuarter->id;
						// Course
						foreach($s['semesters'][$qindex]['C'] as $k => $v){
							// 4. save EduRegistrationQuarterResult
							if($k == '') {
								continue;
							}
							$r_ev = array('EduRegistrationQuarterResult' => array());
							$r_ev['EduRegistrationQuarterResult']['edu_registration_quarter_id'] = $edu_registration_quarter_id;
							$r_ev['EduRegistrationQuarterResult']['edu_course_id'] = $k;
							$r_ev['EduRegistrationQuarterResult']['course_result'] = is_numeric($v)? $v: 0;
							$r_ev['EduRegistrationQuarterResult']['scale_result'] = is_numeric($v)? '-': $v;
							$r_ev['EduRegistrationQuarterResult']['course_rank'] = 0;
							$r_ev['EduRegistrationQuarterResult']['result_indicator'] = 'P';
							
							$this->EduRegistrationQuarterResult->create();
							$this->EduRegistrationQuarterResult->save($r_ev);
						}
						
						// Evaluation
						foreach($s['semesters'][$qindex]['E'] as $k => $v){
							// 4. save EduRegistrationEvaluation
							$r_ev = array('EduRegistrationEvaluation' => array());
							$r_ev['EduRegistrationEvaluation']['edu_registration_id'] = $edu_registration_id;
							$r_ev['EduRegistrationEvaluation']['edu_evaluation_id'] = $k;
							$r_ev['EduRegistrationEvaluation']['edu_quarter_id'] = $q;
							$r_ev['EduRegistrationEvaluation']['edu_guideline_id'] = $v;
							
							$this->EduRegistrationEvaluation->create();
							$this->EduRegistrationEvaluation->save($r_ev);
						}
					}
					foreach($s['semesters'][3]['C'] as $k => $v){
						if($k == '') {
							continue;
						}
						$r_r = array('EduRegistrationResult' => array());
						$r_r['EduRegistrationResult']['edu_registration_id'] = $edu_registration_id;
						$r_r['EduRegistrationResult']['edu_course_id'] = $k;
						$r_r['EduRegistrationResult']['average'] = is_numeric($v)? $v: 0;
						$r_r['EduRegistrationResult']['scale_result'] = is_numeric($v)? '-': $v;
						$r_r['EduRegistrationResult']['status'] = 'P';
						
						$this->EduRegistrationResult->create();
						$this->EduRegistrationResult->save($r_r);
					}
				}
			}
		}
	}
	
	public function migrate_g8($worksheet){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => 3)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		// input into the db
		$edu_section_id = 40; // (12 => A)
		$edu_class_id = 11; // G7
		$birth_date = '2000-06-01';
		$registration_date = '2014-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {

			if($row->getRowIndex() == 1) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$this->loadModel('Edu.EduEvaluation');
				$this->loadModel('Edu.EduCourse');
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						echo 'CV: ' . $cv . ' (' . $co . ')<br/>';
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)
								));
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							} else {
								echo 'Not Evaluation<br/>';
							}
							
							$course = $this->EduCourse->find('first', array('conditions' => array(
									'EduSubject.name' => $cv, 'EduCourse.edu_class_id' => $edu_class_id)
								));
							if($course) {
								$key = str_replace('1', '', $co);
								$course_ids[$key] = $course['EduCourse']['id'];
							} else {
								echo 'Not Course<br/>';
							}
						}
					}
				}
				pr($evaluation_ids);
				pr($course_ids);
			} else {
				$index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// clear it
						$cv = ereg_replace("\r",'',$cv);
						$cv = ereg_replace("\n\n",'',$cv);
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						if(($index - 2) % 3 == 0) { // this is a new student record start
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								
								$student['registration_date'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['semesters'] = array();
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['semesters'][(($index - 2) % 3) + 1])) {
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							if(isset($evaluation_ids[$key])){
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['E']))
									$student['semesters'][(($index - 2) % 3) + 1]['E'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['E'][$evaluation_ids[$key]] = $guidelines[$cv];
							} elseif(isset($course_ids[$key])) {
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['C']))
									$student['semesters'][(($index - 2) % 3) + 1]['C'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['C'][$course_ids[$key]] = $cv;
							}
							
							if($co == 'R'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['total'] = $cv;
							} elseif($co == 'S'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['average'] = $cv;
							} elseif($co == 'T'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['rank'] = $cv;
							} elseif($co == 'V'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['remark'] = $cv;
							}
						}
					}
				}
				if((($index - 2) % 3) == 2) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		//$this->Session->write('results', $results);
		//$this->redirect(array('controller' => 'migrator', 'action' => 'result_display'));
		pr($students);
		
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduRegistrationQuarter');
		$this->loadModel('Edu.EduRegistrationQuarterResult');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		$this->loadModel('Edu.EduRegistrationResult');
		
		
		foreach($students as $s){
			$student = null;
			$student = $this->EduStudent->find('first', array('conditions' => array('EduStudent.identity_number' => $s['identity_number'])));
			if($student) {
				// regular student
				 
			} else {
				$student = array('EduStudent' => array());
				$student['EduStudent']['name'] = $s['name'];
				$student['EduStudent']['identity_number'] = $s['identity_number'];
				$student['EduStudent']['birth_date'] = $birth_date;
				$student['EduStudent']['registration_date'] = $registration_date;
				$student['EduStudent']['gender'] = $s['sex'];
				$student['EduStudent']['nationality'] = $nationality;
				$student['EduStudent']['edu_parent_id'] = $edu_parent_id;
				$student['EduStudent']['photo_file_name'] = $photo_file_name;
				$student['EduStudent']['maker_id'] = $maker_id;
				$student['EduStudent']['status'] = $status;
				$student['EduStudent']['id'] = null;
				$this->EduStudent->create();
				if($this->EduStudent->save($student)){
					$student['EduStudent']['id'] = $this->EduStudent->id;
				}
			}
			
			if($student['EduStudent']['id']){
				// 2. save EduRegistration
				$edu_student_id = $student['EduStudent']['id'];
				$registration = array('EduRegistration' => array());
				$registration['EduRegistration']['name'] = $s['name'];
				$registration['EduRegistration']['edu_student_id'] = $edu_student_id;
				$registration['EduRegistration']['edu_class_id'] = $edu_class_id;
				$registration['EduRegistration']['edu_section_id'] = $edu_section_id;
				$registration['EduRegistration']['edu_campus_id'] = $edu_campus_id;
				$registration['EduRegistration']['grand_total_average'] = ($s['semesters'][3]['average'] == ''? 0: $s['semesters'][3]['average']);
				$registration['EduRegistration']['rank'] = ($s['semesters'][3]['rank'] == ''? 0: $s['semesters'][3]['rank']);
				$registration['EduRegistration']['class_rank'] = 0;
				$registration['EduRegistration']['status'] = 'P';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = $s['semesters'][3]['remark'];
		
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 15, 16, 17, 18);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$qindex = ($ki > 2)? 2: 1;
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = ($s['semesters'][$qindex]['total'] == ''? 0: $s['semesters'][$qindex]['total']);
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = ($s['semesters'][$qindex]['average'] == ''? 0: $s['semesters'][$qindex]['average']);
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = ($s['semesters'][$qindex]['rank'] == ''? 0: $s['semesters'][$qindex]['rank']);
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
					
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						$edu_registration_quarter_id = $this->EduRegistrationQuarter->id;
						// Course
						foreach($s['semesters'][$qindex]['C'] as $k => $v){
							// 4. save EduRegistrationQuarterResult
							if($k == '') {
								continue;
							}
							$r_ev = array('EduRegistrationQuarterResult' => array());
							$r_ev['EduRegistrationQuarterResult']['edu_registration_quarter_id'] = $edu_registration_quarter_id;
							$r_ev['EduRegistrationQuarterResult']['edu_course_id'] = $k;
							$r_ev['EduRegistrationQuarterResult']['course_result'] = is_numeric($v)? $v: 0;
							$r_ev['EduRegistrationQuarterResult']['scale_result'] = is_numeric($v)? '-': $v;
							$r_ev['EduRegistrationQuarterResult']['course_rank'] = 0;
							$r_ev['EduRegistrationQuarterResult']['result_indicator'] = 'P';
							
							$this->EduRegistrationQuarterResult->create();
							$this->EduRegistrationQuarterResult->save($r_ev);
						}
						
						// Evaluation
						foreach($s['semesters'][$qindex]['E'] as $k => $v){
							// 4. save EduRegistrationEvaluation
							$r_ev = array('EduRegistrationEvaluation' => array());
							$r_ev['EduRegistrationEvaluation']['edu_registration_id'] = $edu_registration_id;
							$r_ev['EduRegistrationEvaluation']['edu_evaluation_id'] = $k;
							$r_ev['EduRegistrationEvaluation']['edu_quarter_id'] = $q;
							$r_ev['EduRegistrationEvaluation']['edu_guideline_id'] = $v;
							
							$this->EduRegistrationEvaluation->create();
							$this->EduRegistrationEvaluation->save($r_ev);
						}
					}
					foreach($s['semesters'][3]['C'] as $k => $v){
						if($k == '') {
							continue;
						}
						$r_r = array('EduRegistrationResult' => array());
						$r_r['EduRegistrationResult']['edu_registration_id'] = $edu_registration_id;
						$r_r['EduRegistrationResult']['edu_course_id'] = $k;
						$r_r['EduRegistrationResult']['average'] = is_numeric($v)? $v: 0;
						$r_r['EduRegistrationResult']['scale_result'] = is_numeric($v)? '-': $v;
						$r_r['EduRegistrationResult']['status'] = 'P';
						
						$this->EduRegistrationResult->create();
						$this->EduRegistrationResult->save($r_r);
					}
				}
			}
		}
	}
	
	public function migrate_g8_ammend($worksheet){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => 3)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		// input into the db
		$edu_section_id = 13; // (12 => A)
		$edu_class_id = 11; // G7
		$birth_date = '1999-06-01';
		$registration_date = '2014-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {

			if($row->getRowIndex() == 1) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$this->loadModel('Edu.EduEvaluation');
				$this->loadModel('Edu.EduCourse');
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						echo 'CV: ' . $cv . ' (' . $co . ')<br/>';
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)
								));
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							} else {
								echo 'Not Evaluation<br/>';
							}
							
							$course = $this->EduCourse->find('first', array('conditions' => array(
									'EduSubject.name' => $cv, 'EduCourse.edu_class_id' => $edu_class_id)
								));
							if($course) {
								$key = str_replace('1', '', $co);
								$course_ids[$key] = $course['EduCourse']['id'];
							} else {
								echo 'Not Course<br/>';
							}
						}
					}
				}
				pr($evaluation_ids);
				pr($course_ids);
			} else {
				$index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// clear it
						$cv = ereg_replace("\r",'',$cv);
						$cv = ereg_replace("\n\n",'',$cv);
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						if(($index - 2) % 3 == 0) { // this is a new student record start
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								
								$student['registration_date'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['semesters'] = array();
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['semesters'][(($index - 2) % 3) + 1])) {
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							if(isset($evaluation_ids[$key])){
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['E']))
									$student['semesters'][(($index - 2) % 3) + 1]['E'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['E'][$evaluation_ids[$key]] = $guidelines[$cv];
							} elseif(isset($course_ids[$key])) {
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['C']))
									$student['semesters'][(($index - 2) % 3) + 1]['C'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['C'][$course_ids[$key]] = $cv;
							}
							
							if($co == 'R'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['total'] = $cv;
							} elseif($co == 'S'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['average'] = $cv;
							} elseif($co == 'T'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['rank'] = $cv;
							} elseif($co == 'V'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['remark'] = $cv;
							}
						}
					}
				}
				if((($index - 2) % 3) == 2) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		//$this->Session->write('results', $results);
		//$this->redirect(array('controller' => 'migrator', 'action' => 'result_display'));
		pr($students);
		
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduRegistrationQuarter');
		$this->loadModel('Edu.EduRegistrationQuarterResult');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		$this->loadModel('Edu.EduRegistrationResult');
		
		
		foreach($students as $s){
			// 1. save EduStudent
			//$this->EduStudent->create();
			$stu = $this->EduStudent->find('first', array('conditions' => array('EduStudent.identity_number' => $s['identity_number'])));
			
			if(!empty($stu) || $stu){
				// 2. save EduRegistration
				$edu_student_id = $stu['EduStudent']['id'];
				$registration = $stu['EduRegistration'][0];
				
				if($registration){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 15, 16, 17, 18);
					$edu_registration_id = $registration['id'];
					
					foreach($qs as $ki => $q) {
						$qindex = ($ki > 2)? 2: 1;
						if(strpos($s['semesters'][$qindex]['rank'], '/') === FALSE){
							continue;
						}
						$rparts = explode('/', $s['semesters'][$qindex]['rank']);
						$rank = $rparts[0];
						
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = ($s['semesters'][$qindex]['total'] == ''? 0: $s['semesters'][$qindex]['total']);
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = ($s['semesters'][$qindex]['average'] == ''? 0: $s['semesters'][$qindex]['average']);
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = $rank;
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
					
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						$edu_registration_quarter_id = $this->EduRegistrationQuarter->id;
						// Course
						foreach($s['semesters'][$qindex]['C'] as $k => $v){
							// 4. save EduRegistrationQuarterResult
							if($k == '') {
								continue;
							}
							$r_ev = array('EduRegistrationQuarterResult' => array());
							$r_ev['EduRegistrationQuarterResult']['edu_registration_quarter_id'] = $edu_registration_quarter_id;
							$r_ev['EduRegistrationQuarterResult']['edu_course_id'] = $k;
							$r_ev['EduRegistrationQuarterResult']['course_result'] = is_numeric($v)? $v: 0;
							$r_ev['EduRegistrationQuarterResult']['scale_result'] = is_numeric($v)? '-': $v;
							$r_ev['EduRegistrationQuarterResult']['course_rank'] = 0;
							$r_ev['EduRegistrationQuarterResult']['result_indicator'] = 'P';
							
							$this->EduRegistrationQuarterResult->create();
							$this->EduRegistrationQuarterResult->save($r_ev);
						}
						
						// Evaluation
						foreach($s['semesters'][$qindex]['E'] as $k => $v){
							// 4. save EduRegistrationEvaluation
							$r_ev = array('EduRegistrationEvaluation' => array());
							$r_ev['EduRegistrationEvaluation']['edu_registration_id'] = $edu_registration_id;
							$r_ev['EduRegistrationEvaluation']['edu_evaluation_id'] = $k;
							$r_ev['EduRegistrationEvaluation']['edu_quarter_id'] = $q;
							$r_ev['EduRegistrationEvaluation']['edu_guideline_id'] = $v;
							
							$this->EduRegistrationEvaluation->create();
							$this->EduRegistrationEvaluation->save($r_ev);
						}
					}
					/*
					foreach($s['semesters'][3]['C'] as $k => $v){
						if($k == '') {
							continue;
						}
						$r_r = array('EduRegistrationResult' => array());
						$r_r['EduRegistrationResult']['edu_registration_id'] = $edu_registration_id;
						$r_r['EduRegistrationResult']['edu_course_id'] = $k;
						$r_r['EduRegistrationResult']['average'] = is_numeric($v)? $v: 0;
						$r_r['EduRegistrationResult']['scale_result'] = is_numeric($v)? '-': $v;
						$r_r['EduRegistrationResult']['status'] = 'P';
						
						$this->EduRegistrationResult->create();
						$this->EduRegistrationResult->save($r_r);
					}*/
				}
			}
		}
	}
	
	public function migrate_g9($worksheet){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => 3)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		// input into the db
		$edu_section_id = 41; // (12 => A)
		$edu_class_id = 12; // G9
		$birth_date = '1999-06-01';
		$registration_date = '2014-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {

			if($row->getRowIndex() == 1) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$this->loadModel('Edu.EduEvaluation');
				$this->loadModel('Edu.EduCourse');
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						echo 'CV: ' . $cv . ' (' . $co . ')<br/>';
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)
								));
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							} else {
								echo 'Not Evaluation<br/>';
							}
							
							$course = $this->EduCourse->find('first', array('conditions' => array(
									'EduSubject.name' => $cv, 'EduCourse.edu_class_id' => $edu_class_id)
								));
							if($course) {
								$key = str_replace('1', '', $co);
								$course_ids[$key] = $course['EduCourse']['id'];
							} else {
								echo 'Not Course<br/>';
							}
						}
					}
				}
				pr($evaluation_ids);
				pr($course_ids);
			} else {
				$index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// clear it
						$cv = ereg_replace("\r",'',$cv);
						$cv = ereg_replace("\n\n",'',$cv);
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						if(($index - 2) % 3 == 0) { // this is a new student record start
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								
								$student['registration_date'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['semesters'] = array();
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['semesters'][(($index - 2) % 3) + 1])) {
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							if(isset($evaluation_ids[$key])){
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['E']))
									$student['semesters'][(($index - 2) % 3) + 1]['E'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['E'][$evaluation_ids[$key]] = $guidelines[$cv];
							} elseif(isset($course_ids[$key])) {
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['C']))
									$student['semesters'][(($index - 2) % 3) + 1]['C'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['C'][$course_ids[$key]] = $cv;
							}
							
							if($co == 'S'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['total'] = $cv;
							} elseif($co == 'T'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['average'] = $cv;
							} elseif($co == 'U'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['rank'] = $cv;
							} elseif($co == 'W'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['remark'] = $cv;
							}
						}
					}
				}
				if((($index - 2) % 3) == 2) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		//$this->Session->write('results', $results);
		//$this->redirect(array('controller' => 'migrator', 'action' => 'result_display'));
		pr($students);
		
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduRegistrationQuarter');
		$this->loadModel('Edu.EduRegistrationQuarterResult');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		$this->loadModel('Edu.EduRegistrationResult');
		
		
		foreach($students as $s){
			$student = null;
			$student = $this->EduStudent->find('first', array('conditions' => array('EduStudent.identity_number' => $s['identity_number'])));
			if($student) {
				// regular student
				 
			} else {
				$student = array('EduStudent' => array());
				$student['EduStudent']['name'] = $s['name'];
				$student['EduStudent']['identity_number'] = $s['identity_number'];
				$student['EduStudent']['birth_date'] = $birth_date;
				$student['EduStudent']['registration_date'] = $registration_date;
				$student['EduStudent']['gender'] = $s['sex'];
				$student['EduStudent']['nationality'] = $nationality;
				$student['EduStudent']['edu_parent_id'] = $edu_parent_id;
				$student['EduStudent']['photo_file_name'] = $photo_file_name;
				$student['EduStudent']['maker_id'] = $maker_id;
				$student['EduStudent']['status'] = $status;
				$student['EduStudent']['id'] = null;
				$this->EduStudent->create();
				if($this->EduStudent->save($student)){
					$student['EduStudent']['id'] = $this->EduStudent->id;
				}
			}
			
			if($student['EduStudent']['id']){
				// 2. save EduRegistration
				$edu_student_id = $student['EduStudent']['id'];
				$registration = array('EduRegistration' => array());
				$registration['EduRegistration']['name'] = $s['name'];
				$registration['EduRegistration']['edu_student_id'] = $edu_student_id;
				$registration['EduRegistration']['edu_class_id'] = $edu_class_id;
				$registration['EduRegistration']['edu_section_id'] = $edu_section_id;
				$registration['EduRegistration']['edu_campus_id'] = $edu_campus_id;
				$registration['EduRegistration']['grand_total_average'] = ($s['semesters'][3]['average'] == ''? 0: $s['semesters'][3]['average']);
				$registration['EduRegistration']['rank'] = ($s['semesters'][3]['rank'] == ''? 0: $s['semesters'][3]['rank']);
				$registration['EduRegistration']['class_rank'] = 0;
				$registration['EduRegistration']['status'] = 'P';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = $s['semesters'][3]['remark'];
		
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 15, 16, 17, 18);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$qindex = ($ki > 2)? 2: 1;
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = ($s['semesters'][$qindex]['total'] == ''? 0: $s['semesters'][$qindex]['total']);
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = ($s['semesters'][$qindex]['average'] == ''? 0: $s['semesters'][$qindex]['average']);
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = ($s['semesters'][$qindex]['rank'] == ''? 0: $s['semesters'][$qindex]['rank']);
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
					
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						$edu_registration_quarter_id = $this->EduRegistrationQuarter->id;
						// Course
						foreach($s['semesters'][$qindex]['C'] as $k => $v){
							// 4. save EduRegistrationQuarterResult
							if($k == '') {
								continue;
							}
							$r_ev = array('EduRegistrationQuarterResult' => array());
							$r_ev['EduRegistrationQuarterResult']['edu_registration_quarter_id'] = $edu_registration_quarter_id;
							$r_ev['EduRegistrationQuarterResult']['edu_course_id'] = $k;
							$r_ev['EduRegistrationQuarterResult']['course_result'] = is_numeric($v)? $v: 0;
							$r_ev['EduRegistrationQuarterResult']['scale_result'] = is_numeric($v)? '-': $v;
							$r_ev['EduRegistrationQuarterResult']['course_rank'] = 0;
							$r_ev['EduRegistrationQuarterResult']['result_indicator'] = 'P';
							
							$this->EduRegistrationQuarterResult->create();
							$this->EduRegistrationQuarterResult->save($r_ev);
						}
						
						// Evaluation
						foreach($s['semesters'][$qindex]['E'] as $k => $v){
							// 4. save EduRegistrationEvaluation
							$r_ev = array('EduRegistrationEvaluation' => array());
							$r_ev['EduRegistrationEvaluation']['edu_registration_id'] = $edu_registration_id;
							$r_ev['EduRegistrationEvaluation']['edu_evaluation_id'] = $k;
							$r_ev['EduRegistrationEvaluation']['edu_quarter_id'] = $q;
							$r_ev['EduRegistrationEvaluation']['edu_guideline_id'] = $v;
							
							$this->EduRegistrationEvaluation->create();
							$this->EduRegistrationEvaluation->save($r_ev);
						}
					}
					foreach($s['semesters'][3]['C'] as $k => $v){
						if($k == '') {
							continue;
						}
						$r_r = array('EduRegistrationResult' => array());
						$r_r['EduRegistrationResult']['edu_registration_id'] = $edu_registration_id;
						$r_r['EduRegistrationResult']['edu_course_id'] = $k;
						$r_r['EduRegistrationResult']['average'] = is_numeric($v)? $v: 0;
						$r_r['EduRegistrationResult']['scale_result'] = is_numeric($v)? '-': $v;
						$r_r['EduRegistrationResult']['status'] = 'P';
						
						$this->EduRegistrationResult->create();
						$this->EduRegistrationResult->save($r_r);
					}
				}
			}
		}
	}
	
	public function migrate_g10($worksheet){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => 3)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		// input into the db
		$edu_section_id = 29; // (12 => A)
		$edu_class_id = 13; // G10
		$birth_date = '2000-06-01';
		$registration_date = '2013-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {

			if($row->getRowIndex() == 1) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$this->loadModel('Edu.EduEvaluation');
				$this->loadModel('Edu.EduCourse');
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// Strip out carriage returns
						$cv = ereg_replace("\r",'',$cv);
						// Handle paragraphs
						$cv = ereg_replace("\n\n",'',$cv);
						// Handle line breaks
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						echo 'CV: ' . $cv . ' (' . $co . ')<br/>';
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)
								));
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							} else {
								echo 'Not Evaluation<br/>';
							}
							
							$course = $this->EduCourse->find('first', array('conditions' => array(
									'EduSubject.name' => $cv, 'EduCourse.edu_class_id' => $edu_class_id)
								));
							if($course) {
								$key = str_replace('1', '', $co);
								$course_ids[$key] = $course['EduCourse']['id'];
							} else {
								echo 'Not Course<br/>';
							}
						}
					}
				}
				pr($evaluation_ids);
				pr($course_ids);
			} else {
				$index = $row->getRowIndex();
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					if (!is_null($cell)) {
						$cv = $cell->getCalculatedValue();
						// clear it
						$cv = ereg_replace("\r",'',$cv);
						$cv = ereg_replace("\n\n",'',$cv);
						$cv = ereg_replace("\n",'',$cv);
						$cv = ereg_replace("  ", ' ',$cv);
						
						$co = $cell->getCoordinate();
						if(($index - 2) % 3 == 0) { // this is a new student record start
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								
								$student['registration_date'] = $cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['semesters'] = array();
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['semesters'][(($index - 2) % 3) + 1])) {
								$student['semesters'][(($index - 2) % 3) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							if(isset($evaluation_ids[$key])){
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['E']))
									$student['semesters'][(($index - 2) % 3) + 1]['E'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['E'][$evaluation_ids[$key]] = $guidelines[$cv];
							} elseif(isset($course_ids[$key])) {
								if(!isset($student['semesters'][(($index - 2) % 3) + 1]['C']))
									$student['semesters'][(($index - 2) % 3) + 1]['C'] = array();
								$student['semesters'][(($index - 2) % 3) + 1]['C'][$course_ids[$key]] = $cv;
							}
							
							if($co == 'S'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['total'] = $cv;
							} elseif($co == 'T'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['average'] = $cv;
							} elseif($co == 'U'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['rank'] = $cv;
							} elseif($co == 'W'.$i){
								$student['semesters'][(($index - 2) % 3) + 1]['remark'] = $cv;
							}
						}
					}
				}
				if((($index - 2) % 3) == 2) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		//$this->Session->write('results', $results);
		//$this->redirect(array('controller' => 'migrator', 'action' => 'result_display'));
		pr($students);
		
		$this->loadModel('Edu.EduStudent');
		$this->loadModel('Edu.EduRegistration');
		$this->loadModel('Edu.EduRegistrationQuarter');
		$this->loadModel('Edu.EduRegistrationQuarterResult');
		$this->loadModel('Edu.EduRegistrationEvaluation');
		$this->loadModel('Edu.EduRegistrationResult');
		
		
		foreach($students as $s){
			$student = null;
			$student = $this->EduStudent->find('first', array('conditions' => array('EduStudent.identity_number' => $s['identity_number'])));
			if($student) {
				// regular student
				 
			} else {
				$student = array('EduStudent' => array());
				$student['EduStudent']['name'] = $s['name'];
				$student['EduStudent']['identity_number'] = $s['identity_number'];
				$student['EduStudent']['birth_date'] = $birth_date;
				$student['EduStudent']['registration_date'] = $registration_date;
				$student['EduStudent']['gender'] = $s['sex'];
				$student['EduStudent']['nationality'] = $nationality;
				$student['EduStudent']['edu_parent_id'] = $edu_parent_id;
				$student['EduStudent']['photo_file_name'] = $photo_file_name;
				$student['EduStudent']['maker_id'] = $maker_id;
				$student['EduStudent']['status'] = $status;
				$student['EduStudent']['id'] = null;
				$this->EduStudent->create();
				if($this->EduStudent->save($student)){
					$student['EduStudent']['id'] = $this->EduStudent->id;
				}
			}
			
			if($student['EduStudent']['id']){
				// 2. save EduRegistration
				$edu_student_id = $student['EduStudent']['id'];
				$registration = array('EduRegistration' => array());
				$registration['EduRegistration']['name'] = $s['name'];
				$registration['EduRegistration']['edu_student_id'] = $edu_student_id;
				$registration['EduRegistration']['edu_class_id'] = $edu_class_id;
				$registration['EduRegistration']['edu_section_id'] = $edu_section_id;
				$registration['EduRegistration']['edu_campus_id'] = $edu_campus_id;
				$registration['EduRegistration']['grand_total_average'] = ($s['semesters'][3]['average'] == ''? 0: $s['semesters'][3]['average']);
				$registration['EduRegistration']['rank'] = ($s['semesters'][3]['rank'] == ''? 0: $s['semesters'][3]['rank']);
				$registration['EduRegistration']['class_rank'] = 0;
				$registration['EduRegistration']['status'] = 'P';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = $s['semesters'][3]['remark'];
		
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 11, 12, 13, 14);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$qindex = ($ki > 2)? 2: 1;
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = ($s['semesters'][$qindex]['total'] == ''? 0: $s['semesters'][$qindex]['total']);
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = ($s['semesters'][$qindex]['average'] == ''? 0: $s['semesters'][$qindex]['average']);
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = ($s['semesters'][$qindex]['rank'] == ''? 0: $s['semesters'][$qindex]['rank']);
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
					
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						$edu_registration_quarter_id = $this->EduRegistrationQuarter->id;
						// Course
						foreach($s['semesters'][$qindex]['C'] as $k => $v){
							// 4. save EduRegistrationQuarterResult
							if($k == '') {
								continue;
							}
							$r_ev = array('EduRegistrationQuarterResult' => array());
							$r_ev['EduRegistrationQuarterResult']['edu_registration_quarter_id'] = $edu_registration_quarter_id;
							$r_ev['EduRegistrationQuarterResult']['edu_course_id'] = $k;
							$r_ev['EduRegistrationQuarterResult']['course_result'] = is_numeric($v)? $v: 0;
							$r_ev['EduRegistrationQuarterResult']['scale_result'] = is_numeric($v)? '-': $v;
							$r_ev['EduRegistrationQuarterResult']['course_rank'] = 0;
							$r_ev['EduRegistrationQuarterResult']['result_indicator'] = 'P';
							
							$this->EduRegistrationQuarterResult->create();
							$this->EduRegistrationQuarterResult->save($r_ev);
						}
						
						// Evaluation
						foreach($s['semesters'][$qindex]['E'] as $k => $v){
							// 4. save EduRegistrationEvaluation
							$r_ev = array('EduRegistrationEvaluation' => array());
							$r_ev['EduRegistrationEvaluation']['edu_registration_id'] = $edu_registration_id;
							$r_ev['EduRegistrationEvaluation']['edu_evaluation_id'] = $k;
							$r_ev['EduRegistrationEvaluation']['edu_quarter_id'] = $q;
							$r_ev['EduRegistrationEvaluation']['edu_guideline_id'] = $v;
							
							$this->EduRegistrationEvaluation->create();
							$this->EduRegistrationEvaluation->save($r_ev);
						}
					}
					foreach($s['semesters'][3]['C'] as $k => $v){
						if($k == '') {
							continue;
						}
						$r_r = array('EduRegistrationResult' => array());
						$r_r['EduRegistrationResult']['edu_registration_id'] = $edu_registration_id;
						$r_r['EduRegistrationResult']['edu_course_id'] = $k;
						$r_r['EduRegistrationResult']['average'] = is_numeric($v)? $v: 0;
						$r_r['EduRegistrationResult']['scale_result'] = is_numeric($v)? '-': $v;
						$r_r['EduRegistrationResult']['status'] = 'P';
						
						$this->EduRegistrationResult->create();
						$this->EduRegistrationResult->save($r_r);
					}
				}
			}
		}
	}
	
	
	function result_display() {
		$this->set('results', $this->Session->read('results'));
	}
	
	function result_display_pdf() {
		$this->layout = 'ajax';
		$this->set('result', $this->Session->read('result'));
	}

}

?>