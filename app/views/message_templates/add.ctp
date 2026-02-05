//<script>
		<?php
			$this->ExtForm->create('MessageTemplate');
			$this->ExtForm->defineFieldFunctions();
		?>
		var MessageTemplateAddForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'message_templates', 'action' => 'add')); ?>',
			defaultType: 'textfield',

			items: [
				<?php 
					$options = array();
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('body', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('default_body', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('placeholders', $options);
				?>			]
		});
		
		var MessageTemplateAddWindow = new Ext.Window({
			title: '<?php __('Add Message Template'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: MessageTemplateAddForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					MessageTemplateAddForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to insert a new Message Template.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(MessageTemplateAddWindow.collapsed)
						MessageTemplateAddWindow.expand(true);
					else
						MessageTemplateAddWindow.collapse(true);
				}
			}],
			buttons: [  {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					MessageTemplateAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							MessageTemplateAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
							RefreshParentMessageTemplateData();
<?php } else { ?>
							RefreshMessageTemplateData();
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
					MessageTemplateAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							MessageTemplateAddWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentMessageTemplateData();
<?php } else { ?>
							RefreshMessageTemplateData();
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
					MessageTemplateAddWindow.close();
				}
			}]
		});
