<?php
class Supervisor extends HrAppModel {
	var $name = 'Supervisor';
	var $useTable = 'hr_supervisors';
	var $belongsTo = array(
		'SupEmployee' => array(
			'className' => 'Employee',
			'foreignKey' => 'sup_emp_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'EmpEmployee' => array(
			'className' => 'Employee',
			'foreignKey' => 'emp_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


}
?>