<?php

class RestorePointsController extends AppController {

    var $name = 'RestorePoints';

    function index() {
        
    }

    function search() {
        
    }

    function list_data() {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");

        $this->set('restore_points', $this->RestorePoint->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->RestorePoint->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid restore point', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->RestorePoint->recursive = 2;
        $this->set('restore_point', $this->RestorePoint->read(null, $id));
    }
    
    function createRestorePoint() {
        // define variables
		$db = $this->RestorePoint->query("SELECT DATABASE() AS DBNAME FROM DUAL");
		//$this->log($db, 'ddebbug');
		$db_name = $db[0][0]['DBNAME'];
        $tables = $this->RestorePoint->query("SHOW TABLES");
        $content = "";
		// Take the backup
        foreach($tables as $table){
            $table_name = $table['TABLE_NAMES']['Tables_in_' . $db_name];
            $table_data = $this->RestorePoint->query("SELECT * FROM $table_name");
            $field_def = $this->RestorePoint->query("SHOW COLUMNS FROM $table_name");
            $field_defs = array();
            foreach($field_def as $fd){
                $field_defs[$fd['COLUMNS']['Field']] = $fd['COLUMNS'];
            }
            $content .= "\n-- Table: $table_name (" . (count($table_data) >= 1? count($table_data): "No") . " record" . (count($table_data) > 1? "s": "") . ") --\n";
            $count = 0;
            $temp_content = "";
            $temp_count = 0;
            foreach($table_data as $record) {
                $fields = array();
                $values = array();
                foreach($record[$table_name] as $field => $value) {
                    $fields[] = $field;
                    $values[] = (is_numeric($value)? $value: ($field_defs[$field]['Type'] == 'int(11)' && $value == ''? 'NULL': "'" . $value . "'"));
                }
                $count++;
                if(/*$count <= 100 && */$temp_content == '' && $count <= count($table_data)) {
                    $temp_content .= "INSERT INTO $table_name (`" . join("`, `", $fields) . "`) VALUES\n (" . join(", ", $values) . "),\n";
                } else if(/*$count <= 100 && ($count + ($temp_count * 100)) < count($table_data) && */(strlen($temp_content) + strlen(" (" . join(", ", $values) . "),\n")) < 50000) {
                    $temp_content .= " (" . join(", ", $values) . "),\n";
                } else {
                    if($temp_content == ''){
                        $temp_content .= "INSERT INTO $table_name (`" . join("`, `", $fields) . "`) VALUES\n (" . join(", ", $values) . ");\n";
                    } else {
                        $temp_content .= " (" . join(", ", $values) . ");\n";
                    }
                    $count = 0;
                    $content .= $temp_content;
                    $temp_content = "";
                    $temp_count++;
                }
            }
            if($temp_content <> ""){
                $content .= substr($temp_content, 0, strlen($temp_content)-2) . ";\n";
                $temp_content = "";
            }
        }

        $filename = date('Y-m-d-H-i-s') . '.sql';

        if (!file_exists(IMAGES . 'restore_points')) {
            mkdir(IMAGES . 'restore_points', 0777);
        }
        if (!file_exists(IMAGES . 'restore_points' . DS . date('Y-m-d'))) {
            mkdir(IMAGES . 'restore_points' . DS . date('Y-m-d'), 0777);
        }
        $handle = fopen(IMAGES . 'restore_points' . DS . date('Y-m-d') . DS . $filename, 'a+');

        if (fwrite($handle, $content) === FALSE) {
            echo "Cannot write to file ($filename)";
            return false;
        }
        fclose($handle);
        
        //ftp upload here
        
        $restore_point = array('RestorePoint' => array());
        $restore_point['RestorePoint']['name'] = substr($filename, 0, strlen($filename) - 4);
        $this->RestorePoint->create();
        $this->autoRender = false;
        if ($this->RestorePoint->save($restore_point)) {
            return true;
        } else {
            return false;
        }
    }

    function add() {
        if (!empty($this->data)) {
            
            $this->autoRender = false;
            if ($this->createRestorePoint()) {
                $this->Session->setFlash(__('The restore point has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The restore point could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for restore point', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->RestorePoint->delete($i);
                }
                $this->Session->setFlash(__('Restore point deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Restore point was not deleted', true) . ' ERROR: ' . $e->getMessage(), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->RestorePoint->delete($id)) {
                $this->Session->setFlash(__('Restore point deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Restore point was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }
}
