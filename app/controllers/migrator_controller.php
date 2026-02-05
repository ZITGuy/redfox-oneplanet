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
	
	public function result_2015() {
		$file_name = IMAGES . 'migration_2015.xlsx';
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel = $objReader->load($file_name);
		$results = '';
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			echo $worksheet->getTitle() . '<br/>';
			$this->log('Sheet Title: ' . $worksheet->getTitle(), 'nd');
			if($worksheet->getTitle() == 'Pre-KG x') {
				$this->migrate_kgs($worksheet, 2, 48, 1, '2012-06-01');
				break;
			}
			elseif($worksheet->getTitle() == 'KG x') {
				$this->migrate_kgs($worksheet, 1, 49, 2, '2011-06-01');
				break;
			}
			elseif($worksheet->getTitle() == 'Prep-A x') {
				$this->migrate_preparatory($worksheet, 3, 50, 3, '2010-06-01');
				break;
			}
			elseif($worksheet->getTitle() == 'Prep-B xx') {
				$this->migrate_preparatory($worksheet, 3, 51, 3, '2010-06-01');
				break;
			}
			elseif($worksheet->getTitle() == 'G1A') {
				$this->migrate_gs($worksheet, 3, 52, 4, '2009-06-01', /*$total_start*/'O');
				break;
			}
			elseif($worksheet->getTitle() == 'G1B x') {
				$this->migrate_gs($worksheet, 3, 53, 4, '2009-06-01', /*$total_start*/'O');
				break;
			}
			elseif($worksheet->getTitle() == 'G2A x') {
				$this->migrate_gs($worksheet, 3, 54, 5, '2008-06-01', /*$total_start*/'O');
				break;
			}
			elseif($worksheet->getTitle() == 'G2B x') {
				$this->migrate_gs($worksheet, 3, 55, 5, '2008-06-01', /*$total_start*/'O');
				break;
			}
			elseif($worksheet->getTitle() == 'G3A x') {
				$this->migrate_gs($worksheet, 3, 56, 6, '2007-06-01', /*$total_start*/'O');
				break;
			}
			elseif($worksheet->getTitle() == 'G4A x') {
				$this->migrate_gs($worksheet, 3, 57, 7, '2006-06-01', /*$total_start*/'O');
				break;
			}
			elseif($worksheet->getTitle() == 'G4B x') {
				$this->migrate_gs($worksheet, 3, 58, 7, '2006-06-01', /*$total_start*/'O');
				break;
			}
			elseif($worksheet->getTitle() == 'G5A x') {
				$this->migrate_gs($worksheet, 3, 59, 8, '2005-06-01', /*$total_start*/'P');
				break;
			}
			elseif($worksheet->getTitle() == 'G5B x') {
				$this->migrate_gs($worksheet, 3, 60, 8, '2005-06-01', /*$total_start*/'P');
				break;
			}
			elseif($worksheet->getTitle() == 'G6A x') {
				$this->migrate_gs($worksheet, 3, 61, 9, '2004-06-01', /*$total_start*/'P');
				break;
			}
			elseif($worksheet->getTitle() == 'G7A x') {
				$this->migrate_gs($worksheet, 3, 62, 10, '2003-06-01', /*$total_start*/'R');
				break;
			}
			elseif($worksheet->getTitle() == 'G7B x') {
				$this->migrate_gs($worksheet, 3, 63, 10, '2003-06-01', /*$total_start*/'R');
				break;
			}
			elseif($worksheet->getTitle() == 'G8A x') {
				$this->migrate_gs($worksheet, 3, 64, 11, '2002-06-01', /*$total_start*/'R');
				break;
			}
			elseif($worksheet->getTitle() == 'G9A x') {
				$this->migrate_gs($worksheet, 3, 65, 12, '2001-06-01', /*$total_start*/'S');
				break;
			}
			elseif($worksheet->getTitle() == 'G10A x') {
				$this->migrate_gs($worksheet, 3, 66, 13, '2000-06-01', /*$total_start*/'S');
				break;
			}
		}
		$this->autoRender = false;
		$this->render('/migrator/index');
    }
	
    public function result() {
		$file_name = IMAGES . 'migration_2016.xlsx';
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel = $objReader->load($file_name);
		$results = '';
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			echo $worksheet->getTitle() . '<br/>';
			$this->log('Sheet Title: ' . $worksheet->getTitle(), 'nd');
			if($worksheet->getTitle() == 'Pre-KG x') {
				$this->migrate_kgs($worksheet, 2, 48, 1, '2012-06-01');
				break;
			}
			elseif($worksheet->getTitle() == 'KG x') {
				$this->migrate_kgs($worksheet, 1, 49, 2, '2011-06-01');
				break;
			}
			elseif($worksheet->getTitle() == 'Prep-A x') {
				$this->migrate_preparatory($worksheet, 3, 50, 3, '2010-06-01');
				break;
			}
			elseif($worksheet->getTitle() == 'Prep-B xx') {
				$this->migrate_preparatory($worksheet, 3, 51, 3, '2010-06-01');
				break;
			}
			elseif($worksheet->getTitle() == 'G1A x') {
				// $worksheet, $g_group, $edu_section_id, $edu_class_id, $birth_date, $total_start = 'O'
				$this->migrate_gs2($worksheet, 3, 67, 4, '2010-06-01', /*$total_start*/'O');
				break;
			}
			elseif($worksheet->getTitle() == 'G1B x') {
				$this->migrate_gs2($worksheet, 3, 68, 4, '2010-06-01', /*$total_start*/'O');
				break;
			}
			elseif($worksheet->getTitle() == 'G2A x') {
				$this->migrate_gs2($worksheet, 3, 69, 5, '2009-06-01', /*$total_start*/'O');
				break;
			}
			elseif($worksheet->getTitle() == 'G3A x') {
				$this->migrate_gs2($worksheet, 3, 70, 6, '2008-06-01', /*$total_start*/'O');
				break;
			}
			elseif($worksheet->getTitle() == 'G3B x') {
				$this->migrate_gs2($worksheet, 3, 71, 6, '2008-06-01', /*$total_start*/'O');
				break;
			}
			elseif($worksheet->getTitle() == 'G4A x') {
				$this->migrate_gs2($worksheet, 3, 72, 7, '2007-06-01', /*$total_start*/'O');
				break;
			}
			elseif($worksheet->getTitle() == 'G5A x') {
				$this->migrate_gs2($worksheet, 3, 73, 8, '2006-06-01', /*$total_start*/'P');
				break;
			}
			elseif($worksheet->getTitle() == 'G5B x') {
				$this->migrate_gs2($worksheet, 3, 74, 8, '2006-06-01', /*$total_start*/'P');
				break;
			}
			elseif($worksheet->getTitle() == 'G6A x') {
				$this->migrate_gs2($worksheet, 3, 75, 9, '2005-06-01', /*$total_start*/'P');
				break;
			}
			elseif($worksheet->getTitle() == 'G6B x') {
				$this->migrate_gs2($worksheet, 3, 76, 9, '2005-06-01', /*$total_start*/'P');
				break;
			}
			elseif($worksheet->getTitle() == 'G7A x') {
				$this->migrate_gs2($worksheet, 3, 77, 10, '2004-06-01', /*$total_start*/'R');
				break;
			}
			elseif($worksheet->getTitle() == 'G8A x') {
				$this->migrate_gs2($worksheet, 3, 78, 11, '2003-06-01', /*$total_start*/'R');
				break;
			}
			elseif($worksheet->getTitle() == 'G8B x') {
				$this->migrate_gs2($worksheet, 3, 79, 11, '2003-06-01', /*$total_start*/'R');
				break;
			}
			elseif($worksheet->getTitle() == 'G9A x') {
				$this->migrate_gs2($worksheet, 3, 80, 12, '2002-06-01', /*$total_start*/'S');
				break;
			}
			elseif($worksheet->getTitle() == 'G10A') {
				$this->migrate_gs2($worksheet, 3, 81, 13, '2001-06-01', /*$total_start*/'S');
				break;
			}
		}
		$this->autoRender = false;
		$this->render('/migrator/index');
    }
	
	public function migrate_kgs($worksheet, $g_group, $edu_section_id, $edu_class_id, $birth_date){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$guidelines = array();
		// $g_group = 2 for PreKG, 1 for KG
		$this->loadModel('Edu.EduGuideline');   
		$gs = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => $g_group)));
		
		$na_id = 0;
		foreach($gs as $g) {
			$guidelines[$g['EduGuideline']['name']] = $g['EduGuideline']['id'];
			if($g['EduGuideline']['name'] == '-'){
				$na_id = $g['EduGuideline']['id'];
			}
		}
		$guidelines[''] = $na_id;
		
		pr($guidelines);
		
		$this->log('guidelines', 'nd');
		$this->log($guidelines, 'nd');
		// input into the db
		//$edu_section_id = 43; // (1 => A)
		//$edu_class_id = 1; // Pre-KG
		//$birth_date = '2010-06-01';
		$registration_date = '2015-06-01';
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
						
						if(!in_array($co, array('A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1'))) {
							$evaluation = $this->EduEvaluation->find('first', array('conditions' => array(
									'EduEvaluationArea.name' => $cv, 'EduEvaluation.edu_class_id' => $edu_class_id)));
							
							if($evaluation) {
								$key = str_replace('1', '', $co);
								$evaluation_ids[$key] = $evaluation['EduEvaluation']['id'];
							}
						}
					}
				}
				pr($evaluation_ids);
				$this->log('Evaluations', 'nd');
				$this->log($evaluation_ids, 'nd');
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
							$this->log('Evaluation ID: ' . $evaluation_ids[$key] . ' of key ' . $key, 'nd');
							$this->log('Guideline: ' . $guidelines[$cv] . ' of cv ' . $cv, 'nd');
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
		$this->log('students', 'nd');
		$this->log($students, 'nd');
		
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
					$qs = array(1 => 19, 20, 21, 22);
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
	
	public function migrate_preparatory($worksheet, $g_group, $edu_section_id, $edu_class_id, $birth_date){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => $g_group)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		
		$this->log('guidelines', 'nd');
		$this->log($guidelines, 'nd');
		// input into the db
		//$edu_section_id = 47; // (18 => A, 19 => B)
		//$edu_class_id = 3; // PREP
		//$birth_date = '2008-06-01';
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
				$this->log('Evaluations', 'nd');
				$this->log($evaluation_ids, 'nd');
				
				$this->log('Courses', 'nd');
				$this->log($course_ids, 'nd');
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
		pr($students);
		$this->log('students', 'nd');
		$this->log($students, 'nd');
		
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
				$registration['EduRegistration']['rank'] = (!isset($s['semesters'][3]['rank']) || $s['semesters'][3]['rank'] == ''? 0: $s['semesters'][3]['rank']);
				$registration['EduRegistration']['class_rank'] = 0;
				$registration['EduRegistration']['status'] = 'P';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = isset($s['semesters'][3]['remark'])? $s['semesters'][3]['remark']: '-';
		
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 19, 20, 21, 22);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$qindex = ($ki > 2)? 2: 1;
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = ($s['semesters'][$qindex]['total'] == ''? 0: $s['semesters'][$qindex]['total']);
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = ($s['semesters'][$qindex]['average'] == ''? 0: $s['semesters'][$qindex]['average']);
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = (!isset($s['semesters'][$qindex]['rank']) || $s['semesters'][$qindex]['rank'] == ''? 0: $s['semesters'][$qindex]['rank']);
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
	
	public function migrate_gs($worksheet, $g_group, $edu_section_id, $edu_class_id, $birth_date, $total_start = 'O'){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => $g_group)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		$this->log($guidelines, 'nd');
		// input into the db
		//$edu_section_id = 31; // (30 => A, 31 => B)
		//$edu_class_id = 4; // G1
		//$birth_date = '2006-06-01';
		$registration_date = '2014-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {
			// header row
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
				$this->log('evaluation_ids', 'nd');
				$this->log($evaluation_ids, 'nd');
				pr($course_ids);
				$this->log('course_ids', 'nd');
				$this->log($course_ids, 'nd');
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
						if(($index - 2) % 5 == 0) { // this is a new student record start 2, 7, 12, 17, 22 ....
							// (2-2)%5==0, (7-2)%5==0
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
								$student['semesters'][1] = array();
							}
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['semesters'][(($index - 2) % 5) + 1])) {
								$student['semesters'][(($index - 2) % 5) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							if(isset($evaluation_ids[$key])){
								if(!isset($student['semesters'][(($index - 2) % 5) + 1]['E']))
									$student['semesters'][(($index - 2) % 5) + 1]['E'] = array();
								$student['semesters'][(($index - 2) % 5) + 1]['E'][$evaluation_ids[$key]] = $guidelines[$cv];
							} elseif(isset($course_ids[$key])) {
								if(!isset($student['semesters'][(($index - 2) % 5) + 1]['C']))
									$student['semesters'][(($index - 2) % 5) + 1]['C'] = array();
								$student['semesters'][(($index - 2) % 5) + 1]['C'][$course_ids[$key]] = $cv;
							}
							
							$ts = ord($total_start);
							
							if($co == chr($ts).$i){
								$student['semesters'][(($index - 2) % 5) + 1]['total'] = $cv;
							} elseif($co == chr($ts+1).$i){
								$student['semesters'][(($index - 2) % 5) + 1]['average'] = $cv;
							} elseif($co == chr($ts+2).$i){
								$student['semesters'][(($index - 2) % 5) + 1]['rank'] = $cv;
							} elseif($co == chr($ts+4).$i){
								$student['semesters'][(($index - 2) % 5) + 1]['remark'] = $cv;
							}
						}
					}
				}
				if((($index - 2) % 5) == 4) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		pr($students);
		$this->log('students', 'nd');
		$this->log($students, 'nd');
		
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
				$registration['EduRegistration']['grand_total_average'] = ($s['semesters'][5]['average'] == ''? 0: $s['semesters'][5]['average']);
				$registration['EduRegistration']['rank'] = ($s['semesters'][5]['rank'] == ''? 0: $s['semesters'][5]['rank']);
				$registration['EduRegistration']['class_rank'] = 0;
				$registration['EduRegistration']['status'] = 'P';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = $s['semesters'][5]['remark'];
		
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 19, 20, 21, 22);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = ($s['semesters'][$ki]['total'] == ''? 0: $s['semesters'][$ki]['total']);
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = ($s['semesters'][$ki]['average'] == ''? 0: $s['semesters'][$ki]['average']);
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = ($s['semesters'][$ki]['rank'] == ''? 0: $s['semesters'][$ki]['rank']);
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
					
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						$edu_registration_quarter_id = $this->EduRegistrationQuarter->id;
						// Course
						foreach($s['semesters'][$ki]['C'] as $k => $v){
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
						foreach($s['semesters'][$ki]['E'] as $k => $v){
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
					foreach($s['semesters'][5]['C'] as $k => $v){
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
	
	public function migrate_gs2($worksheet, $g_group, $edu_section_id, $edu_class_id, $birth_date, $total_start = 'O'){
		$students = array();
		$student = array();
		$evaluation_ids = array();
		$course_ids = array();
		$guidelines = array();
		$this->loadModel('Edu.EduGuideline');
		$gs3 = $this->EduGuideline->find('all', array('conditions' => array('EduGuideline.guideline_group' => $g_group)));
		
		foreach($gs3 as $g3) {
			$guidelines[$g3['EduGuideline']['name']] = $g3['EduGuideline']['id'];
		}
		$guidelines[''] = 'NA';
		
		pr($guidelines);
		$this->log($guidelines, 'nd');
		// input into the db
		//$edu_section_id = 31; // (30 => A, 31 => B)
		//$edu_class_id = 4; // G1
		//$birth_date = '2006-06-01';
		$registration_date = '2016-06-01';
		$nationality = 'ETHIOPIAN';
		$edu_parent_id = 0;
		$edu_campus_id = 1;
		$photo_file_name = 'No file';
		$maker_id = 38;
		$status = 1;
		
		foreach ($worksheet->getRowIterator() as $row) {
			// header row
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
				$this->log('evaluation_ids', 'nd');
				$this->log($evaluation_ids, 'nd');
				pr($course_ids);
				$this->log('course_ids', 'nd');
				$this->log($course_ids, 'nd');
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
						if($index % 2 == 0) { // this is a new student record start 2, 4, 6, 8, 10 ....
							// (2-2)%5==0, (7-2)%5==0
							if($co == 'B' . $index){
								$student['name'] = $cv;
							}
							if($co == 'C' . $index){
								$ps = explode('/', $cv);
								
								$student['age'] = $birth_date;//$cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'D' . $index){
								$student['sex'] = $cv;
							}
							if($co == 'E' . $index){
								$student['identity_number'] = $cv;
							}
							if($co == 'F' . $index){
								$ps = explode('/', $cv);
								$student['registration_date'] = $registration_date;//$cv == ''? '': date('Y-m-d', strtotime($ps[2].'-'.$ps[1].'-'.$ps[0]));
							}
							if($co == 'G' . $index){
								$student['semesters'] = array();
								$student['semesters'][1] = array();
							}
						}
						$i = $index;
						if(!in_array($co, array('A'.$i, 'B'.$i, 'C'.$i, 'D'.$i, 'E'.$i, 'F'.$i, 'G'.$i))){
							//if($co >= 'G' . $index) {
							if(!isset($student['semesters'][($index % 2) + 1])) {
								$student['semesters'][($index % 2) + 1] = array();
							}
							$key = str_replace($index, '', $co);
							if(isset($evaluation_ids[$key])){
								if(!isset($student['semesters'][($index % 2) + 1]['E']))
									$student['semesters'][($index % 2) + 1]['E'] = array();
								$student['semesters'][($index % 2) + 1]['E'][$evaluation_ids[$key]] = $guidelines[$cv];
							} elseif(isset($course_ids[$key])) {
								if(!isset($student['semesters'][($index % 2) + 1]['C']))
									$student['semesters'][($index % 2) + 1]['C'] = array();
								$student['semesters'][($index % 2) + 1]['C'][$course_ids[$key]] = $cv;
							}
							
							$ts = ord($total_start);
							
							if($co == chr($ts).$i){
								$student['semesters'][($index % 2) + 1]['total'] = $cv;
							} elseif($co == chr($ts+1).$i){
								$student['semesters'][($index % 2) + 1]['average'] = $cv;
							} elseif($co == chr($ts+2).$i){
								$student['semesters'][($index % 2) + 1]['rank'] = $cv;
							} elseif($co == chr($ts+4).$i){
								$student['semesters'][($index % 2) + 1]['remark'] = $cv;
							}
						}
					}
				}
				if(($index % 2) == 1) {
					$students[] = $student;
					$student = array();
				}
			}
		}
		pr($students);
		$this->log('students', 'nd');
		$this->log($students, 'nd');
		
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
				
			} else { // new student
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
				$registration['EduRegistration']['status'] = 'A';
				$registration['EduRegistration']['failure_count'] = 0;
				$registration['EduRegistration']['allowed'] = 'A';
				$registration['EduRegistration']['disciplinary_failure'] = 'P';
				$registration['EduRegistration']['remark'] = '-';
		
				$this->EduRegistration->create();
				if($this->EduRegistration->save($registration)){
					// 3. save EduRegistrationEvaluation
					$qs = array(1 => 2, 3, 4, 5);
					$edu_registration_id = $this->EduRegistration->id;
					foreach($qs as $ki => $q) {
						$reg_quarter = array('EduRegistrationQuarter' => array());
						$reg_quarter['EduRegistrationQuarter']['edu_registration_id'] = $edu_registration_id;
						$reg_quarter['EduRegistrationQuarter']['edu_quarter_id'] = $q;
						$reg_quarter['EduRegistrationQuarter']['quarter_total'] = $ki <= 2? (($s['semesters'][$ki]['total'] == ''? 0: $s['semesters'][$ki]['total'])): 0;
						$reg_quarter['EduRegistrationQuarter']['quarter_average'] = $ki <= 2? (($s['semesters'][$ki]['average'] == ''? 0: $s['semesters'][$ki]['average'])): 0;
						$reg_quarter['EduRegistrationQuarter']['quarter_rank'] = $ki <= 2? (($s['semesters'][$ki]['rank'] == ''? 0: $s['semesters'][$ki]['rank'])): 0;
						$reg_quarter['EduRegistrationQuarter']['class_rank'] = 0;
						$reg_quarter['EduRegistrationQuarter']['absentees'] = 0;
						$reg_quarter['EduRegistrationQuarter']['parent_comment'] = '-';
						$reg_quarter['EduRegistrationQuarter']['homeroom_comment'] = '-';
					
						$this->EduRegistrationQuarter->create();
						$this->EduRegistrationQuarter->save($reg_quarter);
						
						$edu_registration_quarter_id = $this->EduRegistrationQuarter->id;
						// Course
						if($ki <= 2){
							foreach($s['semesters'][$ki]['C'] as $k => $v){
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
							foreach($s['semesters'][$ki]['E'] as $k => $v){
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