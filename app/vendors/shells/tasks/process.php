<?php
class ProcessTask extends Shell {
	var $uses = array('User'); // same as controller var $uses
   
	function execute() {
		$this->out("Welcome to the Process Task\n");
		
		
	}
}
