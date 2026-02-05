//<script>
	<?php
		$this->ExtForm->create('AssessmentRecord');
		$this->ExtForm->defineFieldFunctions();
	?>
	var AssessmentRecordEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'assessmentRecords', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $assessment_record['AssessmentRecord']['id'])); ?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $students;
				}
				$options['value'] = $assessment_record['AssessmentRecord']['student_id'];
				$this->ExtForm->input('student_id', $options);
			?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $assessments;
				}
				$options['value'] = $assessment_record['AssessmentRecord']['assessment_id'];
				$this->ExtForm->input('assessment_id', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $assessment_record['AssessmentRecord']['rank'];
				$this->ExtForm->input('rank', $options);
			?>
		]
	});
	
	var AssessmentRecordEditWindow = new Ext.Window({
		title: '<?php __('Edit Assessment Record'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: AssessmentRecordEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				AssessmentRecordEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Assessment Record.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(AssessmentRecordEditWindow.collapsed)
					AssessmentRecordEditWindow.expand(true);
				else
					AssessmentRecordEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				AssessmentRecordEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						AssessmentRecordEditWindow.close();
<?php if(isset($parent_id)){ ?>
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
		},{
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				AssessmentRecordEditWindow.close();
			}
		}]
	});
