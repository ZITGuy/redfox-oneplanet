//<script>
    var store_parent_eduCalendarEvents = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'edu_calendar_event_type', 'start_date', 'end_date', 'edu_quarter', 'edu_campus', 'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_calendar_events', 'action' => 'list_data', $parent_id)); ?>'})
    });

    function AddParentEduCalendarEvent() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_calendar_events', 'action' => 'add', $parent_id)); ?>',
            success: function (response, opts) {
                var parent_eduCalendarEvent_data = response.responseText;

                eval(parent_eduCalendarEvent_data);

                EduCalendarEventAddWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Calendar Event add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function EditParentEduCalendarEvent(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_calendar_events', 'action' => 'edit')); ?>/' + id + '/<?php echo $parent_id; ?>',
            success: function (response, opts) {
                var parent_eduCalendarEvent_data = response.responseText;

                eval(parent_eduCalendarEvent_data);

                EduCalendarEventEditWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Calendar Event edit form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function DeleteParentEduCalendarEvent(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_calendar_events', 'action' => 'delete')); ?>/' + id,
            success: function (response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Calendar Event(s) successfully deleted!'); ?>');
                RefreshParentEduCalendarEventData();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Calendar Event to be deleted. Error code'); ?>: ' + response.status);
            }
        });
    }

    function RefreshParentEduCalendarEventData() {
        store_parent_eduCalendarEvents.reload();
    }

    var g = new Ext.grid.GridPanel({
        title: '<?php __('Calendar Events'); ?>',
        store: store_parent_eduCalendarEvents,
        loadMask: true,
        stripeRows: true,
        height: 300,
        anchor: '100%',
        id: 'eduCalendarEventGrid',
        columns: [
            {header: "Name", dataIndex: 'name', sortable: true},
            {header: "Event Type", dataIndex: 'edu_calendar_event_type', sortable: true},
            {header: "Start Date", dataIndex: 'start_date', sortable: true},
            {header: "End Date", dataIndex: 'end_date', sortable: true},
            {header: "Campus", dataIndex: 'edu_campus', sortable: true},
            {header: "Created", dataIndex: 'created', sortable: true, hidden: true},
            {header: "Modified", dataIndex: 'modified', sortable: true, hidden: true}
        ],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        viewConfig: {
            forceFit: true
        },
        tbar: new Ext.Toolbar({
            items: [{
                    xtype: 'tbbutton',
                    text: '<?php __('Add'); ?>',
                    tooltip: '<?php __('<b>Add Calendar Event</b><br />Click here to create a new Calendar Event'); ?>',
                    icon: 'img/table_add.png',
                    cls: 'x-btn-text-icon',
                    disabled: <?php echo ($quarter['EduQuarter']['status_id'] == 8 || ($quarter['EduQuarter']['status_id'] == 1 && $quarter['EduQuarter']['start_date'] != $quarter['EduAcademicYear']['start_date']))? 'true': 'false'; ?>,
                    handler: function (btn) {
                        AddParentEduCalendarEvent();
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Edit'); ?>',
                    id: 'edit-parent-eduCalendarEvent',
                    tooltip: '<?php __('<b>Edit Calendar Event</b><br />Click here to modify the selected Calendar Event'); ?>',
                    icon: 'img/table_edit.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function (btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()) {
                            EditParentEduCalendarEvent(sel.data.id);
                        }
                        ;
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Delete'); ?>',
                    id: 'delete-parent-eduCalendarEvent',
                    tooltip: '<?php __('<b>Delete Calendar Event(s)</b><br />Click here to remove the selected Calendar Event(s)'); ?>',
                    icon: 'img/table_delete.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function (btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelections();
                        if (sm.hasSelection()) {
                            if (sel.length == 1) {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Calendar Event'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove'); ?> ' + sel[0].data.name + '?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function (btn) {
                                        if (btn == 'yes') {
                                            DeleteParentEduCalendarEvent(sel[0].data.id);
                                        }
                                    }
                                });
                            } else {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Calendar Event'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove the selected Calendar Event'); ?>?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function (btn) {
                                        if (btn == 'yes') {
                                            var sel_ids = '';
                                            for (i = 0; i < sel.length; i++) {
                                                if (i > 0)
                                                    sel_ids += '_';
                                                sel_ids += sel[i].data.id;
                                            }
                                            DeleteParentEduCalendarEvent(sel_ids);
                                        }
                                    }
                                });
                            }
                        } else {
                            Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                        }
                        ;
                    }
                }
            ]}),
        bbar: new Ext.PagingToolbar({
            pageSize: list_size,
            store: store_parent_eduCalendarEvents,
            displayInfo: true,
            displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
            beforePageText: '<?php __('Page'); ?>',
            afterPageText: '<?php __('of {0}'); ?>',
            emptyMsg: '<?php __('No data to display'); ?>'
        })
    });

    g.getSelectionModel().on('rowselect', function (sm, rowIdx, r) {
<?php if($quarter['EduQuarter']['status_id'] == 8 || ($quarter['EduQuarter']['status_id'] == 1 && $quarter['EduQuarter']['start_date'] != $quarter['EduAcademicYear']['start_date'])) { ?>
        g.getTopToolbar().findById('edit-parent-eduCalendarEvent').disable();
        g.getTopToolbar().findById('delete-parent-eduCalendarEvent').disable();
<?php } else { ?>
        g.getTopToolbar().findById('edit-parent-eduCalendarEvent').enable();
        g.getTopToolbar().findById('delete-parent-eduCalendarEvent').enable();
<?php } ?>
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('edit-parent-eduCalendarEvent').disable();
        }

    });

    g.getSelectionModel().on('rowdeselect', function (sm, rowIdx, r) {
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('edit-parent-eduCalendarEvent').disable();
            g.getTopToolbar().findById('delete-parent-eduCalendarEvent').enable();
        } else if (this.getSelections().length == 1) {
            g.getTopToolbar().findById('edit-parent-eduCalendarEvent').enable();
            g.getTopToolbar().findById('delete-parent-eduCalendarEvent').enable();
        } else {
            g.getTopToolbar().findById('edit-parent-eduCalendarEvent').disable();
            g.getTopToolbar().findById('delete-parent-eduCalendarEvent').disable();
        }
    });

    var parentEduCalendarEventsViewWindow = new Ext.Window({
        title: 'Calendar Events of the <i><?php echo $quarter['EduQuarter']['name']; ?></i> [<?php echo $quarter['EduAcademicYear']['name']; ?>]',
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
            handler: function (btn) {
                parentEduCalendarEventsViewWindow.close();
            }
        }]
    });

    store_parent_eduCalendarEvents.load({
        params: {
            start: 0,
            limit: list_size
        }
    });