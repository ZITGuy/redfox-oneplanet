{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php $st = false; foreach($periods as $period){ if($st) echo ","; ?>			{
            "id":"<?php echo $period['id']; ?>",
            "day":"<b><?php echo $period['day']; ?></b>" 
            <?php for($i = 1; $i <= $num_periods; $i++) { echo ","; ?>
            "period<?php echo $i; ?>":"<?php echo $period['period'.$i]['subject']; ?>"
            <?php } ?>	}
<?php $st = true; echo ","; ?>			{
            "id":"<?php echo $period['id'] + 5; ?>",
            "day":"<?php echo '<b>Asig. Teacher</b>'; ?>" 
            <?php for($i = 1; $i <= $num_periods; $i++) { echo ","; ?>
            "period<?php echo $i; ?>":"<?php echo $period['period'.$i]['teacher']; ?>"
            <?php } ?>	}	
<?php } ?>		]
}
