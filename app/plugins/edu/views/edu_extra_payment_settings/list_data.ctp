{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_extra_payment_settings as $edu_extra_payment_setting){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_extra_payment_setting['EduExtraPaymentSetting']['id']; ?>",
				"name":"<?php echo $edu_extra_payment_setting['EduExtraPaymentSetting']['name']; ?>",
				"edu_class":"<?php echo $edu_extra_payment_setting['EduClass']['name']; ?>",
				"edu_extra_payment_type":"<?php echo $edu_extra_payment_setting['EduExtraPaymentType']['name']; ?>",
				"amount":"<?php echo $edu_extra_payment_setting['EduExtraPaymentSetting']['amount']; ?>",
				"edu_academic_year":"<?php echo $edu_extra_payment_setting['EduAcademicYear']['name']; ?>"			}
<?php $st = true; } ?>		]
}