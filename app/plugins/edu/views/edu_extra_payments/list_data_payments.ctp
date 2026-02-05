{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_extra_payments as $edu_payment){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_payment['EduExtraPayment']['id']; ?>",
				"name":"<?php echo $edu_payment['EduExtraPaymentSetting']['name']; ?>",
				"amount":"<?php echo $edu_payment['EduExtraPaymentSetting']['amount']; ?>",
				"student_name":"<?php echo $edu_payment['EduStudent']['name']; ?>",
				"is_paid":<?php echo $edu_payment['EduExtraPayment']['is_paid']? 'true': 'false'; ?>			}
<?php $st = true; } ?>		
	]
}