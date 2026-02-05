//<script>
	<?php
		$this->ExtForm->create('EduAssignment');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduAssignmentAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduAssignments', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				$options['items'] = $edu_teachers;
				$this->ExtForm->input('edu_teacher_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_courses;
				$this->ExtForm->input('edu_course_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_sections;
				$this->ExtForm->input('edu_section_id', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('start_date', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('end_date', $options);
			?>
		]
	});
	
	var EduAssignmentAddWindow = new Ext.Window({
		title: '<?php __('Add Edu Assignment'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduAssignmentAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduAssignmentAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Edu Assignment.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduAssignmentAddWindow.collapsed)
					EduAssignmentAddWindow.expand(true);
				else
					EduAssignmentAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduAssignmentAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduAssignmentAddForm.getForm().reset();
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
			text: '<?php __('Save & Close'); ?>',
			handler: function(btn){
				EduAssignmentAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduAssignmentAddWindow.close();
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
		},{
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				EduAssignmentAddWindow.close();
			}
		}]
	});