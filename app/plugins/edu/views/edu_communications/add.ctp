//<script>
	<?php
		$this->ExtForm->create('EduCommunication');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduCommunicationAddForm = new Ext.form.FormPanel({
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
			'controller' => 'edu_communications', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				$options['items'] = $edu_students;
				$this->ExtForm->input('edu_student_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_sections;
				$this->ExtForm->input('edu_section_id', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('post_date', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('teacher_comment', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('parent_comment', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $users;
				$this->ExtForm->input('user_id', $options);
			?>
		]
	});
	
	var EduCommunicationAddWindow = new Ext.Window({
		title: '<?php __('Add Communication'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduCommunicationAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduCommunicationAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Edu Communication.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduCommunicationAddWindow.collapsed)
					EduCommunicationAddWindow.expand(true);
				else
					EduCommunicationAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduCommunicationAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduCommunicationAddForm.getForm().reset();
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
		}, {
			text: '<?php __('Save & Close'); ?>',
			handler: function(btn){
				EduCommunicationAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduCommunicationAddWindow.close();
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
				EduCommunicationAddWindow.close();
			}
		}]
	});
