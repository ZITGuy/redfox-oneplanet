//<script>
    var store_eduQuarters = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'short_name', 'start_date', 'end_date',
                'edu_academic_year', 'status', 'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'eduQuarters', 'action' => 'list_data')); ?>'
        })
        , sortInfo: {field: 'name', direction: "ASC"},
        groupField: 'start_date'
    });


    function AddEduQuarter() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduQuarters', 'action' => 'add')); ?>',
            success: function(response, opts) {
                var eduQuarter_data = response.responseText;

                eval(eduQuarter_data);

                EduQuarterAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduQuarter add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function EditEduQuarter(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduQuarters', 'action' => 'edit')); ?>/' + id,
            success: function(response, opts) {
                var eduQuarter_data = response.responseText;

                eval(eduQuarter_data);

                EduQuarterEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduQuarter edit form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function ViewEduQuarter(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduQuarters', 'action' => 'view')); ?>/' + id,
            success: function(response, opts) {
                var eduQuarter_data = response.responseText;

                eval(eduQuarter_data);

                EduQuarterViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduQuarter view form. Error code'); ?>: ' + response.status);
            }
        });
    }
    function ViewParentEduCalendarEvents(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduCalendarEvents', 'action' => 'index2')); ?>/' + id,
            success: function(response, opts) {
                var parent_eduCalendarEvents_data = response.responseText;

                eval(parent_eduCalendarEvents_data);

                parentEduCalendarEventsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
            }
        });
    }


    function DeleteEduQuarter(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduQuarters', 'action' => 'delete')); ?>/' + id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduQuarter successfully deleted!'); ?>');
                RefreshEduQuarterData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduQuarter add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function SearchEduQuarter() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduQuarters', 'action' => 'search')); ?>',
            success: function(response, opts) {
                var eduQuarter_data = response.responseText;

                eval(eduQuarter_data);

                eduQuarterSearchWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduQuarter search form. Error Code'); ?>: ' + response.status);
            }
        });
    }

    function SearchByEduQuarterName(value) {
        var conditions = '\'EduQuarter.name LIKE\' => \'%' + value + '%\'';
        store_eduQuarters.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshEduQuarterData() {
        store_eduQuarters.reload();
    }


    if (center_panel.find('id', 'eduQuarter-tab') != "") {
        var p = center_panel.findById('eduQuarter-tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Edu Quarters'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduQuarter-tab',
            xtype: 'grid',
            store: store_eduQuarters,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Short Name'); ?>", dataIndex: 'short_name', sortable: true},
                {header: "<?php __('Start Date'); ?>", dataIndex: 'start_date', sortable: true},
                {header: "<?php __('End Date'); ?>", dataIndex: 'end_date', sortable: true},
                {header: "<?php __('EduAcademicYear'); ?>", dataIndex: 'edu_academic_year', sortable: true},
                {header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
            ],
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduQuarters" : "EduQuarter"]})'
            })
            ,
            listeners: {
                celldblclick: function() {
                    ViewEduQuarter(Ext.getCmp('eduQuarter-tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: '<?php __('Add'); ?>',
                        tooltip: '<?php __('<b>Add EduQuarters</b><br />Click here to create a new EduQuarter'); ?>',
                        icon: 'img/table_add.png',
                        cls: 'x-btn-text-icon',
                        handler: function(btn) {
                            AddEduQuarter();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('Edit'); ?>',
                        id: 'edit-eduQuarter',
                        tooltip: '<?php __('<b>Edit EduQuarters</b><br />Click here to modify the selected EduQuarter'); ?>',
                        icon: 'img/table_edit.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                EditEduQuarter(sel.data.id);
                            }
                            ;
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('Delete'); ?>',
                        id: 'delete-eduQuarter',
                        tooltip: '<?php __('<b>Delete EduQuarters(s)</b><br />Click here to remove the selected EduQuarter(s)'); ?>',
                        icon: 'img/table_delete.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelections();
                            if (sm.hasSelection()) {
                                if (sel.length == 1) {
                                    Ext.Msg.show({
                                        title: '<?php __('Remove EduQuarter'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove'); ?> ' + sel[0].data.name + '?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn) {
                                            if (btn == 'yes') {
                                                DeleteEduQuarter(sel[0].data.id);
                                            }
                                        }
                                    });
                                } else {
                                    Ext.Msg.show({
                                        title: '<?php __('Remove EduQuarter'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove the selected EduQuarters'); ?>?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn) {
                                            if (btn == 'yes') {
                                                var sel_ids = '';
                                                for (i = 0; i < sel.length; i++) {
                                                    if (i > 0)
                                                        sel_ids += '_';
                                                    sel_ids += sel[i].data.id;
                                                }
                                                DeleteEduQuarter(sel_ids);
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
                        text: '<?php __('View EduQuarter'); ?>',
                        id: 'view-eduQuarter',
                        tooltip: '<?php __('<b>View EduQuarter</b><br />Click here to see details of the selected EduQuarter'); ?>',
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewEduQuarter(sel.data.id);
                            }
                            ;
                        },
                        menu: {
                            items: [
                                {
                                    text: '<?php __('View Edu Calendar Events'); ?>',
                                    icon: 'img/table_view.png',
                                    cls: 'x-btn-text-icon',
                                    handler: function(btn) {
                                        var sm = p.getSelectionModel();
                                        var sel = sm.getSelected();
                                        if (sm.hasSelection()) {
                                            ViewParentEduCalendarEvents(sel.data.id);
                                        }
                                        ;
                                    }
                                }
                            ]
                        }
                    }, ' ', '-', '<?php __('EduAcademicYear'); ?>: ', {
                        xtype: 'combo',
                        emptyText: 'All',
                        store: new Ext.data.ArrayStore({
                            fields: ['id', 'name'],
                            data: [
                                ['-1', 'All'],
    <?php $st = false;
    foreach ($eduacademicyears as $item) {
        if ($st) echo ",
							"; ?>['<?php echo $item['EduAcademicYear']['id']; ?>', '<?php echo $item['EduAcademicYear']['name']; ?>']<?php $st = true;
} ?>]
                        }),
                        displayField: 'name',
                        valueField: 'id',
                        mode: 'local',
                        value: '-1',
                        disableKeyFilter: true,
                        triggerAction: 'all',
                        listeners: {
                            select: function(combo, record, index) {
                                store_eduQuarters.reload({
                                    params: {
                                        start: 0,
                                        limit: list_size,
                                        eduacademicyear_id: combo.getValue()
                                    }
                                });
                            }
                        }
                    },
                    '->', {
                        xtype: 'textfield',
                        emptyText: '<?php __('[Search By Name]'); ?>',
                        id: 'eduQuarter_search_field',
                        listeners: {
                            specialkey: function(field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduQuarterName(Ext.getCmp('eduQuarter_search_field').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('GO'); ?>',
                        tooltip: '<?php __('<b>GO</b><br />Click here to get search results'); ?>',
                        id: 'eduQuarter_go_button',
                        handler: function() {
                            SearchByEduQuarterName(Ext.getCmp('eduQuarter_search_field').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('Advanced Search'); ?>',
                        tooltip: '<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
                        handler: function() {
                            SearchEduQuarter();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_eduQuarters,
                displayInfo: true,
                displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
                beforePageText: '<?php __('Page'); ?>',
                afterPageText: '<?php __('of {0}'); ?>',
                emptyMsg: '<?php __('No data to display'); ?>'
            })
        });
        p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            p.getTopToolbar().findById('edit-eduQuarter').enable();
            p.getTopToolbar().findById('delete-eduQuarter').enable();
            p.getTopToolbar().findById('view-eduQuarter').enable();
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit-eduQuarter').disable();
                p.getTopToolbar().findById('view-eduQuarter').disable();
            }
        });
        p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit-eduQuarter').disable();
                p.getTopToolbar().findById('view-eduQuarter').disable();
                p.getTopToolbar().findById('delete-eduQuarter').enable();
            }
            else if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('edit-eduQuarter').enable();
                p.getTopToolbar().findById('view-eduQuarter').enable();
                p.getTopToolbar().findById('delete-eduQuarter').enable();
            }
            else {
                p.getTopToolbar().findById('edit-eduQuarter').disable();
                p.getTopToolbar().findById('view-eduQuarter').disable();
                p.getTopToolbar().findById('delete-eduQuarter').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_eduQuarters.load({
            params: {
                start: 0,
                limit: list_size
            }
        });

    }
