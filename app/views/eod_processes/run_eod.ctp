//<script>
		<?php
			$this->ExtForm->create('EodProcess');
			$this->ExtForm->defineFieldFunctions();
		?>
		var EodProcessRunForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'eod_processes', 'action' => 'run_eod')); ?>',
			defaultType: 'textfield',

			items: [
				<?php 
					$options = array();
					$options['value'] = $name;
					$options['readOnly'] = true;
					$this->ExtForm->input('name', $options);
				?>,
				<?php
					$options = array('fieldLabel' => 'Backup Date');
					$options['value'] = $process_date;
					$options['readOnly'] = true;
					$this->ExtForm->input('process_date', $options);
				?>,
				<?php 
					$options = array('xtype' => 'combo', 'fieldLabel' => 'Backup Type');
					$options['items'] = array('I' => 'Incremental Backup', 'F' => 'Full Backup');
					$options['value'] = $backup_type;
					$options['readOnly'] = true;
					$this->ExtForm->input('backup_type', $options);
				?>
			]
		});
		
		var EodProcessRunWindow = new Ext.Window({
			title: '<?php __('End of Day Process'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: EodProcessRunForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					EodProcessRunForm.getForm().reset();
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
					if(EodProcessRunWindow.collapsed)
						EodProcessRunWindow.expand(true);
					else
						EodProcessRunWindow.collapse(true);
				}
			}],
			buttons: [{
				text: '<?php __('Run'); ?>',
				handler: function(btn) {
					Ext.MessageBox.confirm(
						'Confirm', 
						'During EOD Run, Backup wiil be taken, portal will be updated, FTP will be sent to server. Are you sure you want to do that?', 
						function(btn){
							if(btn == 'yes') {
								EodProcessRunForm.getForm().submit({
									waitMsg: '<?php __('Submitting your data...'); ?>',
									waitTitle: '<?php __('Wait Please...'); ?>',
									success: function(f,a){
										Ext.Msg.show({
											title: '<?php __('Success'); ?>',
											buttons: Ext.MessageBox.OK,
											msg: a.result.msg,
											icon: Ext.MessageBox.INFO,
											fn: function(btn){
												if (btn == 'ok'){
													location.reload();
												}
											}
										});
										EodProcessRunWindow.close();
										
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
						}
					);
				}
			}, {
				text: '<?php __('Cancel'); ?>',
				handler: function(btn) {
					EodProcessRunWindow.close();
				}
			}]
		});
		