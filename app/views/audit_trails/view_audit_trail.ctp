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
                <h2>AUDIT TRAIL</h2>
                <h3>From: ' . $from_date . ' to ' . $to_date . '</h3>
            </center>
        </td>
    </tr>
    <tr>
        <td align="center">' . $company_address . '</td>
    </tr>
    <tr>
        <td align="center">&nbsp;</td>
    </tr>
</table>
<table class="bpmTopic">
    <thead>
        <tr class="headerrow">
            <th>Date Time</th>
            <th>User</th>
            <th>Description</th>
            <th>Model Object</th>
            <th>Old Value</th>
            <th>New Value</th>
        </tr>
    </thead>
    <tfoot>
        <tr class="footerrow">
            <th>Date Time</th>
            <th>User</th>
            <th>Description</th>
            <th>Model Object</th>
            <th>Old Value</th>
            <th>New Value</th>
        </tr>
    </tfoot>
    <tbody>
';

$actions = array('C' => 'Created', 'U' => 'Edited', 'D' => 'Deleted');

foreach($audit_trails as $audit_trail) {
    $html .= '
	<tr>
            <th>' . $audit_trail['AuditTrail']['created'] . '</th>
            <td>' . $audit_trail['User']['username'] . '</td>
            <td>' . ($audit_trail['AuditTrail']['audit_desc'] == ''? strtoupper($audit_trail['User']['username']) . ' ' . 
                    $actions[$audit_trail['AuditTrail']['action_made']] . ' record of table ' .
                    $audit_trail['AuditTrail']['table_name']: $audit_trail['AuditTrail']['audit_desc']) . '</td>
            <td>' . $audit_trail['AuditTrail']['table_name'] . '</td>
            <td>' . ($audit_trail['AuditTrail']['old_value'] == ''? 'NULL': 'DATA') . '</td>
            <td>' . ($audit_trail['AuditTrail']['new_value'] == ''? 'NULL': 'DATA') . '</td>
        </tr>';
}

$html .= '
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