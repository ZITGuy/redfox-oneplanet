//<script>
    <?php
        $this->ExtForm->create('RestorePoint');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var RestorePointAddForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'restore_points', 'action' => 'add')); ?>',
        defaultType: 'textfield',

        items: [
            <?php 
                $options = array('value' => date('Y-m-d'), 'readOnly' => true);
                $this->ExtForm->input('name', $options);
            ?>	
        ]
    });
		
    var RestorePointAddWindow = new Ext.Window({
        title: '<?php __('Create Restore Point'); ?>',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'right',
        items: RestorePointAddForm,
        tools: [{
                id: 'refresh',
                qtip: 'Reset',
                handler: function () {
                    RestorePointAddForm.getForm().reset();
                },
                scope: this
            }, {
                id: 'help',
                qtip: 'Help',
                handler: function () {
                    Ext.Msg.show({
                        title: 'Help',
                        buttons: Ext.MessageBox.OK,
                        msg: 'This form is used to insert a new Restore Point.',
                        icon: Ext.MessageBox.INFO
                    });
                }
            }, {
                id: 'toggle',
                qtip: 'Collapse / Expand',
                handler: function () {
                    if(RestorePointAddWindow.collapsed)
                        RestorePointAddWindow.expand(true);
                    else
                        RestorePointAddWindow.collapse(true);
                }
            }
        ],
        buttons: [{
            text: '<?php __('Create Backup'); ?>',
            handler: function(btn){
                RestorePointAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        RestorePointAddWindow.close();
                        RefreshRestorePointData();
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
                RestorePointAddWindow.close();
            }
        }]
    });
