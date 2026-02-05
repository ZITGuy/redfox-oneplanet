//<script>
	<?php
		$this->ExtForm->create('EduExtraPayment');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduExtraPaymentEditForm = new Ext.form.FormPanel({
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
			'controller' => 'eduExtraPayments', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_extra_payment['EduExtraPayment']['id'])); ?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_extra_payment_settings;
				}
				$options['value'] = $edu_extra_payment['EduExtraPayment']['edu_extra_payment_setting_id'];
				$this->ExtForm->input('edu_extra_payment_setting_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_students;
				$options['value'] = $edu_extra_payment['EduExtraPayment']['edu_student_id'];
				$this->ExtForm->input('edu_student_id', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_extra_payment['EduExtraPayment']['is_paid'];
				$this->ExtForm->input('is_paid', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_extra_payment['EduExtraPayment']['date_paid'];
				$this->ExtForm->input('date_paid', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_extra_payment['EduExtraPayment']['paid_amount'];
				$this->ExtForm->input('paid_amount', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_extra_payment['EduExtraPayment']['cheque_number'];
				$this->ExtForm->input('cheque_number', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_extra_payment['EduExtraPayment']['cheque_amount'];
				$this->ExtForm->input('cheque_amount', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_extra_payment['EduExtraPayment']['invoice'];
				$this->ExtForm->input('invoice', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_extra_payment['EduExtraPayment']['transaction_ref'];
				$this->ExtForm->input('transaction_ref', $options);
			?>
		]
	});
	
	var EduExtraPaymentEditWindow = new Ext.Window({
		title: '<?php __('Edit Extra Payment'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduExtraPaymentEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduExtraPaymentEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Extra Payment.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduExtraPaymentEditWindow.collapsed)
					EduExtraPaymentEditWindow.expand(true);
				else
					EduExtraPaymentEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduExtraPaymentEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduExtraPaymentEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduExtraPaymentData();
<?php } else { ?>
						RefreshEduExtraPaymentData();
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
				EduExtraPaymentEditWindow.close();
			}
		}]
	});
