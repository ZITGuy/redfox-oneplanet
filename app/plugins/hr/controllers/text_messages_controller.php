<?php

class TextMessagesController extends HrAppController {

    var $name = 'TextMessages';

    function index() {
        
    }

    function search() {
        
    }

    function batch_messages() {
        $this->autoRender = false;
        $conditions['TextMessage.status'] = 'not_sent';
        $messages = $this->TextMessage->find('all', array('conditions' => $conditions));
        foreach ($messages as $message) {

            $this->sms(substr($message['TextMessage']['name'], 1), $message['TextMessage']['text']);
            $this->data['id'] = $message['TextMessage']['id'];
            $this->data['status'] = 'sent';
            $this->TextMessage->save($this->data);
            //print_r($this->data);
        }
    }

    function sms($from, $text) {
        $this->autoRender = false;
        /* shell_exec('cd\\');
          shell_exec('cd GalaxyS2RootNew\files');
          shell_exec('adb wait-for-device');
          shell_exec('adb shell am start -a android.intent.action.SENDTO -d sms:+251912179525 --es sms_body "PHP Automated" --ez exit_on_sent true');
          //exec('adb shell input keyevent 66');
         * 
         */
        $unixtime = time();

// Sets up your exe or other path.
        $cmd = 'C:\\GalaxyS2RootNew\\files\\adb.exe';

// Setup an array of arguments to be sent.
        $arg[] = '1';
        $arg[] = '2';
        $arg[] = '3';
        $arg[] = '4';
        $arg[] = '5';

// Pick a place for the temp_file to be placed.
        $outputfile = 'C:\\GalaxyS2RootNew\\files\\tmp\\unixtime.txt';

// Setup the command to run from "run"
//$cmdline = "cmd /C $cmd " . implode(' ', $arg) . " > $outputfile";
        sleep(1);
        $cmdx = 'shell am start -a android.intent.action.SENDTO -d sms:+251' . $from . ' --es sms_body "' . $text . '" --ez exit_on_sent true';
        $cmdline = "cmd /C $cmd " . $cmdx;
// Make a new instance of the COM object
        $WshShell = new COM("WScript.Shell");

// Make the command window but don't show it.
        $oExec = $WshShell->Run($cmdline, 0, true);
        sleep(2);
        $cmdx = "shell input keyevent 66";
        $cmdline = "cmd /C $cmd " . $cmdx;
        $oExec = $WshShell->Run($cmdline, 0, true);
// Read the file file.
        $output = file($outputfile);

//print_r($output);
// Delete the temp_file.
//unlink($outputfile);
        //print_r($cmd);
        // echo $rtn;
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");

        $this->set('text_messages', $this->TextMessage->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->TextMessage->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid text message', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->TextMessage->recursive = 2;
        $this->set('textMessage', $this->TextMessage->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->TextMessage->create();
            $this->autoRender = false;
            if ($this->TextMessage->save($this->data)) {
                $this->Session->setFlash(__('The text message has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The text message could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid text message', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->TextMessage->save($this->data)) {
                $this->Session->setFlash(__('The text message has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The text message could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('text__message', $this->TextMessage->read(null, $id));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for text message', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->TextMessage->delete($i);
                }
                $this->Session->setFlash(__('Text message deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Text message was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->TextMessage->delete($id)) {
                $this->Session->setFlash(__('Text message deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Text message was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}

?>