//<script>
		<?php
			$this->ExtForm->create('ReportCategory');
			$this->ExtForm->defineFieldFunctions();
		?>
		var ReportCategoryEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'report_categories', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $report_category['ReportCategory']['id'])); ?>,
				<?php 
					$options = array();
					$options['value'] = $report_category['ReportCategory']['name'];
					$this->ExtForm->input('name', $options);
				?>			]
		});
		
		var ReportCategoryEditWindow = new Ext.Window({
			title: '<?php __('Edit Report Category'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: ReportCategoryEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					ReportCategoryEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Report Category.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(ReportCategoryEditWindow.collapsed)
						ReportCategoryEditWindow.expand(true);
					else
						ReportCategoryEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					ReportCategoryEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							ReportCategoryEditWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentReportCategoryData();
<?php } else { ?>
							RefreshReportCategoryData();
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
					ReportCategoryEditWindow.close();
				}
			}]
		});
