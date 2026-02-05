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

	hr {
		width: 70%; height: 1px;
		text-align: center; color: #999999;
		margin-top: 8pt; margin-bottom: 8pt;
	}

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
                    <h2>Lesson Plan</h2>
                </center>
            </td>
	</tr>
	<tr>
            <td align="center">&nbsp;</td>
	</tr>
</table>';

// About the Academic Year
$html .=
'<h3>Lesson Plan Details</h3>
    <table class="bpmTopic">
	<tbody>
            <tr>
                <th>Grade and Section: </th>
                <td>' . $lesson_plan['EduSection']['EduClass']['name'] . ' ' .
					$lesson_plan['EduSection']['name'] . '</td>
            </tr>
            <tr>
                <th>Academic Year: </th>
                <td>' . $academic_year . '</td>
            </tr>
            <tr>
                <th>Course: </th>
                <td>' . $lesson_plan['EduCourse']['description'] . '</td>
            </tr>
            <tr>
                <th>Prepared By: </th>
                <td>' . $lesson_plan['Maker']['Person']['first_name'] . ' ' .
					$lesson_plan['Maker']['Person']['middle_name'] . ' ' . $lesson_plan['Maker']['Person']['last_name'] . '</td>
            </tr>
            <tr>
                <th>Approved By: </th>
                <td>' . $lesson_plan['Checker']['Person']['first_name'] . ' ' .
					$lesson_plan['Checker']['Person']['middle_name'] . ' ' . $lesson_plan['Checker']['Person']['last_name'] . '</td>
            </tr>
	</tbody>
</table>';

// About the Lesson Plan Items
$html .=
    '<hr/><table class="bpmTopic" >
	<thead>
            <tr class="headerrow">
                <th>Date</th>
                <th>Outline Description</th>
                <th>Activity</th>
                <th>Materials Needed</th>
            </tr>
	</thead>
	<tfoot>
            <tr class="headerrow">
                <th>Date</th>
                <th>Outline Description</th>
                <th>Activity</th>
                <th>Materials Needed</th>
            </tr>
	</tfoot>
        <tbody>
        ';

foreach ($lesson_plan['EduLessonPlanItem'] as $lpi) {
$html .=
    '       <tr>
                <th>' . date('D M. d, Y', strtotime($lpi['EduDay']['date'])) . '</th>
                <td>' . $lpi['EduOutline']['name'] . '</td>
                <td>' . $lpi['activity'] . '</td>
                <td>' . $lpi['materials_needed'] . '</td>
            </tr>';
}
$html .=
    '
    </tbody>
</table>';


//==============================================================
//==============================================================
//==============================================================

include (APPLIBS . "mpdf/mpdf.php");

$mpdf=new mPDF('c', 'A4-L', '', 'Nyala', 30, 25, 26, 26, 19, 14);

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
