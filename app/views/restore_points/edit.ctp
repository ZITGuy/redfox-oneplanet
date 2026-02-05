//<script>
		<?php
			$this->ExtForm->create('RestorePoint');
			$this->ExtForm->defineFieldFunctions();
		?>
		var RestorePointEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'restore_points', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $restore_point['RestorePoint']['id'])); ?>,
				<?php 
					$options = array();
					$options['value'] = $restore_point['RestorePoint']['name'];
					$this->ExtForm->input('name', $options);
				?>			]
		});
		
		var RestorePointEditWindow = new Ext.Window({
			title: '<?php __('Edit Restore Point'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: RestorePointEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					RestorePointEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Restore Point.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(RestorePointEditWindow.collapsed)
						RestorePointEditWindow.expand(true);
					else
						RestorePointEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					RestorePointEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
								icon: Ext.MessageBox.INFO
							});
							RestorePointEditWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentRestorePointData();
<?php } else { ?>
							RefreshRestorePointData();
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
					RestorePointEditWindow.close();
				}
			}]
		});
