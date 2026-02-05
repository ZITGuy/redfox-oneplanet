{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_class_payments as $edu_class_payment){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_class_payment['EduClassPayment']['id']; ?>",
				"class_order":"<?php echo $edu_class_payment['EduClass']['cvalue']; ?>",
				"edu_class":"<?php echo $edu_class_payment['EduClass']['name']; ?>",
				"edu_academic_year":"<?php echo $edu_class_payment['EduAcademicYear']['name']; ?>",
				"enrollment_fee":"<?php echo $edu_class_payment['EduClassPayment']['enrollment_fee']; ?>",
				"registration_fee":"<?php echo $edu_class_payment['EduClassPayment']['registration_fee']; ?>",
				"tuition_fee":"<?php echo $edu_class_payment['EduClassPayment']['tuition_fee']; ?>",
				"created":"<?php echo $edu_class_payment['EduClassPayment']['created']; ?>",
				"modified":"<?php echo $edu_class_payment['EduClassPayment']['modified']; ?>"			}
<?php $st = true; } ?>		]
}