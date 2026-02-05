{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_payments as $edu_payment){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_payment['EduPayment']['id']; ?>",
				"edu_payment_schedule":"<?php echo $edu_payment['EduPaymentSchedule']['id']; ?>",
				"edu_student":"<?php echo $edu_payment['EduStudent']['name']; ?>",
				"is_paid":"<?php echo $edu_payment['EduPayment']['is_paid']; ?>",
				"date_paid":"<?php echo $edu_payment['EduPayment']['date_paid']; ?>",
				"paid_amount":"<?php echo $edu_payment['EduPayment']['paid_amount']; ?>",
				"cheque_number":"<?php echo $edu_payment['EduPayment']['cheque_number']; ?>",
				"invoice":"<?php echo $edu_payment['EduPayment']['invoice']; ?>",
				"transaction_ref":"<?php echo $edu_payment['EduPayment']['transaction_ref']; ?>",
				"created":"<?php echo $edu_payment['EduPayment']['created']; ?>",
				"modified":"<?php echo $edu_payment['EduPayment']['modified']; ?>"			}
<?php $st = true; } ?>		]
}