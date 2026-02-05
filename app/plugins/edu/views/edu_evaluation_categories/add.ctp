//<script>
	<?php
		$this->ExtForm->create('EduEvaluationCategory');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduEvaluationCategoryAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_evaluation_categories', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array('xtype' => 'combo', 'fieldLabel' => 'Value Group',
					'items' => array(
						1 => 'Formal (Excellent, Very Good, Good, Fair, Poor)',
						2 => 'Observatory (Needs Improvement, Satisfactory, Excellent)',
						3 => 'Scaly (A, B, C, D, F)'));
				$this->ExtForm->input('evaluation_value_group', $options);
			?>,
			<?php
				$options = array('anchor' => '60%');
				$this->ExtForm->input('list_order', $options);
			?>
		]
	});
	
	var EduEvaluationCategoryAddWindow = new Ext.Window({
		title: '<?php __('Add Evaluation Category'); ?>',
		width: 500,
		minWidth: 500,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduEvaluationCategoryAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduEvaluationCategoryAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Evaluation Category.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduEvaluationCategoryAddWindow.collapsed)
					EduEvaluationCategoryAddWindow.expand(true);
				else
					EduEvaluationCategoryAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduEvaluationCategoryAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduEvaluationCategoryAddForm.getForm().reset();
						RefreshEduEvaluationCategoryData();
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
				EduEvaluationCategoryAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduEvaluationCategoryAddWindow.close();
						RefreshEduEvaluationCategoryData();
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
				EduEvaluationCategoryAddWindow.close();
			}
		}]
	});
