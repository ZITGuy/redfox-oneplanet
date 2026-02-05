//<script>
	<?php
		$this->ExtForm->create('EduExtraPaymentSetting');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduExtraPaymentSettingAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduExtraPaymentSettings', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array('fieldLabel' => 'Payment Type');
				$options['items'] = $edu_extra_payment_types;
				$options['listeners'] = "{
					scope: this,
					'select': function(combo, record, index){
						var txtname = Ext.getCmp('data[EduExtraPaymentSetting][name]');
						var record = combo.findRecord(combo.valueField, combo.getValue());
						var payment_type  = record? record.get(combo.displayField) : combo.valueNotFoundText;
						txtname.setValue(payment_type + ' for ... ');
					}
				}";
				$this->ExtForm->input('edu_extra_payment_type_id', $options);
			?>,
			<?php
				$options = array('id' => 'data[EduExtraPaymentSetting][name]');
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_classes;
				}
				$this->ExtForm->input('edu_class_id', $options);
			?>,
			<?php
				$options = array('anchor' => '60%', 'value' => '0.00');
				$this->ExtForm->input('amount', $options);
			?>
		]
	});
	
	var EduExtraPaymentSettingAddWindow = new Ext.Window({
		title: '<?php __('Add Extra Payment Setting'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduExtraPaymentSettingAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduExtraPaymentSettingAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Extra Payment Setting.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduExtraPaymentSettingAddWindow.collapsed)
					EduExtraPaymentSettingAddWindow.expand(true);
				else
					EduExtraPaymentSettingAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduExtraPaymentSettingAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduExtraPaymentSettingAddForm.getForm().reset();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduExtraPaymentSettingData();
<?php } else { ?>
						RefreshEduExtraPaymentSettingData();
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
				EduExtraPaymentSettingAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduExtraPaymentSettingAddWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduExtraPaymentSettingData();
<?php } else { ?>
						RefreshEduExtraPaymentSettingData();
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
				EduExtraPaymentSettingAddWindow.close();
			}
		}]
	});
