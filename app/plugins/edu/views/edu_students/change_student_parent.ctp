//<script>
		<?php
			$this->ExtForm->create('EduStudent');
			$this->ExtForm->defineFieldFunctions();
		?>
		var selectedParent = '';
		var EduParentChangeForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 150,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'change_student_parent')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $student['EduStudent']['id'])); ?>,
				<?php 
					$options = array('xtype' => 'combo', 'fieldLabel' => 'Parent', 'anchor' => '60%');
					$options['items'] = $parents;
					$options['listeners'] = "{
						scope: this,
						'select': function(combo, record, index){
							selectedParent = combo.getValue();
						}
					}";
					$options['value'] = $student['EduStudent']['edu_parent_id'];
					$this->ExtForm->input('edu_parent_id', $options);
				?>
			]
		});
		
		var EduParentChangeWindow = new Ext.Window({
			title: 'Change Student Parent',
			width: 650,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: EduParentChangeForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					EduParentChangeForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to change Parent for the selected student.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(EduParentChangeWindow.collapsed)
						EduParentChangeWindow.expand(true);
					else
						EduParentChangeWindow.collapse(true);
				}
			}],
			buttons: [  {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					EduParentChangeForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							EduParentChangeWindow.close();
							parentEduParentDetailsViewWindow.close();
							RefreshEduStudentData();
						},
						failure: function(f,a) {
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
				text: '<?php __('Cancel'); ?>',
				handler: function(btn){
					EduParentChangeWindow.close();
				}
			}]
		});