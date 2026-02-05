//<script>
	<?php
		$this->ExtForm->create('EduClassPayment');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduClassPaymentEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_class_payments', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_class_payment['EduClassPayment']['id'])); ?>,
			<?php
				$options = array('fieldLabel' => 'Class');
				$options['items'] = $edu_classes;
				$options['disabled'] = 'true';
				$options['value'] = $edu_class_payment['EduClassPayment']['edu_class_id'];
				$this->ExtForm->input('edu_class_id', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Academic Yr/Batch');
				$options['items'] = $edu_academic_years;
				$options['disabled'] = 'true';
				$options['value'] = $edu_class_payment['EduClassPayment']['edu_academic_year_id'];
				$this->ExtForm->input('edu_academic_year_id', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_class_payment['EduClassPayment']['enrollment_fee'];
				$this->ExtForm->input('enrollment_fee', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_class_payment['EduClassPayment']['registration_fee'];
				$this->ExtForm->input('registration_fee', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_class_payment['EduClassPayment']['tuition_fee'];
				$this->ExtForm->input('tuition_fee', $options);
			?>
		]
	});
	
	var EduClassPaymentEditWindow = new Ext.Window({
		title: '<?php __('Edit Edu Class Payment'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduClassPaymentEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduClassPaymentEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Class Payment.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduClassPaymentEditWindow.collapsed)
					EduClassPaymentEditWindow.expand(true);
				else
					EduClassPaymentEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduClassPaymentEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduClassPaymentEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduClassPaymentData();
<?php } else { ?>
						RefreshEduClassPaymentData();
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
				EduClassPaymentEditWindow.close();
			}
		}]
	});
