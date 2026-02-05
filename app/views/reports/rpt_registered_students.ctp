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
									<form id="frmSearch" method="get" action="<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_registered_students')); ?>" class="col-md-6 form-horizontal">
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
											<li><a href="<?= $base_query . $concat . 'format=pdf' ?>" target="_blank"> PDF</a></li>
											<li><a href="<?= $base_query . $concat . 'format=excel' ?>" target="_blank">MS Excel</a></li>
											<li><a href="<?= $base_query . $concat . 'format=word' ?>" target="_blank"> MS Word</a></li>
										</ul>
									</div>
								</div><!-- /.box-body -->
							</div><!-- /.box -->

							<div class="box">
								<div class="box-header">
									<center>
										<h2 style="margin-bottom: -10px;">Registered Students</h2><br/>
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
												<th>Date Registered</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($registrations as $registration) { ?>
											<tr>
												<td><?php echo $registration['EduRegistration']['name']; ?></td>
												<td><?php echo $registration['EduStudent']['identity_number']; ?></td>
												<td><?php echo $registration['EduClass']['name']; ?></td>
												<td><?php echo ($registration['EduRegistration']['edu_section_id'] == 0)? 'N/A': $registration['EduSection']['name']; ?></td>
												<td><?php echo $registration['EduCampus']['name']; ?></td>
												<td><?php echo date('Y-m-d', strtotime($registration['EduRegistration']['created'])); ?></td>
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
												<th>Date Registered</th>
											</tr>
										</tfoot>
									</table>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div><!-- /.col -->
					</div><!-- /.row -->
				</section><!-- /.content -->