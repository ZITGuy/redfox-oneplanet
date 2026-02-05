//<script>
	<?php
		$this->ExtForm->create('AssessmentType');
		$this->ExtForm->defineFieldFunctions();
	?>
	var AssessmentTypeEditForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
		url:'<?php echo $this->Html->url(array('controller' => 'assessment_types', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $assessment_type['AssessmentType']['id'])); ?>,
			<?php
				$options = array();
				$options['value'] = $assessment_type['AssessmentType']['name'];
				$this->ExtForm->input('name', $options);
			?>
		]
	});
	
	var AssessmentTypeEditWindow = new Ext.Window({
		title: '<?php __('Edit Assessment Type'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: AssessmentTypeEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				AssessmentTypeEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Assessment Type.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(AssessmentTypeEditWindow.collapsed)
					AssessmentTypeEditWindow.expand(true);
				else
					AssessmentTypeEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				AssessmentTypeEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						AssessmentTypeEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentAssessmentTypeData();
<?php } else { ?>
						RefreshAssessmentTypeData();
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
				AssessmentTypeEditWindow.close();
			}
		}]
	});
