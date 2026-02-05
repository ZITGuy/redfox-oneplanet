<?php
/* AccountingAccounts Test cases generated on: 2014-04-30 23:04:21 : 1398901701*/
App::import('Controller', 'Accounting.AccountingAccounts');

class TestAccountingAccountsController extends AccountingAccountsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class AccountingAccountsControllerTestCase extends CakeTestCase {
	function startTest() {
		$this->AccountingAccounts =& new TestAccountingAccountsController();
		$this->AccountingAccounts->constructClasses();
	}

	function endTest() {
		unset($this->AccountingAccounts);
		ClassRegistry::flush();
	}

}
?>