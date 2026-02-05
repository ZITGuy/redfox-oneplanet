//<script>
	<?php
		$this->ExtForm->create('EduAssignment');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduAssignmentEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduAssignments', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_assignment['EduAssignment']['id'])); ?>,
			<?php
				$options = array();
				$options['items'] = $edu_teachers;
				$options['value'] = $edu_assignment['EduAssignment']['edu_teacher_id'];
				$this->ExtForm->input('edu_teacher_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_courses;
				$options['value'] = $edu_assignment['EduAssignment']['edu_course_id'];
				$this->ExtForm->input('edu_course_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_sections;
				$options['value'] = $edu_assignment['EduAssignment']['edu_section_id'];
				$this->ExtForm->input('edu_section_id', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_assignment['EduAssignment']['start_date'];
				$this->ExtForm->input('start_date', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_assignment['EduAssignment']['end_date'];
				$this->ExtForm->input('end_date', $options);
			?>
		]
	});
	
	var EduAssignmentEditWindow = new Ext.Window({
		title: '<?php __('Edit Edu Assignment'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduAssignmentEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduAssignmentEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Assignment.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduAssignmentEditWindow.collapsed)
					EduAssignmentEditWindow.expand(true);
				else
					EduAssignmentEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduAssignmentEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduAssignmentEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduAssignmentData();
<?php } else { ?>
						RefreshEduAssignmentData();
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
				EduAssignmentEditWindow.close();
			}
		}]
	});