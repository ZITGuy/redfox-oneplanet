//<script>
    var store_parent_eduEventTasks = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'edu_calendar_event_type', 'task', 'permissions', 'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'eduEventTasks', 'action' => 'list_data', $parent_id)); ?>'})
    });


    function AddParentEduEventTask() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduEventTasks', 'action' => 'add', $parent_id)); ?>',
            success: function(response, opts) {
                var parent_eduEventTask_data = response.responseText;

                eval(parent_eduEventTask_data);

                EduEventTaskAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEventTask add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function EditParentEduEventTask(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduEventTasks', 'action' => 'edit')); ?>/' + id + '/<?php echo $parent_id; ?>',
            success: function(response, opts) {
                var parent_eduEventTask_data = response.responseText;

                eval(parent_eduEventTask_data);

                EduEventTaskEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEventTask edit form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function ViewEduEventTask(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduEventTasks', 'action' => 'view')); ?>/' + id,
            success: function(response, opts) {
                var eduEventTask_data = response.responseText;

                eval(eduEventTask_data);

                EduEventTaskViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEventTask view form. Error code'); ?>: ' + response.status);
            }
        });
    }


    function DeleteParentEduEventTask(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduEventTasks', 'action' => 'delete')); ?>/' + id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduEventTask(s) successfully deleted!'); ?>');
                RefreshParentEduEventTaskData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEventTask to be deleted. Error code'); ?>: ' + response.status);
            }
        });
    }

    function SearchByParentEduEventTaskName(value) {
        var conditions = '\'EduEventTask.name LIKE\' => \'%' + value + '%\'';
        store_parent_eduEventTasks.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshParentEduEventTaskData() {
        store_parent_eduEventTasks.reload();
    }



    var g = new Ext.grid.GridPanel({
        title: '<?php __('Tasks Allowed'); ?>',
        store: store_parent_eduEventTasks,
        loadMask: true,
        stripeRows: true,
        height: 300,
        anchor: '100%',
        id: 'eduEventTaskGrid',
        columns: [
            {header: "<?php __('Event Type'); ?>", dataIndex: 'edu_calendar_event_type', sortable: true},
            {header: "<?php __('Task'); ?>", dataIndex: 'task', sortable: true},
            {header: "<?php __('Permissions'); ?>", dataIndex: 'permissions', sortable: true},
            {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
            {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
        ],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
        }),
        viewConfig: {
            forceFit: true
        },
        listeners: {
            celldblclick: function() {
                ViewEduEventTask(Ext.getCmp('eduEventTaskGrid').getSelectionModel().getSelected().data.id);
            }
        },
        tbar: new Ext.Toolbar({
            items: [{
                    xtype: 'tbbutton',
                    text: '<?php __('Add'); ?>',
                    tooltip: '<?php __('<b>Add Event Task</b><br />Click here to create a new Event Task'); ?>',
                    icon: 'img/table_add.png',
                    cls: 'x-btn-text-icon',
                    handler: function(btn) {
                        AddParentEduEventTask();
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Edit'); ?>',
                    id: 'edit-parent-eduEventTask',
                    tooltip: '<?php __('<b>Edit Event Task</b><br />Click here to modify the selected Event Task'); ?>',
                    icon: 'img/table_edit.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()) {
                            EditParentEduEventTask(sel.data.id);
                        }
                        ;
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Delete'); ?>',
                    id: 'delete-parent-eduEventTask',
                    tooltip: '<?php __('<b>Delete Event Task(s)</b><br />Click here to remove the selected Event Task(s)'); ?>',
                    icon: 'img/table_delete.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelections();
                        if (sm.hasSelection()) {
                            if (sel.length == 1) {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Event Task'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove'); ?> ' + sel[0].data.name + '?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn) {
                                        if (btn == 'yes') {
                                            DeleteParentEduEventTask(sel[0].data.id);
                                        }
                                    }
                                });
                            } else {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Event Task'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove the selected Event Task'); ?>?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn) {
                                        if (btn == 'yes') {
                                            var sel_ids = '';
                                            for (i = 0; i < sel.length; i++) {
                                                if (i > 0)
                                                    sel_ids += '_';
                                                sel_ids += sel[i].data.id;
                                            }
                                            DeleteParentEduEventTask(sel_ids);
                                        }
                                    }
                                });
                            }
                        } else {
                            Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                        }
                        ;
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbsplit',
                    text: '<?php __('View Event Task'); ?>',
                    id: 'view-eduEventTask2',
                    tooltip: '<?php __('<b>View Event Task</b><br />Click here to see details of the selected Event Task'); ?>',
                    icon: 'img/table_view.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()) {
                            ViewEduEventTask(sel.data.id);
                        }
                        ;
                    },
                    menu: {
                        items: [
                        ]
                    }

                }, ' ', '->', {
                    xtype: 'textfield',
                    emptyText: '<?php __('[Search By Name]'); ?>',
                    id: 'parent_eduEventTask_search_field',
                    listeners: {
                        specialkey: function(field, e) {
                            if (e.getKey() == e.ENTER) {
                                SearchByParentEduEventTaskName(Ext.getCmp('parent_eduEventTask_search_field').getValue());
                            }
                        }

                    }
                }, {
                    xtype: 'tbbutton',
                    icon: 'img/search.png',
                    cls: 'x-btn-text-icon',
                    text: 'GO',
                    tooltip: '<?php __('<b>GO</b><br />Click here to get search results'); ?>',
                    id: 'parent_eduEventTask_go_button',
                    handler: function() {
                        SearchByParentEduEventTaskName(Ext.getCmp('parent_eduEventTask_search_field').getValue());
                    }
                }, ' '
            ]}),
        bbar: new Ext.PagingToolbar({
            pageSize: list_size,
            store: store_parent_eduEventTasks,
            displayInfo: true,
            displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
            beforePageText: '<?php __('Page'); ?>',
            afterPageText: '<?php __('of {0}'); ?>',
            emptyMsg: '<?php __('No data to display'); ?>'
        })
    });
    g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
        g.getTopToolbar().findById('edit-parent-eduEventTask').enable();
        g.getTopToolbar().findById('delete-parent-eduEventTask').enable();
        g.getTopToolbar().findById('view-eduEventTask2').enable();
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('edit-parent-eduEventTask').disable();
            g.getTopToolbar().findById('view-eduEventTask2').disable();
        }
    });
    g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('edit-parent-eduEventTask').disable();
            g.getTopToolbar().findById('delete-parent-eduEventTask').enable();
            g.getTopToolbar().findById('view-eduEventTask2').disable();
        }
        else if (this.getSelections().length == 1) {
            g.getTopToolbar().findById('edit-parent-eduEventTask').enable();
            g.getTopToolbar().findById('delete-parent-eduEventTask').enable();
            g.getTopToolbar().findById('view-eduEventTask2').enable();
        }
        else {
            g.getTopToolbar().findById('edit-parent-eduEventTask').disable();
            g.getTopToolbar().findById('delete-parent-eduEventTask').disable();
            g.getTopToolbar().findById('view-eduEventTask2').disable();
        }
    });

    var parentEduEventTasksViewWindow = new Ext.Window({
        title: 'Tasks allowed during <i><?php echo $edu_calendar_event_type['EduCalendarEventType']['name']; ?>',
        width: 700,
        height: 375,
        minWidth: 700,
        minHeight: 400,
        resizable: false,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        modal: true,
        items: [
            g
        ],
        buttons: [{
                text: 'Close',
                handler: function(btn) {
                    parentEduEventTasksViewWindow.close();
                }
            }]
    });

    store_parent_eduEventTasks.load({
        params: {
            start: 0,
            limit: list_size
        }
    });