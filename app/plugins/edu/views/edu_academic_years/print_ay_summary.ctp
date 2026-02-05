<?php

$html = '
<style>
	body { font-family: DejaVuSansCondensed; font-size: 11pt;  }
	p { 	text-align: justify; margin-bottom: 4pt; margin-top:0pt;  }

	table {font-family: DejaVuSansCondensed; font-size: 9pt; line-height: 1.2;
		margin-top: 2pt; margin-bottom: 5pt;
		border-collapse: collapse;  }

	thead {	font-weight: bold; vertical-align: bottom; }
	tfoot {	font-weight: bold; vertical-align: top; }
	thead td { font-weight: bold; }
	tfoot td { font-weight: bold; }

	thead td, thead th, tfoot td, tfoot th { font-variant: small-caps; }

	.headerrow td, .headerrow th { background-gradient: linear #b7cebd #f5f8f5 0 1 0 0.2;  }
	.footerrow td, .footerrow th { background-gradient: linear #b7cebd #f5f8f5 0 1 0 0.2;  }

	th {	font-weight: bold; 
		vertical-align: top; 
		text-align:left; 
		padding-left: 2mm; 
		padding-right: 2mm; 
		padding-top: 0.5mm; 
		padding-bottom: 0.5mm; 
	 }

	td {	padding-left: 2mm; 
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
        
        h1, h2, h3 {
            font-family: DejaVuSerifCondensed;
        }

	hr {	width: 70%; height: 1px; 
		text-align: center; color: #999999; 
		margin-top: 8pt; margin-bottom: 8pt; }

	a {	color: #000066; font-style: normal; text-decoration: underline; 
		font-weight: normal; }

	ul {	text-indent: 5mm; margin-bottom: 9pt; }
	ol {	text-indent: 5mm; margin-bottom: 9pt; }

	pre { font-family: DejaVuSansMono; font-size: 9pt; margin-top: 5pt; margin-bottom: 5pt; }

	.breadcrumb {
		text-align: right; font-size: 8pt; font-family: DejaVuSerifCondensed; color: #666666;
		font-weight: bold; font-style: normal; margin-bottom: 6pt; }

	.evenrow td, .evenrow th { background-color: #f5f8f5; } 
	.oddrow td, .oddrow th { background-color: #e3ece4; } 

	.bpmTopic {	background-color: #e3ece4; width: 100%; }
	.bpmTopicC { background-color: #e3ece4; width: 100%;}
	.bpmNoLines { background-color: #e3ece4; }
	.bpmNoLinesC { background-color: #e3ece4; }
	.bpmClear {		}
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
	.pmhMiddleRight {	text-align:right; vertical-align:middle; }
	.pmhBottomCenter { text-align:center; vertical-align:bottom; }
	.pmhBottomRight {	text-align:right; vertical-align:bottom; }
	.pmhTopCenter {	text-align:center; vertical-align:top; }
	.pmhTopRight {	text-align:right; vertical-align:top; }
	.pmhTopLeft {	text-align:left; vertical-align:top; }
	.pmhBottomLeft {	text-align:left; vertical-align:bottom; }
	.pmhMiddleLeft {	text-align:left; vertical-align:middle; }

	.infobox { margin-top:10pt; background-color:#DDDDBB; text-align:center; border:1px solid #880000; }

	.bpmTopic td, .bpmTopic th  {	border-top: 1px solid #FFFFFF; }
	.bpmTopicC td, .bpmTopicC th  {	border-top: 1px solid #FFFFFF; }
	.bpmTopnTail td, .bpmTopnTail th  {	border-top: 1px solid #FFFFFF; }
	.bpmTopnTailC td, .bpmTopnTailC th  {	border-top: 1px solid #FFFFFF; }
</style>
<table border="0" width="100%">
	<tr>
            <td align="center">
                <center>
                    <h1>' . $company_name . '</h1>
                    <h2>Academic Year Summary</h2>
                    <h3>Academic Year: ' . $academic_year . '</h3>
                </center>
            </td>
	</tr>
	<tr>
            <td align="center">' . $company_address . '</td>
	</tr>
	<tr>
            <td align="center">&nbsp;</td>
	</tr>
</table>';

// About the Academic Year
$html .= 
'<h3>Academic Year Details</h3>
    <table class="bpmTopic">
	<tbody>
            <tr>
                <th>Status</th>
                <td>ACTIVE</td>
            </tr>
            <tr>
                <th>Start Date</th>
                <td>' . $ay['EduAcademicYear']['start_date'] . '</td>
            </tr>
            <tr>
                <th>End Date</th>
                <td>' . $ay['EduAcademicYear']['end_date'] . '</td>
            </tr>
            <tr>
                <th>Number of ' . Inflector::pluralize($term_name) . '</th>
                <td>' . count($ay['EduQuarter']) . '</td>
            </tr>
            <tr>
                <th>Days Covered</th>
                <td>' . ((strtotime($ay['EduAcademicYear']['end_date']) - strtotime($ay['EduAcademicYear']['start_date'])) / (60 * 60 * 24) + 1) . '</td>
            </tr>
            <tr>
                <th>Education Days</th>
                <td>0</td>
            </tr>
            <tr>
                <th>Off Days</th>
                <td>0</td>
            </tr>
	</tbody>
</table>';

// About the Quarters
$html .= 
    '<h3>' . Inflector::pluralize($term_name) . '</h3><table class="bpmTopic" >
	<thead>
            <tr class="headerrow">
                <th>' . $term_name . '</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Days Covered</th>
                <th>Education Days</th>
                <th>Off Days</th>
                <th>Events Defined?</th>
            </tr>
	</thead>
	<tfoot>
            <tr class="headerrow">
                <th>' . $term_name . '</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Days Covered</th>
                <th>Education Days</th>
                <th>Off Days</th>
                <th>Events Defined?</th>
            </tr>
	</tfoot>
        <tbody>
        ';

$statuses = array(
        'CR' => " <b>Created / Not Started</b>", 
        'CL' => " <font color='red'><b>Closed</b></font>", 
        'AC' => " <font color='lightgreen'><b> Active / Open</b></font>"
    );
foreach($ay['EduQuarter'] as $quarter) {
$html .= 
    '       <tr>
                <th>' . $quarter['name'] . '</th>
                <td>' . $statuses[$quarter['status']] . '</td>
                <td>' . $quarter['start_date'] . '</td>
                <td>' . $quarter['end_date'] . '</td>
                <td>' . ((strtotime($quarter['end_date']) - strtotime($quarter['start_date'])) / (60 * 60 * 24) + 1) . '</td>
                <td>0</td>
                <td>0</td>
                <td>' . (count($quarter['EduCalendarEvent']) > 0? 'TRUE': '<font color=red>FALSE</font>') . '</td>
            </tr>';
}
$html .= 
    '     
    </tbody>
</table>';


// About the Classes
$html .= 
    '<h3>Classes</h3><table class="bpmTopic" >
	<thead>
            <tr class="headerrow">
                <th>Class/Grade</th>
                <th>Courses</th>
                <th>Payment Schedule</th>
                <th>Class Level</th>
                <th>Uni-Teacher?</th>
                <th>Grading Type</th>
                <th>Sections</th>
                <th>Teachers</th>
                <th>Campus</th>
            </tr>
	</thead>
	<tfoot>
            <tr class="headerrow">
                <th>Class/Grade</th>
                <th>Courses</th>
                <th>Payment Schedule</th>
                <th>Class Level</th>
                <th>Uni-Teacher?</th>
                <th>Grading Type</th>
                <th>Sections</th>
                <th>Teachers</th>
                <th>Campus</th>
            </tr>
	</tfoot>
        <tbody>
        ';
$months = array(1 => '01 - September', 2 => '02 - October',3 => '03 - November',4 => '04 - December',5 => '05 - January',
        6 => '06 - February',7 => '07 - March',8 => '08 - April',9 => '09 - May',
        10 => '10 - June',11 => '11 - July',12 => '12 - August'); 
$quarters = array(1 => $term_name . ' 1', 2 => $term_name . ' 2',3 => $term_name . ' 3',4 => $term_name . ' 4', 5 => 'Summer');
$class_levels = array(1 => 'Lower Level', 2 => 'Primary', 3 => 'Mid-Level', 4 => 'High Level');
$grading_types = array('N' => 'Numeric', 'A' => 'Alphabetic', 'G' => 'Evaluation Value', 'M' => 'Mixed');

foreach($ay['EduClass'] as $class) {
    $payment_table = '<table>';
    foreach($class['EduPaymentSchedule'] as $ps) {
        $payment_table .= '<tr><td>' . ($payment_mode == 'M'? $months[$ps['month']]: $quarters[$ps['month']]) . '</td>';
        $payment_table .= '<td>' . $ps['amount'] . '</td></tr>';
    }
    if($payment_table == '<table>'){
        $payment_table = 'Not Maintained';
    } else {
        $payment_table .= '</table>';
    }
    $html .= 
    '       <tr>
                <th>' . $class['EduClass']['name'] . '</th>
                <td>' . count($class['EduCourse']) . '</td>
                <td>' . $payment_table . '</td>
                <td>' . $class_levels[$class['EduClass']['class_level']] . '</td>
                <td>' . ($class['EduClass']['uni_teacher']? 'YES': 'NO') . '</td>
                <td>' . $grading_types[$class['EduClass']['grading_type']] . '</td>
                <td>' . count($class['EduSection']) . '</td>
                <td>0</td>
                <td>MAIN</td>
            </tr>';
}
$html .= 
    '     
    </tbody>
</table>';


// About the Global Settings
$html .= 
    '<h3>Global Settings</h3><table class="bpmTopic" >
	<thead>
            <tr class="headerrow">
                <th>Setting</th>
                <th>Value</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
	</thead>
	<tfoot>
            <tr class="headerrow">
                <th>Setting</th>
                <th>Value</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
	</tfoot>
        <tbody>
    ';

foreach($settings as $setting) {
$html .= 
    '       <tr>
                <th>' . $setting['Setting']['setting_key'] . '</th>
                <td>' . $setting['Setting']['setting_value'] . '</td>
                <td>' . $setting['Setting']['date_from'] . '</td>
                <td>' . $setting['Setting']['date_to'] . '</td>
            </tr>';
}
$html .= 
    '     
    </tbody>
</table>';


//==============================================================
//==============================================================
//==============================================================

include(APPLIBS . "mpdf/mpdf.php");

$mpdf=new mPDF('c','A4-L','','Nyala',30,25,26,26,19,14); 

$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins

$mpdf->defaultheaderfontsize = 10;	/* in pts */
$mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */

$mpdf->defaultfooterfontsize = 12;	/* in pts */
$mpdf->defaultfooterline = 1; 	/* 1 to include line below header/above footer */

$mpdf->SetHeader('{DATE d-m-Y}||' . $report_title . ' - ' . $company_name);

$mpdf->setHTMLFooter('<div align="center"><b><i>Page - {PAGENO}</i></b></div>') ;
$mpdf->setHTMLFooter('<div align="center"><b><i>Page - {PAGENO}</i></b></div>','E') ;

$mpdf->WriteHTML($html);
$mpdf->Output();
exit;

//==============================================================
//==============================================================
//==============================================================


?>