<?php

	function getOrdinalForm($number) {
		$ords = array(0 => 'th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
		$o = $ords[substr($number, strlen($number) - 1)]; 
		return $o;
	}

	function ordinal($num){
		// Special case "teenth"
		if ( ($num / 10) % 10 != 1 ){
			// Handle 1st, 2nd, 3rd
			switch( $num % 10 ){
				case 1: return $num . 'st';
				case 2: return $num . 'nd';
				case 3: return $num . 'rd'; 
			}
		}
		// Everything else is "nth"
		return $num . 'th';
	}

$html = '
<style>
    body {
        font-family: sans-serif;
		font-size: 12pt;
    }
    @media screen, print {
        page-break-after: hr;
    }
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
$quarter_totals = array();
$q_headers = '<tr>';
$q_headers .= '<td width="200px" align="center"><strong>Subject</strong></td>';

foreach ($registrations[0]['EduRegistrationQuarter'] as $erq) {
    $quarters[$erq['edu_quarter_id']] = $erq['EduQuarter']['name'];

    $q_headers .= '<td width="12%" align="center"><strong>' . $erq['EduQuarter']['name'] . '</strong></td>';
}

$q_headers .= '<td width="12%" align="center"><strong>AV</strong></td>';
$q_headers .= '</tr>';

$class = $section['EduClass']['name'];
$section_name = $section['EduSection']['name'];

$count = 0;
// for each students registrations
foreach($registrations as $registration) {
    // prepare student related basic info
    $full_name = $registration['EduRegistration']['name'];
    $identity_number = $registration['EduStudent']['identity_number'];
    // prepare the table before printing
    $coures_quarters = array();
    //if($count++ > 1){
	//	break;
	//}
    $total_quarter_average = 0;
    $quarter_averages = array();
    $quarter_ranks = array();
    $class_ranks = array();
    $quarter_totals = array();
    $absentees = array();
    // develop card for a student (for each courses)
    foreach($registration['EduRegistrationQuarter'] as $rq) {
		//pr($rq);
        foreach ($rq['EduRegistrationQuarterResult'] as $rqr) {
            if(!isset($coures_quarters[$rqr['edu_course_id']])){
				if(isset($courses[$rqr['edu_course_id']])){
					$coures_quarters[$rqr['edu_course_id']] = array(
						'course' => $courses[$rqr['edu_course_id']],
						'q' . $rq['edu_quarter_id'] => ($rqr['scale_result'] != '-')? $rqr['scale_result'] . '<sup>' . $rqr['course_result'] . '</sup>': $rqr['course_result'],
						'cr' . $rq['edu_quarter_id'] => $rqr['course_rank'],
						'ri' . $rq['edu_quarter_id'] => $rqr['result_indicator'],
						'av' => '-',
						'avs' => '-'
					);
				}
            } else {
                $coures_quarters[$rqr['edu_course_id']]['cr' . $rq['edu_quarter_id']] = $rqr['course_rank'];
                $coures_quarters[$rqr['edu_course_id']]['q' . $rq['edu_quarter_id']] = (($rqr['course_result'] == '0.00')? $rqr['scale_result']: $rqr['course_result']);
                $coures_quarters[$rqr['edu_course_id']]['ri' . $rq['edu_quarter_id']] = $rqr['result_indicator'];
            }
			/*
            if(!isset($quarter_totals[$rq['edu_quarter_id']])){
                $quarter_totals[$rq['edu_quarter_id']] = ($rqr['course_result'] == '-'? 0: $rqr['course_result']);
            } else {
                $quarter_totals[$rq['edu_quarter_id']] += ($rqr['course_result'] == '-'? 0: $rqr['course_result']);
            }*/
        }
		$quarter_totals[$rq['edu_quarter_id']] = $rq['quarter_total'];
        $quarter_averages[$rq['edu_quarter_id']] = $rq['quarter_average'];
		if($rq['quarter_average'] == 0 || $rq['quarter_average'] == '-'){
			$total_quarter_average = 0;
		} else {
			$total_quarter_average += $rq['quarter_average'];
		}
        
        $quarter_ranks[$rq['edu_quarter_id']] = $rq['quarter_rank'];
        $class_ranks[$rq['edu_quarter_id']] = $rq['class_rank'];
        $absentees[$rq['edu_quarter_id']] = $rq['absentees'];
    }

    foreach ($registration['EduRegistrationResult'] as $rr) {
        $coures_quarters[$rr['edu_course_id']]['av'] = $rr['average'];
		$coures_quarters[$rr['edu_course_id']]['scale_av'] = $rr['scale_result'];
        $coures_quarters[$rr['edu_course_id']]['avs'] = $rr['status'];
    }
    
    $results = '';  
    // print each of the marks in the quarters (actually in $q_marks)
    $is_final_quarter = (count($registration['EduRegistrationResult']) > 0);
	//pr($coures_quarters);
    foreach ($coures_quarters as $cq) {
        $results .= '<tr><td align="left">' . $cq['course'] . '</td>';
        foreach($quarters as $k => $v){
            //$results .= pr($section, true);
			if(isset($cq['cr' . $k])){
				$this_course_rank = ($cq['cr' . $k] == 0 || $section['EduClass']['rank_display'] == 'N'? '': ($section['EduClass']['rank_display'] == 'U'? ($cq['cr' . $k] > $section['EduClass']['rank_display_upto']? '': '<sup>' . ordinal($cq['cr' . $k]) . '</sup>'): '<sup>' . ordinal($cq['cr' . $k]) . '</sup>'));
			} else {
				$this_course_rank = '';
			}
            if(isset($cq['q' . $k])){
				$results .= '<td align="center">' . ($cq['q' . $k] == '0'? '-': '<font color=' . ($cq['ri' . $k] == 'P'? 'green': 'red') . '>' . $cq['q' . $k] . $this_course_rank . ' </font>') . '</td>';
			} else {
				$results .= '<td align="center">-</font></td>';
			}
	    }
		$av_text = '';
		if(isset($cq['scale_av']) && $cq['scale_av'] != '-'){
			$av_text = $cq['scale_av'];
			$cq['av'] = '-';
		} else {
			$av_text = (((isset($cq['av']) && $cq['av'] == '-') || (isset($cq['av']) && $cq['av'] == 0))? '-': '<font color=' . ($cq['avs'] == 'P'? 'green': 'red') . '>' . $cq['av'] . '</font>');
		}
        $results .= '<td align="left">' . $av_text . '</td>';
        $results .= '</tr>';
    }
    
    $c = count($coures_quarters);
    while ($c < 20) {
        // Blank
        $results .= '<tr><td>&nbsp;</td>';
        for($i = 0; $i < count($quarters); $i++) {
            $results .= '<td align="center">&nbsp;</td>';
        }
        $results .= '<td align="center">&nbsp;</td>';
        $results .= '</tr>';
        $c++;
    }
	
    // Total
	$results .= '<tr><td>Total</td>';
    foreach ($quarters as $k => $v){
        $results .= '<td align="center">' . ($quarter_totals[$k] == 0? '-': $quarter_totals[$k]) . '</td>';
    }
	$results .= '<td align="center">' . ($total_quarter_average == 0? '-': $total_quarter_average) . '</td>';
    $results .= '</tr>';
	
    // Average
    $results .= '<tr><td>Average</td>';
    foreach ($quarters as $k => $v){
        $results .= '<td align="center">' . ($quarter_averages[$k] == 0? '-': $quarter_averages[$k]) . '</td>';
    }
    $gta = $registration['EduRegistration']['grand_total_average'];
    $results .= '<td align="center">' . ($gta == 0? '-': $gta) . '</td>';
	$results .= '</tr>';
	
    $gstatus = $registration['EduRegistration']['status_id'];
    $p_image = ($gstatus == 13? 'promoted': ($gstatus == 14? 'not-promoted': 'not-available'));
	$p_image .= '-' . strtolower($registration['EduStudent']['gender']) . '.png';
    
	foreach($evaluations as $evaluation) {
		if(18 == $evaluation['EduEvaluationArea']['edu_evaluation_category_id']) {
			$results .= '<tr><td>' . $evaluation['EduEvaluationArea']['name'] . '</td>';
			foreach ($quarters as $k => $v){
				$found = false;
				foreach($registration_evaluations as $reg_eval) {
					if($reg_eval['EduRegistration']['edu_student_id'] == $registration['EduStudent']['id'] && 
							$reg_eval['EduRegistrationEvaluation']['edu_quarter_id'] == $k && 
							$reg_eval['EduRegistrationEvaluation']['edu_evaluation_id'] == $evaluation['EduEvaluation']['id']) {
						$results .= '<td align="center">' . $reg_eval['EduEvaluationValue']['name'] . '</td>';
						$found = true;
						break;
					}
				}
				if(!$found)
					$results .= '<td align="center">-</td>';
			}
			$results .= '<td align="center">-</td>';
			$results .= '</tr>';
		}
	}
	
    // Rank (Class/Section)
    $results .= '<tr><td>Class Rank</td>';
    foreach ($quarters as $k => $v){
        $results .= '<td align="center">' . ($quarter_ranks[$k] == 0 || $section['EduClass']['rank_display'] == 'N'? '-': ($section['EduClass']['rank_display'] == 'U'? ($quarter_ranks[$k] > $section['EduClass']['rank_display_upto']? '-': $quarter_ranks[$k]): $quarter_ranks[$k])) . '</td>';
    }
    $crank = $registration['EduRegistration']['rank'];
    $results .= '<td align="center">' . ($crank == 0 || $section['EduClass']['rank_display'] == 'N'? '-': ($section['EduClass']['rank_display'] == 'U'? ($crank > $section['EduClass']['rank_display_upto']? '-': $crank): $crank)) . '</td>';
    $results .= '</tr>';
    
    // Rank (All Section)
    $results .= '<tr><td>All Section Rank</td>';
    foreach ($quarters as $k => $v){
        $results .= '<td align="center">' . ($class_ranks[$k] == 0 || $section['EduClass']['rank_display'] == 'N'? '-': ($section['EduClass']['rank_display'] == 'U'? ($class_ranks[$k] > $section['EduClass']['rank_display_upto']? '-': $class_ranks[$k]): $class_ranks[$k])) . '</td>';
    }
    $all_sec_rank = $registration['EduRegistration']['class_rank'];
    $results .= '<td align="center">' . ($all_sec_rank == 0 || $section['EduClass']['rank_display'] == 'N'? '-': ($section['EduClass']['rank_display'] == 'U'? ($all_sec_rank > $section['EduClass']['rank_display_upto']? '-': $all_sec_rank): $all_sec_rank)) . '</td>';
    $results .= '</tr>';
    

	// Number of Students
	$results .= '<tr><td>Number of Students</td>';
	// # of students for the average column is equal to the last quarters #of students
    $no_of_students = 0;
    foreach ($quarters as $k => $v){
		$no_of_st = ($quarter_averages[$k] == 0? '-': count($section['EduRegistration']));
        $results .= '<td align="center">' . $no_of_st . '</td>';
        $no_of_students = $no_of_st;
    }
	$results .= '<td align="center">' . $no_of_students . '</td>';
    $results .= '</tr>';
	

	// Absent Days
	$results .= '<tr><td>Absent Days</td>';
	$average_absent_days = 0;
	foreach ($quarters as $k => $v){
		$results .= '<td align="center">' . 
                ($absentees[$k] == 0? '-': 
                    $absentees[$k]) . '</td>';
		$average_absent_days += $absentees[$k];
    }
	//$results .= '<td align="center">' . ($average_absent_days / count($quarters)) . '</td>';
    $results .= '<td align="center">&nbsp;</td>';
    $results .= '</tr>';
	
	
	// construct the evaluation results table
	$evaluation_result = '
	<p><font size=2em>Principal Comment</font></p><br/>
	<p>________________________________________________________________</p>
	<br/>
	<p>________________________________________________________________</p>
	<br/><br/><br/><p><font size=2em>Homeroom Teacher Comment</font></p><br/>
	<p>________________________________________________________________</p>
	<br/>
	<p>________________________________________________________________</p>
	<br/><br/><br/><p><font size=2em>Parents Comment</font></p><br/>
	<p>________________________________________________________________</p>
	<br/>
	<p>________________________________________________________________</p>';
	
	
    $html .= '<div width="100%" height="100%">
    <table border="0" width="100%">
    <tbody>
        <tr>
            <td width="47%">
                <table width="100%">
                    <tbody>
                        <tr>
                            <td width="100%">
                                <table width="100%">
                                    <tbody> 
                                        <tr>
                                            <td><strong>Student Name:</strong></td>
                                            <td>' . $full_name . '</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Student ID:</strong></td>
                                            <td>' . $identity_number . '</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Class/Section:</strong></td>
                                            <td>' . $class . '/' . $section_name . '</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td width="100%">
                                <table border="1" width="100%">
                                    <thead>' . $q_headers . '</thead>
                                    <tbody>' . $results . '</tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td width="6%">
                
            </td>
            <td valign=top>
				<table width="100%">
					<tbody> 
						<tr>
							<td align=right><img src="http://' . Configure::read('domain') . Configure::read('localhost_string') . '/img/logo_cha.png" width=150px></td>
						</tr>
						<tr>
							<td><strong>&nbsp;</strong></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
					</tbody>
				</table>
                ' . $evaluation_result . '
                <img src="http://' . Configure::read('domain') . Configure::read('localhost_string') . '/img/promotion/' . $p_image . '" />
            </td>
        </tr>
    </tbody>
    </table>
    </div>';
}

echo $html;

?>