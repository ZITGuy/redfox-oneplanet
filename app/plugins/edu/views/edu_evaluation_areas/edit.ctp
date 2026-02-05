//<script>
	<?php
		$this->ExtForm->create('EduEvaluationArea');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduEvaluationAreaEditForm = new Ext.form.FormPanel({
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
			'controller' => 'eduEvaluationAreas', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_evaluation_area['EduEvaluationArea']['id'])); ?>,
			<?php
				$options = array();
				$options['value'] = $edu_evaluation_area['EduEvaluationArea']['name'];
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_evaluation_categories;
				}
				$options['value'] = $edu_evaluation_area['EduEvaluationArea']['edu_evaluation_category_id'];
				$this->ExtForm->input('edu_evaluation_category_id', $options);
			?>
		]
	});
	
	var EduEvaluationAreaEditWindow = new Ext.Window({
		title: '<?php __('Edit Evaluation Area'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduEvaluationAreaEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduEvaluationAreaEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Evaluation Area.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduEvaluationAreaEditWindow.collapsed)
					EduEvaluationAreaEditWindow.expand(true);
				else
					EduEvaluationAreaEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduEvaluationAreaEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduEvaluationAreaEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduEvaluationAreaData();
<?php } else { ?>
						RefreshEduEvaluationAreaData();
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
				EduEvaluationAreaEditWindow.close();
			}
		}]
	});
