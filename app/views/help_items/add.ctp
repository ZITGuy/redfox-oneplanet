//<script>
    <?php
        $this->ExtForm->create('HelpItem');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var HelpItemAddForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'help_items', 'action' => 'add')); ?>',
        defaultType: 'textfield',

        items: [
            <?php 
                $options = array();
                $this->ExtForm->input('title', $options);
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
            ?>,
            <?php 
                $options = array('anchor' => '40%');
                $this->ExtForm->input('list_order', $options);
            ?>,
            <?php 
                $options = array();
                $options['hidden'] = $parent_id;
                $this->ExtForm->input('parent_id', $options);
            ?>
        ]
    });
		
    var HelpItemAddWindow = new Ext.Window({
        title: '<?php __('Add Help Item'); ?>',
        width: 600,
        minWidth: 500,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: HelpItemAddForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                HelpItemAddForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to insert a new Help Item.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(HelpItemAddWindow.collapsed)
                    HelpItemAddWindow.expand(true);
                else
                    HelpItemAddWindow.collapse(true);
            }
        }],
        buttons: [  {
            text: '<?php __('Save'); ?>',
            handler: function(btn){
                HelpItemAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        HelpItemAddForm.getForm().reset();
                        RefreshHelpItemData();
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
                HelpItemAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        HelpItemAddWindow.close();
                        RefreshHelpItemData();
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
                HelpItemAddWindow.close();
            }
        }]
    });
