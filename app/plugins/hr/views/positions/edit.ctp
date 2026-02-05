		<?php
			$this->ExtForm->create('Position');
			$this->ExtForm->defineFieldFunctions();
		?>
		var PositionEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'positions', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $position['Position']['id'])); ?>,
				<?php 
					$options4 = array(
                                            'enableKeyEvents' => 'true',
                                            'anchor' => '99%', 'emptyText' => 'የስራ መደብ', 'fieldLabel'=>'የስራ መደብ');
                                        $options4['listeners'] = '{
                                            keypress: function(tb,e){
                                                translateOnKeyPress(e,1);
                                            }
                                        }';
					$options4['value'] = $position['Position']['name'];
					$this->ExtForm->input('name', $options4);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $grades;
					$options['value'] = $position['Position']['grade_id'];
					$this->ExtForm->input('grade_id', $options);
				?>,
				<?php
					$options = array();
					$options['value'] = $position['Position']['is_managerial'];
					$this->ExtForm->input('is_managerial', $options);
					?>,
    <?php 
                $options = array();
                $options = array('xtype' => 'combo', 'anchor' => '100%', 'fieldLabel' => 'Job Classification');
                $options['items'] = array('Managerial / High level Supervisor'=>'Managerial / High level Supervisor','Professional'=>'Professional','Semi - Professional'=>'Semi - Professional','Technical'=>'Technical','Clerical'=>'Clerical','Other'=>'Other');
                $options['value'] = $position['Position']['classification'];
                $this->ExtForm->input('classification', $options);
                ?>				]
		});
		
		var PositionEditWindow = new Ext.Window({
			title: '<?php __('Edit Position'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: PositionEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					PositionEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Position.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(PositionEditWindow.collapsed)
						PositionEditWindow.expand(true);
					else
						PositionEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					PositionEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							PositionEditWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentPositionData();
<?php } else { ?>
							RefreshPositionData();
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
					PositionEditWindow.close();
				}
			}]
		});
