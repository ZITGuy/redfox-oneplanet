//<script>
    <?php
        $this->ExtForm->create('AcctCategory');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var AcctCategoryEditForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'acct_categories', 'action' => 'edit', 'plugin'=>'acct')); ?>',
        defaultType: 'textfield',

        items: [
            <?php $this->ExtForm->input('id', array('hidden' => $acct_category['AcctCategory']['id'])); ?>,
            <?php 
                $options0 = array();
                $options0['value'] = $acct_category['AcctCategory']['name'];
                $this->ExtForm->input('name', $options0);
            ?>,
            <?php 
                $options1 = array();
                $options1['value'] = $acct_category['AcctCategory']['normal_side'];
                $this->ExtForm->input('normal_side', $options1);
            ?>,
            <?php 
                $options2 = array();
                $options2['value'] = $acct_category['AcctCategory']['prefix'];
                $this->ExtForm->input('prefix', $options2);
            ?>,
            <?php 
                $options3 = array();
                $options3['value'] = $acct_category['AcctCategory']['code'];
                $this->ExtForm->input('code', $options3);
            ?>,
            <?php 
                $options4 = array();
                $options4['value'] = $acct_category['AcctCategory']['postfix'];
                $this->ExtForm->input('postfix', $options4);
            ?>,
            <?php 
                $options5 = array();
                $options5['value'] = $acct_category['AcctCategory']['last_code'];
                $this->ExtForm->input('last_code', $options5);
            ?>,
            <?php 
                $options6 = array();
                $options6['hidden'] = $acct_category['AcctCategory']['parent_id'];
                $this->ExtForm->input('parent_id', $options6);
            ?>
        ]
    });
		
    var AcctCategoryEditWindow = new Ext.Window({
        title: '<?php __('Edit Account Category'); ?>',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: AcctCategoryEditForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                AcctCategoryEditForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to modify an existing Acct Category.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(AcctCategoryEditWindow.collapsed)
                    AcctCategoryEditWindow.expand(true);
                else
                    AcctCategoryEditWindow.collapse(true);
            }
        }],
        buttons: [ {
            text: '<?php __('Save'); ?>',
            handler: function(btn){
                AcctCategoryEditForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        AcctCategoryEditWindow.close();
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
            text: '<?php __('Cancel'); ?>',
            handler: function(btn){
                AcctCategoryEditWindow.close();
            }
        }]
    });