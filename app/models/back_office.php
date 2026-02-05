<?php
class BackOffice extends AppModel {
	var $name = 'BackOffice';
	var $useTable = false;

	function RunPortalIntegration() {
		$msg = file_get_contents('http://127.0.0.1/redfox/back_office/call_integrate_portal');
		$this->log($msg, 'debug');
		return $msg;
	}
}
