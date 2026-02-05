//<script>
	<?php
		$this->ExtForm->create('TextMessage');
		$this->ExtForm->defineFieldFunctions();
	?>
	var TextMessageBroadcastForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
		url:'<?php echo $this->Html->url(array('controller' => 'text_messages', 'action' => 'broadcast')); ?>',
		defaultType: 'textfield',

		items: [
			<?php 
				$options = array('id' => 'txtTitle');
				$this->ExtForm->input('title', $options);
			?>,
			new Ext.form.CheckboxGroup({
                id: 'receiverGroup',
                xtype: 'checkboxgroup',
                fieldLabel: 'To',
                itemCls: 'x-check-group-alt',
                columns: 3,
                items: [
<?php
				$groups = array(
					'ALL' => 'All', 'T' => 'Teachers', 'P' => 'Parents', 
					'OU' => 'Office Users', 'SH' => 'Shareholders',
					'BOD' => 'Board Members', 'PR' => 'Principal'
					);
                $st = false;
                foreach($groups as $key => $value){
                    if($st) echo ",";
?>
                    {
                        boxLabel: '<?php echo Inflector::humanize($value); ?>', 
                        name: '<?php echo "data[Receiver][" . $key . "]"; ?>'
                    }
<?php
                    $st = true;
                }
?>
                ],
                listeners: {
					change: function(cbg, checkeds){
						
						var found = false;
						for(var i = 0; i < checkeds.length; i++){
							if(checkeds[i].getName() == 'data[Receiver][ALL]'){
								found = true;
								break;
							}
						}
						if(found){
							cbg.setValue([true, true, true, true, true, true, true]);
						}
					}
				}
            }),
			<?php 
				$options = array('xtype'=>'textarea', 'id' => 'txtBody');
				$this->ExtForm->input('message', $options);
			?>, 
			new Ext.form.CheckboxGroup({
				id:'optGroup',
				xtype: 'checkboxgroup',
				fieldLabel: 'Via',
				itemCls: 'x-check-group-alt',
				columns: 3,
				items: [
					{
						boxLabel: 'SMS', 
						name: 'SMS',
						disabled: true,
						checked: true
					}, {
						boxLabel: 'EMAIL', 
						name: 'EMAIL'
					}
				]
            })
        ]
	});
	
	var TextMessageBroadcastWindow = new Ext.Window({
		title: '<?php __('Text Message Broadcaster'); ?>',
		width: 500,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: TextMessageBroadcastForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				TextMessageBroadcastForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Text Message.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(TextMessageBroadcastWindow.collapsed)
					TextMessageBroadcastWindow.expand(true);
				else
					TextMessageBroadcastWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Send'); ?>',
			handler: function(btn){
				var msg = '';
				
				if(Ext.getCmp('txtBody').getValue() == ''){
					msg = 'Message body should not be empty';
				}
				if(Ext.getCmp('receiverGroup').getValue().length == 0){
					msg = 'Atleast one receiver group should be selected';
				}
				if(Ext.getCmp('txtTitle').getValue() == ''){
					msg = 'Message title should be specified';
				}

				if(msg != ''){
					Ext.Msg.show({
						title: '<?php __('Ooops!'); ?>',
						buttons: Ext.MessageBox.OK,
						msg: msg,
						icon: Ext.MessageBox.ERROR
					});
				} else {
					TextMessageBroadcastForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
								icon: Ext.MessageBox.INFO
							});
							TextMessageBroadcastWindow.close();
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
				
			}
		}, {
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				TextMessageBroadcastWindow.close();
			}
		}]
	});

TextMessageBroadcastWindow.show();