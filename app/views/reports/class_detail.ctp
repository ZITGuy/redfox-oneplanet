				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Class/Grade
						<small><?php echo $class['EduClass']['name'] . ' (' . $class['EduClassLevel']['name'] . ')';  ?> </small>
					</h1>
				</section>
				
				<!-- Main content -->
				<section class="content">
					<div class="row">
						<div class="col-xs-6">
							<div class="box">
								<div class="box-header">
									<h3 style="">Sections</h3><br/>
								</div>
								<div class="box-body">
									<table class="table table-bordered table-striped col-sm-12">
										<thead>
											<tr>
												<th>Academic Year</th>
												<th>Section</th>
												<th>Home Room Teacher</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($class['EduSection'] as $section) { ?>
											<tr>
												<td><?php echo $academic_years[$section['edu_academic_year_id']]; ?></td>
												<td><?php echo $this->Html->link($section['name'] . ' (' . count($section['EduRegistration']) . ' Students)', array('controller' => 'reports', 'action' => 'section_detail', $section['id'])); ?><sup><a href=# title="This may not be the exact value">?</a></sup></td>
												<td><?php echo (empty($section['EduTeacher'])? '-': $section['EduTeacher']['identity_number']); ?></td>
											</tr>
											<?php } ?>

										</tbody>
										<tfoot>
											<tr>
												<th>Academic Year</th>
												<th>Section</th>
												<th>Home Room Teacher</th>
											</tr>
										</tfoot>
									</table>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div><!-- /.col -->
						
						<div class="col-xs-6">
							<div class="box">
								<div class="box-header">
									<h3 style="">Courses</h3><br/>
								</div>
								<div class="box-body">
									<table class="table table-bordered table-striped col-sm-12">
										<thead>
											<tr>
												<th>Subject Name</th>
												<th>Min. for Pass</th>
												<th>Compulsory?</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($class['EduCourse'] as $course) { ?>
											<tr>
												<td><?php echo $course['EduSubject']['name']; ?></td>
												<td><?php echo $course['min_for_pass']; ?></td>
												<td><?php echo ($course['is_mandatory']? 'Yes': 'No'); ?></td>
											</tr>
											<?php } ?>

										</tbody>
										<tfoot>
											<tr>
												<th>Subject Name</th>
												<th>Min. for Pass</th>
												<th>Compulsory?</th>
											</tr>
										</tfoot>
									</table>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div><!-- /.col -->
					</div>
				</section><!-- /.content -->