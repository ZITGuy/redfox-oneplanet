<?php

$html = '<style>
    body { font-family: DejaVuSansCondensed; font-size: 9pt; }
    p {     text-align: justify; margin-bottom: 4pt; margin-top:0pt;  }

    table {font-family: DejaVuSansCondensed; font-size: 9pt; line-height: 1.2;
        margin-top: 1pt; margin-bottom: 2pt; 
		border-collapse: collapse;  }

    thead { font-weight: bold; vertical-align: bottom;  }
    tfoot { font-weight: bold; vertical-align: top; }
    thead td { font-weight: bold; }
    tfoot td { font-weight: bold; }

    thead td, thead th, tfoot td, tfoot th { font-variant: small-caps; }

    .headerrow td, .headerrow th { background-gradient: linear #b7cebd #f5f8f5 0 1 0 0.2;  }
    .footerrow td, .footerrow th { background-gradient: linear #b7cebd #f5f8f5 0 1 0 0.2;  }

    th {    
		font-weight: bold; 
        vertical-align: top; 
        text-align:left; 
        padding-left: 2mm; 
        padding-right: 2mm; 
        padding-top: 0.5mm; 
        padding-bottom: 0.5mm; 
     }

    td {    
		padding-left: 2mm; 
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

    hr {    
		width: 70%; height: 1px; 
        text-align: center; color: #999999; 
        margin-top: 8pt; margin-bottom: 8pt; }

    a { color: #000066; font-style: normal; text-decoration: underline; 
        font-weight: normal; }

    ul {    margin-bottom: 3pt; }
    ol {    margin-bottom: 9pt; }

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
    </style>';

//$grading_type = $section['EduClass']['grading_type'];

$html .= '<table width=100%><tr><td width=25% valign=bottom><img height=100px src="http://'. Configure::read('domain') . Configure::read('localhost_string') . '/img/opis_header.png"> <br/> </td>';
$html .= '</tr></table>';

$html .= '<table width=100% border=1>
            <tr>
				<td rowspan=4 width=4% valign=bottom style="padding: 0; background-color: black"><img height=70px src="http://'. Configure::read('domain') . Configure::read('localhost_string') . '/img/grade_p_' . $registration['EduClass']['cvalue'] . '.png"></td>
				<td rowspan=2 valign=bottom bgcolor=#cccccc>Student Name</td>
				<td rowspan=2 valign=bottom>' . $registration['EduRegistration']['name'] . '</td>
				<td colspan=6 valign=bottom><center><b>Attendance</b> <br> (Days Absent/Tardy)</center></td>
		    </tr>
			<tr>
				<td colspan=2 valign=bottom><center>T1</center></td>
				<td colspan=2 valign=bottom><center>T2</center></td>
				<td colspan=2 valign=bottom><center>T3</center></td>
		    </tr>
			<tr>
				<td rowspan=2 valign=bottom bgcolor=#cccccc>Homeroom Teacher</td>
				<td rowspan=2 valign=bottom>' . $registration['Homeroom']['User']['Person']['first_name'] . ' ' . $registration['Homeroom']['User']['Person']['middle_name'] . '</td>
				<td valign=bottom>A</td>
				<td valign=bottom>T</td>
				<td valign=bottom>A</td>
				<td valign=bottom>T</td>
				<td valign=bottom>A</td>
				<td valign=bottom>T</td>
			</tr>
			<tr>
				<td valign=bottom>' . ($registration['EduRegistrationQuarter'][0]['absentees']) . '</td>
				<td valign=bottom>-</td>
				<td valign=bottom>' . ($registration['EduRegistrationQuarter'][1]['absentees']) . '</td>
				<td valign=bottom>-</td>
				<td valign=bottom>' . ($registration['EduRegistrationQuarter'][2]['absentees']) . '</td>
				<td valign=bottom>-</td>
			</tr>
			';
			
$html .= '</table>';

$html .= '<table width=100%>
			<tr>
				<td>' . 
					($registration['EduClass']['cvalue'] <= 4?
					'<p>For each subject, teachers will assign a grade level according to the following scale.  They may chose to use a 100 point system or grades on a quantitative scale.  Grades reflect the student\'s overall performance. 
					A cumulative GPA of 2:00 is needed to be promoted to the next grade level. If a student failed to score the passing mark, he/she will have to take supplementary classes and examinations.
					</p>': 
					'<p>For each subject, teachers will assign a grade level according to the following scale.  They may choose to use a 100 point system or grades on a quantitative scale.  Grades reflect the student\'s overall performance. 
					A cumulative GPA of 2:00 is needed to be promoted to the next grade level.</p>')
					. 
				'</td>
			</tr>
          </table>';

$html .= '<table width=100% border=1>
			<tr bgcolor=#cccccc style="background-color: #cccccc">
				<th>Grade Level</th>
				<th>%</th>
				<th>Grade Point</th>
				<th>Qualitative Descriptor</th>
			</tr>';
		foreach($scales as $scale) {
$html .= '  <tr>
				<td>' . $scale['EduScale']['scale'] . '</td>
				<td width=10%>' . round($scale['EduScale']['min']) . '-' . round($scale['EduScale']['max'] - ($scale['EduScale']['max'] >= 100? 0: 1)) . '</td>
				<td>' . number_format($scale['EduScale']['grade_point'], 1, '.', ',') . '</td>
				<td>' . $scale['EduScale']['remark'] . '</td>
			</tr>';
		}
$html .= '</table>';


// Courses > RQRs array
$courses = array();
foreach($registration['Rqrs'] as $rqr) {
	if(!isset($courses[$rqr['EduCourse']['id']])) {
		$courses[$rqr['EduCourse']['id']] = array(
			'Subject' => $rqr['EduSubject']['EduSubject'],
			'Course' => $rqr['EduCourse'],
			'Rqrs' => array()
		);
	}
	$courses[$rqr['EduCourse']['id']]['Rqrs'][] = $rqr;
}

// subjects
$html .= '<br/>&nbsp;';
$course_count = 1;	
$already_included = array();
$cgpas = array();
$cgpas_counts = array();
foreach($courses as $course) {
	if(in_array($course['Subject']['name'], $already_included))
		continue;
	$already_included[] = $course['Subject']['name'];
	$html .= '<table width=100% style="margin-bottom: -18pt"><tr><td><table style="margin: 0;" width=100% border=1>';
	// a subject
	$html .= '
			<tr>
				<td bgcolor=#cccccc width=10>' . $course_count . '</td>
				<td bgcolor=#cccccc width=510>' . strtoupper($course['Subject']['name']) . '</td>';
			$course_count++;
			$tcount=1;
			foreach ($course['Rqrs'] as $rqr) {
				$html .= '	
					<td bgcolor=#cccccc>T' . $tcount . '</td>';
					$tcount++;
			}
			$html .= '		<td bgcolor=#cccccc>Comments</td>
			</tr>';

	$items = (count($rqr['EduCourseItem']) == 0? '<tr><td>&nbsp;</td></tr>': ''); // $course['Course']['description']: '');
	foreach($rqr['EduCourseItem'] as $course_item) {
		$items .= ($course['Subject']['name'] == $course_item['EduCourseItem']['name']? '': '<tr bgcolor=#cccccc><td><b>' . $course_item['EduCourseItem']['name'] . '</b></td></tr>') . 
			'<tr><td>' . $course_item['EduCourseItem']['description'] . '</td></tr>';
	}

	$scale_values = array('A' => 4, 'B' => 3, 'C' => 2, 'D' => 1, 'F' => 0);
	$ttcount = 1;
	$html .= '
				<tr>
					<td rowspan=3 colspan=2 style="margin:0; padding:0;"><table width=100% border=0 style="margin:0;">' . $items . '</table>' . '<br/>&nbsp;' . '</td>';
	foreach($course['Rqrs'] as $rqr) {
		$num_result = ($rqr['EduRegistrationQuarterResult']['course_result'] == 0? '&nbsp;': str_replace('.00', '', $rqr['EduRegistrationQuarterResult']['course_result']));
		$scale_result = $rqr['EduRegistrationQuarterResult']['scale_result'];
		$cgg = (isset($scale_values[$scale_result])? $scale_values[$scale_result]: 0);
		if(!isset($cgpas[$ttcount])) $cgpas[$ttcount] = 0;
		$cgpas[$ttcount] += $cgg;
		if(!isset($cgpas_counts[$ttcount])) $cgpas_counts[$ttcount] = 0;
		if($rqr['EduRegistrationQuarterResult']['course_result'] > 0) {
			$cgpas_counts[$ttcount]++;
		}
		
		$html .= '	
					<td rowspan=3' . ($registration['EduClass']['cvalue'] > 4? ' style="margin:0; padding:0;"': '') . '>
						<table border=1' . ($registration['EduClass']['cvalue'] > 4? ' style="margin:0;"': '') . '><tr><td> ' . ($num_result == '' || $num_result == 0? '-': $scale_result) . ' </td></tr></table><br/>
						<p>' . $num_result . '</p>
					</td>';
		$ttcount++;
	}

	$ttcount = 1;
	foreach($course['Rqrs'] as $rqr) {
		if($ttcount > 1) $html .= '	<tr>';
			$html .= '		<td>
						T' . $ttcount . '<br/>' . (($rqr['EduRegistrationQuarterResult']['teacher_comment'] == '-')? '<br/>&nbsp;': $rqr['EduRegistrationQuarterResult']['teacher_comment']) . '
					</td>
				</tr>';
				$ttcount++;
	}
	//break;	
	$html .= '</table></td></tr></table>&nbsp;';
}

$html .= '<br/>&nbsp;';
$html .= '<br/>&nbsp;';
//$html .= '</table><br/>&nbsp;'; // . pr($registration['Evaluation'], true);

// prepare for evaluations

$categories = array();
foreach($registration['EvaluationCategory'] as $category) {
	if(!isset($category['Evaluations'])) $category['Evaluations'] = array();
	foreach($registration['Evaluation'] as $evaluation) {
		if($evaluation['EduEvaluationArea']['edu_evaluation_category_id'] == $category['EduEvaluationCategory']['id']) {
			$category['Evaluations'][] = $evaluation;
		}
	}
	$categories[] = $category;
}
unset($registration['Evaluation']);
$registration['EvaluationCategory'] = $categories;

//$html .= pr($registration['EduRegistrationEvaluation'], true);

// draw the evaluations table
$html .= '<table width=100% border=1>
			<tr>
				<th colspan=5 bgcolor=#cccccc>
					<center>
						<h2>Social / Emotional Growth, Effort and Development</h2>
						<h3>N - Needs Improvement, S - Satisfactory, E - Excellent</h3>
					</center>
				</th>
			</tr>';

foreach($registration['EvaluationCategory'] as $category) {
	$evcount=1;
	$html .= '<tr bgcolor=#cccccc>
				<th width=45%>' . $category['EduEvaluationCategory']['name'] . '</th>
				<th><center>T1</center></th>
				<th><center>T2</center></th>
				<th><center>T3</center></th>
				<th><center>Comments</center></th>
			</tr>';
	foreach($category['Evaluations'] as $evaluation) {
		$html .= 	
				'<tr>
					<td>&nbsp;&nbsp;' . $evaluation['EduEvaluationArea']['name'] . '</td>';
		foreach($registration['EduRegistrationQuarter'] as $rq) {
			$ev = '-';
			foreach($registration['EduRegistrationEvaluation'] as $rev) {
				if($rev['edu_quarter_id'] == $rq['edu_quarter_id'] && 
					$evaluation['EduEvaluation']['id'] == $rev['edu_evaluation_id']) {
						$q = null;
						foreach($registration['EduQuarter'] as $qq){
							if($qq['id'] == $rq['edu_quarter_id'])
								$q = $qq;
						}
						
						$ev = (isset($evaluation_values[$rev['edu_evaluation_value_id']]) && $q != null && ($q['status_id'] == 1 || $q['status_id'] == 8))? $evaluation_values[$rev['edu_evaluation_value_id']]: '-';
						break;
				}
			}
			$html .= '<td><center>' . $ev . '</center></td>';
		}
		if($category['EduEvaluationCategory']['name'] == '10 Characteristics of Successful Learners')
			$html .=  ($evcount == 1? '<td rowspan=4>T1</td>': ($evcount == 5? '<td rowspan=4>T2</td>': ($evcount == 9? '<td rowspan=2>T3</td>': ''))) . 	
				'</tr>';
		elseif($category['EduEvaluationCategory']['name'] == 'Additional Work and Study Skills' &&
				$registration['EduClass']['cvalue'] > 4)
			$html .=  ($evcount == 1? '<td rowspan=2>T1</td>': ($evcount == 3? '<td rowspan=2>T2</td>': ($evcount == 5? '<td>T3</td>': ''))) . 	
				'</tr>';
		else
			$html .=  ($evcount == 1? '<td rowspan=2>T1</td>': ($evcount == 3? '<td rowspan=2>T2</td>': ($evcount == 5? '<td rowspan=2>T3</td>': ''))) . 	
				'</tr>';
		$evcount++;
	}
}

$html .= '</table>';

// extra-curricular for classes above 6
if($registration['EduClass']['cvalue'] > 6) {
	$html .= '<table width=100% border=1 bgcolor=#cccccc>
			<tr>
				<th colspan=4>
					<center><h2>Extracurricular Performance</h2></center>
				</th>
			</tr>
			<tr>
				<th width=30%>Qualitative Descriptor</th>
				<th width=20%>Grade Level</th>
				<th width=15%>Grade Point</th>
				<td width=35% rowspan=6><b>NB.</b> Grades reflect the student’s performance in extracurricular activities. A student who failed to score a cumulative GPA of 2:00 in extracurricular activities will be considered incomplete and will therefore not be able to get his/ her evaluation report (transcript).</td>
			</tr>
			<tr>
				<td>EP =  Excellent Performance</td>
				<td>A</td>
				<td>4.0</td>
			</tr>
			<tr>
				<td>AE =  Above Expectation</td>
				<td>B</td>
				<td>3.0</td>
			</tr>
			<tr>
				<td>ME = Meets Expectation</td>
				<td>C</td>
				<td>2.0</td>
			</tr>
			<tr>
				<td>BE =  Below Expectation</td>
				<td>D</td>
				<td>1.0</td>
			</tr>
			<tr>
				<td>U =   Unsatisfactory</td>
				<td>F</td>
				<td>0</td>
			</tr>
		  </table>';

	$html .= '<table width=100% border=1>
		  <tr bgcolor=#cccccc>
			  <th width=40%>&nbsp;</th>
			  <th width=12%>T1</th>
			  <th width=12%>T2</th>
			  <th width=12%>T3</th>
			  <th width=24%>Comment</th>
		  </tr>
		  <tr>
			  <th>Life Skills</th>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
		  </tr>
		  <tr>
			  <th>Clubs</th>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
		  </tr>
		  <tr>
			  <th>Student Media</th>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
		  </tr>
		  <tr>
			  <th>Community Service</th>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
		  </tr>
		  <tr>
			  <th>Social Action</th>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
		  </tr>
		  <tr>
			  <th>Social Justice</th>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
		  </tr>
		</table>';

	$html .= '<table width=100% border=1>
		<tr>
			<th bgcolor=#cccccc width=25%>&nbsp;</th>
			<th width=25%>T1</th>
			<th width=25%>T2</th>
			<th width=25%>T3</th>
			<th width=25%>Average</th>
		</tr>
		<tr>
			<th>Grade Point Average for Extracurricular Activities</th>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th>Comments :</th>
			<td>
				<table>
					<tr>
						<td><table border=1><tr><td><font size=1em>&nbsp;</font></td></tr></table></td>
						<th><font size=3px>Complete</font></th>
					</tr>
					<tr>
						<td><table border=1><tr><td><font size=1em>&nbsp;</font></td></tr></table></td>
						<th><font size=3px>Incomplete</font></th>
					</tr>
				</table>
			</td>
			<td>
				<table>
					<tr>
						<td><table border=1><tr><td><font size=1em>&nbsp;</font></td></tr></table></td>
						<th><font size=3px>Complete</font></th>
					</tr>
					<tr>
						<td><table border=1><tr><td><font size=1em>&nbsp;</font></td></tr></table></td>
						<th><font size=3px>Incomplete</font></th>
					</tr>
				</table>
			</td>
			<td>
				<table>
					<tr>
						<td><table border=1><tr><td><font size=1em>&nbsp;</font></td></tr></table></td>
						<th><font size=3px>Complete</font></th>
					</tr>
					<tr>
						<td><table border=1><tr><td><font size=1em>&nbsp;</font></td></tr></table></td>
						<th><font size=3px>Incomplete</font></th>
					</tr>
				</table>
			</td>
			<td>
				<table>
					<tr>
						<td><table border=1><tr><td><font size=1em>&nbsp;</font></td></tr></table></td>
						<th><font size=3px>Complete</font></th>
					</tr>
					<tr>
						<td><table border=1><tr><td><font size=1em>&nbsp;</font></td></tr></table></td>
						<th><font size=3px>Incomplete</font></th>
					</tr>
				</table>
			</td>
		</tr>
	  </table>';
}

// averages table
$html .= '<table width=100% border=1>
			<tr>
				<th width=45%>
				Overall Grade Point Average
				</th>';
			
			$cgpa = 0;
			$cgpacount = 1;
			foreach($registration['EduRegistrationQuarter'] as $rq) {
				$tgpa = '0.00';
				if($cgpas_counts[$cgpacount] > 0)  {
					$tgpa = $cgpas[$cgpacount] / $cgpas_counts[$cgpacount];
					$tgpa = number_format($tgpa, 2, '.', ',');
				}
			$html .= 	
				'<th><center>T' . $cgpacount . '<br/>&nbsp;' . $tgpa . '</center></th>';
				$cgpa += $tgpa;
				$cgpacount++;
			}
$html .= 	
			    '<th><center>Average <br/>' . $registration['EduRegistration']['acgpa'] . '</center></th>
			</tr>';
$html .= 	
			'<tr>
				<th>Parent\'s Signature</th>
				<td>&nbsp;<br/>&nbsp;</td>
				<td>&nbsp;<br/>&nbsp;</td>
				<td>&nbsp;<br/>&nbsp;</td>            
				<td>&nbsp;<br/>&nbsp;</td>
			</tr>';
$html .= 	
			'<tr>
				<th>Teacher\'s Signature</th>
				<td>&nbsp;<br/>&nbsp;</td>
				<td>&nbsp;<br/>&nbsp;</td>
				<td>&nbsp;<br/>&nbsp;</td>
				<td>&nbsp;<br/>&nbsp;</td>
			</tr>';
			
$html .= '</table>';

$html .= '<p><center><font size=3px>Based on 4.0 system 
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  Honor Roll Student = 3.5 and higher 
		    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		  Principal’s List = 4.0 and higher</font></center></p><p>&nbsp;</p>';

// promotion table
$html .= '<table width=100% border=1>
			<tr>
				<td colspan=2><br/><font size=4px>This student has been promoted to grade ________ for the next school year.</font><br/>&nbsp;</td>
			</tr>
			<tr>
				<td><br/>Teacher\'s Signature<br/>&nbsp;</td>
				<td><br/>Administrator\'s Signature<br/>&nbsp;</td>
			</tr>';
$html .= '</table>';

//==============================================================
//==============================================================
//==============================================================

//echo $html;

include(APPLIBS . "mpdf/mpdf.php");

$mpdf=null;
$mpdf=new mPDF('','A4','','Nyala',15,15,25,50,15,32, 'P'); 

$mpdf->SetWatermarkImage('/' . Configure::read('localhost_string') . '/img/bg.png', 0.1, '', array(45,-10));
$mpdf->showWatermarkImage = true;

$mpdf->useAdobeCJK = true;
$mpdf->SetAutoFont(AUTOFONT_ALL);

$mpdf->mirrorMargins = 1;

$mpdf->defaultheaderfontsize = 10;
$mpdf->defaultheaderline = 1;

$mpdf->defaultfooterfontsize = 12;
$mpdf->defaultfooterline = 1; 	

$mpdf->SetHeader('Achievement Report');
$mpdf->SetFooter("Student Achevement Report");

$mpdf->WriteHTML($html);
$mpdf->Output();

exit;

?>
