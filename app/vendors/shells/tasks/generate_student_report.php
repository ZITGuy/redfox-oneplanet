<?php
class GenerateStudentReportTask extends Shell {
	var $uses = array('BackOffice'); // same as controller var $uses
	
	function execute() {
		$this->out("Welcome to the RedFox Generate Achievement Report Service\n");
		$this->out("Running the Generate Achievement Report task.\n");
		
		$this->BackOffice->RunGenerateAchievementReport();
		
		$this->out("Generate Achievement Report Task completed.\n");
	}
}
