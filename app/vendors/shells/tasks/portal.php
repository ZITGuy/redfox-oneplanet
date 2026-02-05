<?php
class PortalTask extends Shell {
	var $uses = array('BackOffice'); // same as controller var $uses
	
	function execute() {
		$this->out("Welcome to the RedFox Portal Integration Service\n");
		$this->out("Running the Portal Integration task.\n");
		
		$this->BackOffice->RunPortalIntegration();
		
		$this->out("Portal Integration Task completed.\n");
	}
}
