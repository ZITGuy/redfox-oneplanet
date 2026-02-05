<?php

class AppError extends ErrorHandler {

    function cannotDeleteRecord($params) {
        $this->controller->header("HTTP/1.0 424 Failed Dependency");
        $this->controller->set('message', html_entity_decode($params['message']));
        $this->controller->set('helpcode', $params['helpcode']);
        $this->_outputMessage('cannot_delete_record');
    }

    function cannotSaveRecord($params) {
        $this->controller->header("HTTP/1.0 500 Internal Server Error");
        $this->controller->set('message', html_entity_decode(str_replace('&#039;', "'", $params['message'])));
        $this->controller->set('helpcode', $params['helpcode']);
        $this->_outputMessage('cannot_save_record');
    }
    
    function cannotViewRecord($params) {
        $this->controller->header("HTTP/1.0 404 Not Found");
        $this->controller->set('message', html_entity_decode($params['message']));
        $this->controller->set('helpcode', $params['helpcode']);
        $this->_outputMessage('cannot_view_record');
    }
    
    function cannotRedefineRecord($params) {
        $this->controller->header("HTTP/1.0 424 Failed Dependency");
        $this->controller->set('message', html_entity_decode($params['message']));
        $this->controller->set('helpcode', $params['helpcode']);
        $this->_outputMessage('cannot_redefine_record');
    }
	
}
