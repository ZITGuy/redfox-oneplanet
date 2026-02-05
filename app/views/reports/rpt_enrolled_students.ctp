<?php 	if($format == 'html') { ?>
				<!-- Main content -->
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
							<div class="box box-default collapsed-box box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Filter Box</h3>
									<div class="box-tools pull-right">
										<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
									</div><!-- /.box-tools -->
								</div><!-- /.box-header -->
								<div class="box-body">
									<form id="frmSearch" method="get" action="<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_enrolled_students')); ?>" class="col-md-6 form-horizontal">
										<div class="input-group margin">
											<select id="input_ay" name="input_ay" onselect="document.getElementById('frmSearch').submit()" class="col-sm-8 form-control select2">
												<option selected="selected">Select Academic Year</option>
												<?php foreach ($academic_years as $ay) { ?>
												<option value="<?php echo $ay['EduAcademicYear']['id']; ?>"><?php echo $ay['EduAcademicYear']['name']; ?></option>
												<?php } ?>
											</select>
											<span class="input-group-btn">
												<button class="btn btn-info btn-flat" type="submit">Go!</button>
											</span>
										</div>
										
									</form>
									<div class="btn-group pull-right" style="margin-right: 6px;">
										<button type="button" class="btn btn-default">Export Records to ...</button>
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
											<span class="caret"></span>
											<span class="sr-only">Toggle Dropdown</span>
										</button>
										<ul class="dropdown-menu" role="menu">
											<?php $base_query = "http://" . Configure::read('domain') . $_SERVER['REQUEST_URI']; ?>
											<?php $concat = '?'; if(strpos($base_query, '?') !== FALSE) $concat = '&'; ?>
											<li><a href="<?php echo $base_query . $concat . 'format=pdf'; ?>" target="_blank"> PDF</a></li>
											<li><a href="<?php echo $base_query . $concat . 'format=excel'; ?>" target="_blank">MS Excel</a></li>
										</ul>
									</div>
								</div><!-- /.box-body -->
							</div><!-- /.box -->

							<div class="box">
								<div class="box-header">
									<center>
										<h2 style="margin-bottom: -10px;">Enrolled Students</h2><br/>
										<h3 class="box-title">Academic Year - <?php echo $selected_ay['EduAcademicYear']['name']; ?></h3>
									</center>
								</div><!-- /.box-header -->
								<div class="box-body">
									
									<table id="example1" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>Student Full Name</th>
												<th>ID Number</th>
												<th>Grade</th>
												<th>Section</th>
												<th>Campus</th>
												<th>Date Enrolled</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($enrollments as $enrollment) { ?>
											<tr>
												<td><?php echo $enrollment['EduStudent']['name']; ?></td>
												<td><?php echo $enrollment['EduStudent']['identity_number']; ?></td>
												<td><?php echo $enrollment['EduRegistration'][0]['EduClass']['name']; ?></td>
												<td><?php echo ($enrollment['EduRegistration'][0]['edu_section_id'] == 0)? 'N/A': $enrollment['EduRegistration'][0]['EduSection']['name']; ?></td>
												<td><?php echo $enrollment['EduRegistration'][0]['EduCampus']['name']; ?></td>
												<td><?php echo $enrollment['EduStudent']['registration_date']; ?></td>
											</tr>
											<?php } ?>

										</tbody>
										<tfoot>
											<tr>
												<th>Student Full Name</th>
												<th>ID Number</th>
												<th>Grade</th>
												<th>Section</th>
												<th>Campus</th>
												<th>Date Enrolled</th>
											</tr>
										</tfoot>
									</table>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div><!-- /.col -->
					</div><!-- /.row -->
				</section><!-- /.content -->
<?php 	} else { 

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

$html .= '<table width="100%" style="margin: 10px; border: #fff thin solid;">
            <tr>
                <td align=right>
					
					<img src="http://' . Configure::read('domain') . Configure::read('localhost_string') . '/img/logo_cha.png" width=100px>
					
					
				</td>
				<td colspan="5">
					<center>
                        <h1>' . $company_name . '</h1>
                        <h2>Enrolled Students</h2>
                        <h3>Academic Year ' . $ay['EduAcademicYear']['name'] . '</h3>    
                    </center>
                </td>
            </tr>
        </table>';

$html .= '<table  width="100%" style="margin: 10px; border: #ccc thin solid;">
			<thead>
				<tr>
					<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Student Full Name</th>
					<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">ID Number</th>
					<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Grade</th>
					<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Section</th>
					<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Campus</th>
					<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Date Enrolled</th>
				</tr>
			</thead>
			<tbody>';

foreach ($enrollments as $enrollment) {
	$html .= '<tr>
					<td style="margin: 2px; border: #AACCAA thin solid;">' . $enrollment['EduStudent']['name'] . '</td>
					<td style="margin: 2px; border: #AACCAA thin solid;">' . $enrollment['EduStudent']['identity_number'] . '</td>
					<td style="margin: 2px; border: #AACCAA thin solid;">' . $enrollment['EduRegistration'][0]['EduClass']['name'] . '</td>
					<td style="margin: 2px; border: #AACCAA thin solid;">' . (($enrollment['EduRegistration'][0]['edu_section_id'] == 0)? 'N/A': $enrollment['EduRegistration'][0]['EduSection']['name']) . '</td>
					<td style="margin: 2px; border: #AACCAA thin solid;">' . $enrollment['EduRegistration'][0]['EduCampus']['name'] . '</td>
					<td style="margin: 2px; border: #AACCAA thin solid;">' . $enrollment['EduStudent']['registration_date'] . '</td>
				</tr>';
}

$html .= '</tbody>
			<tfoot>
				<tr>
					<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Student Full Name</th>
					<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">ID Number</th>
					<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Grade</th>
					<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Section</th>
					<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Campus</th>
					<th style="margin: 2px; border: #ccddcc thin solid; background-color: #ccddcc;">Date Enrolled</th>
				</tr>
			</tfoot>
		</table>';

//==============================================================
//==============================================================
//==============================================================
if($format == 'pdf') {
    include(APPLIBS . "mpdf/mpdf.php");

    $mpdf=new mPDF('c','A4-L','','Nyala',15,15,16,16,9,9, 'L'); 
    //$mode='',$format='A4',$default_font_size=0,$default_font='',$mgl=15,$mgr=15,$mgt=16,$mgb=16,$mgh=9,$mgf=9, $orientation='P'
	
	$mpdf->debug = false;
    $mpdf->SetWatermarkText('');
    $mpdf->watermark_font = 'DejaVuSansCondensed';
    $mpdf->showWatermarkText = true;

    $mpdf->useAdobeCJK = true;
    $mpdf->SetAutoFont(AUTOFONT_ALL);

    $mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins

    $mpdf->defaultheaderfontsize = 10;	/* in pts */
    //$mpdf->defaultheaderfontstyle = 'B';	/* blank, B, I, or BI */
    $mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */

    $mpdf->defaultfooterfontsize = 12;	/* in pts */
    //$mpdf->defaultfooterfontstyle = 'B';	/* blank, B, I, or BI */
    $mpdf->defaultfooterline = 1; 	/* 1 to include line below header/above footer */

    $mpdf->SetHeader('{DATE j-m-Y}||Enrolled Students - ' . $company_name);
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
    
} else {
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=income_statement.xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
    echo $html; //no ending ; here
}

exit;
//==============================================================
//==============================================================
//==============================================================   

} ?>