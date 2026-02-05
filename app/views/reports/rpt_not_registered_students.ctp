				<!-- Main content -->
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
							<?php if(!isset($format)) { ?>
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
											<?php $concat = '?'; ?>
											<li><a href="<?php echo $concat . 'format=html'; ?>" target="_blank"> HTML</a></li>
											<li><a href="<?php echo $concat . 'format=pdf'; ?>" target="_blank"> PDF</a></li>
											<li><a href="<?php echo $concat . 'format=excel'; ?>" target="_blank">MS Excel</a></li>
										</ul>
									</div>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
							<?php } ?>
							<div class="box">
								<div class="box-header">
									<center>
										<h2 style="margin-bottom: -10px;">Not Registered Students</h2> 
										<?php $url = $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_not_registered_students')); ?>
										<?php $tag_image = $this->Html->image('print_m.png', array('align' => 'right', 'alt' => 'Print')); ?>
										<?php if(count($students)) { ?>
										
										<h3 style="margin-bottom: -10px;">
											Grade : <?php echo ($class_id == 0 || $class_id == 99)? 'All': $students[0]['EduClass']['name']; ?>										
										</h3>
										<br/>
										<?php } ?>
									</center>
								</div><!-- /.box-header -->
								<div class="box-body">
									<?php if(!isset($format)) { ?>
									<table id="alphatable" class="table table-bordered table-striped" style="text-align:center">
										<tr>
											<td><a href=<?php echo $url . '/99'; ?>>All</a></td>
											<td><a href=<?php echo $url . '/1'; ?>>LKG</a></td>
											<td><a href=<?php echo $url . '/2'; ?>>UKG</a></td>
											<td><a href=<?php echo $url . '/3'; ?>>Preparatory</a></td>
											<td><a href=<?php echo $url . '/4'; ?>>1</a></td>
											<td><a href=<?php echo $url . '/5'; ?>>2</a></td>
											<td><a href=<?php echo $url . '/6'; ?>>3</a></td>
											<td><a href=<?php echo $url . '/7'; ?>>4</a></td>
											<td><a href=<?php echo $url . '/8'; ?>>5</a></td>
											<td><a href=<?php echo $url . '/9'; ?>>6</a></td>
											<td><a href=<?php echo $url . '/10'; ?>>7</a></td>
											<td><a href=<?php echo $url . '/11'; ?>>8</a></td>
											<td><a href=<?php echo $url . '/12'; ?>>9</a></td>
											<td><a href=<?php echo $url . '/13'; ?>>10</a></td>
											<td><a href=<?php echo $url . '/14'; ?>>11 - Natural</a></td>
											<td><a href=<?php echo $url . '/15'; ?>>11 - Social</a></td>
											<td><a href=<?php echo $url . '/16'; ?>>12 - Natural</a></td>
											<td><a href=<?php echo $url . '/17'; ?>>12 - Social</a></td>
										</tr>
									</table>
									<?php } ?>
									<br/>
									<?php if(isset($format)) { echo '<center>'; } ?>
									
									<table id="example1" <?php if(!isset($format)) { echo 'class="table table-bordered table-striped"'; } else { echo 'border=1'; } ?>>
										<thead>
											<tr>
												<?php if(isset($format)) { echo '<th>No</th>'; } ?>
												<th>ID Number</th>
												<th>Full Name</th>
												<th>Grade-Section</th>
												<th>Average</th>
												<th>Rank</th>
											</tr>
										</thead>
										<tbody>
											<?php $count = 1; ?>
											<?php foreach ($students as $student) { ?>
											<tr>
												<?php if(isset($format)) { echo '<td>' . $count++ . '&nbsp;&nbsp;</td>'; } ?>
												<td><?php echo isset($format)? $student['EduStudent']['identity_number']: $this->Html->link($student['EduStudent']['identity_number'],  array('controller' => 'reports', 'action' => 'student_detail', $student['EduStudent']['id'])); ?>&nbsp;&nbsp;</td>
												<td><?php echo strtoupper($student['EduStudent']['name']); ?></td>
												<td><?php echo isset($format)? $student['EduClass']['name'] . '-' . $student['EduSection']['name']: $this->Html->link($student['EduClass']['name'] . '-' . $student['EduSection']['name'], array('controller' => 'reports', 'action' => 'section_detail', $student['EduRegistration']['edu_section_id'])); ?></td>
												<td><?php echo $student['EduRegistration']['grand_total_average']; ?></td>
												<td><?php //echo $student['EduRegistration']['rank']; ?>-</td>
											</tr>
											<?php } ?>

										</tbody>
										<tfoot>
											<tr>
												<?php if(isset($format)) { echo '<th>No</th>'; } ?>
												<th>ID Number</th>
												<th>Full Name</th>
												<th>Grade-Section</th>
												<th>Average</th>
												<th>Rank</th>
											</tr>
										</tfoot>
									</table>
									<?php if(isset($format)) { echo '</center>'; } ?>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div><!-- /.col -->
					</div><!-- /.row -->
				</section><!-- /.content -->