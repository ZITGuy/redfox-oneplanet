//<script>
    var store_eduOutlines = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'edu_course', 'list_order', 'created', 'modified']
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_outlines', 'action' => 'list_data')); ?>"
        }), 
        sortInfo: {field: 'name', direction: "ASC"},
        groupField: 'edu_course'
    });

    function AddEduOutline() {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_outlines', 'action' => 'add')); ?>",
            success: function (response, opts) {
                var eduOutline_data = response.responseText;

                eval(eduOutline_data);

                EduOutlineAddWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduOutline add form. Error code'); ?>: " + response.status);
            }
        });
    }

    function EditEduOutline(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_outlines', 'action' => 'edit')); ?>/" + id,
            success: function (response, opts) {
                var eduOutline_data = response.responseText;

                eval(eduOutline_data);

                EduOutlineEditWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduOutline edit form. Error code'); ?>: " + response.status);
            }
        });
    }

    function ViewEduOutline(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_outlines', 'action' => 'view')); ?>/" + id,
            success: function (response, opts) {
                var eduOutline_data = response.responseText;

                eval(eduOutline_data);

                EduOutlineViewWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Outline view form. Error code'); ?>: " + response.status);
            }
        });
    }

    function DeleteEduOutline(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_outlines', 'action' => 'delete')); ?>/" + id,
            success: function (response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Outline successfully deleted!'); ?>");
                RefreshEduOutlineData();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Outline add form. Error code'); ?>: " + response.status);
            }
        });
    }

    function SearchEduOutline() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_outlines', 'action' => 'search')); ?>',
            success: function (response, opts) {
                var eduOutline_data = response.responseText;

                eval(eduOutline_data);

                eduOutlineSearchWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Outline search form. Error Code'); ?>: " + response.status);
            }
        });
    }

    function SearchByEduOutlineName(value) {
        var conditions = '\'EduOutline.name LIKE\' => \'%' + value + '%\'';
        store_eduOutlines.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshEduOutlineData() {
        store_eduOutlines.reload();
    }


    if (center_panel.find('id', 'eduOutline-tab-m') != "") {
        var p = center_panel.findById('eduOutline-tab-m');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Course Outlines'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduOutline-tab-m',
            xtype: 'grid',
            store: store_eduOutlines,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Course'); ?>", dataIndex: 'edu_course', sortable: true},
                {header: "<?php __('List Order'); ?>", dataIndex: 'list_order', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
            ],
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Outlines" : "Outline"]})'
            }),
            listeners: {
                celldblclick: function () {
                    ViewEduOutline(Ext.getCmp('eduOutline-tab-m').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: "<?php __('Add'); ?>",
                        tooltip: "<?php __('<b>Add Outlines</b><br />Click here to create a new Outline'); ?>",
                        icon: 'img/table_add.png',
                        cls: 'x-btn-text-icon',
                        handler: function (btn) {
                            AddEduOutline();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Edit'); ?>",
                        id: 'edit-eduOutline-m',
                        tooltip: "<?php __('<b>Edit Outlines</b><br />Click here to modify the selected Outline'); ?>",
                        icon: 'img/table_edit.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                EditEduOutline(sel.data.id);
                            }
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Delete'); ?>",
                        id: 'delete-eduOutline-m',
                        tooltip: "<?php __('<b>Delete Outlines(s)</b><br />Click here to remove the selected Outline(s)'); ?>",
                        icon: 'img/table_delete.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelections();
                            if (sm.hasSelection()) {
                                if (sel.length == 1) {
                                    Ext.Msg.show({
                                        title: "<?php __('Remove Outline'); ?>",
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: "<?php __('Remove'); ?> " + sel[0].data.name + '?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function (btn) {
                                            if (btn == 'yes') {
                                                DeleteEduOutline(sel[0].data.id);
                                            }
                                        }
                                    });
                                } else {
                                    Ext.Msg.show({
                                        title: "<?php __('Remove Outline'); ?>",
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: "<?php __('Remove the selected Outlines'); ?>?",
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function (btn) {
                                            if (btn == 'yes') {
                                                var sel_ids = '';
                                                for (i = 0; i < sel.length; i++) {
                                                    if (i > 0)
                                                        sel_ids += '_';
                                                    sel_ids += sel[i].data.id;
                                                }
                                                DeleteEduOutline(sel_ids);
                                            }
                                        }
                                    });
                                }
                            } else {
                                Ext.Msg.alert("<?php __('Warning'); ?>", "<?php __('Please select a record first'); ?>");
                            }
                            ;
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('View Outline'); ?>",
                        id: 'view-eduOutline-m',
                        tooltip: "<?php __('<b>View Outline</b><br />Click here to see details of the selected Outline'); ?>",
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewEduOutline(sel.data.id);
                            }
                        }
                    }, ' ', '-', "<?php __('Filter by Course'); ?>: ", {
                        xtype: 'combo',
                        emptyText: 'All',
                        store: new Ext.data.ArrayStore({
                            fields: ['id', 'name'],
                            data: [
                                ['-1', 'All'],
    <?php $st = false;
    foreach ($edu_courses as $item) {
        if ($st) echo ",
							"; ?>['<?php echo $item['EduCourse']['id']; ?>', '<?php echo $item['EduCourse']['description']; ?>']<?php $st = true;
} ?>]
                        }),
                        displayField: 'name',
                        valueField: 'id',
                        mode: 'local',
                        value: '-1',
                        disableKeyFilter: true,
                        triggerAction: 'all',
                        listeners: {
                            select: function (combo, record, index) {
                                store_eduOutlines.reload({
                                    params: {
                                        start: 0,
                                        limit: list_size,
                                        edu_course_id: combo.getValue()
                                    }
                                });
                            }
                        }
                    }, '->', {
                        xtype: 'textfield',
                        emptyText: "<?php __('[Search By Name]'); ?>",
                        id: 'eduOutline_search_field-m',
                        listeners: {
                            specialkey: function (field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduOutlineName(Ext.getCmp('eduOutline_search_field-m').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('GO'); ?>",
                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
                        id: "eduOutline_go_button-m",
                        handler: function () {
                            SearchByEduOutlineName(Ext.getCmp('eduOutline_search_field-m').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('Advanced Search'); ?>",
                        tooltip: "<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
                        handler: function () {
                            SearchEduOutline();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_eduOutlines,
                displayInfo: true,
                displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
                beforePageText: "<?php __('Page'); ?>",
                afterPageText: "<?php __('of {0}'); ?>",
                emptyMsg: "<?php __('No data to display'); ?>"
            })
        });
        p.getSelectionModel().on('rowselect', function (sm, rowIdx, r) {
            p.getTopToolbar().findById('edit-eduOutline-m').enable();
            p.getTopToolbar().findById('delete-eduOutline-m').enable();
            p.getTopToolbar().findById('view-eduOutline-m').enable();
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit-eduOutline-m').disable();
                p.getTopToolbar().findById('view-eduOutline-m').disable();
            }
        });
        p.getSelectionModel().on('rowdeselect', function (sm, rowIdx, r) {
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit-eduOutline-m').disable();
                p.getTopToolbar().findById('view-eduOutline-m').disable();
                p.getTopToolbar().findById('delete-eduOutline-m').enable();
            } else if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('edit-eduOutline-m').enable();
                p.getTopToolbar().findById('view-eduOutline-m').enable();
                p.getTopToolbar().findById('delete-eduOutline-m').enable();
            } else {
                p.getTopToolbar().findById('edit-eduOutline-m').disable();
                p.getTopToolbar().findById('view-eduOutline-m').disable();
                p.getTopToolbar().findById('delete-eduOutline-m').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_eduOutlines.load({
            params: {
                start: 0,
                limit: list_size
            }
        });
    }