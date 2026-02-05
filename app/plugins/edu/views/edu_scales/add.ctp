//<script>
	<?php
		$this->ExtForm->create('EduScale');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduScaleAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_scales', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array('anchor' => '60%', 'fieldLabel' => 'Min (Inclusive)');
				$this->ExtForm->input('min', $options);
			?>,
			<?php
				$options = array('anchor' => '60%', 'fieldLabel' => 'Max (Exclusive)');
				$this->ExtForm->input('max', $options);
			?>,
			<?php
				$options = array('anchor' => '60%');
				$this->ExtForm->input('scale', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('remark', $options);
			?>
		]
	});
	
	var EduScaleAddWindow = new Ext.Window({
		title: '<?php __('Add Scale'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduScaleAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduScaleAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Scale.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduScaleAddWindow.collapsed)
					EduScaleAddWindow.expand(true);
				else
					EduScaleAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduScaleAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduScaleAddForm.getForm().reset();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduScaleData();
<?php } else { ?>
						RefreshEduScaleData();
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
				EduScaleAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduScaleAddWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduScaleData();
<?php } else { ?>
						RefreshEduScaleData();
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
				EduScaleAddWindow.close();
			}
		}]
	});
