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

$html .= '<pre>' . print_r($receipt, true) . '</pre>
		<table width="95%" style="margin: 4px; border: #0000CC thin solid;">
            <tr>
                <td>
                    <center>
                        <h2>' . $company_name . '</h2>
                        <h1>Payment Receipt</h1>
                    </center>
                    <table border="0" width="100%">
                        <tr>
                            <td align="center">TIN: ' . $company_tin . '</td>
                        </tr>
                        <tr>
                            <td align="left" valign="top">
                                Date: <u>' . $receipt['EduReceipt']['created'] . '</u> <br/>
                                Ref: ___________________ <br/>
                            </td>
                        </tr>
                    </table>
					<hr/>
                    <table border="0" width="100%">
                        <tr>
                            <th align="right" valign="top" width="50%">
                                Cheque Number:
                            </th>
                            <td>
                                '.$transaction['cheque_number'].'
                            </td>
                        </tr>
                        <tr>
                            <th align="right" valign="top">
                                Invoice Number:
                            </th>
                            <td>
                                '.$transaction['invoice_number'].'
                            </td>
                        </tr>
						<tr>
                            <th align="right" valign="top">
                                &nbsp;
                            </th>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
						<tr>
                            <th align="right" valign="top">
                                &nbsp;
                            </th>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
						<tr>
                            <th align="right" valign="top">
                                &nbsp;
                            </th>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
						<tr>
                            <th align="right" valign="top">
                                &nbsp;
                            </th>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="left">
                                <hr/>
                                ARDI Youth Academy - '.date('F d, Y').'
                            </td>
                        </tr>
                    </table>
					<hr/>
                    <table border="0" width="100%">
                        <tr>
                            <th align="right" valign="top" width="40%">
                                Cheque Number:
                            </th>
                            <td>
                                '.$transaction['cheque_number'].'
                            </td>
                        </tr>
                        <tr>
                            <th align="right" valign="top">
                                Invoice Number:
                            </th>
                            <td>
                                '.$transaction['invoice_number'].'
                            </td>
                        </tr>
						<tr>
                            <th align="right" valign="top">
                                &nbsp;
                            </th>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
						<tr>
                            <th align="right" valign="top">
                                &nbsp;
                            </th>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
						<tr>
                            <th align="right" valign="top">
                                &nbsp;
                            </th>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
						<tr>
                            <th align="right" valign="top">
                                &nbsp;
                            </th>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="left">
                                <hr/>
                                ARDI Youth Academy - '.date('F d, Y').'
                            </td>
                        </tr>
                    </table>
					<table border="0" width="100%">
						<tr>
							<td colspan=2>
								<hr/>
							</td>
						</tr>
                        <tr>
                            <td align="left" valign="top" width="50%">
								To: [Parent][Telephone] <br />
								Student: [Student Name] <br />
								Grade: [Student Grade] <br />
								Academic Year: [Academic Year]
							</td>
                            <td align="right" valign="top" width="50%">
                                Receipt No: [Receipt Number] <br />
								Mode: [CASH/CHK] <br />
								CRM No: [CRM No] <br />
								Chk No: [CHKNO]
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>';

//==============================================================
//==============================================================
//==============================================================

include (APPLIBS . "mpdf/mpdf.php");

$mpdf = new mPDF('c', 'A4', '', 'Nyala', 15, 15, 16, 16, 9, 9, 'P');

$mpdf->SetWatermarkText('ATTACHMENT');
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->showWatermarkText = true;

$mpdf->useAdobeCJK = true;
$mpdf->SetAutoFont(AUTOFONT_ALL);

$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins

$mpdf->defaultheaderfontsize = 10;	/* in pts */
$mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */

$mpdf->defaultfooterfontsize = 12;	/* in pts */
$mpdf->defaultfooterline = 1; 	/* 1 to include line below header/above footer */

$mpdf->SetHeader('{DATE j-m-Y}||OnePlanet International School');
$mpdf->SetFooter(array(
        'C' => array(
		'content' => 'www.oneplanetschool.com',
		'font-family' => 'serif',
		'font-style' => 'BI',
		'font-size' => '18',	/* gives default */
	),
        'line' => 1,		/* 1 to include line below header/above footer */
    ), 'E'	/* defines footer for Even Pages */
);

$mpdf->SetFooter(array(
        'C' => array(
		'content' => 'www.oneplanetschool.com',
		'font-family' => 'serif',
		'font-style' => 'BI',
		'font-size' => '18',	/* gives default */
	),
        'line' => 1,		/* 1 to include line below header/above footer */
    ), 'O'	/* defines footer for Odd Pages */
);

$mpdf->WriteHTML($html);
$mpdf->Output();
exit;

//==============================================================
//==============================================================
//==============================================================
