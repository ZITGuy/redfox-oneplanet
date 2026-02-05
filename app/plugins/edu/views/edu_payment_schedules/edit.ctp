//<script>
	<?php
		$this->ExtForm->create('EduPaymentSchedule');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduPaymentScheduleEditForm = new Ext.form.FormPanel({
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
			'controller' => 'eduPaymentSchedules', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_payment_schedule['EduPaymentSchedule']['id'])); ?>,
			<?php
				$options = array('readOnly' => true);
				$options['fieldLabel'] = 'Term';
				$options['value'] = $edu_payment_schedule['EduPaymentSchedule']['month'];
				$this->ExtForm->input('month', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Class');
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_classes;
				}
				$options['value'] = $edu_payment_schedule['EduPaymentSchedule']['edu_class_id'];
				$this->ExtForm->input('edu_class_id', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_payment_schedule['EduPaymentSchedule']['due_date'];
				$this->ExtForm->input('due_date', $options);
			?>
		]
	});
	
	var EduPaymentScheduleEditWindow = new Ext.Window({
		title: '<?php __('Edit Payment Schedule'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduPaymentScheduleEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduPaymentScheduleEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Payment Schedule.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduPaymentScheduleEditWindow.collapsed)
					EduPaymentScheduleEditWindow.expand(true);
				else
					EduPaymentScheduleEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduPaymentScheduleEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduPaymentScheduleEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduPaymentScheduleData();
<?php } else { ?>
						RefreshEduPaymentScheduleData();
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
				EduPaymentScheduleEditWindow.close();
			}
		}]
	});
