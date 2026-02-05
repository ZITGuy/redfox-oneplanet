{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php $st = false; foreach($edu_registrations as $reg){     if ($st) { echo ",";} ?>  {
<?php $today = time(); $dt = strtotime($reg['EduStudent']['birth_date']); $diff = $today - $dt;
      $days = $diff / (60 * 60 * 24);
      $age = $days / 365; 
?>
        "id": "<?php echo $reg['EduRegistration']['id']; ?>",
        "name": "<?php echo $reg['EduStudent']['name']; ?>",
        "identity_number": "<?php echo $reg['EduStudent']['identity_number']; ?>",
        "gender": "<?php echo $reg['EduStudent']['gender'] == 'F'? 'Female': 'Male'; ?>",
        "age": "<?php echo number_format($age, 1); ?>",
        "section": "<?php echo $reg['EduSection']['name'] . ' - ' . $reg['EduClass']['name']; ?>"			}
<?php $st = true; } ?>		]
}
