//<script>
	<?php
		$this->ExtForm->create('EduExtraPaymentSetting');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduExtraPaymentSettingEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduExtraPaymentSettings', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array(
				'hidden' => $edu_extra_payment_setting['EduExtraPaymentSetting']['id'])); ?>,
			<?php
				$options = array();
				$options['value'] = $edu_extra_payment_setting['EduExtraPaymentSetting']['name'];
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_classes;
				}
				$options['value'] = $edu_extra_payment_setting['EduExtraPaymentSetting']['edu_class_id'];
				$this->ExtForm->input('edu_class_id', $options);
			?>,
			<?php
				$options = array('anchor' => '60%');
				$options['value'] = $edu_extra_payment_setting['EduExtraPaymentSetting']['amount'];
				$this->ExtForm->input('amount', $options);
			?>
		]
	});
	
	var EduExtraPaymentSettingEditWindow = new Ext.Window({
		title: '<?php __('Edit Edu Extra Payment Setting'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduExtraPaymentSettingEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduExtraPaymentSettingEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Extra Payment Setting.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduExtraPaymentSettingEditWindow.collapsed)
					EduExtraPaymentSettingEditWindow.expand(true);
				else
					EduExtraPaymentSettingEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduExtraPaymentSettingEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduExtraPaymentSettingEditWindow.close();
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
				EduExtraPaymentSettingEditWindow.close();
			}
		}]
	});
