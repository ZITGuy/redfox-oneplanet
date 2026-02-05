<?php

$days = array(1 => array(), 2 => array(), 3 => array(), 4 => array(), 5 => array());
foreach ($edu_schedule['EduPeriod'] as $p){
    $days[$p['day']][$p['period']] = $p;
}

$html = '
	<style>
	.period {
		width: 120px;
		height: 100px;
		border: 10px groove pink;
	}
	.period-header {
		width: 120px;
		height: 50px;
		border: 10px groove gray;
	}

	hr {
		width: 70%; height: 1px;
		text-align: center; color: #999999;
		margin-top: 8pt; margin-bottom: 8pt;
	}
</style>';

$html .=
'<table border="0" width="100%">
	<tr>
            <td align="center">
                <center>
                    <h1>' . $company_name . '</h1>
                </center>
            </td>
	</tr>
        <tr>
            <td align="center">
                &nbsp;
            </td>
	</tr>
</table>';

$html .= '<table id="periods" width="100%" style="margin: 2px; border: #0000CC thin solid;">
            <tr>
                <td align=center><font size="12px"><b>1-B</b></font></td>';
for ($i = 1; $i <= $num_periods; $i++) {
    $html .= '<td class="period-header" align=center><b>Period ' . $i . '</b></td>';
}
$html .= '</tr>';
$ds = array(1 => 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');

foreach ($days as $day => $period){
    $html .= '<tr>
                <td class="period-header"><b>' . $ds[$day] . '</b></td>';
for ($i = 1; $i <= $num_periods; $i++) {
    $html .= '<td class="period"><table width="100%"><tr><td align=left><b>' .
		$period[$i]['course'] .
		'</b></td></tr><tr><td>&nbsp;</td></tr><tr><td align=right><font color=#adaddf size=1.2em><i>' .
		$period[$i]['teacher'] . '</i></font></td></tr></table></td>';
}
 $html .= '</tr>';
}
$html .= '</table>';


//==============================================================
//==============================================================
//==============================================================

include(APPLIBS . "mpdf/mpdf.php");

$mpdf=new mPDF('', 'A4-L', '', 'Nyala', 15, 15, 16, 16, 9, 9, 'L');

$mpdf->SetWatermarkText($waterMark);
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->showWatermarkText = true;

$mpdf->useAdobeCJK = true;
$mpdf->SetAutoFont(AUTOFONT_ALL);

$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins

$mpdf->defaultheaderfontsize = 10;	/* in pts */
$mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */

$mpdf->defaultfooterfontsize = 12;	/* in pts */
$mpdf->defaultfooterline = 1; 	/* 1 to include line below header/above footer */

$mpdf->SetHeader('{DATE j-m-Y}||' . $edu_schedule['EduSchedule']['name']);
$mpdf->SetFooter(array(
        'C' => array(
		'content' => $company_url,
		'font-family' => 'serif',
		'font-style' => 'BI',
		'font-size' => '18',	/* gives default */
	),
        'line' => 1,		/* 1 to include line below header/above footer */
    ), 'E'	/* defines footer for Even Pages */
);

$mpdf->SetFooter(array(
        'C' => array(
		'content' => $company_url,
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


    