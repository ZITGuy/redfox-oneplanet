				<!-- Main content -->
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
							<div class="col-md-12">
								<!-- Widget: user widget style 1 -->
								<div class="box box-widget widget-user">
									<!-- Add the bg color to the header using any of the bg-* classes -->
									<div class="widget-user-header bg-aqua-active">
										<h3 class="widget-user-username"><?php echo $student['EduStudent']['name']; ?></h3>
										<h5 class="widget-user-desc">Grade <?php echo $student['EduRegistration'][count($student['EduRegistration']) - 1]['EduClass']['name'] . $student['EduRegistration'][count($student['EduRegistration']) - 1]['EduSection']['name']; ?></h5>
										<a href="#" class="bg-aqua-active pull-right">
											<?php echo $this->Html->image('pdf.png', array('title' => 'Get Student Profile in PDF')); ?>
										</a>
									</div>
									<div class="widget-user-image">
										<?php 
											$image = $student['EduStudent']['gender'] == 'M'? 'student-m.jpg': 'student-f.jpg'; 
											if($student['EduStudent']['photo_file_name'] != 'No file'){
												$image = 'students/' . $student['EduStudent']['photo_file_name'];
											}
										?>
										<?php echo $this->Html->image($image, array('class' => 'img-circle', 'alt' => 'Student Photo')) ?>
									</div>
									<div class="box-footer">
										<div class="row">
											<div class="col-sm-4 border-right">
												<div class="description-block">
													<h5 class="description-header">Age</h5>
													<span class="description-text">
														<?php 
															$age = (time() - strtotime($student['EduStudent']['birth_date']))/(60*60*24*365); 
															echo round($age, 1) . ' Years';
														?>
													</span>
												</div><!-- /.description-block -->
											</div><!-- /.col -->
											<div class="col-sm-4 border-right">
												<div class="description-block">
													<h5 class="description-header">Sex</h5>
													<span class="description-text"><?php echo $student['EduStudent']['gender'] == 'M'? 'Male': 'Female'; ?></span>
												</div><!-- /.description-block -->
											</div><!-- /.col -->
											<div class="col-sm-4">
												<div class="description-block">
													<h5 class="description-header">Campus</h5>
													<span class="description-text">Main Campus</span>
												</div><!-- /.description-block -->
											</div><!-- /.col -->
										</div><!-- /.row -->
									</div>
								</div><!-- /.widget-user -->
							</div><!-- /.col -->
						</div>
						<div class="col-xs-6">
							
							<div class="box">
								<div class="box-header">
									<center>
										<h3 style="">Academic Information</h3><br/>
									</center>
								</div>
								<div class="box-body">
									<table class="table table-bordered table-striped col-sm-12">
										<thead>
											<tr>
												<th>Academic Year</th>
												<th>Grade - Section</th>
												<th>Rank</th>
												<th>Status</th>
												<th>Remark</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($student['EduRegistration'] as $registration) { ?>
											<tr>
												<td><?php echo $academic_years[$registration['EduSection']['edu_academic_year_id']]; ?></td>
												<td><?php echo $registration['EduClass']['name'] . '-' . $registration['EduSection']['name']; ?></td>
												<td><?php echo $registration['rank']; ?></td>
												<td><?php echo $status[$registration['status_id']]; ?></td>
												<td><?php echo $registration['remark']; ?></td>
											</tr>
											<?php } ?>

										</tbody>
										<tfoot>
											<tr>
												<th>Academic Year</th>
												<th>Grade - Section</th>
												<th>Rank</th>
												<th>Status</th>
												<th>Remark</th>
											</tr>
										</tfoot>
									</table>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div><!-- /.col -->
						
						<div class="col-xs-6">
							
							<div class="box">
								<div class="box-header">
									<center>
										<h3 style="">Family Information</h3><br/>
									</center>
								</div>
								<div class="box-body">
									<table class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>Name</th>
												<th>Relationship</th>
												<th>Phone/ID</th>
											</tr>
										</thead>
										<tbody>
											<?php $status = array(1 => 'Active', 2 => 'Inactive', 3 => 'Dismissed', 4 => 'Withdrawn', 5 => 'Transferred', 6 => 'Incomplete',  7 => 'Unregistered but Enrolled', 8 => 'Unknown'); ?>
											<?php if(isset($student['EduParent']['EduParentDetail'])){ ?>
											<?php foreach ($student['EduParent']['EduParentDetail'] as $pd) { ?>
											<tr>
												<td><?php echo $pd['first_name'] . ' ' . $pd['middle_name'] . ' ' . $pd['last_name']; ?></td>
												<td><?php echo $pd['relationship']; ?></td>
												<td><?php echo $pd['mobile']; ?></td>
											</tr>
											<?php } ?>
											<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<th>Name</th>
												<th>Relationship</th>
												<th>Phone/ID</th>
											</tr>
										</tfoot>
									</table>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div><!-- /.col -->
						
						<div class="col-xs-12">
							
							<div class="box">
								<div class="box-header">
									<center>
										<h3 style="">Student Evaluation</h3><br/>
									</center>
								</div>
								<div class="box-body">
									<table class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>Academic Year</th>
												<th>Grade - Section</th>
												<th>Homeroom Teacher</th>
												<th>General Comment</th>
											</tr>
										</thead>
										<tbody>
											<?php $status = array('P' => 'Promoted', 'N' => 'Not-Promoted', 'A' => 'Active'); ?>
											<?php foreach ($student['EduRegistration'] as $registration) { ?>
											<tr>
												<td><?php echo $academic_years[$registration['EduSection']['edu_academic_year_id']]; ?></td>
												<td><?php echo $registration['EduClass']['name'] . '-' . $registration['EduSection']['name']; ?></td>
												<td><?php echo $registration['EduSection']['edu_teacher_id'] == 0? '-': ''; ?></td>
												<td><?php echo $registration['general_comment']; ?></td>
											</tr>
											<?php } ?>

										</tbody>
										<tfoot>
											<tr>
												<th>Academic Year</th>
												<th>Grade - Section</th>
												<th>Homeroom Teacher</th>
												<th>General Comment</th>
											</tr>
										</tfoot>
									</table>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div><!-- /.col -->
					</div><!-- /.row -->
				</section><!-- /.content -->