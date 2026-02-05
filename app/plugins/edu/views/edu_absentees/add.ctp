//<script>
	<?php
		$this->ExtForm->create('EduAbsentee');
		$this->ExtForm->defineFieldFunctions();
	?>
	var AbsenteeAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_absentees', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				if(isset($parent_id))
					$options['hidden'] = $parent_id;
				else
					$options['items'] = $attendance_records;
				$this->ExtForm->input('edu_attendance_record_id', $options);
			?>,
			<?php
				$options = array();
					$options['items'] = $students;
				$this->ExtForm->input('edu_student_id', $options);
			?>,
			<?php
				$options = array();
				$options = array('xtype' => 'combo', 'fieldLabel' => 'Absence Code', 'value' => 'Absent');
				$options['items'] = array('Absent' => 'Absent', 'Excused Absent' => 'Excused Absent','Late'=>'Late','Early Checkout'=>'Early Checkout');
				$this->ExtForm->input('code', $options);
			?>,
			<?php 
				$options = array();
				$this->ExtForm->input('reason', $options);
			?>			]
	});
	
	var AbsenteeAddWindow = new Ext.Window({
		title: '<?php __('Add Absentee'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: AbsenteeAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				AbsenteeAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Absentee.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(AbsenteeAddWindow.collapsed)
					AbsenteeAddWindow.expand(true);
				else
					AbsenteeAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				AbsenteeAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						AbsenteeAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
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
		}, {
			text: '<?php __('Save & Close'); ?>',
			handler: function(btn){
				AbsenteeAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						AbsenteeAddWindow.close();
<?php if(isset($parent_id)){ ?>
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
				AbsenteeAddWindow.close();
			}
		}]
	});
