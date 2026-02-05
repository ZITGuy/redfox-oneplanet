//<script>
	<?php
		$this->ExtForm->create('TextMessage');
		$this->ExtForm->defineFieldFunctions();
	?>
	var TextMessageAddForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 150,
		labelAlign: 'right',
		url:'<?php echo $this->Html->url(array('controller' => 'textMessages', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php 
				$options = array();
				$this->ExtForm->input('receiver', $options);
			?>,
			<?php 
				$options = array('xtype'=>'textarea');
				$this->ExtForm->input('message', $options);
			?>,
			<?php 
				$options = array('xtype'=>'checkbox');
				$this->ExtForm->input('send_automatically', $options);
			?>		
        ]
	});
	
	var TextMessageAddWindow = new Ext.Window({
		title: '<?php __('Add Text Message'); ?>',
		width: 500,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: TextMessageAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				TextMessageAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Text Message.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(TextMessageAddWindow.collapsed)
					TextMessageAddWindow.expand(true);
				else
					TextMessageAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Send'); ?>',
			handler: function(btn){
				TextMessageAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						TextMessageAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
						RefreshParentTextMessageData();
<?php } else { ?>
						RefreshTextMessageData();
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
			text: '<?php __('Send & Close'); ?>',
			handler: function(btn){
				TextMessageAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						TextMessageAddWindow.close();
<?php if(isset($parent_id)){ ?>
						RefreshParentTextMessageData();
<?php } else { ?>
						RefreshTextMessageData();
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
				TextMessageAddWindow.close();
			}
		}]
	});
