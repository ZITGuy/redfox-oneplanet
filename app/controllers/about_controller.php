<?php

class AboutController extends AppController {

    public $uses = array();

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('*');
    }

    public function index() {
        $this->layout = 'about';
    }

    function contacts($language = 'en') {
        
    }

}

?>