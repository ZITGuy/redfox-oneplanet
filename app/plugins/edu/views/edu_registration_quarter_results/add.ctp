//<script>
	<?php
		$this->ExtForm->create('EduRegistrationQuarterResult');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduRegistrationQuarterResultAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarterResults', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_registration_quarters;
				}
				$this->ExtForm->input('edu_registration_quarter_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_courses;
				$this->ExtForm->input('edu_course_id', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('course_result', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('result_indicator', $options);
			?>
		]
	});
	
	var EduRegistrationQuarterResultAddWindow = new Ext.Window({
		title: '<?php __('Add Edu Registration Quarter Result'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduRegistrationQuarterResultAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduRegistrationQuarterResultAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Edu Registration Quarter Result.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduRegistrationQuarterResultAddWindow.collapsed)
					EduRegistrationQuarterResultAddWindow.expand(true);
				else
					EduRegistrationQuarterResultAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduRegistrationQuarterResultAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduRegistrationQuarterResultAddForm.getForm().reset();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduRegistrationQuarterResultData();
<?php } else { ?>
						RefreshEduRegistrationQuarterResultData();
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
				EduRegistrationQuarterResultAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduRegistrationQuarterResultAddWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduRegistrationQuarterResultData();
<?php } else { ?>
						RefreshEduRegistrationQuarterResultData();
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
				EduRegistrationQuarterResultAddWindow.close();
			}
		}]
	});
