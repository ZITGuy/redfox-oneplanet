//<script>
	<?php
		$this->ExtForm->create('EduCorrection');
		$this->ExtForm->defineFieldFunctions();
	?>
	var CorrectionSubmitForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
		autoHeight: true,
        width: 500,
        resizable: false,
        plain: true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',
		url:'<?php echo $this->Html->url(array('controller' => 'edu_corrections', 'action' => 'submit_correction')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $correction['EduCorrection']['id'])); ?>,
			<?php
				$options = array('readOnly' => 'true');
				$options['value'] = $correction['EduCorrection']['new_value'];
				$this->ExtForm->input('new_value', $options);
			?>,
			<?php
				$options = array('readOnly' => 'true');
				$options['value'] = $correction['EduCorrection']['reason'];
				$this->ExtForm->input('reason', $options);
			?>
		]
	});
	
	var CorrectionSubmitWindow = new Ext.Window({
		title: '<?php __('Are you sure to submit this correction'); ?>?',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: CorrectionSubmitForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				CorrectionSubmitForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to submit an existing Correction.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(CorrectionSubmitWindow.collapsed)
					CorrectionSubmitWindow.expand(true);
				else
					CorrectionSubmitWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Yes'); ?>',
			handler: function(btn){
				CorrectionSubmitForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						CorrectionSubmitWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentCorrectionData();
<?php } else { ?>
						RefreshCorrectionData();
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
			text: '<?php __('No'); ?>',
			handler: function(btn){
				CorrectionSubmitWindow.close();
			}
		}]
	});
