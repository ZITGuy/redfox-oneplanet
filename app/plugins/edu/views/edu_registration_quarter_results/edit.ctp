//<script>
	<?php
		$this->ExtForm->create('EduRegistrationQuarterResult');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduRegistrationQuarterResultEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarterResults', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array(
				'hidden' => $edu_registration_quarter_result['EduRegistrationQuarterResult']['id'])); ?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_registration_quarters;
				}
				$options['value'] = $edu_registration_quarter_result['EduRegistrationQuarterResult']['edu_registration_quarter_id'];
				$this->ExtForm->input('edu_registration_quarter_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_courses;
				$options['value'] = $edu_registration_quarter_result['EduRegistrationQuarterResult']['edu_course_id'];
				$this->ExtForm->input('edu_course_id', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_registration_quarter_result['EduRegistrationQuarterResult']['course_result'];
				$this->ExtForm->input('course_result', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_registration_quarter_result['EduRegistrationQuarterResult']['result_indicator'];
				$this->ExtForm->input('result_indicator', $options);
			?>
		]
	});
	
	var EduRegistrationQuarterResultEditWindow = new Ext.Window({
		title: '<?php __('Edit Reg. Quarter Result'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduRegistrationQuarterResultEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduRegistrationQuarterResultEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Reg. Quarter Result.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduRegistrationQuarterResultEditWindow.collapsed)
					EduRegistrationQuarterResultEditWindow.expand(true);
				else
					EduRegistrationQuarterResultEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduRegistrationQuarterResultEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduRegistrationQuarterResultEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduRegistrationQuarterResultData();
<?php } else { ?>
						RefreshEduRegistrationQuarterResultData();
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
				EduRegistrationQuarterResultEditWindow.close();
			}
		}]
	});
