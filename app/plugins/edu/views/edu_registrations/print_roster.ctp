<?php

$html_header = '<html><head>
<style>
    body { font-family: DejaVuSansCondensed; font-size: 9pt;  }
    p {     text-align: justify; margin-bottom: 4pt; margin-top:0pt;  }

    table {font-family: DejaVuSansCondensed; font-size: 9pt; line-height: 1.2;
        margin-top: 2pt; margin-bottom: 5pt;
        border-collapse: collapse;  }

    thead { font-weight: bold; vertical-align: bottom;  }
    tfoot { font-weight: bold; vertical-align: top; }
    thead td { font-weight: bold; }
    tfoot td { font-weight: bold; }

    thead td, thead th, tfoot td, tfoot th { font-variant: small-caps; }

    .headerrow td, .headerrow th { background-gradient: linear #b7cebd #f5f8f5 0 1 0 0.2;  }
    .footerrow td, .footerrow th { background-gradient: linear #b7cebd #f5f8f5 0 1 0 0.2;  }

    th {    font-weight: bold;
        vertical-align: top;
        text-align:left;
        padding-left: 2mm;
        padding-right: 2mm;
        padding-top: 0.5mm;
        padding-bottom: 0.5mm;
     }

    td {    padding-left: 2mm;
        vertical-align: top;
        text-align:left;
        padding-right: 2mm;
        padding-top: 0.5mm;
        padding-bottom: 0.5mm;
     }

    th p { text-align: left; margin:0pt;  }
    td p { text-align: left; margin:0pt;  }

    table.widecells td {
        padding-left: 5mm;
        padding-right: 5mm;
    }
    table.tallcells td {
        padding-top: 3mm;
        padding-bottom: 3mm;
    }

    hr {    width: 70%; height: 1px;
        text-align: center; color: #999999;
        margin-top: 8pt; margin-bottom: 8pt; }

    a { color: #000066; font-style: normal; text-decoration: underline;
        font-weight: normal; }

    ul {    text-indent: 5mm; margin-bottom: 9pt; }
    ol {    text-indent: 5mm; margin-bottom: 9pt; }

    pre { font-family: DejaVuSansMono; font-size: 9pt; margin-top: 5pt; margin-bottom: 5pt; }

    .breadcrumb {
        text-align: right; font-size: 8pt; font-family: DejaVuSerifCondensed; color: #666666;
        font-weight: bold; font-style: normal; margin-bottom: 6pt; }

    .evenrow td, .evenrow th { background-color: #f5f8f5; }
    .oddrow td, .oddrow th { background-color: #e3ece4; }

    .bpmTopic { background-color: #e3ece4; width: 100%; }
    .bpmTopicC { background-color: #e3ece4; width: 100%;}
    .bpmNoLines { background-color: #e3ece4; }
    .bpmNoLinesC { background-color: #e3ece4; }
    .bpmClear {     }
    .bpmClearC { text-align: center; }
    .bpmTopnTail { background-color: #e3ece4; topntail: 0.02cm solid #495b4a;}
    .bpmTopnTailC { background-color: #e3ece4; topntail: 0.02cm solid #495b4a;}
    .bpmTopnTailClear { topntail: 0.02cm solid #495b4a; }
    .bpmTopnTailClearC { topntail: 0.02cm solid #495b4a; }

    .bpmTopicC td, .bpmTopicC td p { text-align: center; }
    .bpmNoLinesC td, .bpmNoLinesC td p { text-align: center; }
    .bpmClearC td, .bpmClearC td p { text-align: center; }
    .bpmTopnTailC td, .bpmTopnTailC td p { text-align: center;  }
    .bpmTopnTailClearC td, .bpmTopnTailClearC td p {  text-align: center;  }

    .pmhMiddleCenter { text-align:center; vertical-align:middle; }
    .pmhMiddleRight {   text-align:right; vertical-align:middle; }
    .pmhBottomCenter { text-align:center; vertical-align:bottom; }
    .pmhBottomRight {   text-align:right; vertical-align:bottom; }
    .pmhTopCenter { text-align:center; vertical-align:top; }
    .pmhTopRight {  text-align:right; vertical-align:top; }
    .pmhTopLeft {   text-align:left; vertical-align:top; }
    .pmhBottomLeft {    text-align:left; vertical-align:bottom; }
    .pmhMiddleLeft {    text-align:left; vertical-align:middle; }

    .infobox { margin-top:10pt; background-color:#DDDDBB; text-align:center; border:1px solid #880000; }

    .bpmTopic td, .bpmTopic th  {   border-top: 1px solid #FFFFFF; }
    .bpmTopicC td, .bpmTopicC th  { border-top: 1px solid #FFFFFF; }
    .bpmTopnTail td, .bpmTopnTail th  { border-top: 1px solid #FFFFFF; }
    .bpmTopnTailC td, .bpmTopnTailC th  {   border-top: 1px solid #FFFFFF; }
        br.paging { page-break-after: always; }
    </style>
	</head>
	</body>';

$grading_type = $section['EduClass']['grading_type'];

$html = '<table width=100%><tr><td width=25% valign=bottom><img width=120px src="http://'.
	Configure::read('domain') . Configure::read('localhost_string') . '/img/logo_cha.png"> <br/> </td>';
$html .= '<td width=50%>';
$html .= '<center><table width=100%><tr><td align=center valign=bottom>';
$html .= '<h2>' . Configure::read('company_name') . '</h2>';
$html .= '<h3>Students Roster ' . $section['EduAcademicYear']['name'] . '</h3>';
$html .= '<h3><b>'. $section['EduClass']['name'] . ' ' . $section['EduSection']['name'] . '</b></h3>';

$html .= '</td></tr></table></center>';
$html .= '</td><td valign=bottom align=right><b>Total Number of Students: ' . count($registrations) . '</b>';
$html .= '</td></tr></table>';


if ($section['EduClass']['grading_type'] == 'G') {
	$content = '<table width=100% border=1>';
	// construct the header row
	$content .= '<thead><tr>';
	$content .= '<th valign=bottom>No</th>';
	$content .= '<th valign=bottom>Student <br/>(ID, Gender, Age)</th>';
	$content .= '<th style="text-rotate: 90;" valign=bottom>Quarters</th>';
	
	$evaluation_value_group = 1;
	// construct the course headers
	$cs = array();
	foreach ($evaluations as $evaluation) {
		$cs[$evaluation['EduEvaluation']['id']] = $evaluation['EduEvaluationArea']['name'];
		$evaluation_value_group = $evaluation['EduEvaluationArea']['evaluation_value_group'];
	}
	$cs_includeds = array();
	foreach ($registrations[0]['EduRegistrationEvaluation'] as $rc) {
		if (!in_array($rc['edu_evaluation_id'], $cs_includeds)) {
			$content .= '<th style="text-rotate: 90;" valign=bottom>' . $cs[$rc['edu_evaluation_id']] . '&nbsp;</th>';
			$cs_includeds[] = $rc['edu_evaluation_id'];
		}
	}
	
	$content .= '</tr></thead>';
	
	$content .= '<tfoot>';
	$content .= '<tr>';
	$content .= '<td border=0 colspan=' . (count($evaluations) + 3) . ' align=center><br/>Legend:';
	foreach ($evaluation_values as $evaluation_value) {
		if ($evaluation_value['EduEvaluationValue']['evaluation_value_group'] == $evaluation_value_group) {
			$content .= '&nbsp;&nbsp;&nbsp; ' . $evaluation_value['EduEvaluationValue']['name'] .
				' - ' . $evaluation_value['EduEvaluationValue']['description'];
		}
	}
	$content .= '</td>';
	$content .= '</tr>';
	$content .= '</tfoot>';
	
	$content .= '<tbody>';
	// display the students records
	$count = 1;
	
	foreach ($registrations as $registration) {
		$y1 = date('Y', strtotime($registration['EduStudent']['birth_date']));
		$y2 = date('Y', strtotime($section['EduAcademicYear']['end_date']));
		$age = $y2 - $y1;
		$content .= '<tr>';
		$content .= '<td rowspan=4>' . $count++ . '</td>';
		$content .= '<td rowspan=4>' . $registration['EduRegistration']['name'] . ' (' .
			$registration['EduStudent']['identity_number'] .
			', ' . $registration['EduStudent']['gender'] . ', ' . $age . 'Y)</td>';
		$started = false;
		foreach ($registration['EduRegistrationQuarter'] as $rq) {
			$content .= ($started ? '<tr>': '');
			$started = true;
			$content .= '<td>' . $rq['EduQuarter']['short_name'] . '</td>';
			foreach ($registration['EduRegistrationEvaluation'] as $rev) {
				if ($rev['edu_quarter_id'] == $rq['edu_quarter_id']) {
					$content .= '<td>' . $rev['EduEvaluationValue']['name'] . '</td>';
				}
			}
			
			$content .= '</tr>';
		}
	}
	$content .= '</tbody>';
	$content .= '</table>';
	$html .= substr($content, 0);
} else {
	$content = '<table width=100% border=1>';
	// construct the header row
	$content .= '<thead><tr>';
	$content .= '<th valign=bottom>No</th>';
	$content .= '<th valign=bottom>Student <br/>(ID, Gender, Age)</th>';
	$content .= '<th style="text-rotate: 90;" valign=bottom>Quarters</th>';
	
	// construct the course headers
	$cs = array();
	foreach ($courses as $course) {
		$cs[$course['EduCourse']['id']] = $course['EduSubject']['name'];
	}
	$cs2 = array();
	foreach ($registrations[0]['EduRegistrationQuarter'][0]['EduRegistrationQuarterResult'] as $rc) {
		$cs2[$rc['edu_course_id']] = $cs[$rc['edu_course_id']];
		$content .= '<th style="text-rotate: 90;" valign=bottom>' . $cs[$rc['edu_course_id']] . '&nbsp;</th>';
	}
	
	$content .= '<th style="text-rotate: 90;" valign=bottom>Total</th>';
	$content .= '<th style="text-rotate: 90;" valign=bottom>Average</th>';
	$content .= '<th style="text-rotate: 90;" valign=bottom>CGPA</th>';
	
	// construct evaluation headers
	$content .= '<th style="text-rotate: 90;" valign=bottom>Remark</th>';
	$content .= '</tr></thead>';
	$content .= '<tbody>';
	// display the students records
	$count = 1;
	
	foreach ($registrations as $registration) {
		$course_count = 1;
		
  		$already_included = array();
		$cgpas = array();

		$quarter_count = count($registration['EduRegistrationQuarter']);
		
		$y1 = date('Y', strtotime($registration['EduStudent']['birth_date']));
		$y2 = date('Y', strtotime($section['EduAcademicYear']['end_date']));
		$age = $y2 - $y1;
		$content .= '<tr>';
		$content .= '<td rowspan=' . ($quarter_count + 1) . '><b>' . $count++ . '</b></td>';
		$content .= '<td rowspan=' . ($quarter_count + 1) . '><b>' .
			$registration['EduRegistration']['name'] . ' (' . $registration['EduStudent']['identity_number'] .
			', ' . $registration['EduStudent']['gender'] . ', ' . $age . 'Y)</b></td>';
		$started = false;
		$sortedrqs = array();
		foreach ($registration['EduRegistrationQuarter'] as $rq) {
			$sortedrqs[$rq['EduQuarter']['short_name']] = $rq;
		}
		ksort($sortedrqs);
		$scale_values = array('A*' => 4, 'A' => 4, 'B' => 3, 'C' => 2, 'D' => 1, 'F' => 0);
		$ttcount = 1;
		$atgpa = 0;
		$atgpa_count = 0;
		$courses_count = count($cs2);

		foreach ($sortedrqs as $rq) {
			$content .= ($started ? '<tr>': '');
			$started = true;
			$content .= '<td>' . $rq['EduQuarter']['short_name'] . '</td>';
			$cs_array = $cs2;
			$included = array();

			$cgpa_sum = 0;
			$cgpa_count = 0;
			$course_for_cgpa = 0;
			foreach ($rq['EduRegistrationQuarterResult'] as $rqr) {
				unset($cs_array[$rqr['edu_course_id']]);
				if(in_array($rqr['edu_course_id'], $included)) {
					continue;
				} else {
					$included[] = $rqr['edu_course_id'];
				}
				$rqr['result_indicator'] = ($rqr['result_indicator'] == 'NA'? 'NA': '-');

				$course_for_cgpa += $rqr['course_result'] == 0? 0: 1;

				$content .= '<td>' . ($rqr['course_result'] == 0? $rqr['result_indicator']:
					($rqr['course_result'] . '<sup>' . $rqr['scale_result'] . '</sup>')) . '</td>';
				$scale_result = $rqr['scale_result'];
				$cgg = (isset($scale_values[$scale_result])? $scale_values[$scale_result]: 0);
				
				$cgpa_sum += $cgg;
				if ($rqr['course_result'] > 0) {
					$cgpa_count++;
				}
				// commentded $cgpa_count += ($cgg == 0? 0: 1);
			}
			$cs_arrays[] = $cs_array;

			foreach ($cs_array as $k => $v) {
				$content .= '<td>-</td>';
			}
			$total = $rq['quarter_total'];
			$average = $rq['quarter_total'] / $cgpa_count; //$rq['quarter_average'];
			$average_formatted = number_format($average, 2, '.', ',');
			
			$tgpa = '0.00';
			if ($cgpa_sum > 0) {
				$tgpa = $cgpa_sum / $cgpa_count;
				$atgpa += $tgpa;
				$atgpa_count++;
				$tgpa = number_format($tgpa, 2, '.', ',');
			}
			
			$content .= '<td>' . ($total == 0? '-': $total) . '</td>';
			$content .= '<td>' . ($average == 0? '-': $average_formatted) . '</td>';
			$content .= '<td>' . ($tgpa == '0.00' || $tgpa == 0? '-': '<b>' . $tgpa . '</b>') . '</td>';

			$content .= '<td>&nbsp;</td>';
			
			$content .= '</tr>';
		}
		$atgpa = number_format($atgpa/$atgpa_count, 2, '.', ',');
		$content .= '<tr><td>AV</td>';
		$total = 0;
		$my_cs = $cs;
		$already_included_course = array();
		foreach ($registration['EduRegistrationResult'] as $rr) {
			if(!in_array($rr['edu_course_id'], $already_included_course)) {
				$already_included_course[] = $rr['edu_course_id'];
			} else {
				continue;
			}
			unset($my_cs[$rr['edu_course_id']]);
			$content .= '<td><b>' . $rr['average'] . '<sup>' . $rr['scale_result'] . '</sup></b></td>';
			$total += $rr['average'];
		}
		//???? the av row is empty - why?
		$avcount = 1;
		foreach ($my_cs as $aa){      //??????
			if ($avcount >= count($my_cs)) {
				break;
			}
			$avcount++;
			if(count($already_included_course) != $courses_count) {
				$content .= '<td>-</td>';
			}
		}
		//$content = substr($content, 0, strlen($content) - 10); // for grade 12

		$gtotal = $total;
		$gaverage = $registration['EduRegistration']['grand_total_average'];

		$rank = !isset($rank)? 0: $rank;
		
		//$content .= '<td>-</td>';
		$content .= '<td><b>' . ($gtotal == 0? '-': $gtotal) . '</b></td>';
		$content .= '<td><b>' . ($gaverage == 0? '-': $gaverage) . '</b></td>';
		$content .= '<td><b><u>' . ($atgpa == '0.00'? '-': ($gaverage == 0? '-': $atgpa)) . '</u></b></td>';
		$content .= '<td><b>' . ($rank == 0 || $gaverage == 0? '-': $rank) . '</b></td>';
		
		$content .= '</tr>';
	}
	$content .= '</tbody>';
	$content .= '</table>';
	$html .= substr($content, 0);
}

$html_trailer = '</body></html>';

//==============================================================
//==============================================================
//==============================================================
if ($format == 'PDF') {
	include (APPLIBS . "mpdf/mpdf.php");

	$mpdf=null;
	// commented $mpdf=new mPDF('', 'A4', '', 'Nyala', 15, 15, 16, 16, 9, 9, 'P');
	$mpdf=new mPDF('', 'A4', '', 'Nyala', 15, 15, 25, 50, 15, 32, 'P');

	$mpdf->SetWatermarkImage('/' . Configure::read('localhost_string') . '/img/bg.png', 0.1, '', array(45,-10));
	$mpdf->showWatermarkImage = true;

	$mpdf->useAdobeCJK = true;
	$mpdf->SetAutoFont(AUTOFONT_ALL);

	$mpdf->mirrorMargins = 1;

	$mpdf->defaultheaderfontsize = 10;
	$mpdf->defaultheaderline = 1;

	$mpdf->defaultfooterfontsize = 12;
	$mpdf->defaultfooterline = 1;

	$mpdf->SetHeader('Roster for Grade '. $section['EduClass']['name'] . ' ' .
		$section['EduSection']['name'] . '||' . $section['EduAcademicYear']['name']);
	$mpdf->SetFooter("|Stamp and Signature|");

	$mpdf->WriteHTML($html_header . $html . $html_trailer);
	$mpdf->Output();
	exit;
} elseif ($format == 'EXCEL') {
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=Student-Roster.xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
	$search = '<img width=120px src="http://' . Configure::read('domain') .
		Configure::read('localhost_string') . '/img/logo_cha.png">';
	$html = str_replace($search, '', $html);

    echo $html; //no ending ; here
} else { // HTML
	echo $html_header . $html . $html_trailer;
}
