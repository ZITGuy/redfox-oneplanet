//<script>
	<?php
		$this->ExtForm->create('EduEvaluationCategory');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduEvaluationCategoryEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array(
			'controller' => 'edu_evaluation_categories', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_evaluation_category['EduEvaluationCategory']['id'])); ?>,
			<?php
				$options = array();
				$options['value'] = $edu_evaluation_category['EduEvaluationCategory']['name'];
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array(
					'xtype' => 'combo', 'fieldLabel' => 'Value Group', 'items' => array(
						1 => 'Formal (Excellent, Very Good, Good, Fair, Poor)',
						2 => 'Observatory (Almost Always, Satisfactory, Needs Improvement)',
						3 => 'Scaly (A, B, C, D, F)'));
				$options['value'] = $edu_evaluation_category['EduEvaluationCategory']['evaluation_value_group'];
				$this->ExtForm->input('evaluation_value_group', $options);
			?>,
			<?php
				$options = array('anchor' => '60%');
				$options['value'] = $edu_evaluation_category['EduEvaluationCategory']['list_order'];
				$this->ExtForm->input('list_order', $options);
			?>
		]
	});
	
	var EduEvaluationCategoryEditWindow = new Ext.Window({
		title: '<?php __('Edit Evaluation Category'); ?>',
		width: 500,
		minWidth: 500,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduEvaluationCategoryEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduEvaluationCategoryEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Evaluation Category.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduEvaluationCategoryEditWindow.collapsed)
					EduEvaluationCategoryEditWindow.expand(true);
				else
					EduEvaluationCategoryEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduEvaluationCategoryEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduEvaluationCategoryEditWindow.close();
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
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				EduEvaluationCategoryEditWindow.close();
			}
		}]
	});
