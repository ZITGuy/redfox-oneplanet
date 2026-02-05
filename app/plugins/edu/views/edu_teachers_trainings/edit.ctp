//<script>
<?php
	$this->ExtForm->create('EduTeachersTraining');
	$this->ExtForm->defineFieldFunctions();
?>
var EduTeachersTrainingEditForm = new Ext.form.FormPanel({
	baseCls: 'x-plain',
	labelWidth: 100,
	labelAlign: 'right',
	url:'<?php echo $this->Html->url(array('controller' => 'eduTeachersTrainings', 'action' => 'edit')); ?>',
	defaultType: 'textfield',

	items: [
		<?php $this->ExtForm->input('id', array('hidden' => $edu_teachers_training['EduTeachersTraining']['id'])); ?>,
		<?php 
			$options = array();
			if(isset($parent_id))
				$options['hidden'] = $parent_id;
			else
				$options['items'] = $edu_teachers;
			$options['value'] = $edu_teachers_training['EduTeachersTraining']['edu_teacher_id'];
			$this->ExtForm->input('edu_teacher_id', $options);
		?>,
		<?php 
			$options = array('fieldLabel' => 'Training Taken', 'anchor' => '80%');
			$options['items'] = $edu_trainings;
			$options['value'] = $edu_teachers_training['EduTeachersTraining']['edu_training_id'];
			$this->ExtForm->input('edu_training_id', $options);
		?>,
		<?php
			$options = array('anchor' => '60%');
			$options['value'] = $edu_teachers_training['EduTeachersTraining']['from_date'];
			$this->ExtForm->input('from_date', $options);
		?>,
		<?php 
			$options = array('anchor' => '60%');
			$options['value'] = $edu_teachers_training['EduTeachersTraining']['to_date'];
			$this->ExtForm->input('to_date', $options);
		?>,
		<?php 
			$options = array('anchor' => '80%');
			$options['value'] = $edu_teachers_training['EduTeachersTraining']['trainer'];
			$this->ExtForm->input('trainer', $options);
		?>,
		<?php 
			$options = array('xtype' => 'textarea');
			$options['value'] = $edu_teachers_training['EduTeachersTraining']['remark'];
			$this->ExtForm->input('remark', $options);
		?>			
	]
});

var EduTeachersTrainingEditWindow = new Ext.Window({
	title: '<?php __('Edit Teacher Training'); ?>',
	width: 400,
	minWidth: 400,
	autoHeight: true,
	layout: 'fit',
	modal: true,
	resizable: true,
	plain:true,
	bodyStyle:'padding:5px;',
	buttonAlign:'right',
	items: EduTeachersTrainingEditForm,
	tools: [{
		id: 'refresh',
		qtip: 'Reset',
		handler: function () {
			EduTeachersTrainingEditForm.getForm().reset();
		},
		scope: this
	}, {
		id: 'help',
		qtip: 'Help',
		handler: function () {
			Ext.Msg.show({
				title: 'Help',
				buttons: Ext.MessageBox.OK,
				msg: 'This form is used to modify an existing Teachers Training.',
				icon: Ext.MessageBox.INFO
			});
		}
	}, {
		id: 'toggle',
		qtip: 'Collapse / Expand',
		handler: function () {
			if(EduTeachersTrainingEditWindow.collapsed)
				EduTeachersTrainingEditWindow.expand(true);
			else
				EduTeachersTrainingEditWindow.collapse(true);
		}
	}],
	buttons: [ {
		text: '<?php __('Save'); ?>',
		handler: function(btn){
			EduTeachersTrainingEditForm.getForm().submit({
				waitMsg: '<?php __('Submitting your data...'); ?>',
				waitTitle: '<?php __('Wait Please...'); ?>',
				success: function(f,a){
					Ext.Msg.show({
						title: '<?php __('Success'); ?>',
						buttons: Ext.MessageBox.OK,
						msg: a.result.msg,
						icon: Ext.MessageBox.INFO
					});
					EduTeachersTrainingEditWindow.close();
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
			EduTeachersTrainingEditWindow.close();
		}
	}]
});
