<?php $family_types = array('M' => 'MOTHER', 'F' => 'FATHER', 'G' => 'GUARDIAN'); ?>
{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_parent_details as $edu_parent_detail){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_parent_detail['EduParentDetail']['id']; ?>",
				"short_name":"<?php echo $edu_parent_detail['EduParentDetail']['short_name']; ?>",
				"first_name":"<?php echo $edu_parent_detail['EduParentDetail']['first_name']; ?>",
				"middle_name":"<?php echo $edu_parent_detail['EduParentDetail']['middle_name']; ?>",
				"last_name":"<?php echo $edu_parent_detail['EduParentDetail']['last_name']; ?>",
				"residence_address":"<?php echo $edu_parent_detail['EduParentDetail']['residence_address']; ?>",
				"nationality":"<?php echo $edu_parent_detail['EduParentDetail']['nationality']; ?>",
				"relationship":"<?php echo $edu_parent_detail['EduParentDetail']['relationship']; ?>",
				"occupation":"<?php echo $edu_parent_detail['EduParentDetail']['occupation']; ?>",
				"academic_qualification":"<?php echo $edu_parent_detail['EduParentDetail']['academic_qualification']; ?>",
				"employment_status":"<?php echo $edu_parent_detail['EduParentDetail']['employment_status']; ?>",
				"employer":"<?php echo $edu_parent_detail['EduParentDetail']['employer']; ?>",
				"work_address":"<?php echo $edu_parent_detail['EduParentDetail']['work_address']; ?>",
				"work_telephone":"<?php echo $edu_parent_detail['EduParentDetail']['work_telephone']; ?>",
				"mobile":"<?php echo $edu_parent_detail['EduParentDetail']['mobile']; ?>",
				"email":"<?php echo $edu_parent_detail['EduParentDetail']['email']; ?>",
				"photo_file":"<?php echo $edu_parent_detail['EduParentDetail']['photo_file']; ?>",
				"family_type":"<?php echo $family_types[$edu_parent_detail['EduParentDetail']['family_type']]; ?>",
				"edu_parent":"<?php echo $edu_parent_detail['EduParent']['id']; ?>",
				"created":"<?php echo $edu_parent_detail['EduParentDetail']['created']; ?>",
				"modified":"<?php echo $edu_parent_detail['EduParentDetail']['modified']; ?>"			}
<?php $st = true; } ?>		]
}