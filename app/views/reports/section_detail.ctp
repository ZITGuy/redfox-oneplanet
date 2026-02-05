				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Section
						<small><?php echo $section['EduSection']['name'] . ' (Class: ' . $section['EduClass']['name'] . ' of AY ' . $academic_years[$section['EduSection']['edu_academic_year_id']] . ')';  ?> </small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="#"><?php echo $this->Html->image('pdf.png', array('alt' => 'PDF')); ?></a></li>
					</ol>
				</section>
				
				<!-- Main content -->
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
							<div class="box">
								<div class="box-header">
									<h3 style="">Students</h3><br/>
								</div>
								<div class="box-body">
									<table id="example1" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>ID Number</th>
												<th>Full Name</th>
												<th>Status</th>
												<th>Date Enrolled</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($students as $student) { ?>
											<tr>
												<td><?php echo $this->Html->link($student['EduStudent']['identity_number'],  array('controller' => 'reports', 'action' => 'student_detail', $student['EduStudent']['id'])); ?></td>
												<td><?php echo $student['EduStudent']['name']; ?></td>
												<td><?php echo $statuses[$student['EduStudent']['status_id']]; ?></td>
												<td><?php echo $student['EduStudent']['registration_date']; ?></td>
											</tr>
											<?php } ?>

										</tbody>
										<tfoot>
											<tr>
												<th>ID Number</th>
												<th>Full Name</th>
												<th>Status</th>
												<th>Date Enrolled</th>
											</tr>
										</tfoot>
									</table>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div><!-- /.col -->
					</div>
				</section><!-- /.content -->