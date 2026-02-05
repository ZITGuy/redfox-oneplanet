//<script>
    <?php
        $this->ExtForm->create('AcctAccount');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var AcctAccountAddForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 160,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'acctAccounts', 'action' => 'add', 'plugin'=>'acct')); ?>',
        defaultType: 'textfield',

        items: [
            <?php 
                    $options0 = array();
                    $this->ExtForm->input('name', $options0);
            ?>,
            <?php 
                    $options1 = array('fieldLabel' => 'Account Category', 'anchor'=>'80%');
                    $options1['items'] = $acct_categories;
                    $this->ExtForm->input('acct_category_id', $options1);
            ?>,
            <?php 
                    $options2 = array('anchor'=>'60%');
                    $this->ExtForm->input('code', $options2);
            ?>,
            <?php 
                    $options3 = array('anchor'=>'60%', 'value'=> '0.00', 'vtype'=>'Currency');
                    $this->ExtForm->input('balance', $options3);
            ?>,
            <?php 
                $options6 = array();
                $options6['hidden'] = $parent_id;
                $this->ExtForm->input('parent_id', $options6);
            ?>		
        ]
    });

    var AcctAccountAddWindow = new Ext.Window({
        title: '<?php __('Add Account'); ?>',
        width: 550,
        minWidth: 500,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: AcctAccountAddForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                AcctAccountAddForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to insert a new Acct Account.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(AcctAccountAddWindow.collapsed)
                    AcctAccountAddWindow.expand(true);
                else
                    AcctAccountAddWindow.collapse(true);
            }
        }],
        buttons: [  {
            text: '<?php __('Save'); ?>',
            handler: function(btn){
                AcctAccountAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        AcctAccountAddForm.getForm().reset();
                        RefreshAcctAccountData();
                        p.getRootNode().reload();
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
                AcctAccountAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        AcctAccountAddWindow.close();
                        RefreshAcctAccountData();
                        p.getRootNode().reload();
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
                AcctAccountAddWindow.close();
            }
        }]
    });