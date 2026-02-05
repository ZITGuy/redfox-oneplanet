//<script>
	<?php
		$this->ExtForm->create('EduScale');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduScaleEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_scales', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_scale['EduScale']['id'])); ?>,
			<?php
				$options = array('anchor' => '60%', 'fieldLabel' => 'Min (Inclusive)');
				$options['value'] = $edu_scale['EduScale']['min'];
				$this->ExtForm->input('min', $options);
			?>,
			<?php
				$options = array('anchor' => '60%', 'fieldLabel' => 'Max (Exclusive)');
				$options['value'] = $edu_scale['EduScale']['max'];
				$this->ExtForm->input('max', $options);
			?>,
			<?php
				$options = array('anchor' => '60%');
				$options['value'] = $edu_scale['EduScale']['scale'];
				$this->ExtForm->input('scale', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_scale['EduScale']['remark'];
				$this->ExtForm->input('remark', $options);
			?>
		]
	});
	
	var EduScaleEditWindow = new Ext.Window({
		title: '<?php __('Edit Scale'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduScaleEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduScaleEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Scale.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduScaleEditWindow.collapsed)
					EduScaleEditWindow.expand(true);
				else
					EduScaleEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduScaleEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduScaleEditWindow.close();
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
				EduScaleEditWindow.close();
			}
		}]
	});
