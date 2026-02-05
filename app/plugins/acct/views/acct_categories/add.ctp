//<script>
    <?php
        $this->ExtForm->create('AcctCategory');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var AcctCategoryAddForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'acct_categories', 'action' => 'add', 'plugin'=>'acct')); ?>',
        defaultType: 'textfield',

        items: [
            <?php 
                $options0 = array();
                $this->ExtForm->input('name', $options0);
            ?>,
            <?php 
                $options1 = array();
                $this->ExtForm->input('normal_side', $options1);
            ?>,
            <?php 
                $options2 = array();
                $this->ExtForm->input('prefix', $options2);
            ?>,
            <?php 
                $options3 = array();
                $this->ExtForm->input('code', $options3);
            ?>,
            <?php 
                $options4 = array();
                $this->ExtForm->input('postfix', $options4);
            ?>,
            <?php 
                $options5 = array();
                $this->ExtForm->input('last_code', $options5);
            ?>,
            <?php 
                $options6 = array();
                $options6['hidden'] = $parent_id;
                $this->ExtForm->input('parent_id', $options6);
            ?>		
        ]
    });
		
    var AcctCategoryAddWindow = new Ext.Window({
        title: '<?php __('Add Account Category'); ?>',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: AcctCategoryAddForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                AcctCategoryAddForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to insert a new Acct Category.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(AcctCategoryAddWindow.collapsed)
                    AcctCategoryAddWindow.expand(true);
                else
                    AcctCategoryAddWindow.collapse(true);
            }
        }],
        buttons: [  {
            text: '<?php __('Save'); ?>',
            handler: function(btn){
                AcctCategoryAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        AcctCategoryAddForm.getForm().reset();
                        RefreshAcctCategoryData();
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
                AcctCategoryAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        AcctCategoryAddWindow.close();
                        RefreshAcctCategoryData();
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
                AcctCategoryAddWindow.close();
            }
        }]
    });