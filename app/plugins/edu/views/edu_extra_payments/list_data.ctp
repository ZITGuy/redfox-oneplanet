{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_extra_payments as $edu_extra_payment){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_extra_payment['EduExtraPayment']['id']; ?>",
				"edu_extra_payment_setting":"<?php echo $edu_extra_payment['EduExtraPaymentSetting']['name']; ?>",
				"edu_student":"<?php echo $edu_extra_payment['EduStudent']['name']; ?>",
				"is_paid":"<?php echo $edu_extra_payment['EduExtraPayment']['is_paid']; ?>",
				"date_paid":"<?php echo $edu_extra_payment['EduExtraPayment']['date_paid']; ?>",
				"paid_amount":"<?php echo $edu_extra_payment['EduExtraPayment']['paid_amount']; ?>",
				"cheque_number":"<?php echo $edu_extra_payment['EduExtraPayment']['cheque_number']; ?>",
				"cheque_amount":"<?php echo $edu_extra_payment['EduExtraPayment']['cheque_amount']; ?>",
				"invoice":"<?php echo $edu_extra_payment['EduExtraPayment']['invoice']; ?>",
				"transaction_ref":"<?php echo $edu_extra_payment['EduExtraPayment']['transaction_ref']; ?>",
				"created":"<?php echo $edu_extra_payment['EduExtraPayment']['created']; ?>",
				"modified":"<?php echo $edu_extra_payment['EduExtraPayment']['modified']; ?>"			}
<?php $st = true; } ?>		]
}