//<script>
	<?php
		$this->ExtForm->create('EduDay');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduDayEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduDays', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_day['EduDay']['id'])); ?>,
			<?php
				$options = array();
				$options['value'] = $edu_day['EduDay']['date'];
				$this->ExtForm->input('date', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_day['EduDay']['week_day'];
				$this->ExtForm->input('week_day', $options);
			?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_quarters;
				}
				$options['value'] = $edu_day['EduDay']['edu_quarter_id'];
				$this->ExtForm->input('edu_quarter_id', $options);
			?>
		]
	});
	
	var EduDayEditWindow = new Ext.Window({
		title: '<?php __('Edit Day'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduDayEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduDayEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Day.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduDayEditWindow.collapsed)
					EduDayEditWindow.expand(true);
				else
					EduDayEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduDayEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduDayEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduDayData();
<?php } else { ?>
						RefreshEduDayData();
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
				EduDayEditWindow.close();
			}
		}]
	});