<?php

$html = '
	<style>
		body {
			font-family: sans-serif;
			font-size: 11pt;
		}
		@media screen, print {
			page-break-after: hr;
		}
	</style>';

$tbl = "";
// fields
$tbl .= "<tr>";
$tbl .= "   <th>No</th>";
$tbl .= "   <th>Name</th>";
foreach ($fields as $field) {
    // 40-123-FE
    $parts = explode('-', $field);
	$fl = (count($parts)>1? $parts[0]: $field);
    $fl .= (count($parts)>2? '<br/>' . $parts[2]: '');
    $tbl .= "   <th>$fl</th>";
}
$tbl .= "</tr>";

$row_count = 1;
foreach ($records as $record) {
    $tbl .= "<tr>";
    $tbl .= "<td>" . $row_count . "</td>";
    $tbl .= "<td>" . $record['student_name'] . "</td>";
    $pos = false;
    $neg = false;
    foreach ($fields as $field) {
        if ($record[$field] == '0') {
            $neg = true;
            $record['Scale'] = '<font color=red>NG</font>';
        } elseif ($field !== 'Scale') {
            $pos = true;
        }

        if ($field == 'Scale') {
            $record['Scale'] = ($pos && $neg? '<font color=red>I</font>': ($neg? '<font color=red>NG</font>': $record['Scale']));
        }
        $tbl .= "<td align=center>" . ($record[$field] == '0'? '<font color=red>-</font>':
            $record[$field]) . "</td>";
    }
    $tbl .= "</tr>";
    $row_count++;
}

$status = $assessment['EduAssessment']['status'] == 'SB'? 'Submitted': 'Not Submitted';
if ($assessment['EduAssessment']['checked_by_id'] > 0) { $status = 'Checked'; }
if ($assessment['EduAssessment']['approved_by_id'] > 0) { $status = 'Approved'; }

$html .= '<table border="1" width="100%" style="margin: 5px; border: #000066 thin solid;">
            ' . $tbl . '
        </table>

        <table width="95%" border="0">
            <tr>
                <td>
                    Submitted: ' . $assessment['User']['username'] . '<br/>on <b>' .
                    ($assessment['EduAssessment']['submitted_at'] == '0000-00-00 00:00:00'? '-':
                    $assessment['EduAssessment']['submitted_at']) . '</b> <br/>
                    Signature _____________<br/>
                </td>
                <td>
                    Checked: ' .
                    ($status == 'Approved' || $status == 'Checked'?
                        $assessment['Checker']['User']['username']: '____________') .
                    '<br/>on <b>' .
                    ($assessment['EduAssessment']['checked_at'] == '0000-00-00 00:00:00'? '-':
                        $assessment['EduAssessment']['checked_at']) . '</b> <br/>
                    Signature _____________<br/>
                </td>
                <td>
                    Approved: ' .
                    ($status == 'Approved'?
                        $assessment['Approver']['User']['username']: '__________') .
                    '<br/>on <b> ' .
                    ($assessment['EduAssessment']['approved_at'] == '0000-00-00 00:00:00'? '-':
                        $assessment['EduAssessment']['approved_at']) . '</b> <br/>
                    Signature _____________<br/>
                </td>
            </tr>
        </table>
        ';


//==============================================================
//==============================================================
//==============================================================

include_once APPLIBS . "mpdf/mpdf.php";

$mpdf = new mPDF('c', 'A4', '', 'Nyala', 15, 15, 16, 16, 9, 9, 'P');
// comment $mode='',$format='A4',$default_font_size=0,$default_font='',
// $mgl=15,$mgr=15,$mgt=16,$mgb=16,$mgh=9,$mgf=9, $orientation='P'

$wm = $section['EduClass']['name'] . $section['EduSection']['name'] . ' ' .
    $course['EduSubject']['name'] . ' - ' . $status;
$mpdf->SetWatermarkText($wm);
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->showWatermarkText = true;

$mpdf->useAdobeCJK = true;
$mpdf->SetAutoFont(AUTOFONT_ALL);

$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins

$mpdf->defaultheaderfontsize = 10;	/* in pts */
//comment $mpdf->defaultheaderfontstyle = 'B';	/* blank, B, I, or BI */
$mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */

$mpdf->defaultfooterfontsize = 12;	/* in pts */
//comment $mpdf->defaultfooterfontstyle = 'B';	/* blank, B, I, or BI */
$mpdf->defaultfooterline = 1; 	/* 1 to include line below header/above footer */

$mpdf->SetHeader('{DATE j-m-Y} - ' . $quarter['EduAcademicYear']['name'] . ' ' .
    $quarter['EduQuarter']['name'] . ' - Mark Sheet||OnePlanet - ' . $section['EduClass']['name'] .
    $section['EduSection']['name'] . ' ' . $course['EduSubject']['name']);
$mpdf->SetFooter(array(
        'C' => array(
            'content' => 'www.oneplanetschool.com',
            'font-family' => 'serif',
            'font-style' => 'BI',
            'font-size' => '16',	/* gives default */
        ),
        'line' => 1,		/* 1 to include line below header/above footer */
    ), 'E'	/* defines footer for Even Pages */
);

$mpdf->SetFooter(array(
        'C' => array(
            'content' => 'www.oneplanetschool.com',
            'font-family' => 'serif',
            'font-style' => 'BI',
            'font-size' => '16',	/* gives default */
        ),
        'line' => 1,		/* 1 to include line below header/above footer */
    ), 'O'	/* defines footer for Even Pages */
);

$mpdf->WriteHTML($html);
$mpdf->Output();
exit;

//==============================================================
//==============================================================
//==============================================================
