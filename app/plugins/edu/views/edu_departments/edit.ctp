//<script>
	<?php
		$this->ExtForm->create('EduDepartment');
		$this->ExtForm->defineFieldFunctions();
	?>
	var DepartmentEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_departments', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $department['EduDepartment']['id'])); ?>,
			<?php
				$options = array();
				$options['value'] = $department['EduDepartment']['name'];
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $department['EduDepartment']['user_id'];
				if(isset($parent_id))
					$options['hidden'] = $parent_id;
				else
					$options['items'] = $users;
				$this->ExtForm->input('user_id', $options);
			?>
		]
	});
	
	var DepartmentEditWindow = new Ext.Window({
		title: '<?php __('Edit Department'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: DepartmentEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				DepartmentEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Department.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(DepartmentEditWindow.collapsed)
					DepartmentEditWindow.expand(true);
				else
					DepartmentEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				DepartmentEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						DepartmentEditWindow.close();
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
				DepartmentEditWindow.close();
			}
		}]
	});
