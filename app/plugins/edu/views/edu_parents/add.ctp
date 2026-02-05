//<script>
	<?php
		$this->ExtForm->create('EduParent');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduParentAddForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 150,
		labelAlign: 'right',
		autoHeight: true,
        width: 500,
        resizable: false,
        plain:true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',
		url:'<?php echo $this->Html->url(array(
			'controller' => 'edu_parents', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				$this->ExtForm->input('authorized_person', $options);
			?>,
			<?php
				$options = array('xtype' => 'combo', 'fieldLabel' => 'Marital Status', 'anchor' => '60%');
				$options['items'] = array(
					'S' => 'Single', 'M' => 'Married', 'D' => 'Divorsed', 'W' => 'Widowed', 'P' => 'Separated');
				$options['value'] = 'M';
				$this->ExtForm->input('marital_status', $options);
			?>,
			<?php
				$options = array('xtype' => 'combo', 'fieldLabel' => 'Primary Parent', 'anchor' => '60%');
				$options['items'] = array('M' => 'Mother', 'F' => 'Father', 'G' => 'Guardian');
				$options['value'] = 'M';
				$this->ExtForm->input('primary_parent', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Phone Number for SMS', 'anchor' => '60%');
				$this->ExtForm->input('sms_phone_number', $options);
			?>
		]
	});
	
	var EduParentAddWindow = new Ext.Window({
		title: '<?php __('Add Parent'); ?>',
		width: 650,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduParentAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduParentAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Edu Parent.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduParentAddWindow.collapsed)
					EduParentAddWindow.expand(true);
				else
					EduParentAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduParentAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduParentAddForm.getForm().reset();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduParentData();
<?php } else { ?>
						RefreshEduParentData();
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
				EduParentAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduParentAddWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduParentData();
<?php } else { ?>
						RefreshEduParentData();
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
				EduParentAddWindow.close();
			}
		}]
	});