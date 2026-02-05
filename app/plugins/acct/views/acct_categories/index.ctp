//<script>
    var store_acctCategories = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','name','parent_acct_category','prefix',
                'code','postfix','last_code','lft','rght',
                'created','modified'		
            ]
	}),
	proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'acct_categories', 'action' => 'list_data', 'plugin' => 'acct')); ?>"
	})
    });

    function AddAcctCategory(id) {
	Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'acct_categories', 'action' => 'add', 'plugin' => 'acct')); ?>/" + id,
            success: function(response, opts) {
                var acctCategory_data = response.responseText;
			
                eval(acctCategory_data);
			
                AcctCategoryAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctCategory add form. Error code'); ?>: " + response.status);
            }
	});
    }

    function EditAcctCategory(id) {
	Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'acct_categories', 'action' => 'edit', 'plugin' => 'acct')); ?>/"+id,
            success: function(response, opts) {
                var acctCategory_data = response.responseText;
			
                eval(acctCategory_data);
			
                AcctCategoryEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Account Category edit form. Error code'); ?>: " + response.status);
            }
	});
    }

    function ViewAcctCategory(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'acct_categories', 'action' => 'view', 'plugin' => 'acct')); ?>/"+id,
            success: function(response, opts) {
                var acctCategory_data = response.responseText;

                eval(acctCategory_data);

                AcctCategoryViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Account Category view form. Error code'); ?>: " + response.status);
            }
        });
    }

    function DeleteAcctCategory(id) {
	Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'acct_categories', 'action' => 'delete', 'plugin' => 'acct')); ?>/"+id,
            success: function(response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('AcctCategory successfully deleted!'); ?>");
                RefreshAcctCategoryData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Account Category add form. Error code'); ?>: " + response.status);
            }
	});
    }

    function RefreshAcctCategoryData() {
	store_acctCategories.reload();
        
        var p = center_panel.findById('acctCategory-tab');
	p.getRootNode().reload();
    }

    var selected_item_name = '';

    if(center_panel.find('id', 'acctCategory-tab') != "") {
	var p = center_panel.findById('acctCategory-tab');
	center_panel.setActiveTab(p);
    } else {
	var p = center_panel.add(
            new Ext.ux.tree.TreeGrid({
                title: '<?php __('Acct Categories'); ?>',
                closable: true,
                id: 'acctCategory-tab',
                forceFit:true,
                columns:[
                    {header: 'Categories', width: 350, dataIndex: 'name'},
                    {header: 'Normal Side', width: 100, dataIndex: 'normal_side'},
                    {header: 'Prefix', width: 100, dataIndex: 'prefix'},
                    {header: 'Code', width: 100, dataIndex: 'code'},
                    {header: 'Postfix', width: 100, dataIndex: 'postfix'},
                    {header: 'Last Code', width: 100, dataIndex: 'last_code'}
                ],
                dataUrl: '<?php echo $this->Html->url(array('controller' => 'acct_categories', 'action' => 'list_data', 'plugin'=>'acct')); ?>',
                listeners: {
                    click: function(n) {
                        selected_item_id = n.attributes.id;
                        selected_item_name = n.attributes.name;
                        p.getTopToolbar().findById('add_acctCategory').enable();
                        p.getTopToolbar().findById('edit_acctCategory').enable();
                        p.getTopToolbar().findById('delete_acctCategory').enable();
                        if(n.attributes.name == 'Chart of Accounts'){
                            p.getTopToolbar().findById('edit_acctCategory').disable();
                            p.getTopToolbar().findById('delete_acctCategory').disable();
                        }
                    }
                },
                tbar: new Ext.Toolbar({
                    items:[
                        {
                            xtype: 'tbbutton',
                            text: '<?php __('Add'); ?>',
                            id: 'add_acctCategory',
                            tooltip:'<?php __('Add Child Account Category'); ?>',
                            icon: 'img/table_add.png',
                            cls: 'x-btn-text-icon',
                            disabled: true,
                            handler: function(btn) {
                                if (selected_item_id != 0){
                                    AddAcctCategory(selected_item_id);
                                }
                            }
                        },
                        {
                            xtype: 'tbbutton',
                            text: '<?php __('Edit'); ?>',
                            id: 'edit_acctCategory',
                            tooltip:'<?php __('Edit Account Category'); ?>',
                            icon: 'img/table_edit.png',
                            cls: 'x-btn-text-icon',
                            disabled: true,
                            handler: function(btn) {
                                if (selected_item_id != 0){
                                    EditAcctCategory(selected_item_id);
                                };
                            }
                        },' ', '-', ' ',
                        {
                            xtype: 'tbbutton',
                            text: '<?php __('Delete'); ?>',
                            id: 'delete_acctCategory',
                            tooltip:'<?php __('Delete Account Category'); ?>',
                            icon: 'img/table_delete.png',
                            cls: 'x-btn-text-icon',
                            disabled: true,
                            handler: function(btn) {
                                if (selected_item_id != 0){
                                    Ext.Msg.show({
                                        title: '<?php __('Remove Account Category'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove'); ?> '+selected_item_name+' <?php __('with all its child items'); ?>?',
                                        fn: function(btn){
                                            if (btn == 'yes'){
                                                DeleteAcctCategory(selected_item_id);
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