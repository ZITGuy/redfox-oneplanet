<?php
class TextMessage extends AppModel {
	var $name = 'TextMessage';
	var $displayField = 'receiver';
	
	var $validate = array(
		'receiver' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'status' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	function SendMessages() {
		$msg = file_get_contents('http://127.0.0.1/redfox/text_messages/call_sms_messages');
		$this->log($msg, 'debug');
		return $msg;
	}

	function queueSMSMessage($to = '', $msg = '') {
		$text_message = array('TextMessage' => array(
				'receiver' => $to,
				'message' => $msg,
				'status' => 'N',
				'remark' => '-'
			));

		$this->create();
		if($this->save($text_message)) {
			return true;
		} else {
			return false;
		}
	}
}
