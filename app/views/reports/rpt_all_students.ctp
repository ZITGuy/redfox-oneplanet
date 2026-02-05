				<!-- Main content -->
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
							<div class="box box-default collapsed-box box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Export Data</h3>
									<div class="box-tools pull-right">
										<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
									</div><!-- /.box-tools -->
								</div><!-- /.box-header -->
								<div class="box-body">
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
										<h2 style="margin-bottom: -10px;">All Students</h2> <?php echo $this->Html->image('print_m.png', array('align' => 'right', 'alt' => 'Print', 'title' => 'Print', 'url' => '#')); ?><br/>
									</center>
								</div><!-- /.box-header -->
								<div class="box-body">
									<?php $url = $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_all_students')); ?>
									<table id="alphatable" class="table table-bordered table-striped" style="text-align:center">
										<tr>
											<td><a href=<?php echo $url . '/ALL'; ?>>ALL</a></td>
											<td><a href=<?php echo $url . '/A'; ?>>A</a></td>
											<td><a href=<?php echo $url . '/B'; ?>>B</a></td>
											<td><a href=<?php echo $url . '/C'; ?>>C</a></td>
											<td><a href=<?php echo $url . '/D'; ?>>D</a></td>
											<td><a href=<?php echo $url . '/E'; ?>>E</a></td>
											<td><a href=<?php echo $url . '/F'; ?>>F</a></td>
											<td><a href=<?php echo $url . '/G'; ?>>G</a></td>
											<td><a href=<?php echo $url . '/H'; ?>>H</a></td>
											<td><a href=<?php echo $url . '/I'; ?>>I</a></td>
											<td><a href=<?php echo $url . '/J'; ?>>J</a></td>
											<td><a href=<?php echo $url . '/K'; ?>>K</a></td>
											<td><a href=<?php echo $url . '/L'; ?>>L</a></td>
											<td><a href=<?php echo $url . '/M'; ?>>M</a></td>
											<td><a href=<?php echo $url . '/N'; ?>>N</a></td>
											<td><a href=<?php echo $url . '/O'; ?>>O</a></td>
											<td><a href=<?php echo $url . '/P'; ?>>P</a></td>
											<td><a href=<?php echo $url . '/Q'; ?>>Q</a></td>
											<td><a href=<?php echo $url . '/R'; ?>>R</a></td>
											<td><a href=<?php echo $url . '/S'; ?>>S</a></td>
											<td><a href=<?php echo $url . '/T'; ?>>T</a></td>
											<td><a href=<?php echo $url . '/U'; ?>>U</a></td>
											<td><a href=<?php echo $url . '/V'; ?>>V</a></td>
											<td><a href=<?php echo $url . '/W'; ?>>W</a></td>
											<td><a href=<?php echo $url . '/X'; ?>>X</a></td>
											<td><a href=<?php echo $url . '/Y'; ?>>Y</a></td>
											<td><a href=<?php echo $url . '/Z'; ?>>Z</a></td>
										</tr>
									</table>
									
									<br/>
									<table id="example1" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>ID Number</th>
												<th>Full Name</th>
												<th>Grade-Section</th>
												<th>Campus</th>
												<th>Status</th>
												<th>Mother</th>
												<th>Father</th>
												<th>Date Enrolled</th>
											</tr>
										</thead>
										<tbody>
											<?php $status = array(1 => 'Active', 2 => 'Inactive', 3 => 'Dismissed', 4 => 'Withdrawn', 5 => 'Transferred', 6 => 'Incomplete',  7 => 'Unregistered but Enrolled', 8 => 'Unknown'); ?>
											<?php foreach ($students as $student) { ?>
											<tr>
												<td><?php echo $this->Html->link($student['EduStudent']['identity_number'],  array('controller' => 'reports', 'action' => 'student_detail', $student['EduStudent']['id'])); ?></td>
												<td><?php echo strtoupper($student['EduStudent']['name']); ?></td>
												<td><?php echo $this->Html->link($student['EduRegistration'][count($student['EduRegistration']) - 1]['EduClass']['name'], array('controller' => 'reports', 'action' => 'class_detail', $student['EduRegistration'][count($student['EduRegistration']) - 1]['EduClass']['id'])) . (($student['EduRegistration'][count($student['EduRegistration']) - 1]['edu_section_id'] == 0)? '' : '-' . $student['EduRegistration'][count($student['EduRegistration']) - 1]['EduSection']['name']); ?></td>
												<td><?php echo $student['EduRegistration'][count($student['EduRegistration']) - 1]['EduCampus']['name']; ?></td>
												<td><?php echo $student['Status']['name']; ?></td>
												<td><?php echo isset($student['EduParent']['authorized_person'])? $this->Html->link($student['EduParent']['authorized_person'], array('controller' => 'reports', 'action' => 'parent_detail', $student['EduStudent']['id'])): 'NA'; ?></td>
												<td><?php echo isset($student['EduParent']['authorized_person'])? $this->Html->link($student['EduParent']['authorized_person'], array('controller' => 'reports', 'action' => 'parent_detail', $student['EduStudent']['id'])): 'NA'; ?></td>
												<td><?php echo $student['EduStudent']['registration_date']; ?></td>
											</tr>
											<?php } ?>

										</tbody>
										<tfoot>
											<tr>
												<th>ID Number</th>
												<th>Full Name</th>
												<th>Grade-Section</th>
												<th>Campus</th>
												<th>Status</th>
												<th>Mother</th>
												<th>Father</th>
												<th>Date Enrolled</th>
											</tr>
										</tfoot>
									</table>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div><!-- /.col -->
					</div><!-- /.row -->
				</section><!-- /.content -->