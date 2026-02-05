<?php 
$months = array(); 

if($payment_schedule_method == 'M'){
	$months = array(1 => 'September', 'October', 'November', 'December', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August');
} else {
	$i = 1;
	foreach ($quarters as $quarter) {
		$months[$i++] = $quarter['EduQuarter']['name'];
	}
}
 

?>
{
	success: true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_payments as $edu_payment){ if($st) echo ","; ?>			{
<?php $pymt = $edu_payment['amount'] - ($edu_payment['amount'] * ($registration['scholarship'] / 100)); ?> 
				"id": "<?php echo $edu_payment['EduPayment']['id']; ?>",
				"month":"<?php echo $months[$edu_payment['EduPaymentSchedule']['month']]; ?>",
				"amount":"<?php echo $pymt; ?>",
				"penalty":"<?php echo $pymt * ($edu_payment['penalty'] / 100); ?>",
				"sibling_discount":"<?php echo $pymt * ($edu_payment['sibling_discount'] / 100); ?>",
				"student_name":"<?php echo $edu_payment['EduStudent']['name']; ?>",
				"is_paid":<?php echo $edu_payment['EduPayment']['is_paid']? 'true': 'false'; ?>			}
<?php $st = true; } ?>
	]
}