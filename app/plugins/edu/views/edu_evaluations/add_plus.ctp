//<script>
	<?php
		$this->ExtForm->create('EduEvaluation');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduEvaluationAddPlusForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_evaluations', 'action' => 'add_plus')); ?>',
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
				$options = array('fieldLabel' => 'Eval. Category');
				$options['items'] = $edu_evaluation_categories;
				$this->ExtForm->input('edu_evaluation_category_id', $options);
			?>
		]
	});
	
	var EduEvaluationAddPlusWindow = new Ext.Window({
		title: '<?php __('Add Evaluations'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduEvaluationAddPlusForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduEvaluationAddPlusForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert list of Evaluations.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduEvaluationAddPlusWindow.collapsed)
					EduEvaluationAddPlusWindow.expand(true);
				else
					EduEvaluationAddPlusWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduEvaluationAddPlusForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduEvaluationAddPlusForm.getForm().reset();
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
				EduEvaluationAddPlusForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduEvaluationAddPlusWindow.close();
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
				EduEvaluationAddPlusWindow.close();
			}
		}]
	});
