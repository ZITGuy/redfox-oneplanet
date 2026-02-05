//<script>
	<?php
		$this->ExtForm->create('EduDepartment');
		$this->ExtForm->defineFieldFunctions();
	?>
	var DepartmentAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_departments', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $users;
				}
				$this->ExtForm->input('user_id', $options);
			?>
		]
	});
	
	var DepartmentAddWindow = new Ext.Window({
		title: '<?php __('Add Department'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: DepartmentAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				DepartmentAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Department.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(DepartmentAddWindow.collapsed)
					DepartmentAddWindow.expand(true);
				else
					DepartmentAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				DepartmentAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						DepartmentAddForm.getForm().reset();
<?php if (isset($parent_id)) { ?>
						RefreshParentDepartmentData();
<?php } else { ?>
						RefreshDepartmentData();
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
				DepartmentAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						DepartmentAddWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentDepartmentData();
<?php } else { ?>
						RefreshDepartmentData();
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
				DepartmentAddWindow.close();
			}
		}]
	});
