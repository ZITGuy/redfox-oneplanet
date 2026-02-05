//<script>
	<?php
		$this->ExtForm->create('EduRegistration');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduRegistrationEditForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
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
			'controller' => 'eduRegistrations', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_registration['EduRegistration']['id'])); ?>,
			<?php
				$options = array();
				$options['value'] = $edu_registration['EduRegistration']['name'];
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_students;
				}
				$options['value'] = $edu_registration['EduRegistration']['edu_student_id'];
				$this->ExtForm->input('edu_student_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_sections;
				$options['value'] = $edu_registration['EduRegistration']['edu_section_id'];
				$this->ExtForm->input('edu_section_id', $options);
			?>
		]
	});
	
	var EduRegistrationEditWindow = new Ext.Window({
		title: '<?php __('Edit Edu Registration'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduRegistrationEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduRegistrationEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Registration.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduRegistrationEditWindow.collapsed)
					EduRegistrationEditWindow.expand(true);
				else
					EduRegistrationEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduRegistrationEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduRegistrationEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduRegistrationData();
<?php } else { ?>
						RefreshEduRegistrationData();
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
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				EduRegistrationEditWindow.close();
			}
		}]
	});
