//<script>
	<?php
		$this->ExtForm->create('EduEvaluation');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduEvaluationEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduEvaluations', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_evaluation['EduEvaluation']['id'])); ?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_classes;
				}
				$options['value'] = $edu_evaluation['EduEvaluation']['edu_class_id'];
				$this->ExtForm->input('edu_class_id', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Eval. Area',
					'hidden' => $edu_evaluation['EduEvaluation']['edu_evaluation_area_id']);
				$options['value'] = $edu_evaluation['EduEvaluation']['edu_evaluation_area_id'];
				$this->ExtForm->input('edu_evaluation_area_id', $options);
			?>,
			<?php
				$options = array('anchor' => '53%');
				$options['value'] = $edu_evaluation['EduEvaluation']['order_level'];
				$this->ExtForm->input('order_level', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Default Evaluation Value');
				$options['items'] = $edu_evaluation_values;
				$options['value'] = $edu_evaluation['EduEvaluation']['edu_evaluation_value_id'];
				$this->ExtForm->input('edu_evaluation_value_id', $options);
			?>
		]
	});
	
	var EduEvaluationEditWindow = new Ext.Window({
		title: '<?php __('Edit Evaluation'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduEvaluationEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduEvaluationEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Evaluation.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduEvaluationEditWindow.collapsed)
					EduEvaluationEditWindow.expand(true);
				else
					EduEvaluationEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduEvaluationEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduEvaluationEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduEvaluationData();
<?php } else { ?>
						RefreshEduEvaluationData();
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
				EduEvaluationEditWindow.close();
			}
		}]
	});
