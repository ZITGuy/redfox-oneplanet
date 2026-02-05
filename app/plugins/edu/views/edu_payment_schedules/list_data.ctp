<?php 
$months = array(1 => '01 - September', 2 => '02 - October',3 => '03 - November',4 => '04 - December',5 => '05 - January',
				6 => '06 - February',7 => '07 - March',8 => '08 - April',9 => '09 - May',
				10 => '10 - June',11 => '11 - July',12 => '12 - August'); 
$quarters = array(1 => 'Term 1', 2 => 'Term 2',3 => 'Term 3',4 => 'Term 4',
                                5 => 'Summer');
?>
{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php $st = false; foreach($edu_payment_schedules as $edu_payment_schedule){ if($st) echo ","; ?>			{
        "id":"<?php echo $edu_payment_schedule['EduPaymentSchedule']['id']; ?>",
        "month":"<?php echo ($payment_schedule_method == 'M')? $months[$edu_payment_schedule['EduPaymentSchedule']['month']]: $quarters[$edu_payment_schedule['EduPaymentSchedule']['month']]; ?>",
        "edu_class":"<?php echo $edu_payment_schedule['EduClass']['name']; ?>",
        "amount":"<?php echo $edu_payment_schedule['EduPaymentSchedule']['amount']; ?>",
        "edu_academic_year":"<?php echo $edu_payment_schedule['EduAcademicYear']['name']; ?>",
        "due_date":"<?php echo $edu_payment_schedule['EduPaymentSchedule']['due_date']; ?>"		}
<?php $st = true; } ?>		]
}