//<script>
<?php
	$this->ExtForm->create('EduTraining');
	$this->ExtForm->defineFieldFunctions();
?>
var EduTrainingEditForm = new Ext.form.FormPanel({
	baseCls: 'x-plain',
	labelWidth: 100,
	labelAlign: 'right',
	url:'<?php echo $this->Html->url(array('controller' => 'edu_trainings', 'action' => 'edit')); ?>',
	defaultType: 'textfield',

	items: [
		<?php $this->ExtForm->input('id', array('hidden' => $edu_training['EduTraining']['id'])); ?>,
		<?php 
			$options = array();
			$options['value'] = $edu_training['EduTraining']['name'];
			$this->ExtForm->input('name', $options);
		?>,
		<?php 
			$options = array('fieldLabel' => 'Category');
			if(isset($parent_id))
				$options['hidden'] = $parent_id;
			else
				$options['items'] = $categories;
			$options['value'] = $edu_training['EduTraining']['edu_training_category_id'];
			$this->ExtForm->input('edu_training_category_id', $options);
		?>
	]
});

var EduTrainingEditWindow = new Ext.Window({
	title: '<?php __('Edit Training'); ?>',
	width: 400,
	minWidth: 400,
	autoHeight: true,
	layout: 'fit',
	modal: true,
	resizable: true,
	plain:true,
	bodyStyle:'padding:5px;',
	buttonAlign:'right',
	items: EduTrainingEditForm,
	tools: [{
		id: 'refresh',
		qtip: 'Reset',
		handler: function () {
			EduTrainingEditForm.getForm().reset();
		},
		scope: this
	}, {
		id: 'help',
		qtip: 'Help',
		handler: function () {
			Ext.Msg.show({
				title: 'Help',
				buttons: Ext.MessageBox.OK,
				msg: 'This form is used to modify an existing Training.',
				icon: Ext.MessageBox.INFO
			});
		}
	}, {
		id: 'toggle',
		qtip: 'Collapse / Expand',
		handler: function () {
			if(EduTrainingEditWindow.collapsed)
				EduTrainingEditWindow.expand(true);
			else
				EduTrainingEditWindow.collapse(true);
		}
	}],
	buttons: [ {
		text: '<?php __('Save'); ?>',
		handler: function(btn){
			EduTrainingEditForm.getForm().submit({
				waitMsg: '<?php __('Submitting your data...'); ?>',
				waitTitle: '<?php __('Wait Please...'); ?>',
				success: function(f,a){
					Ext.Msg.show({
						title: '<?php __('Success'); ?>',
						buttons: Ext.MessageBox.OK,
						msg: a.result.msg,
						icon: Ext.MessageBox.INFO
					});
					EduTrainingEditWindow.close();
<?php if(isset($parent_id)){ ?>
					RefreshParentEduTrainingData();
<?php } else { ?>
					RefreshEduTrainingData();
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
			EduTrainingEditWindow.close();
		}
	}]
});
