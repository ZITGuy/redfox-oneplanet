							<div class="box">
								<div class="box-header">
									<center>
										<h2 style="margin-bottom: -10px;">Active Students</h2><br/>
										<?php if(count($students) > 0) { ?>
										<h3 style="margin-bottom: -10px;">
											Grade : <?php echo $students[0]['EduClass']['name']; ?>	
										</h3><br/>
										<?php } ?>
									</center>
								</div><!-- /.box-header -->
								<div class="box-body">
									<table id="example1" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>ID Number</th>
												<th>Full Name</th>
												<th>Section</th>
												<th>Date Enrolled</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($students as $student) { ?>
											<tr>
												<td><?php echo $student['EduStudent']['identity_number']; ?></td>
												<td><?php echo strtoupper($student['EduStudent']['name']); ?></td>
												<td><?php echo $student['EduSection']['name']; ?></td>
												<td><?php echo $student['EduStudent']['registration_date']; ?></td>
											</tr>
											<?php } ?>

										</tbody>
										<tfoot>
											<tr>
												<th>ID Number</th>
												<th>Full Name</th>
												<th>Section</th>
												<th>Date Enrolled</th>
											</tr>
										</tfoot>
									</table>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
							<script> window.print(); </script>