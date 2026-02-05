//<script>
	<?php
		$this->ExtForm->create('EduRegistration');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduRegistrationAllForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 160,
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
			'controller' => 'edu_registrations', 'action' => 'register_all')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array('fieldLabel' => 'Class/Grade', 'id' => 'data[EduRegistration][edu_class_id]');
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_classes;
				}
				$options['listeners'] = '{
					select: function(fld){
						var r = CheckValidity();
						if(r){
							Ext.getCmp(\'btnRegisterAll\').enable();
						} else {
							Ext.getCmp(\'btnRegisterAll\').disable();
						}
					}
				}';
				$this->ExtForm->input('edu_class_id', $options);
			?>
		]
	});

	var EduRegistrationAllWindow = new Ext.Window({
		title: '<?php __('Register All'); ?>',
		width: 550,
		minWidth: 500,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduRegistrationAllForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduRegistrationAllForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to register all promoted students from the selected class in given academic year.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduRegistrationAllWindow.collapsed)
					EduRegistrationAllWindow.expand(true);
				else
					EduRegistrationAllWindow.collapse(true);
			}
		}],
		buttons: [{
				text: '<?php __('Register All'); ?>',
				disabled: true,
				id: 'btnRegisterAll',
				handler: function(btn){
					EduRegistrationAllForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
								icon: Ext.MessageBox.INFO
							});
							EduRegistrationAllWindow.close();
							
							OpenSectionsDetail();
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
					EduRegistrationAllWindow.close();
				}
		}]
	});
	
	EduRegistrationAllWindow.show();
	
	function CheckValidity(){
		var class_selected = Ext.getCmp('data[EduRegistration][edu_class_id]').getValue();

		switch(class_selected) {
			<?php
			foreach ($messages as $k => $m) {
				echo "case '$k':\n";
				echo "\tShowErrorBox('$m', 'ERR-0001');\n";
				echo "\treturn false;\n";
			}
			?>
			default:
				return true;
		}
		
	}
