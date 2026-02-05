//<script>
		<?php
			$this->ExtForm->create('Permission');
			$this->ExtForm->defineFieldFunctions();
		?>
		var PermissionEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'permissions', 'action' => 'Edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $permission['Permission']['id'])); ?>,
				<?php 
					$options = array();
					$options['value'] = $permission['Permission']['name'];
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array('xtype' => 'textarea', 'height' => '50');
					$options['value'] = $permission['Permission']['description'];
					$this->ExtForm->input('description', $options);
				?>
			]
		});
		
		var PermissionEditWindow = new Ext.Window({
			title: '<?php __('Edit Permission'); ?>',
			width: 400,
			height: 185,
			layout: 'fit',
			modal: true,
			resizable: false,
			plain: true,
			bodyStyle: 'padding:5px;',
			buttonAlign: 'right',
			items: PermissionEditForm,

			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					PermissionEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Successfully saved!'); ?>');
							PermissionEditWindow.close();
							RefreshPermissionData();
						},
						failure: function(f,a){
							Ext.Msg.alert('<?php __('Warning'); ?>', a.result.errormsg);
						}
					});
				}
			},{
				text: '<?php __('Cancel'); ?>',
				handler: function(btn){
					PermissionEditWindow.close();
				}
			}]
		});
