//<script>
    <?php
        $this->ExtForm->create('HelpItem');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var HelpItemEditForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'help_items', 'action' => 'edit')); ?>',
        defaultType: 'textfield',

        items: [
            <?php $this->ExtForm->input('id', array('hidden' => $help_item['HelpItem']['id'])); ?>,
            <?php 
                $options = array();
                $options['value'] = $help_item['HelpItem']['title'];
                $this->ExtForm->input('title', $options);
            ?>,
            <?php 
                $options = array(
                    'xtype' => 'htmleditor', 
                    'height' => 300,
                    'enableFont' => false,
                    'enableFontSize' => true
                );
                $options['value'] = $help_item['HelpItem']['content'];
                $this->ExtForm->input('content', $options);
            ?>,
            <?php
                $options = array('anchor' => '40%');
                $options['value'] = $help_item['HelpItem']['list_order'];
                $this->ExtForm->input('list_order', $options);
            ?>	
        ]
    });
		
    var HelpItemEditWindow = new Ext.Window({
        title: '<?php __('Edit Help Item'); ?>',
        width: 600,
        minWidth: 500,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: HelpItemEditForm,
        tools: [{
                id: 'refresh',
                qtip: 'Reset',
                handler: function () {
                    HelpItemEditForm.getForm().reset();
                },
                scope: this
            }, {
                id: 'help',
                qtip: 'Help',
                handler: function () {
                    Ext.Msg.show({
                        title: 'Help',
                        buttons: Ext.MessageBox.OK,
                        msg: 'This form is used to modify an existing Help Item.',
                        icon: Ext.MessageBox.INFO
                    });
                }
            }, {
                id: 'toggle',
                qtip: 'Collapse / Expand',
                handler: function () {
                    if(HelpItemEditWindow.collapsed)
                        HelpItemEditWindow.expand(true);
                    else
                        HelpItemEditWindow.collapse(true);
                }
            }
        ],
        buttons: [{
                text: '<?php __('Save'); ?>',
                handler: function(btn){
                    HelpItemEditForm.getForm().submit({
                        waitMsg: '<?php __('Submitting your data...'); ?>',
                        waitTitle: '<?php __('Wait Please...'); ?>',
                        success: function(f,a){
                            Ext.Msg.show({
                                title: '<?php __('Success'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
                            });
                            HelpItemEditWindow.close();
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
                    HelpItemEditWindow.close();
                }
            }
        ]
    });
