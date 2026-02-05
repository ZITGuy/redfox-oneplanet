//<script>
		<?php
			$this->ExtForm->create('AcctAccount');
			$this->ExtForm->defineFieldFunctions();
		?>
		var AcctAccountEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 160,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'acctAccounts', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $acct_account['AcctAccount']['id'])); ?>,
				<?php 
					$options = array();
					$options['value'] = $acct_account['AcctAccount']['name'];
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array('fieldLabel' => 'Account Category', 'anchor'=>'80%');
					$options['items'] = $acct_categories;
					$options['value'] = $acct_account['AcctAccount']['acct_category_id'];
					$this->ExtForm->input('acct_category_id', $options);
				?>,
				<?php 
					$options = array('anchor'=>'60%');
					$options['value'] = $acct_account['AcctAccount']['code'];
					$this->ExtForm->input('code', $options);
				?>,
				<?php 
					$options = array('anchor'=>'60%', 'vtype'=>'Currency');
					$options['value'] = $acct_account['AcctAccount']['balance'];
					$this->ExtForm->input('balance', $options);
				?>,
                                <?php 
                                    $options6 = array();
                                    $options6['hidden'] = $parent_id;
                                    $this->ExtForm->input('parent_id', $options6);
                                ?>		
                        ]
		});
		
		var AcctAccountEditWindow = new Ext.Window({
			title: '<?php __('Edit Acct Account'); ?>',
			width: 550,
                        minWidth: 500,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: AcctAccountEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					AcctAccountEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Acct Account.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(AcctAccountEditWindow.collapsed)
						AcctAccountEditWindow.expand(true);
					else
						AcctAccountEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					AcctAccountEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							AcctAccountEditWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentAcctAccountData();
<?php } else { ?>
							RefreshAcctAccountData();
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
					AcctAccountEditWindow.close();
				}
			}]
		});