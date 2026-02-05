//<script>
	<?php
		$this->ExtForm->create('EduPeriod');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduPeriodEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduPeriods', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_period['EduPeriod']['id'])); ?>,
			<?php
				$options = array();
				$options['items'] = $edu_sections;
				$options['value'] = $edu_period['EduPeriod']['edu_section_id'];
				$this->ExtForm->input('edu_section_id', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_period['EduPeriod']['edu_course_Id'];
				$this->ExtForm->input('edu_course_Id', $options);
			?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_schedules;
				}
				$options['value'] = $edu_period['EduPeriod']['edu_schedule_id'];
				$this->ExtForm->input('edu_schedule_id', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_period['EduPeriod']['day'];
				$this->ExtForm->input('day', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_period['EduPeriod']['period'];
				$this->ExtForm->input('period', $options);
			?>
		]
	});
	
	var EduPeriodEditWindow = new Ext.Window({
		title: '<?php __('Edit Edu Period'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduPeriodEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduPeriodEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Period.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduPeriodEditWindow.collapsed)
					EduPeriodEditWindow.expand(true);
				else
					EduPeriodEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduPeriodEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduPeriodEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduPeriodData();
<?php } else { ?>
						RefreshEduPeriodData();
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
				EduPeriodEditWindow.close();
			}
		}]
	});
