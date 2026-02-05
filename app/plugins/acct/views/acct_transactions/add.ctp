//<script>
    <?php
        $this->ExtForm->create('AcctTransaction');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var AcctTransactionAddForm = new Ext.form.FormPanel({
            baseCls: 'x-plain',
            labelWidth: 150,
            labelAlign: 'right',
            url:'<?php echo $this->Html->url(array('controller' => 'acctTransactions', 'action' => 'add')); ?>',
            defaultType: 'textfield',

            items: [
                    <?php 
                        $options0 = array('fieldLabel'=>'Reference', 'value' => time());
                        $this->ExtForm->input('name', $options0);
                    ?>,
                    <?php 
                        $options1 = array('fieldLabel'=>'Narrative');
                        $this->ExtForm->input('description', $options1);
                    ?>,
                    <?php 
                        $options2 = array('anchor'=>'70%');
                        $this->ExtForm->input('cheque_number', $options2);
                    ?>,
                    <?php 
                        $options3 = array('anchor'=>'70%');
                        $this->ExtForm->input('invoice_number', $options3);
                    ?>,
                    <?php 
                        $options4 = array('value'=>date('Y-m-d'), 'anchor'=>'60%');
                        $this->ExtForm->input('transaction_date', $options4);
                    ?>, { 
                        xtype: 'compositefield',
                        labelWidth: 120,
                        fieldLabel: '<span style="color:red;">*</span>DR',
                        items:[
                            <?php 
                                $options5 = array('anchor'=>'50%');
                                $options5['items'] = $dr_accounts;
                                $this->ExtForm->input('dr_account_id', $options5);
                            ?>,
                            <?php 
                                $options6 = array('anchor'=>'50%', 
                                    'style' => 'text-align: right;', 
                                    'maskRe' => '/^([0-9.,])*$/', 
                                    'value' => '0.00');
                                $this->ExtForm->input('dr_value', $options6);
                            ?>
                        ]
                    }, { 
                        xtype: 'compositefield',
                        labelWidth: 120,
                        fieldLabel: '<span style="color:red;">*</span>CR',
                        items:[
                            <?php 
                                $options7 = array('anchor'=>'50%');
                                $options7['items'] = $cr_accounts;
                                $this->ExtForm->input('cr_account_id', $options7);
                            ?>,
                            <?php 
                                $options8 = array('anchor'=>'50%', 
                                    'style' => 'text-align: right;',
                                    'maskRe' => '/^([0-9.,])*$/', 
                                    'value' => '0.00');
                                $this->ExtForm->input('cr_value', $options8);
                            ?>
                        ]
                    }	
                ]
    });

    
	
    var AcctTransactionAddWindow = new Ext.Window({
            title: '<?php __('Add Acct Transaction'); ?>',
            width: 700,
            minWidth: 650,
            autoHeight: true,
            layout: 'fit',
            modal: true,
            resizable: true,
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'right',
            items: [AcctTransactionAddForm],
            tools: [{
                    id: 'refresh',
                    qtip: 'Reset',
                    handler: function () {
                            AcctTransactionAddForm.getForm().reset();
                    },
                    scope: this
            }, {
                    id: 'help',
                    qtip: 'Help',
                    handler: function () {
                            Ext.Msg.show({
                                    title: 'Help',
                                    buttons: Ext.MessageBox.OK,
                                    msg: 'This form is used to insert a new Acct Transaction.',
                                    icon: Ext.MessageBox.INFO
                            });
                    }
            }, {
                    id: 'toggle',
                    qtip: 'Collapse / Expand',
                    handler: function () {
                            if(AcctTransactionAddWindow.collapsed)
                                    AcctTransactionAddWindow.expand(true);
                            else
                                    AcctTransactionAddWindow.collapse(true);
                    }
            }],
            buttons: [  {
                    text: '<?php __('Save'); ?>',
                    handler: function(btn){
                            AcctTransactionAddForm.getForm().submit({
                                    waitMsg: '<?php __('Submitting your data...'); ?>',
                                    waitTitle: '<?php __('Wait Please...'); ?>',
                                    success: function(f,a){
                                            Ext.Msg.show({
                                                    title: '<?php __('Success'); ?>',
                                                    buttons: Ext.MessageBox.OK,
                                                    msg: a.result.msg,
                    icon: Ext.MessageBox.INFO
                                            });
                                            AcctTransactionAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
                                            RefreshParentAcctTransactionData();
<?php } else { ?>
                                            RefreshAcctTransactionData();
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
            }, {
                    text: '<?php __('Save & Close'); ?>',
                    handler: function(btn){
                            AcctTransactionAddForm.getForm().submit({
                                    waitMsg: '<?php __('Submitting your data...'); ?>',
                                    waitTitle: '<?php __('Wait Please...'); ?>',
                                    success: function(f,a){
                                            Ext.Msg.show({
                                                    title: '<?php __('Success'); ?>',
                                                    buttons: Ext.MessageBox.OK,
                                                    msg: a.result.msg,
                    icon: Ext.MessageBox.INFO
                                            });
                                            AcctTransactionAddWindow.close();
<?php if(isset($parent_id)){ ?>
                                            RefreshParentAcctTransactionData();
<?php } else { ?>
                                            RefreshAcctTransactionData();
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
                            AcctTransactionAddWindow.close();
                    }
            }]
    });