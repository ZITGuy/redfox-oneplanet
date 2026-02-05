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
        br.paging { page-break-after: always; }
	</style>';

$logo = ''; //'<img src="http://' . Configure::read('domain') . Configure::read('localhost_string') . '/img/logo.png" width=100 height=100 />';
	
$html .= '
		<div style="position: absolute; top: 60; left: 57; width: 680px; height: 200px;">
			<table border="0" width="100%">
				<tr>
					<td align="center">
						<center>
							<h1>' . $company_name . '</h1>
							<h1>Payment Attachement</h1>
						</center>
					</td>
				</tr>
				<tr>
					<td align="center">' . $company_address . '</td>
				</tr>
				<tr>
					<td align="center">TIN: ' . $company_tin . '</td>
				</tr>
				<tr>
					<td align="center">&nbsp;</td>
				</tr>
				<tr>
					<td align="right" valign="top">
						Date: <u><b><i>' . $receipt['EduReceipt']['invoice_date'] . '</i></b></u> <br/>
						Ref: <u><b><i>' . $receipt['EduReceipt']['reference_number'] . '</i></b></u> <br/>
					</td>
				</tr>
			</table>
		</div>
		<div style="position: absolute; top: 70; left: 67; width: 100px; height: 100px;">
			<img src="http://' . Configure::read('domain') . Configure::read('localhost_string') . '/img/logo_cha.png" width=100px>
		</div>
		<div style="position: absolute; top: 270; left: 57; width: 680px; height: 200px; background-color: #fafafa">
			<table border="0" width="100%">
				<tr>
					<td align="left" valign="top" width="50%">
						To: <b><i>' . $receipt['EduReceipt']['parent_name'] . '<br/>' . $receipt['EduReceipt']['parent_address'] . '</i></b><br /><br />
						Student: <b><i>' . $receipt['EduReceipt']['student_name'] . '</i></b> <br />
						Grade: <b><i>' . $receipt['EduReceipt']['student_class'] . '</i></b> <br />
						Academic Year: <b><i>' . $receipt['EduReceipt']['student_academic_year'] . '</i></b> 
					</td>
					<td align="right" valign="top" width="50%">
						Receipt No: <b><i>' . ($receipt['EduReceipt']['name'] == ''? 'NA': $receipt['EduReceipt']['name']) . '</i></b> <br />
						CRM No: <b><i>' . $receipt['EduReceipt']['crm_number'] . '</i></b>
					</td>
				</tr>
			</table>
		</div>
		<div style="position: absolute; top: 480; left: 57; width: 680px; height: 400px; background-color: #fafafa">
			<table class="bpmTopic">
				<tr>
					<th width="5%">
						S.No
					</th>
					<th width="45%">
						Particular
					</th>
					<th width="10%">
						Unit
					</th>
					<th width="20%" align="right">
						Amount
					</th>
					<th width="20%" align="right">
						Total
					</th>
				</tr>';
$count = 1;
$total = 0;
foreach($receipt['EduReceiptItem'] as $ri) {
	$total += $ri['amount'];
	$html .= '
				<tr>
					<td>
						' . $count++ . '
					</td>
					<td>
						' . $ri['name'] . '
					</td>
					<td>
						1
					</td>
					<td align="right">
						' . number_format($ri['amount'], 2, '.', ',') . '
					</td>
					<td align="right">
						<strong>' . number_format($ri['amount'], 2, '.', ',') . '</strong>
					</td>
				</tr>';
}

	/* VAT line
	$html .= '
				<tr>
					<td colspan="4" align="right">
						VAT (15%)
					</td>
					<td align="right">
						<strong>' . number_format(0, 2, '.', ',') . '</strong>
					</td>
				</tr>';
	*/
	// Grand Total Line
	$html .= '
				<tr>
					<td colspan="4" align="right">
						<strong>Total</strong>
					</td>
					<td align="right">
						<strong><u>' . number_format($total, 2, '.', ',') . '</u></strong>
					</td>
				</tr>';
	
        $prepared_by = $this->Session->read('Auth.Person.first_name') . ' ' . $this->Session->read('Auth.Person.middle_name');
        
	$html .= '		
			</table>
		</div>
		<div style="position: absolute; top: 890; left: 57; width: 680px; height: 100px; background-color: #fafafa">
			<table border="0" width="100%">
				<tr>
					<th width="50%">
						Prepared By
					</th>
					<th width="50%">
						&nbsp;
					</th>
				</tr>
				<tr>
					<td>
						Name <u>' . $prepared_by . '</u>
					</td>
					<td>
						Signator or Stamp: _____________________
					</td>
				</tr>
			</table>
		</div>
		
		';

//$html .= '<br class="paging" />';
//$html .= file_get_contents($base_url . '/edu/edu_students/get_enrollment_certificate/' . $receipt['EduReceipt']['edu_student_id']);

//==============================================================
//==============================================================
//==============================================================

//include(APPLIBS . "mpdf/mpdf.php");
//
//$mpdf=new mPDF('c','A4','','Nyala',15,15,16,16,9,9, 'P'); 
////$mode='',$format='A4',$default_font_size=0,$default_font='',$mgl=15,$mgr=15,$mgt=16,$mgb=16,$mgh=9,$mgf=9, $orientation='P'
//
////$mpdf->SetWatermarkImage('/redfox/img/bg.png', 0.1, '', array(45,-10));
////$mpdf->showWatermarkImage = true;
//$mpdf->SetWatermarkText('ATTACHMENT');
//$mpdf->watermark_font = 'DejaVuSansCondensed';
//$mpdf->showWatermarkText = true;
//
//$mpdf->useAdobeCJK = true;
//$mpdf->SetAutoFont(AUTOFONT_ALL);
//
//$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins
//
//$mpdf->defaultheaderfontsize = 10;	/* in pts */
////$mpdf->defaultheaderfontstyle = 'B';	/* blank, B, I, or BI */
//$mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */
//
//$mpdf->defaultfooterfontsize = 12;	/* in pts */
////$mpdf->defaultfooterfontstyle = 'B';	/* blank, B, I, or BI */
//$mpdf->defaultfooterline = 1; 	/* 1 to include line below header/above footer */
//
//$mpdf->SetHeader('{DATE j-m-Y}||' . $company_name);
//$mpdf->SetFooter(array(
//        'C' => array(
//		'content' => $company_url,
//		'font-family' => 'serif',
//		'font-style' => 'BI',
//		'font-size' => '18',	/* gives default */
//	),
//        'line' => 1,		/* 1 to include line below header/above footer */
//    ), 'E'	/* defines footer for Even Pages */
//);
//
//$mpdf->SetFooter(array(
//        'C' => array(
//		'content' => $company_url,
//		'font-family' => 'serif',
//		'font-style' => 'BI',
//		'font-size' => '18',	/* gives default */
//	),
//        'line' => 1,		/* 1 to include line below header/above footer */
//    ), 'O'	/* defines footer for Odd Pages */
//);
//
//$mpdf->WriteHTML($html);
//$mpdf->Output();

echo $html;
echo "<script> window.print(); </script>";
exit;

//==============================================================
//==============================================================
//==============================================================


?>
