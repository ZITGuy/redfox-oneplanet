//<script>
		<?php
			$this->ExtForm->create('EduTrainingCategory');
			$this->ExtForm->defineFieldFunctions();
		?>
		var EduTrainingCategoryAddForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'eduTrainingCategories', 'action' => 'add')); ?>',
			defaultType: 'textfield',

			items: [
				<?php 
					$options = array();
					$this->ExtForm->input('name', $options);
				?>			
			]
		});
		
		var EduTrainingCategoryAddWindow = new Ext.Window({
			title: '<?php __('Add Training Category'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: EduTrainingCategoryAddForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					EduTrainingCategoryAddForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to insert a new Edu Training Category.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(EduTrainingCategoryAddWindow.collapsed)
						EduTrainingCategoryAddWindow.expand(true);
					else
						EduTrainingCategoryAddWindow.collapse(true);
				}
			}],
			buttons: [  {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					EduTrainingCategoryAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							EduTrainingCategoryAddForm.getForm().reset();
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
			}, {
				text: '<?php __('Save & Close'); ?>',
				handler: function(btn){
					EduTrainingCategoryAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							EduTrainingCategoryAddWindow.close();
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
					EduTrainingCategoryAddWindow.close();
				}
			}]
		});
