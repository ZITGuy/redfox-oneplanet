<?php
/* AcctCategory Test cases generated on: 2014-05-02 08:05:53 : 1399020053*/
App::import('Model', 'Acct.AcctCategory');

class AcctCategoryTestCase extends CakeTestCase {
	function startTest() {
		$this->AcctCategory =& ClassRegistry::init('AcctCategory');
	}

	function endTest() {
		unset($this->AcctCategory);
		ClassRegistry::flush();
	}

}
?>