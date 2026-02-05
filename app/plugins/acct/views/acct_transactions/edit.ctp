//<script>
		<?php
			$this->ExtForm->create('AcctTransaction');
			$this->ExtForm->defineFieldFunctions();
		?>
		var AcctTransactionEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'acctTransactions', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $acct_transaction['AcctTransaction']['id'])); ?>,
				<?php 
					$options = array();
					$options['value'] = $acct_transaction['AcctTransaction']['name'];
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $acct_transaction['AcctTransaction']['description'];
					$this->ExtForm->input('description', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $acct_transaction['AcctTransaction']['cheque_number'];
					$this->ExtForm->input('cheque_number', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $acct_transaction['AcctTransaction']['invoice_number'];
					$this->ExtForm->input('invoice_number', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $acct_transaction['AcctTransaction']['transaction_date'];
					$this->ExtForm->input('transaction_date', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $acct_fiscal_years;
					$options['value'] = $acct_transaction['AcctTransaction']['acct_fiscal_year_id'];
					$this->ExtForm->input('acct_fiscal_year_id', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $users;
					$options['value'] = $acct_transaction['AcctTransaction']['user_id'];
					$this->ExtForm->input('user_id', $options);
				?>			]
		});
		
		var AcctTransactionEditWindow = new Ext.Window({
			title: '<?php __('Edit Acct Transaction'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: AcctTransactionEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					AcctTransactionEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Acct Transaction.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(AcctTransactionEditWindow.collapsed)
						AcctTransactionEditWindow.expand(true);
					else
						AcctTransactionEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					AcctTransactionEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							AcctTransactionEditWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentAcctTransactionData();
<?php } else { ?>
							RefreshAcctTransactionData();
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
					AcctTransactionEditWindow.close();
				}
			}]
		});