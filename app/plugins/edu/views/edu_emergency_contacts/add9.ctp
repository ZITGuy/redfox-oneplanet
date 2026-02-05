//<script>
	<?php
		$this->ExtForm->create('EduEmergencyContact');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduEmergencyContactAddForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 120,
		labelAlign: 'right',
		autoHeight: true,
        width: 500,
        resizable: false,
        plain:true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',
		url:'<?php echo $this->Html->url(array('controller' => 'edu_emergency_contacts', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array('anchor' => '80%');
				$this->ExtForm->input('first_name', $options);
			?>,
			<?php
				$options = array('anchor' => '80%');
				$this->ExtForm->input('middle_name', $options);
			?>,
			<?php
				$options = array('anchor' => '80%');
				$this->ExtForm->input('last_name', $options);
			?>,
			<?php
				$options = array('anchor' => '80%');
				$this->ExtForm->input('relationship', $options);
			?>,
			<?php
				$options = array('anchor' => '80%');
				$this->ExtForm->input('phone_number', $options);
			?>
		]
	});
	
	var EduEmergencyContactAddWindow = new Ext.Window({
		title: '<?php __('Add Emergency Contact'); ?>',
		width: 450,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduEmergencyContactAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduEmergencyContactAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Emergency Contact.',
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
				EduEmergencyContactAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduEmergencyContactAddForm.getForm().reset();
						RefreshEduEmergencyContactData();
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
				EduEmergencyContactAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduEmergencyContactAddWindow.close();
						RefreshEduEmergencyContactData();
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
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				EduEmergencyContactAddWindow.close();
			}
		}]
	});