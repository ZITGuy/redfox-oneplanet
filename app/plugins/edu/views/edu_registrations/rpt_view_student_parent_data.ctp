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
				<h2>' . $report_title . '</h2>
				<h3>Academic Year: ' . $academic_year . '</h3>
			</center>
		</td>
	</tr>
	<tr>
		<td align="center">' . $company_address . '</td>
	</tr>
	<tr>
		<td align="right">
			<h2>Total Students: ' . (count($edu_students)) . '</h2>
			<h2>Campus: Main</h2>
		</td>
	</tr>
	<tr>
		<td align="center">&nbsp;</td>
	</tr>
</table>
<table class="bpmTopic">
     <thead>
	<tr class="headerrow">
	    <th width="6%" valign="middle" rowspan="2">#</th>
            <th width="20%" valign="middle" rowspan="2">Name of Students</th>
	    <th width="12%" valign="middle" rowspan="2">Students Nationality</th>
	    <th width="12%" align="center" colspan="2">Name of Parents</th>
	    <th width="10%" align="center" colspan="2">Phone</th>
            <th width="10%" align="center" colspan="2">Occupation</th>
            <th width="10%" align="center" colspan="2">Nationality</th>
            <th width="20%" align="center" colspan="2">Email</th>
		</tr>
        <tr>
            <th align="center">Father</th>
            <th align="center">Mother</th>
            <th align="center">Father</th>
            <th align="center">Mother</th>
            <th align="center">Father</th>
            <th align="center">Mother</th>
            <th align="center">Father</th>
            <th align="center">Mother</th>
            <th align="center">Father</th>
            <th align="center">Mother</th>
        </tr>
	</thead>
	<tbody>
';

$count = 1;
foreach($edu_students as $student) {
    $html .= '
	 <tr>
            <td>' . $count . '</td>
	    <td>' . $student['st']['name'] . '</td>
	    <td>' . $student['st']['nationality'] . '</td>
	    <td>' . ($student['fpd']['first_name'] != 'NA' ? $student['fpd']['first_name'] : '') . ' ' . ($student['fpd']['middle_name'] != 'NA' ? $student['fpd']['middle_name'] : '') . ' ' . ($student['fpd']['last_name'] != 'NA' ? $student['fpd']['last_name'] : '') . '</td>
	    <td>' . ($student['mpd']['first_name'] != 'NA' ? $student['mpd']['first_name'] : '') . ' ' . ($student['mpd']['middle_name'] != 'NA' ? $student['mpd']['middle_name'] : '') . ' ' . ($student['mpd']['last_name'] != 'NA' ? $student['mpd']['last_name'] : '') . '</td>
            <td>' . $student['fpd']['mobile'] . '</td>
            <td>' . $student['mpd']['mobile'] . '</td>
            <td>' . $student['fpd']['occupation'] . '</td>
            <td>' . $student['mpd']['occupation'] . '</td>
            <td>' . $student['fpd']['nationality'] . '</td>
            <td>' . $student['mpd']['nationality'] . '</td>
            <td>' . $student['fpd']['email'] . '</td>
            <td>' . $student['mpd']['email'] . '</td>
        </tr>';
    $count++;
    //if($count > 100) {
    //    break;
    //}
}

$html .= '
	</tbody>
</table>';

//$html .= print_r($edu_students, true);

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
