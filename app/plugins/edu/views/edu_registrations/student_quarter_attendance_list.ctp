<?php

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
                    <h1>One Planet International School</h1>
                    <h2>Attendance Count for Quarter</h2>
                    <h3>' .  $section['EduClass']['name'] . ' - ' . $section['EduSection']['name'] . '</h3>
                </center>
            </td>
	</tr>
        <tr>
            <td align="center">
                &nbsp;
            </td>
	</tr>
</table>';

$html .= '<table id="periods" width="100%" style="margin: 2px; border: #2222dd thin solid;" border="1">
        <tr>
            <th>No</th>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Term 1</th>
            <th>Term 2</th>
            <th>Term 3</th>
            <th>Total</th>
        </tr>';
$count = 1;
foreach ($regs as $reg) {
    $html .=
        '<tr>
            <td>' . $count . '</td>
            <td>' . $reg['EduStudent']['identity_number'] . '</td>
            <td>' . $reg['EduRegistration']['name'] . '</td>
            ';
        $total_absentee = 0;
        foreach ($reg['EduRegistrationQuarter'] as $rq) {
            $html .= '<td>' . $rq['absentees'] . '</td>';
            $total_absentee += $rq['absentees'];
        }
    $html .=
         '  <td>' . $total_absentee . '</td>
        </tr>
        ';

    $count++;
}

$html .= '</table>';


//==============================================================
//==============================================================
//==============================================================

include (APPLIBS . "mpdf/mpdf.php");

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

$mpdf->SetHeader('{DATE j-m-Y}||One Planet International');
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
