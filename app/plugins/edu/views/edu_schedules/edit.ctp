//<script>
	<?php
		$this->ExtForm->create('EduSchedule');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduScheduleEditForm = new Ext.form.FormPanel({
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
			'controller' => 'eduSchedules', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_schedule['EduSchedule']['id'])); ?>,
			<?php
				$options = array();
				$options['value'] = $edu_schedule['EduSchedule']['name'];
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_schedule['EduSchedule']['periods'];
				$this->ExtForm->input('periods', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_schedule['EduSchedule']['days'];
				$this->ExtForm->input('days', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_schedule['EduSchedule']['status'];
				$this->ExtForm->input('status', $options);
			?>
		]
	});
	
	var EduScheduleEditWindow = new Ext.Window({
		title: '<?php __('Edit Edu Schedule'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduScheduleEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduScheduleEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Schedule.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduScheduleEditWindow.collapsed)
					EduScheduleEditWindow.expand(true);
				else
					EduScheduleEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduScheduleEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduScheduleEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduScheduleData();
<?php } else { ?>
						RefreshEduScheduleData();
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
				EduScheduleEditWindow.close();
			}
		}]
	});
