//<script>
	<?php
		$this->ExtForm->create('EduRegistrationQuarterResult');
		$this->ExtForm->defineFieldFunctions();
	?>

	var CourseCommentEditForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 1,
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
			'controller' => 'edu_registration_quarter_results', 'action' => 'set_comment')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array(
				'hidden' => $edu_registration_quarter_result['EduRegistrationQuarterResult']['id'])); ?>,
			<?php
				$options = array('xtype' => 'textarea', 'fieldLabel' => '');
				$options['value'] = $edu_registration_quarter_result['EduRegistrationQuarterResult']['teacher_comment'];
				$this->ExtForm->input('teacher_comment', $options);
			?>]
	});
	
	var CourseCommentEditWindow = new Ext.Window({
		title: '<?php __('Teacher Comment'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: CourseCommentEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				CourseCommentEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Registration Quarter Result.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(CourseCommentEditWindow.collapsed)
					CourseCommentEditWindow.expand(true);
				else
					CourseCommentEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				CourseCommentEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						CourseCommentEditWindow.close();
						RefreshStudentListData();
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
				CourseCommentEditWindow.close();
			}
		}]
	});
