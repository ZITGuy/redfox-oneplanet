<?php
//require_once APPLIBS . 'smpp.php';
//App::import('SMPP');
//App::import('Vendor', 'SMPP/SMPP', array('file' => 'smpp.php'));
App::import('Vendor', 'SMPP/SMPPClass', array('file' => 'smppclass.php'));
App::import('Vendor', 'Amharic', array('file' => 'amharic.php'));

class TextMessagesController extends AppController {

    var $name = 'TextMessages';

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('call_sms_messages');
	}
	
	function broadcast() {
		if (!empty($this->data)) {
			$rgroups = $this->data['Receiver'];

			$receivers = array();
            
            foreach ($rgroups as $key => $value) {
                if($key == 'T'){ // teachers
                	$this->loadModel('Edu.EduTeacher');
                	$teachers = $this->EduTeacher->find('all');
                	foreach ($teachers as $teacher) {
                		$to = $this->clearReceiver($teacher['EduTeacher']['telephone_mobile']);
                		if($to != '' && !in_array($to, $receivers)) {
                			$receivers[] = $to;
                		}
                	}
                } elseif($key == 'P') { // parents
                	$this->loadModel('Edu.EduParent');
                	$parents = $this->EduParent->find('all');
                	foreach ($parents as $parent) {
                		$to = $this->clearReceiver($parent['EduParent']['sms_phone_number']);
                		if($to != '' && !in_array($to, $receivers)) {
                			$receivers[] = $to;
                		}
                	}
                } elseif($key == 'OU') {  // office users
                	$this->loadModel('Group');
                	$this->Group->recursive = 2;
                	$groups = $this->Group->find('all', array('conditions' => array('Group.id' => array(4, 8, 22))));
                	foreach ($groups as $group) {
                		foreach ($group['User'] as $user) {
                			$to = $this->clearReceiver($user['mobile']);
	                		if($to != '' && !in_array($to, $receivers)) {
	                			$receivers[] = $to;
	                		}
                		}
                	}
                }
            }

            foreach ($receivers as $receiver) {
            	$this->TextMessage->create();
            	$msg = array('TextMessage' => array(
            			'receiver' => $receiver,
            			'message' => $this->data['TextMessage']['message'],
            			'status' => 'N',
            			'remark' => '-'
            		));
            	$this->TextMessage->save($msg);

            }

            
            $this->autoRender = false;
            
            $this->Session->setFlash(__('The text message has been queued', true), '');
            $this->render('/elements/success');
        }
	}

	function call_sms_messages() {
		$this->layout = 'ajax';
		$sms_enabled = $this->getSystemSetting('SMS_ENABLED');
		if($sms_enabled == 'True') {
			$s = $this->getSystemSetting('SMS RUNNING');
			if($s == 1) {
				$this->set('msg', "SMS RUNNING OR Dead-lock is created!\n");
				$message = "SMS RUNNING OR Dead lock is created!. " . date('Y-m-d+H:i:s');
				$this->sendSMS('251930328163', $message);
			} else {
				$this->setSystemSetting('SMS RUNNING', 1);
				
				$msg = $this->send_sms_messages();
				
				$this->setSystemSetting('SMS RUNNING', 0);
				$this->set('msg', $msg);
			}
		} else {
			$this->set('msg', 'SMS Mesaging is not enabled.');
		}
	}
	
	/**
	 * Function to send SMS Messages
	 */
	function send_sms_messages() {
		$msg = 'OK';
		$text_messages = $this->TextMessage->find('all', array('conditions' => array('TextMessage.status' => 'N')));
		
		$counts = 0;
		$oks = 0;
		$start = time();
		foreach($text_messages as $text_message) {
			$counts++;
			$to = $text_message['TextMessage']['receiver'];
			$message = $text_message['TextMessage']['message'];
			
			$return = $this->sendSMS($to, $message);
			
			$this->TextMessage->read(null, $text_message['TextMessage']['id']);
			if(strpos($return, 'Error opening') === FALSE && strpos($return, 'Not connected') === FALSE) {
				$oks++;
				$this->TextMessage->set('status', 'S');
			} else {
				$this->TextMessage->set('status', 'F');
				$this->TextMessage->set('remark', $return);
			}
			$this->TextMessage->save();
			
			// run this for a minute ony
			if(time() - 50 > $start)
				break;
		}
		$msg .= "\n" . 'Out of ' . $counts . ' records ' . $oks . ' are sent successfully.' . "\n";
		return $msg;
	}
	
	function send_text_message($id) {
		$msg = 'OK';
		$text_message = $this->TextMessage->read(null, $id);
		
		if(!empty($text_message)) {
			$to = $text_message['TextMessage']['receiver'];
			$message = $text_message['TextMessage']['message'];
			
			$return = $this->sendSMS($to, $message);
			
			$this->TextMessage->read(null, $text_message['TextMessage']['id']);
			if(strpos($return, 'Error opening') === FALSE && strpos($return, 'Not connected') === FALSE) {
				$this->TextMessage->set('status', 'S');
			} else {
				$this->TextMessage->set('status', 'F');
				$this->TextMessage->set('remark', $return);
			}
			$this->TextMessage->save();
		}
		//$msg .= "\n" . 'SMS Message Sent successfully.' . "\n";
		return $msg;
	}
	
	function sendSMS2($to, $msg) {
		$smpphost = $this->getSystemSetting('SMS_SERVER_IP');
		$smppport = $this->getSystemSetting('SMS_SERVER_PORT');
		$systemid = $this->getSystemSetting('SMS_USER_ID');
		$password = $this->getSystemSetting('SMS_PASSWORD');
		$from = $this->getSystemSetting('SMS_SHORT_CODE');
		$system_type = "WWW";
		
		$smpp = new SMPPClass();
		
		$smpp->SetSender($from);
		/* bind to smpp server */
		$smpp->Start($smpphost, $smppport, $systemid, $password, $system_type);
		/* send enquire link PDU to smpp server */
		$smpp->TestLink();
		
		$to = $this->clearSender($to);
		// finally
		$err_msg = '';
		if(substr($to, 0, 4) != '2519' || strlen($to) != 12) {
			$err_msg .= ($err_msg == '')? "": "\n";
			$err_msg .= "Phone Number is invalid.";
		}
		if($msg == '') {
			$err_msg .= ($err_msg == '')? "": "\n";
			$err_msg .= "Message is empty.";
		}

		if($err_msg == '') {
			logMessage("SMS message sent to $to, message: $msg");
			$amharic = new Amharic();
			$msg = $amharic->encode_amharic($msg);
			$smpp->Send($to, $msg, true);
			$err_msg = "OK";
		} else {
			logMessage("SMS message cannot be sent to $to, message: $msg, ERROR: $err_msg");
		}
		/* unbind from smpp server */
		$smpp->End();

		return $err_msg;
	}
	
	function sendSMS($to, $msg) {
		$msg = str_replace(' ', '+', $msg);
		$return = @file_get_contents("http://127.0.0.1/sms_manager/send.php?to=$to&msg=$msg");
        return $return;
	}

    function logMessage($msg) {
        $this->log($msg, 'sms_logs');
    }
	
	function clearReceiver($to) {
		// clear the $to data
		$findme   = '+251'; // it starts with +251
		$pos = strpos($to, $findme);
		if($pos !== false && $pos == 0) {
			$to = substr($to, 1);
		}
		$findme   = '2510'; // it starts with 2510
		$pos = strpos($to, $findme);
		if($pos !== false && $pos == 0) {
			$to = '2519' . substr($to, 5);
		}
		$findme   = '09'; // it starts with 09
		$pos = strpos($to, $findme);
		if($pos !== false && $pos == 0 && substr($to, 0, 3) != '251') {
			$to = '2519' . substr($to, 2);
		}

		$findme   = '9'; // it starts with 9
		$pos = strpos($to, $findme);
		if($pos !== false && $pos == 0 && substr($to, 0, 3) != '251') {
			$to = '2519' . substr($to, 1);
		}
		return $to;
	}
	
    function index() {
        
    }

    function search() {
        
    }

    function list_data() {
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
        $this->set('text_message', $this->TextMessage->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->TextMessage->create();
            $this->autoRender = false;
            $this->data['TextMessage']['receiver'] = $this->clearReceiver($this->data['TextMessage']['receiver']);
            $this->data['TextMessage']['status'] = 'N';
            if ($this->TextMessage->save($this->data)) {
                if(isset($this->data['TextMessage']['send_automatically'])) {
                    $ret = $this->send_text_message($this->TextMessage->id);
                    if($ret == 'OK'){
                        //$this->TextMessage->set('status', 'S');
                        //$this->TextMessage->save();
                    } else {
                        $this->TextMessage->set('status', 'F');
                        $this->TextMessage->set('remark', $ret);
                        $this->TextMessage->save();
                    }
                }
                $this->Session->setFlash(__('The text message has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The text message could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function edit($id = null) {
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
        $this->set('text_message', $this->TextMessage->read(null, $id));
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
                $this->Session->setFlash(__('Text message was not deleted', true) . $e->getMessage(), '');
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