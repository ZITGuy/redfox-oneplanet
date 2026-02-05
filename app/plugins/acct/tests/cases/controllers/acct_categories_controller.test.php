<?php
/* AcctCategories Test cases generated on: 2014-05-02 09:05:22 : 1399021462*/
App::import('Controller', 'Acct.AcctCategories');

class TestAcctCategoriesController extends AcctCategoriesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class AcctCategoriesControllerTestCase extends CakeTestCase {
	function startTest() {
		$this->AcctCategories =& new TestAcctCategoriesController();
		$this->AcctCategories->constructClasses();
	}

	function endTest() {
		unset($this->AcctCategories);
		ClassRegistry::flush();
	}

}
?>