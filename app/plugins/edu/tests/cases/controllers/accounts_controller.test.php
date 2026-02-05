<?php
/* Accounts Test cases generated on: 2014-04-30 23:04:31 : 1398901591*/
App::import('Controller', 'Education.Accounts');

class TestAccountsController extends AccountsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class AccountsControllerTestCase extends CakeTestCase {
	function startTest() {
		$this->Accounts =& new TestAccountsController();
		$this->Accounts->constructClasses();
	}

	function endTest() {
		unset($this->Accounts);
		ClassRegistry::flush();
	}

}
?>