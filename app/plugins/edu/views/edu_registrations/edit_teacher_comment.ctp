//<script>
	<?php
		$this->ExtForm->create('EduRegistrationQuarter');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EditTeacherCommentForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array(
			'controller' => 'edu_registrations', 'action' => 'edit_teacher_comment')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_registration_quarter['EduRegistrationQuarter']['id'])); ?>,
			<?php
				$options = array();
				$options['value'] = $edu_registration_quarter['EduRegistrationQuarter']['parent_comment'];
				$this->ExtForm->input('parent_comment', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_registration_quarter['EduRegistrationQuarter']['homeroom_comment'];
				$this->ExtForm->input('homeroom_comment', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_registration_quarter['EduRegistrationQuarter']['absentees'];
				$this->ExtForm->input('absentees', $options);
			?>
		]
	});
	
	var EditTeacherCommentWindow = new Ext.Window({
		title: '<?php __('Edit Homeroom Comment'); ?>',
		width: 450,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EditTeacherCommentForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EditTeacherCommentForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Registration.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EditTeacherCommentWindow.collapsed)
					EditTeacherCommentWindow.expand(true);
				else
					EditTeacherCommentWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EditTeacherCommentForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EditTeacherCommentWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduRegistrationData();
<?php } else { ?>
						RefreshEduRegistrationData();
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
				EditTeacherCommentWindow.close();
			}
		}]
	});