<?php

class HelpContentsController extends AppController {

    var $name = 'HelpContents';

	function beforeFilter() {
		parent::beforeFilter();
		
		$this->Auth->allow('get_help');
	}
	
    function index() {
        
    }
	
	function get_help($code) {
		$this->layout = 'ajax';
		$help_content = $this->HelpContent->find('first', array('conditions' => array('HelpContent.code' => $code)));
		$msg = 'You are in danger!';
		if($help_content) {
			$msg = $help_content['HelpContent']['content'];
		}
		$this->set('msg', $msg);
	}

    function search() {
        
    }

    function list_data() {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");

        $this->set('help_contents', $this->HelpContent->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->HelpContent->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid help content', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->HelpContent->recursive = 2;
        $this->set('help_content', $this->HelpContent->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->HelpContent->create();
            $this->autoRender = false;
            
            // Strip out carriage returns
            $content = ereg_replace("\r",'',$this->data['HelpContent']['content']);
            // Handle paragraphs
            $content = ereg_replace("\n\n",'<br /><br />',$content);
            // Handle line breaks
            $content = ereg_replace("\n",'<br />',$content);
            // Handle apostrophes
            $content = ereg_replace("'",'&#39;',$content);

            $this->data['HelpContent']['content'] = $content;
            
            if ($this->HelpContent->save($this->data)) {
                $this->Session->setFlash(__('The help content has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The help content could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid help content', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            
            // Strip out carriage returns
            $content = ereg_replace("\r",'',$this->data['HelpContent']['content']);
            // Handle paragraphs
            $content = ereg_replace("\n\n",'<br /><br />',$content);
            // Handle line breaks
            $content = ereg_replace("\n",'<br />',$content);
            // Handle apostrophes
            $content = ereg_replace("'",'&#39;',$content);

            $this->data['HelpContent']['content'] = $content;
            
            if ($this->HelpContent->save($this->data)) {
                $this->Session->setFlash(__('The help content has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The help content could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $help_content = $this->HelpContent->read(null, $id);
        $this->set('help_content', $help_content);
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->header("HTTP/1.0 500 Internal Server Error");
            $this->Session->setFlash(__('Invalid id for help content', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->HelpContent->delete($i);
                }
                $this->Session->setFlash(__('Help content deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->header("HTTP/1.0 500 Internal Server Error");
                $this->Session->setFlash(__('Help content was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->HelpContent->delete($id)) {
                $this->Session->setFlash(__('Help content deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->header("HTTP/1.0 500 Internal Server Error");
                $this->Session->setFlash(__('Help content was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }
}
