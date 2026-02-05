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

$html .= '<table border="0" width="100%">
<tbody>
	<tr>
		<td>
			<h2>Payments Receipt</h2>
		</td>
	</tr>
	<tr>
		<td>
			<p>This is the details of the payment receipt attachement file for the current transaction.</p>' . 
			pr($payments, true) . '
		</td>
	</tr>
</tbody>
</table>';

//==============================================================
//==============================================================
//==============================================================

include(APPLIBS . "mpdf/mpdf.php");

$mpdf=new mPDF('c','A4-L','','Nyala',15,15,16,16,9,9); 
//$mode='',$format='A4',$default_font_size=0,$default_font='',$mgl=15,$mgr=15,$mgt=16,$mgb=16,$mgh=9,$mgf=9, $orientation='P'

$mpdf->SetWatermarkImage('/smis/img/bg.png', 0.1, '', array(45,-10));
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

$mpdf->SetHeader('{DATE j-m-Y}||ARDI YOUTH ACADEMY');
$mpdf->SetFooter(array(
        'C' => array(
		'content' => 'www.ardiyouthacademy.com',
		'font-family' => 'serif',
		'font-style' => 'BI',
		'font-size' => '18',	/* gives default */
	),
        'line' => 1,		/* 1 to include line below header/above footer */
    ), 'E'	/* defines footer for Even Pages */
);

$mpdf->SetFooter(array(
        'C' => array(
		'content' => 'www.ardiyouthacademy.com',
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


?>