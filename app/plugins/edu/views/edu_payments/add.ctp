//<script>
	<?php
		$this->ExtForm->create('EduPayment');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduPaymentAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduPayments', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_payment_schedules;
				}
				$this->ExtForm->input('edu_payment_schedule_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_students;
				$this->ExtForm->input('edu_student_id', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('is_paid', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('date_paid', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('paid_amount', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('cheque_number', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('invoice', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('transaction_ref', $options);
			?>
		]
	});
	
	var EduPaymentAddWindow = new Ext.Window({
		title: '<?php __('Add Edu Payment'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduPaymentAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduPaymentAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Edu Payment.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduPaymentAddWindow.collapsed)
					EduPaymentAddWindow.expand(true);
				else
					EduPaymentAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduPaymentAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduPaymentAddForm.getForm().reset();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduPaymentData();
<?php } else { ?>
						RefreshEduPaymentData();
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
				EduPaymentAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduPaymentAddWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduPaymentData();
<?php } else { ?>
						RefreshEduPaymentData();
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
				EduPaymentAddWindow.close();
			}
		}]
	});
