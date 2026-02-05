//<script>
	<?php
		$this->ExtForm->create('AssessmentType');
		$this->ExtForm->defineFieldFunctions();
	?>
	var AssessmentTypeAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'assessment_types', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				$this->ExtForm->input('name', $options);
			?>
		]
	});
	
	var AssessmentTypeAddWindow = new Ext.Window({
		title: '<?php __('Add Assessment Type'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: AssessmentTypeAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				AssessmentTypeAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Assessment Type.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(AssessmentTypeAddWindow.collapsed)
					AssessmentTypeAddWindow.expand(true);
				else
					AssessmentTypeAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				AssessmentTypeAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						AssessmentTypeAddForm.getForm().reset();
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
		}, {
			text: '<?php __('Save & Close'); ?>',
			handler: function(btn){
				AssessmentTypeAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						AssessmentTypeAddWindow.close();
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
				AssessmentTypeAddWindow.close();
			}
		}]
	});
