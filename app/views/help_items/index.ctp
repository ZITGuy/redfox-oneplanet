//<script>
    var store_help_items = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'title', 'content', 'list_order', 'parent_help_item', 'lft', 'rght'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'help_items', 'action' => 'list_data')); ?>'
        })
    });

    function AddHelpItem(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'help_items', 'action' => 'add')); ?>/' + id,
            success: function (response, opts) {
                var help_item_data = response.responseText;

                eval(help_item_data);

                HelpItemAddWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Help Item add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function EditHelpItem(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'help_items', 'action' => 'edit')); ?>/' + id,
            success: function (response, opts) {
                var help_item_data = response.responseText;

                eval(help_item_data);

                HelpItemEditWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Help Item edit form. Error code'); ?>: ' + response.status);
            }
        });
    }
	
	function RelatedHelpItems(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'help_items', 'action' => 'relateds')); ?>/' + id,
            success: function (response, opts) {
                var help_item_data = response.responseText;

                eval(help_item_data);

                RelatedHelpItemsWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Related Help Items edit form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function ViewHelpItem(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'help_items', 'action' => 'view')); ?>/' + id,
            success: function (response, opts) {
                var help_item_data = response.responseText;

                eval(help_item_data);

                HelpItemViewWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Help Item view form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function DeleteHelpItem(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'help_items', 'action' => 'delete')); ?>/' + id,
            success: function (response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Help Item successfully deleted!'); ?>');
                RefreshHelpItemData();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Help Item add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function RefreshHelpItemData() {
        store_help_items.reload();

        var p = center_panel.findById('help_item_tab');
        p.getRootNode().reload();
    }

    var selected_item_id = 0;
    var selected_item_name = '';

    if (center_panel.find('id', 'help_item_tab') != "") {
        var p = center_panel.findById('help_item_tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add(
            new Ext.ux.tree.TreeGrid({
                title: '<?php __('Help Items'); ?>',
                closable: true,
                id: 'help_item_tab',
                forceFit: true,
                columns: [
                    {header: "<?php __('Title'); ?>", width: 400, dataIndex: 'title'},
                    {header: "<?php __('List Order'); ?>", width: 100, dataIndex: 'list_order', sortable: true}
                ],
                dataUrl: '<?php echo $this->Html->url(array('controller' => 'help_items', 'action' => 'list_data')); ?>',
                listeners: {
                    click: function (n) {
                        selected_item_id = n.attributes.id;
                        selected_item_name = n.attributes.title;
                        p.getTopToolbar().findById('add_help_item').enable();
                        p.getTopToolbar().findById('edit_help_item').enable();
                        p.getTopToolbar().findById('related_help_item').enable();
                        p.getTopToolbar().findById('delete_help_item').enable();
                        if (n.attributes.title == 'RedFox Help') {
                            //p.getTopToolbar().findById('edit_help_item').disable();
                            p.getTopToolbar().findById('related_help_item').disable();
                            p.getTopToolbar().findById('delete_help_item').disable();
                        }
                    }
                },
                tbar: new Ext.Toolbar({
                    items: [{
                            xtype: 'tbbutton',
                            text: '<?php __('Add'); ?>',
                            id: 'add_help_item',
                            tooltip: '<?php __('Add Child Help Item'); ?>',
                            icon: 'img/table_add.png',
                            cls: 'x-btn-text-icon',
                            disabled: true,
                            handler: function (btn) {
                                if (selected_item_id != 0) {
                                    AddHelpItem(selected_item_id);
                                }
                            }
                        }, {
                            xtype: 'tbbutton',
                            text: '<?php __('Edit'); ?>',
                            id: 'edit_help_item',
                            tooltip: '<?php __('Edit Help Item'); ?>',
                            icon: 'img/table_edit.png',
                            cls: 'x-btn-text-icon',
                            disabled: true,
                            handler: function (btn) {
                                if (selected_item_id != 0) {
                                    EditHelpItem(selected_item_id);
                                }
                                ;
                            }
                        }, ' ', '-', ' ', {
                            xtype: 'tbbutton',
                            text: '<?php __('Delete'); ?>',
                            id: 'delete_help_item',
                            tooltip: '<?php __('Delete Help Item'); ?>',
                            icon: 'img/table_delete.png',
                            cls: 'x-btn-text-icon',
                            disabled: true,
                            handler: function (btn) {
                                if (selected_item_id != 0) {
                                    Ext.Msg.show({
                                        title: '<?php __('Remove Help Item'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove'); ?> ' + selected_item_name + ' <?php __('with all its child items'); ?>?',
                                        fn: function (btn) {
                                            if (btn == 'yes') {
                                                DeleteHelpItem(selected_item_id);
                                            }
                                        }
                                    });
                                } else {
                                    Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                                }
                            }
                        }, {
                            xtype: 'tbbutton',
                            text: '<?php __('Related Help Items'); ?>',
                            id: 'related_help_item',
                            tooltip: '<?php __('Related Help Items'); ?>',
                            icon: 'img/table_edit.png',
                            cls: 'x-btn-text-icon',
                            disabled: true,
                            handler: function (btn) {
                                if (selected_item_id != 0) {
                                    RelatedHelpItems(selected_item_id);
                                }
                            }
                        }
                    ]
                })
            })
        );
        center_panel.setActiveTab(p);
    }
