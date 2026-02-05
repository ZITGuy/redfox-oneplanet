<?php

$html = '<style>
    body { font-family: DejaVuSansCondensed; font-size: 9pt;  }
    p {     text-align: justify; margin-bottom: 4pt; margin-top:0pt;  }

    table {font-family: DejaVuSansCondensed; font-size: 9pt; line-height: 1.2;
        margin-top: 2pt; margin-bottom: 5pt;
        border-collapse: collapse;  }

    thead { font-weight: bold; vertical-align: bottom;  }
    tfoot { font-weight: bold; vertical-align: top; }
    thead td { font-weight: bold; }
    tfoot td { font-weight: bold; }

    thead td, thead th, tfoot td, tfoot th { font-variant: small-caps; }

    .headerrow td, .headerrow th { background-gradient: linear #b7cebd #f5f8f5 0 1 0 0.2;  }
    .footerrow td, .footerrow th { background-gradient: linear #b7cebd #f5f8f5 0 1 0 0.2;  }

    th {    font-weight: bold; 
        vertical-align: top; 
        text-align:left; 
        padding-left: 2mm; 
        padding-right: 2mm; 
        padding-top: 0.5mm; 
        padding-bottom: 0.5mm; 
     }

    td {    padding-left: 2mm; 
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

    hr {    width: 70%; height: 1px; 
        text-align: center; color: #999999; 
        margin-top: 8pt; margin-bottom: 8pt; }

    a { color: #000066; font-style: normal; text-decoration: underline; 
        font-weight: normal; }

    ul {    text-indent: 5mm; margin-bottom: 9pt; }
    ol {    text-indent: 5mm; margin-bottom: 9pt; }

    pre { font-family: DejaVuSansMono; font-size: 9pt; margin-top: 5pt; margin-bottom: 5pt; }

    .breadcrumb {
        text-align: right; font-size: 8pt; font-family: DejaVuSerifCondensed; color: #666666;
        font-weight: bold; font-style: normal; margin-bottom: 6pt; }

    .evenrow td, .evenrow th { background-color: #f5f8f5; } 
    .oddrow td, .oddrow th { background-color: #e3ece4; } 

    .bpmTopic { background-color: #e3ece4; width: 100%; }
    .bpmTopicC { background-color: #e3ece4; width: 100%;}
    .bpmNoLines { background-color: #e3ece4; }
    .bpmNoLinesC { background-color: #e3ece4; }
    .bpmClear {     }
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
    .pmhMiddleRight {   text-align:right; vertical-align:middle; }
    .pmhBottomCenter { text-align:center; vertical-align:bottom; }
    .pmhBottomRight {   text-align:right; vertical-align:bottom; }
    .pmhTopCenter { text-align:center; vertical-align:top; }
    .pmhTopRight {  text-align:right; vertical-align:top; }
    .pmhTopLeft {   text-align:left; vertical-align:top; }
    .pmhBottomLeft {    text-align:left; vertical-align:bottom; }
    .pmhMiddleLeft {    text-align:left; vertical-align:middle; }

    .infobox { margin-top:10pt; background-color:#DDDDBB; text-align:center; border:1px solid #880000; }

    .bpmTopic td, .bpmTopic th  {   border-top: 1px solid #FFFFFF; }
    .bpmTopicC td, .bpmTopicC th  { border-top: 1px solid #FFFFFF; }
    .bpmTopnTail td, .bpmTopnTail th  { border-top: 1px solid #FFFFFF; }
    .bpmTopnTailC td, .bpmTopnTailC th  {   border-top: 1px solid #FFFFFF; }
        br.paging { page-break-after: always; }
    </style>';

$grading_type = $section['EduClass']['grading_type'];

$html .= '<table width=100%><tr><td width=25% valign=bottom><img width=120px src="http://'. Configure::read('domain') . Configure::read('localhost_string') . '/img/logo_cha.png"> <br/> </td>';
$html .= '<td width=50%>';
$html .= '<center><table width=100%><tr><td align=center valign=bottom>';
$html .= '<h2>' . Configure::read('company_name') . '</h2>';
$html .= '<h3>Students ' . $section['EduAcademicYear']['name'] . '</h3>';
$html .= '<h3><b>Grade '. $section['EduClass']['name'] . ' ' . $section['EduSection']['name'] . '</b></h3>';

$html .= '</td></tr></table></center>';
$html .= '</td><td valign=bottom align=right><b>Total Number of Students: ' . count($registrations) . '</b>';
$html .= '</td></tr></table>';

$content = '<table width=100% border=1>';
// construct the header row
$content .= '<thead><tr>';
$content .= '<th valign=bottom>No</th>';
$content .= '<th valign=bottom>Student <br/>(ID, Sex, Age)</th>';
$content .= '</tr></thead>';
$content .= '<tbody>';
// display the students records
$count = 1;
foreach($registrations as $registration){
    $content .= '<tr>';
    $y1 = date('Y', strtotime($registration['EduStudent']['birth_date']));
    $y2 = date('Y', strtotime($section['EduAcademicYear']['end_date']));
    $age = $y2 - $y1;
    $content .= '<td rowspan=' . ($quarter_count + 1) . '>' . $count++ . '</td>';
	$content .= '<td rowspan=' . ($quarter_count + 1) . '>' . $registration['EduRegistration']['name'] . ' (' . $registration['EduStudent']['identity_number'] . 
			', ' . $registration['EduStudent']['gender'] . ', ' . $age . 'Y)</td>';
    $content .= '</tr>';
}
$content .= '</tbody>';
$content .= '</table>';
$html .= substr($content, 0);

//==============================================================
//==============================================================
//==============================================================

include(APPLIBS . "mpdf/mpdf.php");

$mpdf=null;
if($grading_type == 'G'){
    //$mpdf=new mPDF('','A4','','Nyala',15,15,16,16,9,9, 'P'); 
	$mpdf=new mPDF('','A4','','Nyala',15,15,25,50,15,32, 'P'); 
} else {
    //$mpdf=new mPDF('','A4-L','','Nyala',30,25,26,26,19,14); 
	$mpdf=new mPDF('','A4','','Nyala',15,15,25,50,15,32, 'P'); 
}

$mpdf->SetWatermarkImage('/' . Configure::read('localhost_string') . '/img/bg.png', 0.1, '', array(45,-10));
$mpdf->showWatermarkImage = true;

$mpdf->useAdobeCJK = true;
$mpdf->SetAutoFont(AUTOFONT_ALL);

$mpdf->mirrorMargins = 1;

$mpdf->defaultheaderfontsize = 10;
$mpdf->defaultheaderline = 1;

$mpdf->defaultfooterfontsize = 12;
$mpdf->defaultfooterline = 1; 	

$mpdf->SetHeader('Students of Grade '. $section['EduClass']['name'] . ' ' . $section['EduSection']['name'] . '||' . $section['EduAcademicYear']['name']);
$mpdf->SetFooter("OPIS");

$mpdf->WriteHTML($html);
$mpdf->Output();
exit;

?>