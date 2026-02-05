<?php

class EduAppModel extends AppModel {

    function beforeFind($queryData) { // mixed
        parent::beforeFind($queryData);
        // TODO: include condition 'deleted' => false in the conditions list
        
		if(!isset($queryData['conditions'][$this->name . '.deleted']))
			$queryData['conditions'][$this->name . '.deleted'] = 0; 
        //$this->log($queryData, 'query');
        
        return $queryData;
    }

    function beforeDelete($cascade) { // boolean 
        parent::beforeDelete($cascade);
        // Update the deleted field 
        $record_id = $this->data[$this->name]['id'];
        $this->loadModel($this->name);
        $this->{$this->name}->read(null, $record_id);
        $this->{$this->name}->set('deleted', true);
        $this->{$this->name}->save();
        Configure::write('soft_deleted', 'yes');
        
        return false; // not to delete the actual record
    }
}

?>
