//<script>
<?php
	$this->ExtForm->create('EduTeachersTraining');
	$this->ExtForm->defineFieldFunctions();
?>
var EduTeachersTrainingAddForm = new Ext.form.FormPanel({
	baseCls: 'x-plain',
	labelWidth: 100,
	labelAlign: 'right',
	url:'<?php echo $this->Html->url(array('controller' => 'edu_teachers_trainings', 'action' => 'add')); ?>',
	defaultType: 'textfield',

	items: [
		<?php 
			$options = array();
			if(isset($parent_id))
				$options['hidden'] = $parent_id;
			else
				$options['items'] = $edu_teachers;
			$this->ExtForm->input('edu_teacher_id', $options);
		?>,
		<?php 
			$options = array('fieldLabel' => 'Training Taken', 'anchor' => '80%');
			$options['items'] = $edu_trainings;
			$this->ExtForm->input('edu_training_id', $options);
		?>,
		<?php 
			$options = array('anchor' => '60%');
			$this->ExtForm->input('from_date', $options);
		?>,
		<?php 
			$options = array('anchor' => '60%');
			$this->ExtForm->input('to_date', $options);
		?>,
		<?php 
			$options = array('anchor' => '80%');
			$this->ExtForm->input('trainer', $options);
		?>,
		<?php 
			$options = array('xtype' => 'textarea');
			$this->ExtForm->input('remark', $options);
		?>			
	]
});

var EduTeachersTrainingAddWindow = new Ext.Window({
	title: '<?php __('Add Teacher Training'); ?>',
	width: 400,
	minWidth: 400,
	autoHeight: true,
	layout: 'fit',
	modal: true,
	resizable: true,
	plain:true,
	bodyStyle:'padding:5px;',
	buttonAlign:'right',
	items: EduTeachersTrainingAddForm,
	tools: [{
		id: 'refresh',
		qtip: 'Reset',
		handler: function () {
			EduTeachersTrainingAddForm.getForm().reset();
		},
		scope: this
	}, {
		id: 'help',
		qtip: 'Help',
		handler: function () {
			Ext.Msg.show({
				title: 'Help',
				buttons: Ext.MessageBox.OK,
				msg: 'This form is used to insert a new Teachers Training.',
				icon: Ext.MessageBox.INFO
			});
		}
	}, {
		id: 'toggle',
		qtip: 'Collapse / Expand',
		handler: function () {
			if(EduTeachersTrainingAddWindow.collapsed)
				EduTeachersTrainingAddWindow.expand(true);
			else
				EduTeachersTrainingAddWindow.collapse(true);
		}
	}],
	buttons: [  {
		text: '<?php __('Save'); ?>',
		handler: function(btn){
			EduTeachersTrainingAddForm.getForm().submit({
				waitMsg: '<?php __('Submitting your data...'); ?>',
				waitTitle: '<?php __('Wait Please...'); ?>',
				success: function(f,a){
					Ext.Msg.show({
						title: '<?php __('Success'); ?>',
						buttons: Ext.MessageBox.OK,
						msg: a.result.msg,
						icon: Ext.MessageBox.INFO
					});
					EduTeachersTrainingAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
					RefreshParentEduTeachersTrainingData();
<?php } else { ?>
					RefreshEduTeachersTrainingData();
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
			EduTeachersTrainingAddForm.getForm().submit({
				waitMsg: '<?php __('Submitting your data...'); ?>',
				waitTitle: '<?php __('Wait Please...'); ?>',
				success: function(f,a){
					Ext.Msg.show({
						title: '<?php __('Success'); ?>',
						buttons: Ext.MessageBox.OK,
						msg: a.result.msg,
						icon: Ext.MessageBox.INFO
					});
					EduTeachersTrainingAddWindow.close();
<?php if(isset($parent_id)){ ?>
					RefreshParentEduTeachersTrainingData();
<?php } else { ?>
					RefreshEduTeachersTrainingData();
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
			EduTeachersTrainingAddWindow.close();
		}
	}]
});
