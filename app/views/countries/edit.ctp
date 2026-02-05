		<?php
			$this->ExtForm->create('Country');
			$this->ExtForm->defineFieldFunctions();
		?>
		var CountryEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'countries', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $country['Country']['id'])); ?>,
				<?php 
					$options = array();
					$options['value'] = $country['Country']['name'];
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $country['Country']['code'];
					$this->ExtForm->input('code', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $country['Country']['currency'];
					$this->ExtForm->input('currency', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $country['Country']['nationality'];
					$this->ExtForm->input('nationality', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $country['Country']['language'];
					$this->ExtForm->input('language', $options);
				?>			]
		});
		
		var CountryEditWindow = new Ext.Window({
			title: '<?php __('Edit Country'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: CountryEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					CountryEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Country.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(CountryEditWindow.collapsed)
						CountryEditWindow.expand(true);
					else
						CountryEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					CountryEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							CountryEditWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentCountryData();
<?php } else { ?>
							RefreshCountryData();
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
					CountryEditWindow.close();
				}
			}]
		});
