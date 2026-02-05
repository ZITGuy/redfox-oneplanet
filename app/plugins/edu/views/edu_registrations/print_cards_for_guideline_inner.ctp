<?php

$html = '
<style>
    .promoted { 
        color: #00CC00;
        font-size: 22pt;
        font-weight: bold;
		text-rotate: 45;
    }
    .not_promoted { 
        color: #CC0000;
        font-size: 22pt;
        font-weight: bold;
		text-rotate: 45;
    }
</style>';

$quarters = array();

foreach ($registrations[0]['EduRegistrationQuarter'] as $erq) {
    $quarters[$erq['edu_quarter_id']] = $erq['EduQuarter']['short_name'];
}

// for each students
foreach($registrations as $registration) {
	// construct the evaluation results table
	$identity_number = $registration['EduStudent']['identity_number'];
	$p_image = ($registration['EduRegistration']['status'] == 'P'? 'promoted': ($registration['EduRegistration']['status'] == 'N'? 'not-promoted': 'not-available'));
	$p_image .= '-' . strtolower($registration['EduStudent']['gender']) . '.png';

	$evaluation_result = '
	<div width="100%" height="100%">
	<font color="#ccc" size="2">' . $identity_number . '</font>
	<table border="1" width="100%"> 
		<thead>
			<tr>
				<td width="60%">&nbsp;</td>';
	foreach ($quarters as $k => $v){
		$evaluation_result .= '<td>' . $v . '</td>';
    }
	$evaluation_result .= '</tr></thead><tbody>';
	
	foreach($categories as $category) {
		$count = 0;
		foreach($evaluations as $evaluation) {
			if($category['EduEvaluationCategory']['id'] == $evaluation['EduEvaluationArea']['edu_evaluation_category_id']) {
				$count++;
				break;
			}
		}
		if($count == 0){
			continue;
		}
		$evaluation_result .= '<tr><td colspan=' . (count($quarters) + 1) . ' bgcolor=gray align="center"><em><strong>' . $category['EduEvaluationCategory']['name'] . '</strong></em></td></tr>';
		foreach($evaluations as $evaluation) {
			if($category['EduEvaluationCategory']['id'] == $evaluation['EduEvaluationArea']['edu_evaluation_category_id']) {
				$evaluation_result .= '<tr><td>' . $evaluation['EduEvaluationArea']['name'] . '</td>';
				
				foreach ($quarters as $k => $v){
					$found = false;
					foreach($registration_evaluations as $reg_eval) {
						if($reg_eval['EduRegistration']['edu_student_id'] == $registration['EduStudent']['id'] && 
								$reg_eval['EduRegistrationEvaluation']['edu_quarter_id'] == $k && 
								$reg_eval['EduRegistrationEvaluation']['edu_evaluation_id'] == $evaluation['EduEvaluation']['id']) {
							$evaluation_result .= '<td>' . $reg_eval['EduEvaluationValue']['name'] . '</td>';
							$found = true;
							break;
						}
					}
					if(!$found)
						$evaluation_result .= '<td>-</td>';
				}
				$evaluation_result .= '</tr>';
			}
		}
	}
	
	$evaluation_result .= '</tbody></table>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <img align="right" src="http://' . Configure::read('domain') . Configure::read('localhost_string') . '/img/promotion/' . $p_image . '" />';

	$evaluation_result .= '</div>';
	
    $html .= $evaluation_result;
}

echo $html;

?>