//<script>
		<?php
			$this->ExtForm->create('EodProcess');
			$this->ExtForm->defineFieldFunctions();
		?>
		var EodProcessAddForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'eod_processes', 'action' => 'add')); ?>',
			defaultType: 'textfield',

			items: [
				<?php 
					$options = array();
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('process_date', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $users;
					$this->ExtForm->input('user_id', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('task1_backup_taken', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('task2_portal_updated', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('task3_ftp_sent', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('backup_type', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('incremental_count', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('backup_incremental_file', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('backup_full_file', $options);
				?>			]
		});
		
		var EodProcessAddWindow = new Ext.Window({
			title: '<?php __('Add Eod Process'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: EodProcessAddForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					EodProcessAddForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to insert a new Eod Process.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(EodProcessAddWindow.collapsed)
						EodProcessAddWindow.expand(true);
					else
						EodProcessAddWindow.collapse(true);
				}
			}],
			buttons: [  {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					EodProcessAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							EodProcessAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
							RefreshParentEodProcessData();
<?php } else { ?>
							RefreshEodProcessData();
<?php } ?>
						},
						failure: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Warning'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.errormsg,
                                icon: Ext.MessageBox.ERROR
							});
						}
					});
				}
			}, {
				text: '<?php __('Save & Close'); ?>',
				handler: function(btn){
					EodProcessAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							EodProcessAddWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentEodProcessData();
<?php } else { ?>
							RefreshEodProcessData();
<?php } ?>
						},
						failure: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Warning'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.errormsg,
                                icon: Ext.MessageBox.ERROR
							});
						}
					});
				}
			},{
				text: '<?php __('Cancel'); ?>',
				handler: function(btn){
					EodProcessAddWindow.close();
				}
			}]
		});
