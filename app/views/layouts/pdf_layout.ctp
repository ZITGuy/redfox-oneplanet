<?php 
$html = $content_for_layout;
	
if($format == "pdf") {
	include(APPLIBS . "mpdf/mpdf.php");

	$mpdf=new mPDF('c','A4','','Nyala',15,15,16,16,9,9, 'P'); 
	//$mode='',$format='A4',$default_font_size=0,$default_font='',$mgl=15,$mgr=15,$mgt=16,$mgb=16,$mgh=9,$mgf=9, $orientation='P'
	
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
		), 'O'	/* defines footer for Even Pages */
	);

	$mpdf->WriteHTML($html);
	$mpdf->Output();
	exit;
} elseif($format == "excel") {
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=report.xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
    echo $html; //no ending ; here
	echo $html;
} else {
	echo $html;
	echo "<script> window.print(); </script>";
}
?>