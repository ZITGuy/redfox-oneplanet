//<script>
	<?php
		$this->ExtForm->create('AssessmentRecord');
		$this->ExtForm->defineFieldFunctions();
	?>
	var AssessmentRecordAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'assessmentRecords', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $students;
				}
				$this->ExtForm->input('student_id', $options);
			?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $assessments;
				}
				$this->ExtForm->input('assessment_id', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('mark', $options);
			?>
		]
	});
	
	var AssessmentRecordAddWindow = new Ext.Window({
		title: '<?php __('Add Assessment Record'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: AssessmentRecordAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				AssessmentRecordAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Assessment Record.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(AssessmentRecordAddWindow.collapsed)
					AssessmentRecordAddWindow.expand(true);
				else
					AssessmentRecordAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				AssessmentRecordAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						AssessmentRecordAddForm.getForm().reset();
<?php if (isset($parent_id)) { ?>
						RefreshParentAssessmentRecordData();
<?php } else { ?>
						RefreshAssessmentRecordData();
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
				AssessmentRecordAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						AssessmentRecordAddWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentAssessmentRecordData();
<?php } else { ?>
						RefreshAssessmentRecordData();
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
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				AssessmentRecordAddWindow.close();
			}
		}]
	});
