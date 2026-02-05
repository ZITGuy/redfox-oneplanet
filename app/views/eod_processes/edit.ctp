//<script>
		<?php
			$this->ExtForm->create('EodProcess');
			$this->ExtForm->defineFieldFunctions();
		?>
		var EodProcessEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'eod_processes', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $eod_process['EodProcess']['id'])); ?>,
				<?php 
					$options = array();
					$options['value'] = $eod_process['EodProcess']['name'];
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $eod_process['EodProcess']['process_date'];
					$this->ExtForm->input('process_date', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $users;
					$options['value'] = $eod_process['EodProcess']['user_id'];
					$this->ExtForm->input('user_id', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $eod_process['EodProcess']['task1_backup_taken'];
					$this->ExtForm->input('task1_backup_taken', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $eod_process['EodProcess']['task2_portal_updated'];
					$this->ExtForm->input('task2_portal_updated', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $eod_process['EodProcess']['task3_ftp_sent'];
					$this->ExtForm->input('task3_ftp_sent', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $eod_process['EodProcess']['backup_type'];
					$this->ExtForm->input('backup_type', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $eod_process['EodProcess']['incremental_count'];
					$this->ExtForm->input('incremental_count', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $eod_process['EodProcess']['backup_incremental_file'];
					$this->ExtForm->input('backup_incremental_file', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $eod_process['EodProcess']['backup_full_file'];
					$this->ExtForm->input('backup_full_file', $options);
				?>			]
		});
		
		var EodProcessEditWindow = new Ext.Window({
			title: '<?php __('Edit Eod Process'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: EodProcessEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					EodProcessEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Eod Process.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(EodProcessEditWindow.collapsed)
						EodProcessEditWindow.expand(true);
					else
						EodProcessEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					EodProcessEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							EodProcessEditWindow.close();
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
					EodProcessEditWindow.close();
				}
			}]
		});
