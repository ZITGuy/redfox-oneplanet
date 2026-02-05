//<script>
    var store_eduCalendarEventTypes = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'educational'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_calendar_event_types', 'action' => 'list_data')); ?>'
        })
    });

    function AddEduCalendarEventType() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_calendar_event_types', 'action' => 'add')); ?>',
            success: function(response, opts) {
                var eduCalendarEventType_data = response.responseText;

                eval(eduCalendarEventType_data);

                EduCalendarEventTypeAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Calendar Event Type add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function EditEduCalendarEventType(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_calendar_event_types', 'action' => 'edit')); ?>/' + id,
            success: function(response, opts) {
                var eduCalendarEventType_data = response.responseText;

                eval(eduCalendarEventType_data);

                EduCalendarEventTypeEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Calendar Event Type edit form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function DeleteEduCalendarEventType(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduCalendarEventTypes', 'action' => 'delete')); ?>/' + id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduCalendarEventType successfully deleted!'); ?>');
                RefreshEduCalendarEventTypeData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCalendarEventType add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function SearchEduCalendarEventType() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduCalendarEventTypes', 'action' => 'search')); ?>',
            success: function(response, opts) {
                var eduCalendarEventType_data = response.responseText;

                eval(eduCalendarEventType_data);

                eduCalendarEventTypeSearchWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCalendarEventType search form. Error Code'); ?>: ' + response.status);
            }
        });
    }

    function SearchByEduCalendarEventTypeName(value) {
        var conditions = '\'EduCalendarEventType.name LIKE\' => \'%' + value + '%\'';
        store_eduCalendarEventTypes.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshEduCalendarEventTypeData() {
        store_eduCalendarEventTypes.reload();
    }


    if (center_panel.find('id', 'eduCalendarEventType-tab') != "") {
        var p = center_panel.findById('eduCalendarEventType-tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Cal. Event Types'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduCalendarEventType-tab',
            xtype: 'grid',
            store: store_eduCalendarEventTypes,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Is Educational?'); ?>", dataIndex: 'educational', sortable: true}
            ],
            viewConfig: {
                forceFit: true
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: '<?php __('Add'); ?>',
                        tooltip: '<?php __('<b>Add Calendar Event Types</b><br />Click here to create a new Calendar Event Type'); ?>',
                        icon: 'img/table_add.png',
                        cls: 'x-btn-text-icon',
                        handler: function(btn) {
                            AddEduCalendarEventType();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('Edit'); ?>',
                        id: 'edit-eduCalendarEventType',
                        tooltip: '<?php __('<b>Edit Calendar Event Types</b><br />Click here to modify the selected Calendar Event Type'); ?>',
                        icon: 'img/table_edit.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                EditEduCalendarEventType(sel.data.id);
                            }
                            ;
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('Delete'); ?>',
                        id: 'delete-eduCalendarEventType',
                        tooltip: '<?php __('<b>Delete Calendar Event Types(s)</b><br />Click here to remove the selected Calendar Event Type(s)'); ?>',
                        icon: 'img/table_delete.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelections();
                            if (sm.hasSelection()) {
                                if (sel.length == 1) {
                                    Ext.Msg.show({
                                        title: '<?php __('Remove Calendar Event Type'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove'); ?> ' + sel[0].data.name + '?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn) {
                                            if (btn == 'yes') {
                                                DeleteEduCalendarEventType(sel[0].data.id);
                                            }
                                        }
                                    });
                                } else {
                                    Ext.Msg.show({
                                        title: '<?php __('Remove Calendar Event Type'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove the selected Calendar Event Types'); ?>?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn) {
                                            if (btn == 'yes') {
                                                var sel_ids = '';
                                                for (i = 0; i < sel.length; i++) {
                                                    if (i > 0)
                                                        sel_ids += '_';
                                                    sel_ids += sel[i].data.id;
                                                }
                                                DeleteEduCalendarEventType(sel_ids);
                                            }
                                        }
                                    });
                                }
                            } else {
                                Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                            }
                            ;
                        }
                    }, ' ', '-', '->', {
                        xtype: 'textfield',
                        emptyText: '<?php __('[Search By Name]'); ?>',
                        id: 'eduCalendarEventType_search_field',
                        listeners: {
                            specialkey: function(field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduCalendarEventTypeName(Ext.getCmp('eduCalendarEventType_search_field').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('GO'); ?>',
                        tooltip: '<?php __('<b>GO</b><br />Click here to get search results'); ?>',
                        id: 'eduCalendarEventType_go_button',
                        handler: function() {
                            SearchByEduCalendarEventTypeName(Ext.getCmp('eduCalendarEventType_search_field').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('Advanced Search'); ?>',
                        tooltip: '<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
                        handler: function() {
                            SearchEduCalendarEventType();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_eduCalendarEventTypes,
                displayInfo: true,
                displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
                beforePageText: '<?php __('Page'); ?>',
                afterPageText: '<?php __('of {0}'); ?>',
                emptyMsg: '<?php __('No data to display'); ?>'
            })
        });
        p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            p.getTopToolbar().findById('edit-eduCalendarEventType').enable();
            p.getTopToolbar().findById('delete-eduCalendarEventType').enable();
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit-eduCalendarEventType').disable();
            }
        });
        p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit-eduCalendarEventType').disable();
                p.getTopToolbar().findById('delete-eduCalendarEventType').enable();
            }
            else if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('edit-eduCalendarEventType').enable();
                p.getTopToolbar().findById('delete-eduCalendarEventType').enable();
            }
            else {
                p.getTopToolbar().findById('edit-eduCalendarEventType').disable();
                p.getTopToolbar().findById('delete-eduCalendarEventType').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_eduCalendarEventTypes.load({
            params: {
                start: 0,
                limit: list_size
            }
        });

    }
