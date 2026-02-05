//<script>
		<?php
			$this->ExtForm->create('RelatedHelpItem');
			$this->ExtForm->defineFieldFunctions();
		?>
		var RelatedHelpItemAddForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'related_help_items', 'action' => 'add')); ?>',
			defaultType: 'textfield',

			items: [
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $help_items;
					$this->ExtForm->input('help_item_id', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $related_help_items;
					$this->ExtForm->input('related_help_item_id', $options);
				?>			]
		});
		
		var RelatedHelpItemAddWindow = new Ext.Window({
			title: '<?php __('Add Related Help Item'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: RelatedHelpItemAddForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					RelatedHelpItemAddForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to insert a new Related Help Item.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(RelatedHelpItemAddWindow.collapsed)
						RelatedHelpItemAddWindow.expand(true);
					else
						RelatedHelpItemAddWindow.collapse(true);
				}
			}],
			buttons: [  {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					RelatedHelpItemAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							RelatedHelpItemAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
							RefreshParentRelatedHelpItemData();
<?php } else { ?>
							RefreshRelatedHelpItemData();
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
					RelatedHelpItemAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							RelatedHelpItemAddWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentRelatedHelpItemData();
<?php } else { ?>
							RefreshRelatedHelpItemData();
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
					RelatedHelpItemAddWindow.close();
				}
			}]
		});
