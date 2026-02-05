<?php

class EduPhotosController extends EduAppController {

    public $name = 'EduPhotos';
	public $uses = array();
	
    function index() {
        
    }

    function list_data() {
		$photos = array();
		if($this->Session->check('photos')) {
			$photos = $this->Session->read('photos');
		} else {
			$this->Session->write('photos', $photos);
		}
        $this->set('photos', $photos);
        $this->set('results', count($photos));
    }

    function add() {
        if (!empty($this->data)) {
			$this->layout = 'ajax';
            $this->autoRender = false;
            $file = $this->data['EduPhoto']['photo_file'];
            $file_name = basename($file['name']);
            $fext = substr($file_name, strrpos($file_name, "."));
            $fname = time(); // str_replace($fext, "", $file_name);
            $file_name = $fname . $fext;
            
            if (!file_exists(IMAGES . 'tmpphotos'))
                mkdir(IMAGES . 'tmpphotos', 0777);

            if (move_uploaded_file($file['tmp_name'], IMAGES . 'tmpphotos' . DS . $file_name)) {
                $this->data['EduPhoto']['photo_file'] = $file_name;
				
				$photos = $this->Session->read('photos');
				$photo = $this->data['EduPhoto'];
				$photos[time()] = $photo;
				$this->Session->write('photos', $photos);
				$this->Session->setFlash(__('Data saved successfully', true));
				
				$this->render('/elements/success');
            }else {
				$this->Session->setFlash(__('Data could not be saved', true));
				$this->render('/elements/failure');
			}
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
		$photos = $this->Session->read('photos');
		$found = false;
		foreach($photos as $k => $v) {
			if($k == $id) {
				unset($photos[$k]);
				$this->Session->write('photos', $photos);
				$found = true;
				break;
			}
		}
		
		return $found;
	}

}
