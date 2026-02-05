<?php
class MessengerTask extends Shell {
	var $uses = array('TextMessage'); // same as controller var $uses
	
	function execute() {
		$this->out("Welcome to the RedFox SMS Messenger Service\n");
		$this->out("Running the SMS Messenger task.\n");
		
		$this->TextMessage->SendMessages();
		
		$this->out("SMS Messenger Task completed.\n");
	}
}
