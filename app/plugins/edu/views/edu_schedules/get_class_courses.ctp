//<script>
<?php for ($i = 1; $i <= $num_periods; $i++) { ?>
    document.getElementById("courseCombo<?php echo $i; ?>").innerHTML=
        '<select name="courses<?php echo $i; ?>" id="courses<?php echo $i; ?>" style="display:none;"><option value="-">-</option>
        <?Php
    foreach ($courses as $course){
        $p = $course['EduSubject']['name'];
        echo '<option value="'.$p.'">'.$p.'</option>';
    }
    ?></select>';
    cm.setEditor(<?php echo $i; ?>, new fm.ComboBox({
            triggerAction: 'all',
            forceSelection: true,
            transform: 'courses<?php echo $i; ?>',
            lazyRender: true,
            listClass: 'x-combo-list-small'
        }));
<?php } ?>
