{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_receipts as $edu_receipt){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_receipt['EduReceipt']['id']; ?>",
				"reference_number":"<?php echo $edu_receipt['EduReceipt']['reference_number']; ?>",
				"invoice_date":"<?php echo $edu_receipt['EduReceipt']['invoice_date']; ?>",
				"crm_number":"<?php echo $edu_receipt['EduReceipt']['crm_number']; ?>",
				"parent_name":"<?php echo $edu_receipt['EduReceipt']['parent_name']; ?>",
				"parent_address":"<?php echo $edu_receipt['EduReceipt']['parent_address']; ?>",
				"edu_student":"<?php echo $edu_receipt['EduStudent']['name']; ?>",
				"student_name":"<?php echo $edu_receipt['EduReceipt']['student_name']; ?>",
				"student_number":"<?php echo $edu_receipt['EduReceipt']['student_number']; ?>",
				"student_class":"<?php echo $edu_receipt['EduReceipt']['student_class']; ?>",
				"student_section":"<?php echo $edu_receipt['EduReceipt']['student_section']; ?>",
				"student_academic_year":"<?php echo $edu_receipt['EduReceipt']['student_academic_year']; ?>",
				"total_before_tax":"<?php echo $edu_receipt['EduReceipt']['total_before_tax']; ?>",
				"total_after_tax":"<?php echo $edu_receipt['EduReceipt']['total_after_tax']; ?>",
				"VAT":"<?php echo $edu_receipt['EduReceipt']['VAT']; ?>",
				"TOT":"<?php echo $edu_receipt['EduReceipt']['TOT']; ?>",
				"created":"<?php echo $edu_receipt['EduReceipt']['created']; ?>",
				"modified":"<?php echo $edu_receipt['EduReceipt']['modified']; ?>"			}
<?php $st = true; } ?>		]
}