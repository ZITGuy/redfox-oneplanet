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
										<h2 style="margin-bottom: -10px;">Active Teachers of Current Academic Year</h2> 
										<?php $url = $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_active_teachers')); ?>
										<?php $tag_image = $this->Html->image('print_m.png', array('align' => 'right', 'alt' => 'Print')); ?>
										<?php if(count($students)) { ?>
										<?php $url_print = $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_active_teachers_print')); ?>
										<a href="<?php echo $url_print; ?>" target="_blank"><?php echo $tag_image; ?></a><br/>
										<br/>
										<?php } ?>
									</center>
								</div><!-- /.box-header -->
								<div class="box-body">
									<table id="example1" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>ID Number</th>
												<th>Full Name</th>
												<th>Sections</th>
												<th>Subjects</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($teachers as $teacher) { ?>
											<tr>
												<td><?php echo $teacher['EduTeacher']['identity_number']; ?></td>
												<td><?php echo strtoupper($teacher['User']['Person']['first_name']); ?></td>
												<td><?php echo 'List of Class Sections' ?></td>
												<td><?php echo 'List of Subjects'; ?></td>
											</tr>
											<?php } ?>

										</tbody>
										<tfoot>
											<tr>
												<th>ID Number</th>
												<th>Full Name</th>
												<th>Sections</th>
												<th>Subjects</th>
											</tr>
										</tfoot>
									</table>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div><!-- /.col -->
					</div><!-- /.row -->
				</section><!-- /.content -->
