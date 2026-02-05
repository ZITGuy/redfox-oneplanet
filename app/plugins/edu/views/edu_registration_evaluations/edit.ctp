//<script>
	<?php
		$this->ExtForm->create('EduRegistrationEvaluation');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduRegistrationEvaluationEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_registration_evaluations', 'action' => 'edit')); ?>',
		defaultType: 'textfield',
		
		items: [
			<?php $this->ExtForm->input('id', array('hidden' =>
				$edu_registration_evaluation['EduRegistrationEvaluation']['id'])); ?>,
			<?php
				$options = array('fieldLabel' => 'Evaluation Value');
				$options['items'] = $edu_evaluation_values;
				$options['value'] =
					$edu_registration_evaluation['EduRegistrationEvaluation']['edu_evaluation_value_id'];
				$this->ExtForm->input('edu_evaluation_value_id', $options);
			?>
		]
	});
	
	var EduRegistrationEvaluationEditWindow = new Ext.Window({
		title: '<?php __('Edit Edu Registration Evaluation'); ?>',
		width: 450,
		minWidth: 450,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduRegistrationEvaluationEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduRegistrationEvaluationEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Registration Evaluation.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduRegistrationEvaluationEditWindow.collapsed)
					EduRegistrationEvaluationEditWindow.expand(true);
				else
					EduRegistrationEvaluationEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduRegistrationEvaluationEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduRegistrationEvaluationEditWindow.close();
						RefreshParentRegistrationEvaluationData();
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
				EduRegistrationEvaluationEditWindow.close();
			}
		}]
	});
