//<script>
	<?php
		$this->ExtForm->create('EduPayment');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduPaymentEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduPayments', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_payment['EduPayment']['id'])); ?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_payment_schedules;
				}
				$options['value'] = $edu_payment['EduPayment']['edu_payment_schedule_id'];
				$this->ExtForm->input('edu_payment_schedule_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_students;
				$options['value'] = $edu_payment['EduPayment']['edu_student_id'];
				$this->ExtForm->input('edu_student_id', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_payment['EduPayment']['is_paid'];
				$this->ExtForm->input('is_paid', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_payment['EduPayment']['date_paid'];
				$this->ExtForm->input('date_paid', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_payment['EduPayment']['paid_amount'];
				$this->ExtForm->input('paid_amount', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_payment['EduPayment']['cheque_number'];
				$this->ExtForm->input('cheque_number', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_payment['EduPayment']['invoice'];
				$this->ExtForm->input('invoice', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_payment['EduPayment']['transaction_ref'];
				$this->ExtForm->input('transaction_ref', $options);
			?>
		]
	});
	
	var EduPaymentEditWindow = new Ext.Window({
		title: '<?php __('Edit Payment'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduPaymentEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduPaymentEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Payment.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduPaymentEditWindow.collapsed)
					EduPaymentEditWindow.expand(true);
				else
					EduPaymentEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduPaymentEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduPaymentEditWindow.close();
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
				EduPaymentEditWindow.close();
			}
		}]
	});
