//<script>
		<?php
			$this->ExtForm->create('AcctFiscalYear');
			$this->ExtForm->defineFieldFunctions();
		?>
		var AcctFiscalYearEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'acctFiscalYears', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $acct_fiscal_year['AcctFiscalYear']['id'])); ?>,
				<?php 
					$options = array();
					$options['value'] = $acct_fiscal_year['AcctFiscalYear']['name'];
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $acct_fiscal_year['AcctFiscalYear']['start_date'];
					$this->ExtForm->input('start_date', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $acct_fiscal_year['AcctFiscalYear']['end_date'];
					$this->ExtForm->input('end_date', $options);
				?>			]
		});
		
		var AcctFiscalYearEditWindow = new Ext.Window({
			title: '<?php __('Edit Acct Fiscal Year'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: AcctFiscalYearEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					AcctFiscalYearEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Acct Fiscal Year.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(AcctFiscalYearEditWindow.collapsed)
						AcctFiscalYearEditWindow.expand(true);
					else
						AcctFiscalYearEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					AcctFiscalYearEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							AcctFiscalYearEditWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentAcctFiscalYearData();
<?php } else { ?>
							RefreshAcctFiscalYearData();
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
					AcctFiscalYearEditWindow.close();
				}
			}]
		});