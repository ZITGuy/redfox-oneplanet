//<script>
	<?php
		$this->ExtForm->create('EduAbsentee');
		$this->ExtForm->defineFieldFunctions();
	?>
	var AbsenteeEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_absentees', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $absentee['EduAbsentee']['id'])); ?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $attendance_records;
				}
				$options['value'] = $absentee['EduAbsentee']['attendance_record_id'];
				$this->ExtForm->input('attendance_record_id', $options);
			?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $students;
				}
				$options['value'] = $absentee['EduAbsentee']['student_id'];
				$this->ExtForm->input('student_id', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $absentee['EduAbsentee']['code'];
				$this->ExtForm->input('code', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $absentee['EduAbsentee']['reason'];
				$this->ExtForm->input('reason', $options);
			?>
		]
	});
	
	var AbsenteeEditWindow = new Ext.Window({
		title: '<?php __('Edit Absentee'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: AbsenteeEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				AbsenteeEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Absentee.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(AbsenteeEditWindow.collapsed)
					AbsenteeEditWindow.expand(true);
				else
					AbsenteeEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				AbsenteeEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						AbsenteeEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentAbsenteeData();
<?php } else { ?>
						RefreshAbsenteeData();
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
				AbsenteeEditWindow.close();
			}
		}]
	});
