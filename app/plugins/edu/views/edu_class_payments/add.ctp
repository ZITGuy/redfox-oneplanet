//<script>
	<?php
		$this->ExtForm->create('EduClassPayment');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduClassPaymentAddForm = new Ext.form.FormPanel({
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
			'controller' => 'edu_class_payments', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$edu_classes[0] = 'All';
				$options = array('fieldLabel' => 'Class');
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_classes;
					$options['value'] = 0;
				}
				$this->ExtForm->input('edu_class_id', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Academic Yr/Batch');
				$options['items'] = $edu_academic_years;
				$this->ExtForm->input('edu_academic_year_id', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('enrollment_fee', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('registration_fee', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('tuition_fee', $options);
			?>
		]
	});
	
	var EduClassPaymentAddWindow = new Ext.Window({
		title: '<?php __('Add Class Payment'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduClassPaymentAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduClassPaymentAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Edu Class Payment.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduClassPaymentAddWindow.collapsed)
					EduClassPaymentAddWindow.expand(true);
				else
					EduClassPaymentAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduClassPaymentAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduClassPaymentAddForm.getForm().reset();
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
		}, {
			text: '<?php __('Save & Close'); ?>',
			handler: function(btn){
				EduClassPaymentAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduClassPaymentAddWindow.close();
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
				EduClassPaymentAddWindow.close();
			}
		}]
	});
