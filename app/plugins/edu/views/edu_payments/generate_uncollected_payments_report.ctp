<?php

$html = '
<style>
    body {
        font-family: sans-serif;
		font-size: 12pt;
    }
    @media screen, print {
        page-break-after: hr;
    }
</style>';

$html .= '<table width="100%" style="margin: 10px; border: #fff thin solid;">
            <tr>
                <td>
                    <center>
                        <h2>One Planet International School</h2>
                        <h1>Uncollected Payments ' . ($opt == 1? '': 'Summary') . '</h1>
						<h2>As of ' . $today . '</h2>
                    </center>
                </td>
            </tr>
        </table>';

$count = count($payments);
$tuition_total = 0;
$discount_total = 0;
$penalty_total = 0;


$detail = '<h2>Details</h2><table width="100%" style="margin: 1px; border: #fff thin solid;">
<thead>
	<tr>
		<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">No</th>
		<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Student Name</th>
		<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">ID</th>
		<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Due Date</th>
		<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Amount</th>
		<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Penalty</th>
		<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Discount</th>
		<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Total Uncollected</th>
	</tr>
</thead>
<tbody>';

$detail .= '
<tfoot>
	<tr>
		<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">No</th>
		<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Student Name</th>
		<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">ID</th>
		<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Due Date</th>
		<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Amount</th>
		<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Penalty</th>
		<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Discount</th>
		<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Total Uncollected</th>
	</tr>
</tfoot>';

$count = 0;
foreach($payments as $payment) {
	$reg_indicator = '';
	if($payment['EduPayment']['edu_payment_schedule_id'] != -1) {
		$tuition_total += $payment['EduPayment']['paid_amount'];
	} else {
		$reg_indicator = '* ';
	}
	$discount_total += $payment['EduPayment']['discount'] + $payment['EduPayment']['sibling_discount'] * $payment['EduPayment']['paid_amount'] / 100;
	$penalty_total += $payment['EduPayment']['penalty'] * $payment['EduPayment']['paid_amount'] / 100; // for summary table
	$penalty = number_format($payment['EduPayment']['penalty'] * $payment['EduPayment']['paid_amount'] / 100, 2);
	if($penalty == 0) {
		$penalty = '-';
	}
	$discount = number_format($payment['EduPayment']['discount'] + ($payment['EduPayment']['sibling_discount']) * $payment['EduPayment']['paid_amount'] / 100, 2);
	if($discount == 0) {
		$discount = '-';
	} else {
		$discount = '(' . $discount . ')';
	}
	
	$count++;
	$detail .= '<tr>
		<td style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;">' . $count . '</td>
		<td style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;">' . $payment['EduStudent']['name'] . '</td>
		<td style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;">&nbsp;' . $payment['EduStudent']['identity_number'] . '&nbsp;</td>
		<td style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;">&nbsp;' . $payment['EduPaymentSchedule']['due_date'] . '&nbsp;</td>
		<td style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;" align=right>&nbsp;' . $reg_indicator . number_format($payment['EduPayment']['paid_amount'], 2) . '&nbsp;</td>
		<td style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;" align=right>&nbsp;' . $penalty . '&nbsp;</td>
		<td style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;" align=right>&nbsp;' . $discount . '&nbsp;</td>
		<td style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;" align=right>&nbsp;' . number_format((($payment['EduPayment']['paid_amount'] + $payment['EduPayment']['penalty'] * $payment['EduPayment']['paid_amount'] / 100) - $payment['EduPayment']['discount'] * $payment['EduPayment']['paid_amount'] / 100), 2) . '&nbsp;</td>
	</tr>';
}



$detail .= '</tbody>
</table>';

$html .= ($opt == 1? '<h2>Summary</h2>': '<br/><br/><br/><br/>') . ($opt == 1? '': '<center>') . '<table border="1" width="60%">
<tbody>
	<tr>
		<th align=right style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;">Number of Uncollected Payments : </th>
		<td align=right style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;">' . number_format($count) . '</td>
	</tr>
	<tr>
		<th align=right style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;">Total Uncollected Amount: </th>
		<td align=right style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;">' . number_format($tuition_total, 2) . '</td>
	</tr>
	<tr>
		<th align=right style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;">Total Discounts and Scholarship: </th>
		<td align=right style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;">(' . number_format($discount_total, 2) . ')</td>
	</tr>
	<tr>
		<th align=right style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;">Total Penality: </th>
		<td align=right style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;">' . number_format($penalty_total, 2) . '</td>
	</tr>
	<tr>
		<th align=right style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;">Net Uncollected: </th>
		<td align=right style="margin: 1px; border: #eeeeff thin solid; background-color: #ffffff;"><u><strong>' . number_format((($tuition_total + $penalty_total) - $discount_total), 2) . '</strong></u></td>
	</tr>
</tbody>
</table>';
if($opt == 1)
	$html .= '<br/><br/>' . $detail;
else 
	$html .= '</center>';

//==============================================================
//==============================================================
//==============================================================

include(APPLIBS . "mpdf/mpdf.php");

$mpdf=new mPDF('c','A4-L','','Nyala',15,15,16,16,9,9); 
//$mode='',$format='A4',$default_font_size=0,$default_font='',$mgl=15,$mgr=15,$mgt=16,$mgb=16,$mgh=9,$mgf=9, $orientation='P'

$mpdf->SetWatermarkImage('/redfox/img/bg.png', 0.1, '', array(45,-10));
$mpdf->showWatermarkImage = true;

$mpdf->useAdobeCJK = true;
$mpdf->SetAutoFont(AUTOFONT_ALL);

$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins

$mpdf->defaultheaderfontsize = 10;	/* in pts */
//$mpdf->defaultheaderfontstyle = 'B';	/* blank, B, I, or BI */
$mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */

$mpdf->defaultfooterfontsize = 12;	/* in pts */
//$mpdf->defaultfooterfontstyle = 'B';	/* blank, B, I, or BI */
$mpdf->defaultfooterline = 1; 	/* 1 to include line below header/above footer */

$mpdf->SetHeader('{DATE j-m-Y}||One Planet International School');
$mpdf->SetFooter(array(
        'C' => array(
		'content' => 'www.oneplanet.com',
		'font-family' => 'serif',
		'font-style' => 'BI',
		'font-size' => '18',	/* gives default */
	),
        'line' => 1,		/* 1 to include line below header/above footer */
    ), 'E'	/* defines footer for Even Pages */
);

$mpdf->SetFooter(array(
        'C' => array(
		'content' => 'www.oneplanet.com',
		'font-family' => 'serif',
		'font-style' => 'BI',
		'font-size' => '18',	/* gives default */
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

//echo $html;

?>