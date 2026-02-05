//<script>
    <?php
        $this->ExtForm->create('HelpContent');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var HelpContentEditForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'help_contents', 'action' => 'edit')); ?>',
        defaultType: 'textfield',

        items: [
            <?php $this->ExtForm->input('id', array('hidden' => $help_content['HelpContent']['id'])); ?>,
            <?php 
                $options = array();
                $options['value'] = $help_content['HelpContent']['name'];
                $this->ExtForm->input('name', $options);
            ?>,
            <?php 
                $options = array();
                $options['value'] = $help_content['HelpContent']['code'];
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
                $options['value'] = $help_content['HelpContent']['content'];
                $this->ExtForm->input('content', $options);
            ?>		
        ]
    });
		
    var HelpContentEditWindow = new Ext.Window({
        title: '<?php __('Edit Help Content'); ?>',
        width: 600,
        minWidth: 500,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: HelpContentEditForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                HelpContentEditForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to modify an existing Help Content.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(HelpContentEditWindow.collapsed)
                    HelpContentEditWindow.expand(true);
                else
                    HelpContentEditWindow.collapse(true);
            }
        }],
        buttons: [ {
            text: '<?php __('Save'); ?>',
            handler: function(btn){
                HelpContentEditForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        HelpContentEditWindow.close();
<?php if(isset($parent_id)){ ?>
                        RefreshParentHelpContentData();
<?php } else { ?>
                        RefreshHelpContentData();
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
                HelpContentEditWindow.close();
            }
        }]
    });
