//<script>
		<?php
			$this->ExtForm->create('EduTrainingCategory');
			$this->ExtForm->defineFieldFunctions();
		?>
		var EduTrainingCategoryEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'eduTrainingCategories', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $edu_training_category['EduTrainingCategory']['id'])); ?>,
				<?php
					$options = array();
					$options['value'] = $edu_training_category['EduTrainingCategory']['name'];
					$this->ExtForm->input('name', $options);
				?>
			]
		});
		
		var EduTrainingCategoryEditWindow = new Ext.Window({
			title: '<?php __('Edit Training Category'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: EduTrainingCategoryEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					EduTrainingCategoryEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Edu Training Category.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(EduTrainingCategoryEditWindow.collapsed)
						EduTrainingCategoryEditWindow.expand(true);
					else
						EduTrainingCategoryEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					EduTrainingCategoryEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							EduTrainingCategoryEditWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentEduTrainingCategoryData();
<?php } else { ?>
							RefreshEduTrainingCategoryData();
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
					EduTrainingCategoryEditWindow.close();
				}
			}]
		});
