//<script>
<?php
    $this->ExtForm->create('User');
    $this->ExtForm->defineFieldFunctions();
?>

var UserChangeCampusForm = new Ext.form.FormPanel({
    baseCls: 'x-plain',
	labelWidth: 100,
        labelAlign: 'right',
        autoHeight: true,
        width: 500,
        resizable: false,
        plain:true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',
	url:'<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'change_campus')); ?>',
	defaultType: 'textfield',

	items: [
		<?php 
			$options6 = array('anchor' => '80%', 'fieldLabel' => 'Campus');
			$options6['items'] = $edu_campuses;
			$this->ExtForm->input('edu_campus_id', $options6);
		?>			
	]
});

var UserChangeCampusWindow = new Ext.Window({
    title: '<?php __('Change Campus'); ?>',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: UserChangeCampusForm,
        tools: [{
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Holiday.',
					icon: Ext.MessageBox.INFO
				});
			}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(HolidayAddWindow.collapsed)
						UserChangeCampusWindow.expand(true);
					else
						UserChangeCampusWindow.collapse(true);
				}
			}
		],
        buttons: [  {
            text: '<?php __('Change Campus'); ?>',
            handler: function(btn){
                UserChangeCampusForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO,
							fn: function (btn) {
								UserChangeCampusWindow.close();
								location.reload();
							}
                        });
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
            text: '<?php __('Cancel'); ?>',
            handler: function(btn){
                UserChangeCampusWindow.close();
            }
        }]
});
