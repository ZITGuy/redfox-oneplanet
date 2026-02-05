//<script>
	<?php
		$this->ExtForm->create('EduParent');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduParentEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_parents', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_parent['EduParent']['id'])); ?>,
			<?php
				$options = array();
				$options['value'] = $edu_parent['EduParent']['authorized_person'];
				$this->ExtForm->input('authorized_person', $options);
			?>,
			<?php
				$options = array('xtype' => 'combo', 'fieldLabel' => 'Marital Status', 'anchor' => '60%');
				$options['items'] = array('S' => 'Single', 'M' => 'Married',
					'D' => 'Divorsed', 'W' => 'Widowed', 'P' => 'Separated');
				$options['value'] = $edu_parent['EduParent']['marital_status'];
				$this->ExtForm->input('marital_status', $options);
			?>,
			<?php
				$options = array('xtype' => 'combo', 'fieldLabel' => 'Primary Parent', 'anchor' => '60%');
				$options['items'] = array('M' => 'Mother', 'F' => 'Father', 'G' => 'Guardian');
				$options['value'] = $edu_parent['EduParent']['primary_parent'];
				$this->ExtForm->input('primary_parent', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Phone Number for SMS', 'anchor' => '60%');
				$options['value'] = $edu_parent['EduParent']['sms_phone_number'];
				$this->ExtForm->input('sms_phone_number', $options);
			?>
		]
	});
	
	var EduParentEditWindow = new Ext.Window({
		title: '<?php __('Edit Parent'); ?>',
		width: 650,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduParentEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduParentEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Parent.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduParentEditWindow.collapsed)
					EduParentEditWindow.expand(true);
				else
					EduParentEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduParentEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduParentEditWindow.close();
						RefreshEduParentData();
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
				EduParentEditWindow.close();
			}
		}]
	});