{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php 
    $st = false; 
	
	$marital_statuses = array('S' => 'Single', 'M' => 'Married', 'D' => 'Divorsed', 'W' => 'Widowed', 'P' => 'Separated');
	$primary_parents = array('M' => 'Mother', 'F' => 'Father', 'G' => 'Guardian');
    foreach($edu_parents as $edu_parent){ 
		$pd_names = "";
		if(count($edu_parent['EduParentDetail']) > 0){
			foreach($edu_parent['EduParentDetail'] as $pd){
				$pd_names .= $pd['first_name'] . ", ";
			}
			if($pd_names != ""){
				$pd_names = substr($pd_names, 0, strlen($pd_names) - 2);
			}
		}
		
		$authorized_person = ($pd_names == "")? '<font color=red><i>NO PARENT DETAIL</i></font>': $pd_names;
		if($st) {echo ",";}  
?>			{
            "id":"<?php echo $edu_parent['EduParent']['id']; ?>",
            "authorized_person":"<?php echo $authorized_person; ?>",
            "marital_status":"<?php echo $marital_statuses[$edu_parent['EduParent']['marital_status']]; ?>",
            "primary_parent":"<?php echo $primary_parents[$edu_parent['EduParent']['primary_parent']]; ?>",
            "secret_code":"<?php echo $edu_parent['EduParent']['secret_code']; ?>",
            "sms_phone_number":"<?php echo $edu_parent['EduParent']['sms_phone_number']; ?>",
			"portal_record": "<?php echo $edu_parent['EduParent']['portal_record_id'] == '-'? '<font color=red>Not Published</font>': '<font color=green>Published</font>'; ?>",
            "created":"<?php echo date('F d, Y', strtotime($edu_parent['EduParent']['created'])); ?>",
            "modified":"<?php echo date('F d, Y', strtotime($edu_parent['EduParent']['modified'])); ?>"			}
<?php $st = true; } ?>		]
}