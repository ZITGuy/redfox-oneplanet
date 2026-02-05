//<script>
    var store_acctAccounts = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'code', 'acct_category', 'balance','created_by'		
            ]
	}),
	proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'acct_accounts', 'action' => 'list_data', 'plugin' => 'acct')); ?>"
	}),	
        sortInfo:{field: 'name', direction: "ASC"}
    });

    function AddAcctAccount(id) {
	Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'acctAccounts', 'action' => 'add', 'plugin' => 'acct')); ?>/" + id,
            success: function(response, opts) {
                var acctAccount_data = response.responseText;

                eval(acctAccount_data);

                AcctAccountAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctAccount add form. Error code'); ?>: " + response.status);
            }
	});
    }

    function EditAcctAccount(id) {
	Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'acctAccounts', 'action' => 'edit', 'plugin' => 'acct')); ?>/"+id,
            success: function(response, opts) {
                var acctAccount_data = response.responseText;

                eval(acctAccount_data);

                AcctAccountEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctAccount edit form. Error code'); ?>: " + response.status);
            }
	});
    }

    function ViewAcctAccount(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'acctAccounts', 'action' => 'view', 'plugin' => 'acct')); ?>/"+id,
            success: function(response, opts) {
                var acctAccount_data = response.responseText;

                eval(acctAccount_data);

                AcctAccountViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctAccount view form. Error code'); ?>: " + response.status);
            }
        });
    }
    
    function ViewParentAcctJournals(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'acctJournals', 'action' => 'index2', 'plugin' => 'acct')); ?>/"+id,
            success: function(response, opts) {
                var parent_acctJournals_data = response.responseText;

                eval(parent_acctJournals_data);

                parentAcctJournalsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?><?php __('Cannot get the campus view form. Error code'); ?>: " + response.status);
            }
        });
    }

    function DeleteAcctAccount(id) {
	Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'acct Accounts', 'action' => 'delete', 'plugin' => 'acct')); ?>/"+id,
            success: function(response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Acct Account successfully deleted!'); ?>");
                RefreshAcctAccountData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Acct Account add form. Error code'); ?>: " + response.status);
            }
	});
    }

    function RefreshAcctAccountData() {
	store_acctAccounts.reload();
    }


    if(center_panel.find('id', 'acctAccount-tab') != "") {
	var p = center_panel.findById('acctAccount-tab');
	center_panel.setActiveTab(p);
    } else {
	var p = center_panel.add(
            new Ext.ux.tree.TreeGrid({
                title: '<?php __('Accounts'); ?>',
                closable: true,
                id: 'acctAccount-tab',
                forceFit:true,
                columns:[
                    {header: 'Accounts', width: 350, dataIndex: 'name'},
                    {header: 'Code', width: 100, dataIndex: 'code'},
                    {header: 'Category', width: 100, dataIndex: 'acct_category'},
                    {header: 'Balance', width: 100, dataIndex: 'balance'},
                    {header: 'Created By', width: 100, dataIndex: 'created_by'}
                ],
                dataUrl: '<?php echo $this->Html->url(array('controller' => 'acct_accounts', 'action' => 'list_data', 'plugin'=>'acct')); ?>',
                listeners: {
                    click: function(n) {
                        selected_item_id = n.attributes.id;
                        selected_item_name = n.attributes.name;
                        p.getTopToolbar().findById('add_acctAccount').enable();
                        p.getTopToolbar().findById('edit_acctAccount').enable();
                        p.getTopToolbar().findById('delete_acctAccount').enable();
                        if(n.attributes.name == 'Accounts'){
                            p.getTopToolbar().findById('edit_acctAccount').disable();
                            p.getTopToolbar().findById('delete_acctAccount').disable();
                        }
                    }
                },
                tbar: new Ext.Toolbar({
                    items:[
                        {
                            xtype: 'tbbutton',
                            text: '<?php __('Add'); ?>',
                            id: 'add_acctAccount',
                            tooltip:'<?php __('Add Child Account'); ?>',
                            icon: 'img/table_add.png',
                            cls: 'x-btn-text-icon',
                            disabled: true,
                            handler: function(btn) {
                                if (selected_item_id != 0){
                                    AddAcctAccount(selected_item_id);
                                }
                            }
                        },
                        {
                            xtype: 'tbbutton',
                            text: '<?php __('Edit'); ?>',
                            id: 'edit_acctAccount',
                            tooltip:'<?php __('Edit Account'); ?>',
                            icon: 'img/table_edit.png',
                            cls: 'x-btn-text-icon',
                            disabled: true,
                            handler: function(btn) {
                                if (selected_item_id != 0){
                                    EditAcctAccount(selected_item_id);
                                };
                            }
                        },' ', '-', ' ',
                        {
                            xtype: 'tbbutton',
                            text: '<?php __('Delete'); ?>',
                            id: 'delete_acctAccount',
                            tooltip:'<?php __('Delete Account'); ?>',
                            icon: 'img/table_delete.png',
                            cls: 'x-btn-text-icon',
                            disabled: true,
                            handler: function(btn) {
                                if (selected_item_id != 0){
                                    Ext.Msg.show({
                                        title: '<?php __('Remove Account'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove'); ?> '+selected_item_name+' <?php __('with all its child items'); ?>?',
                                        fn: function(btn){
                                            if (btn == 'yes'){
                                                DeleteAcctAccount(selected_item_id);
                                            }
                                        }
                                    });
                                } else {
                                    Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                                }
                            }
                        }
                    ]
                })
            })
        );
	center_panel.setActiveTab(p);
    }