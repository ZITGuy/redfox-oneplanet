//<script>
		<?php
			$this->ExtForm->create('RelatedHelpItem');
			$this->ExtForm->defineFieldFunctions();
		?>
		var RelatedHelpItemEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'related_help_items', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $related_help_item['RelatedHelpItem']['id'])); ?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $help_items;
					$options['value'] = $related_help_item['RelatedHelpItem']['help_item_id'];
					$this->ExtForm->input('help_item_id', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $related_help_items;
					$options['value'] = $related_help_item['RelatedHelpItem']['related_help_item_id'];
					$this->ExtForm->input('related_help_item_id', $options);
				?>			]
		});
		
		var RelatedHelpItemEditWindow = new Ext.Window({
			title: '<?php __('Edit Related Help Item'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: RelatedHelpItemEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					RelatedHelpItemEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Related Help Item.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(RelatedHelpItemEditWindow.collapsed)
						RelatedHelpItemEditWindow.expand(true);
					else
						RelatedHelpItemEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					RelatedHelpItemEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							RelatedHelpItemEditWindow.close();
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
					RelatedHelpItemEditWindow.close();
				}
			}]
		});
