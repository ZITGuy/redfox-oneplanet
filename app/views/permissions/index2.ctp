//<script>
    var store_permissions = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'description', 'task'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'permissions', 'action' => 'list_data', $parent_id)); ?>'})
    });

    function AddPermission() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'permissions', 'action' => 'add', $parent_id)); ?>',
            success: function(response, opts) {
                var permissions_data = response.responseText;

                eval(permissions_data);

                PermissionAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Permission Add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function EditPermission(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'permissions', 'action' => 'edit')); ?>/' + id + '/<?php echo $parent_id; ?>',
            success: function(response, opts) {
                var permissions_data = response.responseText;

                eval(permissions_data);

                PermissionEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Permission Edit form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function DeletePermission(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'permissions', 'action' => 'delete')); ?>/' + id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Permission(s) successfully deleted!'); ?>');
                RefreshPermissionsData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot delete the Permission. Error code'); ?>: ' + response.status);
            }
        });
    }

    function SearchByPermissionName(value) {
        var conditions = '\'Permission.name LIKE\' => \'%' + value + '%\'';
        store_permissions.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshPermissionsData() {
        store_permissions.reload();
    }

    var g = new Ext.grid.GridPanel({
        title: '<?php __('Permissions Under the Task'); ?>',
        store: store_permissions,
        loadMask: true,
        height: 300,
        anchor: '100%',
        columns: [
            {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
            {header: "<?php __('Description'); ?>", dataIndex: 'description', sortable: true},
            {header: "<?php __('Task'); ?>", dataIndex: 'task'}
        ],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
        }),
        viewConfig: {
            forceFit: true
        },
        tbar: new Ext.Toolbar({
            items: [
                {
                    xtype: 'tbbutton',
                    text: '<?php __('Add'); ?>',
                    tooltip: '<?php __('Add Permission'); ?>',
                    icon: 'img/table_add.png',
                    cls: 'x-btn-text-icon',
                    handler: function(btn) {
                        AddPermission();
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Edit'); ?>',
                    id: 'edit_permission',
                    tooltip: '<?php __('Edit Permission'); ?>',
                    icon: 'img/table_edit.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()) {
                            EditPermission(sel.data.id);
                        }
                        ;
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Delete'); ?>',
                    id: 'delete_permission',
                    tooltip: '<?php __('Delete Permission'); ?>',
                    icon: 'img/table_delete.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelections();
                        if (sm.hasSelection()) {
                            if (sel.length == 1) {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Permission'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove'); ?> ' + sel[0].data.name + '?',
                                    fn: function(btn) {
                                        if (btn == 'yes') {
                                            DeletePermission(sel[0].data.id);
                                        }
                                    }
                                });
                            } else {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Permission'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove the selected Permission'); ?>?',
                                    fn: function(btn) {
                                        if (btn == 'yes') {
                                            var sel_ids = '';
                                            for (i = 0; i < sel.length; i++) {
                                                if (i > 0)
                                                    sel_ids += '_';
                                                sel_ids += sel[i].data.id;
                                            }
                                            DeletePermission(sel_ids);
                                        }
                                    }
                                });
                            }
                        } else {
                            Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                        }
                        ;
                    }
                }, ' ', '->',
                {
                    xtype: 'textfield',
                    emptyText: '<?php __('[Search By Name]'); ?>',
                    id: 'parent_link_search_field',
                    listeners: {
                        specialkey: function(field, e) {
                            if (e.getKey() == e.ENTER) {
                                SearchByPermissionName(Ext.getCmp('parent_link_search_field').getValue());
                            }
                        }

                    }
                },
                {
                    xtype: 'tbbutton',
                    icon: 'img/search.png',
                    cls: 'x-btn-text-icon',
                    text: 'GO',
                    id: 'parent_link_go_button',
                    handler: function() {
                        SearchByPermissionName(Ext.getCmp('parent_link_search_field').getValue());
                    }
                }, ' '
            ]}),
        bbar: new Ext.PagingToolbar({
            pageSize: list_size,
            store: store_permissions,
            displayInfo: true,
            displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
            beforePageText: '<?php __('Page'); ?>',
            afterPageText: '<?php __('of'); ?> {0}'
        })
    });
    g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
        g.getTopToolbar().findById('edit_permission').enable();
        g.getTopToolbar().findById('delete_permission').enable();
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('edit_permission').disable();
        }
    });
    g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('edit_permission').disable();
            g.getTopToolbar().findById('delete_permission').enable();
        }
        else if (this.getSelections().length == 1) {
            g.getTopToolbar().findById('edit_permission').enable();
            g.getTopToolbar().findById('delete_permission').enable();
        }
        else {
            g.getTopToolbar().findById('edit_permission').disable();
            g.getTopToolbar().findById('delete_permission').disable();
        }
    });



    var childPermissionsViewWindow = new Ext.Window({
        title: 'Permissions Under the selected Task',
        width: 700,
        height: 375,
        minWidth: 700,
        minHeight: 400,
        modal: true,
        resizable: false,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        items: [
            g
        ],
        buttons: [{
                text: 'Close',
                handler: function(btn) {
                    childPermissionsViewWindow.hide();
                }
            }]
    });

    store_permissions.load({
        params: {
            start: 0,
            limit: list_size
        }
    });