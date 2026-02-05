<?php
/* AcctCategory Fixture generated on: 2014-05-02 08:05:53 : 1399020053 */
class AcctCategoryFixture extends CakeTestFixture {
	var $name = 'AcctCategory';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'prefix' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20),
		'code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'postfix' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20),
		'last_code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'prefix' => 'Lorem ipsum dolor ',
			'code' => 'Lorem ipsum dolor sit amet',
			'postfix' => 'Lorem ipsum dolor ',
			'last_code' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-05-02 08:40:53',
			'modified' => '2014-05-02 08:40:53'
		),
	);
}
?>