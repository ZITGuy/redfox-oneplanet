//<script>
		<?php
			$this->ExtForm->create('AcctFiscalYear');
			$this->ExtForm->defineFieldFunctions();
		?>
		var AcctFiscalYearAddForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'acctFiscalYears', 'action' => 'add')); ?>',
			defaultType: 'textfield',

			items: [
				<?php 
					$options = array();
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('start_date', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('end_date', $options);
				?>			]
		});
		
		var AcctFiscalYearAddWindow = new Ext.Window({
			title: '<?php __('Add Acct Fiscal Year'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: AcctFiscalYearAddForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					AcctFiscalYearAddForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to insert a new Acct Fiscal Year.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(AcctFiscalYearAddWindow.collapsed)
						AcctFiscalYearAddWindow.expand(true);
					else
						AcctFiscalYearAddWindow.collapse(true);
				}
			}],
			buttons: [  {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					AcctFiscalYearAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							AcctFiscalYearAddForm.getForm().reset();
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
			}, {
				text: '<?php __('Save & Close'); ?>',
				handler: function(btn){
					AcctFiscalYearAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							AcctFiscalYearAddWindow.close();
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
					AcctFiscalYearAddWindow.close();
				}
			}]
		});