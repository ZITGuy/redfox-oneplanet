//<script>
	var cont = '<select name="evaluation_values" id="evaluation_values" style="display:none;">';
	<?php foreach ($edu_evaluation_values as $gl) { ?>
		cont += '<option value="<?php
			echo $gl['EduEvaluationValue']['description']; ?>"><?php echo $gl['EduEvaluationValue']['description']; ?></option>';
	<?php } ?>
	cont += '</select>';
	document.getElementById("EvaluationValueHtmlCombo").innerHTML = cont;