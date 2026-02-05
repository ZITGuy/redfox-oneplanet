//<script>
    var store_tasks = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'controller', 'action', 'iconCls', {name: 'list_order', type: 'int'}, 'built_in', 'parent_task', 'lft', 'rght'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'tasks', 'action' => 'list_data')); ?>'
        }),
        sortInfo:{field: 'list_order', direction: "ASC"}
    });

    function AddTask(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'tasks', 'action' => 'add')); ?>/' + id,
            success: function (response, opts) {
                var task_data = response.responseText;

                eval(task_data);

                TaskAddWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the task add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function EditTask(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'tasks', 'action' => 'edit')); ?>/' + id,
            success: function (response, opts) {
                var task_data = response.responseText;

                eval(task_data);

                TaskEditWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the task edit form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function DeleteTask(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'tasks', 'action' => 'delete')); ?>/' + id,
            success: function (response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Task successfully deleted!'); ?>');
                RefreshTaskData();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the task add form. Error code'); ?>: ' + response.status);
            }
        });
    }


    function ViewChildPermissions(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'permissions', 'action' => 'index2')); ?>/' + id,
            success: function(response, opts) {
                var child_permissions_data = response.responseText;

                eval(child_permissions_data);

                childPermissionsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Permissions View form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function RefreshTaskData() {
        store_tasks.reload();

        var p = center_panel.findById('task-tab');
        p.getRootNode().reload();
    }

    var selected_item_id = 0;
    var selected_item_name = '';

    if (center_panel.find('id', 'task-tab') != "") {
        var p = center_panel.findById('task-tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add(
            new Ext.ux.tree.TreeGrid({
                title: '<?php __('Tasks'); ?>',
                closable: true,
                id: 'task-tab',
                iconCls: 'icon-task',
                useArrows:true,
                autoScroll:true,
                containerScroll: true,
                forceFit: true,
                columns: [
                    {header: 'Task', width: 350, dataIndex: 'name'},
                    {header: 'Controller', width: 200, dataIndex: 'controller'},
                    {header: 'Action', width: 200, dataIndex: 'action'},
                    {header: 'Icon', width: 200, dataIndex: 'iconCls'},
                    {header: 'Order', width: 60, dataIndex: 'list_order', sortable: true},
                    {header: 'Built-In', width: 60, dataIndex: 'built_in'}
                ],
                dataUrl: '<?php echo $this->Html->url(array('controller' => 'tasks', 'action' => 'list_data')); ?>',
                listeners: {
                    click: function (n) {
                        selected_item_id = n.attributes.id;
                        selected_item_name = n.attributes.name;
                        p.getTopToolbar().findById('add_task').enable();
                        p.getTopToolbar().findById('edit_task').enable();
                        p.getTopToolbar().findById('delete_task').enable();
                        
                        if (n.attributes.name == 'All') {
                            p.getTopToolbar().findById('edit_task').disable();
                            p.getTopToolbar().findById('delete_task').disable();
                        }

                        if(n.attributes.controller != '#') {
                            p.getTopToolbar().findById('maintain_permissions').enable();
                            p.getTopToolbar().findById('add_task').disable();
                        } else {
                            p.getTopToolbar().findById('maintain_permissions').disable();

                        }
                    }
                },
                tbar: new Ext.Toolbar({
                    items: [{
                            xtype: 'tbbutton',
                            text: '<?php __('Add'); ?>',
                            id: 'add_task',
                            tooltip: '<?php __('Add Child Task'); ?>',
                            icon: 'img/table_add.png',
                            cls: 'x-btn-text-icon',
                            disabled: true,
                            handler: function (btn) {
                                if (selected_item_id != 0) {
                                    AddTask(selected_item_id);
                                }
                            }
                        }, {
                            xtype: 'tbbutton',
                            text: '<?php __('Edit'); ?>',
                            id: 'edit_task',
                            tooltip: '<?php __('Edit Task'); ?>',
                            icon: 'img/table_edit.png',
                            cls: 'x-btn-text-icon',
                            disabled: true,
                            handler: function (btn) {
                                if (selected_item_id != 0) {
                                    EditTask(selected_item_id);
                                }
                            }
                        }, ' ', '-', ' ', {
                            xtype: 'tbbutton',
                            text: '<?php __('Delete'); ?>',
                            id: 'delete_task',
                            tooltip: '<?php __('Delete Task'); ?>',
                            icon: 'img/table_delete.png',
                            cls: 'x-btn-text-icon',
                            disabled: true,
                            handler: function (btn) {
                                if (selected_item_id != 0) {
                                    Ext.Msg.show({
                                        title: '<?php __('Remove Task'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove'); ?> ' + selected_item_name + ' <?php __('with all its child items'); ?>?',
                                        fn: function (btn) {
                                            if (btn == 'yes') {
                                                DeleteTask(selected_item_id);
                                            }
                                        }
                                    });
                                } else {
                                    Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                                }
                            }
                        }, ' ', '-', ' ', {
                            xtype: 'tbbutton',
                            text: '<?php __('Permissions'); ?>',
                            id: 'maintain_permissions',
                            tooltip: '<?php __('Maintain Task Permissions'); ?>',
                            icon: 'img/table_edit.png',
                            cls: 'x-btn-text-icon',
                            disabled: true,
                            handler: function (btn) {
                                if (selected_item_id != 0) {
                                    ViewChildPermissions(selected_item_id);
                                }
                            }
                        }
                    ]
                })
            })
        );
        center_panel.setActiveTab(p);
    }