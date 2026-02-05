//<script>
	<?php
		$this->ExtForm->create('EduCommunication');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduCommunicationEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_communications', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_communication['EduCommunication']['id'])); ?>,
			<?php
				$options = array();
				$options['items'] = $edu_students;
				$options['value'] = $edu_communication['EduCommunication']['edu_student_id'];
				$this->ExtForm->input('edu_student_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_sections;
				$options['value'] = $edu_communication['EduCommunication']['edu_section_id'];
				$this->ExtForm->input('edu_section_id', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_communication['EduCommunication']['post_date'];
				$this->ExtForm->input('post_date', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_communication['EduCommunication']['teacher_comment'];
				$this->ExtForm->input('teacher_comment', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_communication['EduCommunication']['parent_comment'];
				$this->ExtForm->input('parent_comment', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $users;
				$options['value'] = $edu_communication['EduCommunication']['user_id'];
				$this->ExtForm->input('user_id', $options);
			?>
		]
	});
	
	var EduCommunicationEditWindow = new Ext.Window({
		title: '<?php __('Edit Edu Communication'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduCommunicationEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduCommunicationEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Communication.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduCommunicationEditWindow.collapsed)
					EduCommunicationEditWindow.expand(true);
				else
					EduCommunicationEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduCommunicationEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduCommunicationEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduCommunicationData();
<?php } else { ?>
						RefreshEduCommunicationData();
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
				EduCommunicationEditWindow.close();
			}
		}]
	});
