//<script>
		<?php
			$this->ExtForm->create('EduStudent');
			$this->ExtForm->defineFieldFunctions();
		?>
		var EduStudentAddForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'add')); ?>',
			defaultType: 'textfield',

			items: [
				<?php 
					$options = array();
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('birth_date', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('registration_date', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $edu_parents;
					$this->ExtForm->input('edu_parent_id', $options);
				?>,
				<?php 
					$options = array();
					$options['items'] = $users;
					$this->ExtForm->input('user_id', $options);
				?>		
			]
		});
		
		var EduStudentAddWindow = new Ext.Window({
			title: '<?php __('Add Student'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: EduStudentAddForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					EduStudentAddForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to insert a new Student.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(EduStudentAddWindow.collapsed)
						EduStudentAddWindow.expand(true);
					else
						EduStudentAddWindow.collapse(true);
				}
			}],
			buttons: [  {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					EduStudentAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							EduStudentAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
							RefreshParentEduStudentData();
<?php } else { ?>
							RefreshEduStudentData();
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
					EduStudentAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							EduStudentAddWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentEduStudentData();
<?php } else { ?>
							RefreshEduStudentData();
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
					EduStudentAddWindow.close();
				}
			}]
		});