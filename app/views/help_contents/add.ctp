//<script>
    <?php
        $this->ExtForm->create('HelpContent');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var HelpContentAddForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'help_contents', 'action' => 'add')); ?>',
        defaultType: 'textfield',

        items: [
            <?php 
                $options = array('fieldLabel' => 'Title');
                $this->ExtForm->input('name', $options);
            ?>,
            <?php 
                $options = array('anchor' => '40%');
                $this->ExtForm->input('code', $options);
            ?>,
            <?php 
                $options = array(
                    'xtype' => 'htmleditor', 
                    'height' => 300,
                    'enableFont' => false,
                    'enableFontSize' => true,
                    'enableLinks' => false
                );
                $this->ExtForm->input('content', $options);
            ?>	
        ]
    });
		
    var HelpContentAddWindow = new Ext.Window({
        title: '<?php __('Add Help Content'); ?>',
        width: 600,
        minWidth: 500,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: HelpContentAddForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                HelpContentAddForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to insert a new Help Content.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(HelpContentAddWindow.collapsed)
                    HelpContentAddWindow.expand(true);
                else
                    HelpContentAddWindow.collapse(true);
            }
        }],
        buttons: [{
            text: '<?php __('Save'); ?>',
            handler: function(btn){
                HelpContentAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        HelpContentAddForm.getForm().reset();
                        RefreshHelpContentData();
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
                HelpContentAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        HelpContentAddWindow.close();
                        RefreshHelpContentData();
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
                HelpContentAddWindow.close();
            }
        }]
    });
