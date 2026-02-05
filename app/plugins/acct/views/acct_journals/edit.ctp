//<script>
		<?php
			$this->ExtForm->create('AcctJournal');
			$this->ExtForm->defineFieldFunctions();
		?>
		var AcctJournalEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'acctJournals', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $acct_journal['AcctJournal']['id'])); ?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $acct_transactions;
					$options['value'] = $acct_journal['AcctJournal']['acct_transaction_id'];
					$this->ExtForm->input('acct_transaction_id', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $acct_accounts;
					$options['value'] = $acct_journal['AcctJournal']['acct_account_id'];
					$this->ExtForm->input('acct_account_id', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $acct_journal['AcctJournal']['dr'];
					$this->ExtForm->input('dr', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $acct_journal['AcctJournal']['cr'];
					$this->ExtForm->input('cr', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $acct_journal['AcctJournal']['bbf'];
					$this->ExtForm->input('bbf', $options);
				?>			]
		});
		
		var AcctJournalEditWindow = new Ext.Window({
			title: '<?php __('Edit Acct Journal'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: AcctJournalEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					AcctJournalEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Acct Journal.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(AcctJournalEditWindow.collapsed)
						AcctJournalEditWindow.expand(true);
					else
						AcctJournalEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					AcctJournalEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							AcctJournalEditWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentAcctJournalData();
<?php } else { ?>
							RefreshAcctJournalData();
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
					AcctJournalEditWindow.close();
				}
			}]
		});