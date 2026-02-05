//<script>
		<?php
			$this->ExtForm->create('MessageTemplate');
			$this->ExtForm->defineFieldFunctions();
		?>
		var MessageTemplateEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'message_templates', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $message_template['MessageTemplate']['id'])); ?>,
				<?php 
					$options = array('readOnly' => true);
					$options['value'] = $message_template['MessageTemplate']['name'];
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array('xtype' => 'textarea', 'height' => '100px');
					$options['value'] = $message_template['MessageTemplate']['body'];
					$this->ExtForm->input('body', $options);
				?>
			]
		});
		
		var MessageTemplateEditWindow = new Ext.Window({
			title: '<?php __('Edit Message Template'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: MessageTemplateEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					MessageTemplateEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Message Template.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(MessageTemplateEditWindow.collapsed)
						MessageTemplateEditWindow.expand(true);
					else
						MessageTemplateEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					MessageTemplateEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							MessageTemplateEditWindow.close();
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
					MessageTemplateEditWindow.close();
				}
			}]
		});
