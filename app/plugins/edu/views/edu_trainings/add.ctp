//<script>
<?php
	$this->ExtForm->create('EduTraining');
	$this->ExtForm->defineFieldFunctions();
?>
var EduTrainingAddForm = new Ext.form.FormPanel({
	baseCls: 'x-plain',
	labelWidth: 100,
	labelAlign: 'right',
	url:'<?php echo $this->Html->url(array('controller' => 'edu_trainings', 'action' => 'add')); ?>',
	defaultType: 'textfield',

	items: [
		<?php 
			$options = array();
			$this->ExtForm->input('name', $options);
		?>,
		<?php
			$options = array('fieldLabel' => 'Category');
			if(isset($parent_id))
				$options['hidden'] = $parent_id;
			else
				$options['items'] = $categories;
			$this->ExtForm->input('edu_training_category_id', $options);
		?>
	]
});

var EduTrainingAddWindow = new Ext.Window({
	title: '<?php __('Add Training'); ?>',
	width: 400,
	minWidth: 400,
	autoHeight: true,
	layout: 'fit',
	modal: true,
	resizable: true,
	plain:true,
	bodyStyle:'padding:5px;',
	buttonAlign:'right',
	items: EduTrainingAddForm,
	tools: [{
		id: 'refresh',
		qtip: 'Reset',
		handler: function () {
			EduTrainingAddForm.getForm().reset();
		},
		scope: this
	}, {
		id: 'help',
		qtip: 'Help',
		handler: function () {
			Ext.Msg.show({
				title: 'Help',
				buttons: Ext.MessageBox.OK,
				msg: 'This form is used to insert a new Training.',
				icon: Ext.MessageBox.INFO
			});
		}
	}, {
		id: 'toggle',
		qtip: 'Collapse / Expand',
		handler: function () {
			if(EduTrainingAddWindow.collapsed)
				EduTrainingAddWindow.expand(true);
			else
				EduTrainingAddWindow.collapse(true);
		}
	}],
	buttons: [  {
		text: '<?php __('Save'); ?>',
		handler: function(btn){
			EduTrainingAddForm.getForm().submit({
				waitMsg: '<?php __('Submitting your data...'); ?>',
				waitTitle: '<?php __('Wait Please...'); ?>',
				success: function(f,a){
					Ext.Msg.show({
						title: '<?php __('Success'); ?>',
						buttons: Ext.MessageBox.OK,
						msg: a.result.msg,
						icon: Ext.MessageBox.INFO
					});
					EduTrainingAddForm.getForm().reset();
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
	}, {
		text: '<?php __('Save & Close'); ?>',
		handler: function(btn){
			EduTrainingAddForm.getForm().submit({
				waitMsg: '<?php __('Submitting your data...'); ?>',
				waitTitle: '<?php __('Wait Please...'); ?>',
				success: function(f,a){
					Ext.Msg.show({
						title: '<?php __('Success'); ?>',
						buttons: Ext.MessageBox.OK,
						msg: a.result.msg,
						icon: Ext.MessageBox.INFO
					});
					EduTrainingAddWindow.close();
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
			EduTrainingAddWindow.close();
		}
	}]
});
