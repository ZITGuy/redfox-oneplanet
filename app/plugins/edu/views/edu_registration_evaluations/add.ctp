//<script>
	<?php
		$this->ExtForm->create('EduRegistrationEvaluation');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduRegistrationEvaluationAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduRegistrationEvaluations', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_registrations;
				}
				$this->ExtForm->input('edu_registration_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_evaluations;
				$this->ExtForm->input('edu_evaluation_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_quarters;
				$this->ExtForm->input('edu_quarter_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_evaluation_values;
				$this->ExtForm->input('edu_evaluation_value_id', $options);
			?>
		]
	});
	
	var EduRegistrationEvaluationAddWindow = new Ext.Window({
		title: '<?php __('Add Edu Registration Evaluation'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduRegistrationEvaluationAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduRegistrationEvaluationAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Edu Registration Evaluation.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduRegistrationEvaluationAddWindow.collapsed)
					EduRegistrationEvaluationAddWindow.expand(true);
				else
					EduRegistrationEvaluationAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduRegistrationEvaluationAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduRegistrationEvaluationAddForm.getForm().reset();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduRegistrationEvaluationData();
<?php } else { ?>
						RefreshEduRegistrationEvaluationData();
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
				EduRegistrationEvaluationAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduRegistrationEvaluationAddWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduRegistrationEvaluationData();
<?php } else { ?>
						RefreshEduRegistrationEvaluationData();
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
				EduRegistrationEvaluationAddWindow.close();
			}
		}]
	});
