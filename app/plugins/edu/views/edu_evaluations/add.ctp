//<script>
	<?php
		$this->ExtForm->create('EduEvaluation');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduEvaluationAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_evaluations', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				if(isset($parent_id))
					$options['hidden'] = $parent_id;
				else
					$options['items'] = $edu_classes;
				$this->ExtForm->input('edu_class_id', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Eval. Area');
				$options['items'] = $edu_evaluation_areas;
				$this->ExtForm->input('edu_evaluation_area_id', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('order_level', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Default Evaluation Value');
				$options['items'] = $edu_evaluation_values;
				$this->ExtForm->input('edu_evaluation_value_id', $options);
			?>
		]
	});
	
	var EduEvaluationAddWindow = new Ext.Window({
		title: '<?php __('Add Evaluation'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduEvaluationAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduEvaluationAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Edu Evaluation.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduEvaluationAddWindow.collapsed)
					EduEvaluationAddWindow.expand(true);
				else
					EduEvaluationAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduEvaluationAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduEvaluationAddForm.getForm().reset();
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
		}, {
			text: '<?php __('Save & Close'); ?>',
			handler: function(btn){
				EduEvaluationAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduEvaluationAddWindow.close();
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
				EduEvaluationAddWindow.close();
			}
		}]
	});
