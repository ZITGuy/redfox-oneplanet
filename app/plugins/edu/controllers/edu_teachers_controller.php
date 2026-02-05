<?php

class EduTeachersController extends EduAppController {

    var $name = 'EduTeachers';
    var $ds_user;
    var $ds_person;
    var $ds_edu_teacher;
    
    function index() {
        $users = $this->EduTeacher->User->find('all');
        $this->set(compact('users'));
    }
    
    function index_m() {
    }
	
	function index_o() {
    }
	
    function index_v() {
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
            $this->loadModel('Edu.EduClass');
            $class = $this->EduClass->read(null, $edu_class_id);
			$t_ids = array();
			foreach($class['EduTeacher'] as $et) {
				$t_ids[] = $et['id'];
			}
            $conditions['EduTeacher.id'] = $t_ids;
        }
        $conditions['EduTeacher.deleted'] = 0;
        
		$this->EduTeacher->unbindModel(
			array('hasMany' => array('EduSection'))
		);
		$this->EduTeacher->unbindModel(
			array('hasMany' => array('EduAssignment'))
		);
		$this->EduTeacher->unbindModel(
			array('hasAndBelongsToMany' => array('EduSubject'))
		);
		$this->EduTeacher->unbindModel(
			array('hasAndBelongsToMany' => array('EduClass'))
		);
		
        $this->EduTeacher->recursive = 1;
		$teachers = $this->EduTeacher->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start, 'order' => 'User.username ASC'));
        $this->set('edu_teachers', $teachers);
		/*foreach($teachers as $teacher) {
			$this->EduTeacher->read(null, $teacher['EduTeacher']['id']);
			$identity = $teacher['EduTeacher']['id'];
			while(strlen($identity) < 4) {
				$identity = "0" . $identity;
			}
			$this->EduTeacher->set('identity_number', 'OPIS/'. $identity);
			$this->EduTeacher->save();
		}*/
        $this->set('results', $this->EduTeacher->find('count', array('conditions' => $conditions)));
    }
    
    function list_data_for_campus($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_campus_id = ($id) ? $id : -1;
        $conditions = array();
        if ($edu_campus_id != -1) {
            $conditions['User.edu_campus_id'] = $edu_campus_id;
        }
        $conditions['EduTeacher.deleted'] = 0;
        
        $this->EduTeacher->recursive = 3;
        $this->set('edu_teachers', $this->EduTeacher->find('all', 
            array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduTeacher->find('count', array('conditions' => $conditions)));
    }
	
	function list_data_teacher_subject($id = null) { // $id is teacher_id
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_teacher_id = (isset($_REQUEST['edu_teacher_id'])) ? $_REQUEST['edu_teacher_id'] : -1;
        if ($id) {
            $edu_teacher_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';
		
		$subjects = array();
        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_teacher_id != -1) {
            $this->loadModel('Edu.EduSubjectsTeacher');
			$this->EduSubjectsTeacher->recursive = 2;
			$this->EduSubjectsTeacher->bindModel(
				array('belongsTo' => array('EduSubject'))
			);
			$this->EduSubjectsTeacher->bindModel(
				array('belongsTo' => array('EduTeacher'))
			);
            $teacher_subjects = $this->EduSubjectsTeacher->find('all', 
                array('conditions' => array('edu_teacher_id' => $edu_teacher_id)));
            
			foreach ($teacher_subjects as $teacher_subject) {
                $subjects[] = $teacher_subject['EduSubject'];
            }
        }
        $this->set('subjects', $subjects);
        $this->set('results', count($subjects));
    }
	
	function list_data_teacher_class($id = null) { // $id is teacher_id
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_teacher_id = (isset($_REQUEST['edu_teacher_id'])) ? $_REQUEST['edu_teacher_id'] : -1;
        if ($id) {
            $edu_teacher_id = ($id) ? $id : -1;
        }
        
		$classes = array();
        if ($edu_teacher_id != -1) {
            $this->loadModel('Edu.EduClassesTeacher');
			$this->EduClassesTeacher->recursive = 2;
			$this->EduClassesTeacher->bindModel(
				array('belongsTo' => array('EduClass'))
			);
			$this->EduClassesTeacher->bindModel(
				array('belongsTo' => array('EduTeacher'))
			);
            $teacher_classes = $this->EduClassesTeacher->find('all', 
                    array('conditions' => array('edu_teacher_id' => $edu_teacher_id)));
            
			foreach ($teacher_classes as $teacher_class) {
                $classes[] = $teacher_class['EduClass'];
            }
        }
        $this->set('classes', $classes);
        $this->set('results', count($classes));
    }
    
    function list_data_teacher_section($id = null) { // $id is teacher_id
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_teacher_id = (isset($_REQUEST['edu_teacher_id'])) ? $_REQUEST['edu_teacher_id'] : -1;
        if ($id) {
            $edu_teacher_id = ($id) ? $id : -1;
        }
        
        $sections = array();
        if ($edu_teacher_id != -1) {
            $this->loadModel('Edu.EduSectionsTeacher');
			$this->EduSectionsTeacher->recursive = 2;
			$this->EduSectionsTeacher->bindModel(
				array('belongsTo' => array('EduSection'))
			);
			$this->EduSectionsTeacher->bindModel(
				array('belongsTo' => array('EduTeacher'))
			);
            $teacher_sections = $this->EduSectionsTeacher->find('all', 
                    array('conditions' => array('edu_teacher_id' => $edu_teacher_id)));
            
			foreach ($teacher_sections as $teacher_section) {
                $sections[] = $teacher_section['Edu<section'];
            }
        }

        $this->set('sections', $sections);
        $this->set('results', count($sections));
    }

    function list_data_subject($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $edu_subject_id = (isset($_REQUEST['edu_subject_id'])) ? $_REQUEST['edu_subject_id'] : -1;
        if ($id) {
            $edu_subject_id = ($id) ? $id : -1;
        }
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($edu_subject_id != -1) {
            $this->loadModel('Edu.EduSubjectsTeacher');
            $sub_teachers = $this->EduSubjectsTeacher->find('all', 
                 array('conditions' => array('edu_subject_id' => $edu_subject_id)));
            $sub_teacher_ids = array();
            foreach ($sub_teachers as $sub_teacher) {
                $sub_teacher_ids[] = $sub_teacher['EduSubjectsTeacher']['edu_teacher_id'];
            }
            $conditions['EduTeacher.id'] = $sub_teacher_ids;
        }
        $conditions['EduTeacher.deleted'] = 0;
        
        $this->EduTeacher->recursive = 3;
        $this->set('edu_teachers', $this->EduTeacher->find('all', 
              array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->EduTeacher->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid edu teacher', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->EduTeacher->recursive = 2;
        $this->set('edu_teacher', $this->EduTeacher->read(null, $id));
    }
    
    function loadRequiredModels() {
        $this->loadModel('User');
    }
    
    function instantiateModelObjects() {
        $this->ds_user = $this->User->getDataSource();
        $this->ds_person = $this->User->Person->getDataSource();
        $this->ds_edu_teacher = $this->EduTeacher->getDataSource();
    }

    function beginTransactions() {
        $this->ds_user->begin($this->User);
        $this->ds_person->begin($this->User->Person);
        $this->ds_edu_teacher->begin($this->EduTeacher);
    }

    function rollbackTransactions() {
        $this->ds_user->rollback($this->User);
        $this->ds_person->rollback($this->User->Person);
        $this->ds_edu_teacher->rollback($this->EduTeacher);
    }

    function commitTransactions() {
        $this->ds_user->commit($this->User);
        $this->ds_person->commit($this->User->Person);
        $this->ds_edu_teacher->commit($this->EduTeacher);
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->layout = 'ajax';
            $this->autoRender = false;
            
            $this->loadRequiredModels();
            $this->instantiateModelObjects();
            $this->beginTransactions();
            
            // 1 Create Person Object
            $person_data['Person'] = array(
                'first_name' => $this->data['EduTeacher']['teacher_name1'],
                'middle_name' => $this->data['EduTeacher']['teacher_name2'],
                'last_name' => $this->data['EduTeacher']['teacher_name3'],
                'birthdate' => '2100-01-01',
                'birth_location_id' => 1,
                'residence_location_id' => 1,
                'kebele_or_farmers_association' => 'Not Specified',
                'house_number' => $this->data['EduTeacher']['house_number']
            );
            
            $this->User->Person->create();
            if ($this->User->Person->save(array('Person' => $person_data['Person']))) {
                // 2 Create User (including group)
                // prepare the user data to include the Group HABTM associated data.
                $u = array(
                    'username' => $this->data['EduTeacher']['username'],
                    'password' => $this->data['EduTeacher']['password'],
                    'email' => $this->data['EduTeacher']['email'],
                    'edu_campus_id' => $this->data['EduTeacher']['edu_campus_id'],
                    'mobile' => $this->data['EduTeacher']['telephone_mobile']
                );
                $group = $this->User->Group->find('first', array('conditions' => array('Group.name' => 'Teacher')));
                
                $g['Group'] = array('Group' => array());
                $g['Group']['Group'][] = $group['Group']['id'];
                
                $user_data = array('User' => $u, 'Group' => $g['Group']);
                
                $user_data['User']['person_id'] = $this->User->Person->id;
                $user_data['User']['is_active'] = true;
                $user_data['User']['password'] = $this->Auth->password($user_data['User']['password']);
                $user_data['User']['email'] = strtolower($user_data['User']['email']);
                $user_data['User']['change_campus'] = 1;  // TODO: get this from the group to which the user is about to register
				
                $user_data['User']['photo_file'] = 'No file';

                // create the user record.
                $this->User->create();
                if ($this->User->save($user_data)) {
                    // 3 Create Teacher (including subjects)
                    $teacher['EduTeacher'] = array(
                        'identity_number' => $this->data['EduTeacher']['tidentity_number'],
                        'date_of_employment' => $this->data['EduTeacher']['date_of_employment'],
                        'user_id' => $this->User->id,
                        'city' => $this->data['EduTeacher']['city'],
                        'sub_city' => $this->data['EduTeacher']['sub_city'],
                        'woreda' => $this->data['EduTeacher']['woreda'],
                        'house_number' => $this->data['EduTeacher']['house_number'],
                        'telephone_home' => $this->data['EduTeacher']['telephone_home'],
                        'telephone_mobile' => $this->data['EduTeacher']['telephone_mobile'],
                        'qualification' => $this->data['EduTeacher']['qualification'],
                        'remark' => $this->data['EduTeacher']['remark'],
                        'photo' => 'No file'
                    );
					
					$classes = $this->data['EduClass'];

					$this->data['EduClass'] = array('EduClass' => array());
					foreach ($classes as $key => $value) {
						if($key != 'None')
							$this->data['EduClass']['EduClass'][] = $key;
					}
					
					$subjects = $this->data['EduSubject'];

					$this->data['EduSubject'] = array('EduSubject' => array());
					foreach ($subjects as $key => $value) {
						if($key != 'None')
							$this->data['EduSubject']['EduSubject'][] = $key;
					}
					
                    // Saving the favorite subjects and classes of the teacher along side.
					$teacher_data = array(
						'EduTeacher' => $teacher['EduTeacher'], 
						'EduClass' => $this->data['EduClass'], 
						'EduSubject' => $this->data['EduSubject']);
					
                    $this->EduTeacher->create();
                    if ($this->EduTeacher->save($teacher_data)) {
						
                        $this->commitTransactions();

                        $this->Session->setFlash(__('The Teacher has been successfully created', true), '');
                        $this->render('/elements/success');
                    } else {
                        $this->rollbackTransactions();

                        $this->Session->setFlash(__('The Teacher could not be created.', true), '');
                        $this->render('/elements/failure');
                    }
                } else {
                    $this->rollbackTransactions();

                    $this->Session->setFlash(__('The Teacher User could not be created.', true), '');
                    $this->render('/elements/failure');
                }
                
            } else {
                $this->rollbackTransactions();

                $this->Session->setFlash(__('The Teacher Person could not be created.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id) {
            $this->set('parent_id', $id);
        }
		
		$this->EduTeacher->recursive = 0;
		$last_record = $this->EduTeacher->find('all', 
		    array('conditions' => array('EduTeacher.deleted' => array(0, 1)), 'order' => 'EduTeacher.id DESC'));
		
		$last_id = $last_record[0]['EduTeacher']['id'];
		$suffix = $last_id + 1;
		while(strlen($suffix) < 4) {
			$suffix = '0' . $suffix;
		}
		$next_identity = 'OPIS1' . $suffix;

		$this->set('next_identity', $next_identity);
        $this->set('edu_campuses', $this->EduTeacher->User->EduCampus->find('list', array()));
        $this->set('classes', $this->EduTeacher->EduClass->find('list', array('conditions' => array(), 'order' => 'cvalue ASC')));
        $this->set('subjects', $this->EduTeacher->EduSubject->find('list', array('conditions' => array(), 'order' => 'name ASC')));
    }

    function associate($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu teacher', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->layout = 'ajax';
            $this->autoRender = false;
            
			// 3 Create Teacher (including class, section, and subjects)
			$teacher['EduTeacher'] = array(
				'id' => $this->data['EduTeacher']['id'],
			);
			
			$classes = $this->data['EduClass'];

			$this->data['EduClass'] = array('EduClass' => array());
			foreach ($classes as $key => $value) {
				if($key != 'None')
					$this->data['EduClass']['EduClass'][] = $key;
			}
			
			$sections = $this->data['EduSection'];

			$this->data['EduSection'] = array('EduSection' => array());
			foreach ($sections as $key => $value) {
				if($key != 'None')
					$this->data['EduSection']['EduSection'][] = $key;
            }
            
            $subjects = $this->data['EduSubject'];

			$this->data['EduSubject'] = array('EduSubject' => array());
			foreach ($subjects as $key => $value) {
				if($key != 'None')
					$this->data['EduSubject']['EduSubject'][] = $key;
			}
			
			// Saving the favorite subjects and classes of the teacher along side.
			$teacher_data = array(
				'EduTeacher' => $teacher['EduTeacher'], 
				'EduClass' => $this->data['EduClass'], 
				'EduSubject' => $this->data['EduSubject']);
			
			//$this->EduTeacher->create();
			if ($this->EduTeacher->save($teacher_data)) {
				$this->Session->setFlash(__('The Teacher has been successfully created', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The Teacher could not be created.', true), '');
				$this->render('/elements/failure');
			}
        }
        $this->set('edu_teacher', $this->EduTeacher->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        /*$this->loadModel('Edu.EduAcademicYear');
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $edu_academic_year_id = $ay['EduAcademicYear']['id'];

        $this->EduTeacher->EduSection->unbindModel(
            array('belongsTo' => array('EduAcademicYear', 'EduTeacher', 'EduCampus'), 
                  'hasMany' => array('EduAssessment', 'EduAssignment', 'EduRegistration')
            )
		);
        $this->EduTeacher->unbindModel(
			array('hasMany' => array('EduSection'))
		);*/

		$this->set('edu_campuses', $this->EduTeacher->User->EduCampus->find('list', array()));
        $this->set('classes', $this->EduTeacher->EduClass->find('list', array('conditions' => array(), 'order' => 'cvalue ASC')));
        //$this->set('edu_sections', $this->EduTeacher->EduSection->find('all', 
        //    array('conditions' => array('EduSection.edu_academic_year_id' => $edu_academic_year_id), 
        //    'order' => 'EduClass.cvalue, EduSection.name')));
        $this->set('subjects', $this->EduTeacher->EduSubject->find('list', array('conditions' => array(), 'order' => 'name ASC')));
		
    }

    function associate_sections($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid edu teacher', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->layout = 'ajax';
            $this->autoRender = false;
            
			// 3 Create Teacher (including class, section, and subjects)
			$teacher['EduTeacher'] = array(
				'id' => $this->data['EduTeacher']['id'],
			);
			
			$sections = $this->data['EduSection'];

			$this->data['EduSection'] = array('EduSection' => array());
			foreach ($sections as $key => $value) {
				if($key != 'None')
					$this->data['EduSection']['EduSection'][] = $key;
            }
			
			// Saving the associated sections of the teacher along side.
			$teacher_data = array(
				'EduTeacher' => $teacher['EduTeacher'], 
				'EduSection' => $this->data['EduSection']);
			
			$this->EduTeacher->create();
			if ($this->EduTeacher->save($teacher_data)) {
				$this->Session->setFlash(__('The Teacher is successfully associated with sections', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The Teacher could not be associated.', true), '');
				$this->render('/elements/failure');
			}
        }
        $edu_teacher = $this->EduTeacher->read(null, $id);
        $this->set('edu_teacher', $edu_teacher);

        $this->loadModel('EduAcademicYear');

        $associated_classes = array();
		foreach($edu_teacher['EduClass'] as $class) {
			$associated_classes[] = $class['id'];
		}

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }
        $ay = $this->EduAcademicYear->getActiveAcademicYear();
        $edu_academic_year_id = $ay['EduAcademicYear']['id'];

        $cond = array('EduSection.edu_class_id' => $associated_classes, 'EduSection.edu_academic_year_id' => $edu_academic_year_id);
        $secs = $this->EduTeacher->EduSection->find('all', array('conditions' => $cond, 'order' => 'edu_class_id ASC'));
        $sections = array();
        foreach($secs as $sec) {
            $sections[$sec['EduSection']['id']] = $sec['EduClass']['name'] . ' ' . $sec['EduSection']['name'];
        }
		$this->set('edu_campuses', $this->EduTeacher->User->EduCampus->find('list', array()));
        $this->set('sections', $sections);
    	
    }
	
	function upload_photo($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Teacher', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->layout = 'ajax';
            $this->autoRender = false;
			
			$id = $this->data['EduTeacher']['id'];
			$teacher = $this->EduTeacher->read(null, $id);
			
			// upload image
            $file = $this->data['EduTeacher']['photo'];
            $file_name = basename($file['name']);
            $fext = substr($file_name, strrpos($file_name, "."));
            $fname = time(); 
            $file_name = $id . '_' . $fname . $fext;
			
            if (!file_exists(IMAGES . 'teachers')) {
                mkdir(IMAGES . 'teachers', 0777);
            }
			unset($this->data['EduTeacher']['name']);
			
            if (!move_uploaded_file($file['tmp_name'], IMAGES . 'teachers' . DS . $file_name)) {
                unset($this->data['EduTeacher']['photo']);
            } else {
                $this->data['EduTeacher']['photo'] = $file_name;
            }
			
            if ($this->EduTeacher->save($this->data)) {
				$this->Session->setFlash(__('The Teacher Photo has been updated', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The Teacher Photo could not be updated. Please, try again.', true) . 
                    'ERROR: ' . pr($this->EduTeacher->validationErrors, true), '');
                $this->render('/elements/failure');
            }
        }
		$this->EduTeacher->recursive = 3;
        $this->set('edu_teacher', $this->EduTeacher->read(null, $id));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for edu teacher', true), '');
            $this->render('/elements/failure');
        }
        $this->EduTeacher->read(null, $id);
        $this->EduTeacher->set('deleted', 1);
        
        if ($this->EduTeacher->save()) {
            $this->Session->setFlash(__('The teacher deleted successfully', true), '');
            $this->render('/elements/success');
        } else {
            $this->Session->setFlash(__('The teacher was not deleted', true), '');
            $this->render('/elements/failure');
        }
    }

    /**
     * Teachers report form
     */
    function rpt_teachers() {
        $this->loadModel('Edu.EduAcademicYear');

        $edu_academic_years = $this->EduAcademicYear->find('list', array('order' => 'EduAcademicYear.start_date DESC'));
        $active_ay = $this->EduAcademicYear->getActiveAcademicYear();

        $this->set('edu_academic_years', $edu_academic_years);
        $this->set('active_ay', $active_ay);
    }

    /**
     * Teachers report viewer
     */
    function rpt_view_teachers($id = null, $title = null) {
        $this->layout = 'ajax';

        $conditions = array();
        $this->loadModel('Edu.EduAcademicYear');
	$this->loadModel('Edu.EduSection');
        $this->loadModel('Edu.EduTeacher');

        $ay = $this->EduAcademicYear->read(null, $id);

        $this->EduTeacher->recursive = 2;
	$this->EduTeacher->unbindModel(array('hasMany' => array('EduSection')));
	$this->EduTeacher->unbindModel(array('hasMany' => array('EduAssignment')));
	$this->EduTeacher->unbindModel(array('hasAndBelongsToMany' => array('EduSubject')));
	$this->EduTeacher->unbindModel(array('hasAndBelongsToMany' => array('EduClass')));
        $teachers = $this->EduTeacher->find('all', array('conditions' => $conditions, 'order' => 'User.username ASC'));

        $this->set('edu_teachers', $teachers);
	$sections = $this->EduSection->find('all', array('conditions' => array('EduSection.edu_academic_year_id' => $ay['EduAcademicYear']['id'])));
	$this->set('edu_sections', $sections);

        $this->set('company_url', Configure::read('company_url'));
        $this->set('company_name', Configure::read('company_name'));
        $this->set('company_tin', Configure::read('company_tin'));
        $this->set('company_address', Configure::read('company_address'));
        $this->set('report_title', str_replace('_', ' ', $title));
        $this->set('academic_year', $ay['EduAcademicYear']['name']);
    }

}
