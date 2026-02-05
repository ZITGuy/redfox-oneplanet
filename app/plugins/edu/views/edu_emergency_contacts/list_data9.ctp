{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php 
    $st = false; 
    foreach($emergency_contacts as $k => $emergency_contact){ if($st) {echo ",";}  
?>			{
            "id":"<?php echo $k; ?>",
            "first_name":"<?php echo $emergency_contact['first_name']; ?>",
            "middle_name":"<?php echo $emergency_contact['middle_name']; ?>",
            "last_name":"<?php echo $emergency_contact['last_name']; ?>",
            "relationship":"<?php echo $emergency_contact['relationship']; ?>",
            "phone_number":"<?php echo $emergency_contact['phone_number']; ?>"			}
<?php $st = true; } ?>		]
}