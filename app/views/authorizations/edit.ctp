		<?php
			$this->ExtForm->create('Authorization');
			$this->ExtForm->defineFieldFunctions();
		?>
		var AuthorizationEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'authorizations', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $authorization['Authorization']['id'])); ?>,
				<?php 
					$options = array();
					$options['value'] = $authorization['Authorization']['name'];
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $authorization['Authorization']['command_script'];
					$this->ExtForm->input('command_script', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $makers;
					$options['value'] = $authorization['Authorization']['maker_id'];
					$this->ExtForm->input('maker_id', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $authorizers;
					$options['value'] = $authorization['Authorization']['authorizer_id'];
					$this->ExtForm->input('authorizer_id', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $authorization['Authorization']['status'];
					$this->ExtForm->input('status', $options);
				?>			]
		});
		
		var AuthorizationEditWindow = new Ext.Window({
			title: '<?php __('Edit Authorization'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: AuthorizationEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					AuthorizationEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Authorization.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(AuthorizationEditWindow.collapsed)
						AuthorizationEditWindow.expand(true);
					else
						AuthorizationEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					AuthorizationEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							AuthorizationEditWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentAuthorizationData();
<?php } else { ?>
							RefreshAuthorizationData();
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
					AuthorizationEditWindow.close();
				}
			}]
		});
