//<script>
    var store_eduSections = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'edu_class', 'edu_academic_year', 'created', 'modified']
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'eduSections', 'action' => 'list_data')); ?>"
        })
        , sortInfo: {field: 'name', direction: "ASC"},
        groupField: 'edu_class'
    });

    function AddEduSection() {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'eduSections', 'action' => 'add')); ?>",
            success: function(response, opts) {
                var eduSection_data = response.responseText;
                eval(eduSection_data);
                EduSectionAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the eduSection add form. Error code'); ?>: " + response.status);
            }
        });
    }

    function EditEduSection(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'eduSections', 'action' => 'edit')); ?>/" + id,
            success: function(response, opts) {
                var eduSection_data = response.responseText;
                eval(eduSection_data);
                EduSectionEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduSection edit form. Error code'); ?>: " + response.status);
            }
        });
    }

    function ViewEduSection(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'eduSections', 'action' => 'view')); ?>/" + id,
            success: function(response, opts) {
                var eduSection_data = response.responseText;
                eval(eduSection_data);
                EduSectionViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduSection view form. Error code'); ?>: " + response.status);
            }
        });
    }
    function ViewParentEduAssessments(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'eduAssessments', 'action' => 'index2')); ?>/" + id,
            success: function(response, opts) {
                var parent_eduAssessments_data = response.responseText;
                eval(parent_eduAssessments_data);
                parentEduAssessmentsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?><?php __('Cannot get the campus view form. Error code'); ?>: " + response.status);
            }
        });
    }

    function ViewParentEduAssignments(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'eduAssignments', 'action' => 'index2')); ?>/" + id,
            success: function(response, opts) {
                var parent_eduAssignments_data = response.responseText;
                eval(parent_eduAssignments_data);
                parentEduAssignmentsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?><?php __('Cannot get the campus view form. Error code'); ?>: " + response.status);
            }
        });
    }

    function ViewParentEduRegistrations(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'eduRegistrations', 'action' => 'index2')); ?>/" + id,
            success: function(response, opts) {
                var parent_eduRegistrations_data = response.responseText;
                eval(parent_eduRegistrations_data);
                parentEduRegistrationsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?><?php __('Cannot get the campus view form. Error code'); ?>: " + response.status);
            }
        });
    }


    function DeleteEduSection(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'eduSections', 'action' => 'delete')); ?>/" + id,
            success: function(response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('EduSection successfully deleted!'); ?>");
                RefreshEduSectionData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduSection add form. Error code'); ?>: " + response.status);
            }
        });
    }

    function SearchEduSection() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduSections', 'action' => 'search')); ?>',
            success: function(response, opts) {
                var eduSection_data = response.responseText;
                eval(eduSection_data);
                eduSectionSearchWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduSection search form. Error Code'); ?>: " + response.status);
            }
        });
    }

    function SearchByEduSectionName(value) {
        var conditions = '\'EduSection.name LIKE\' => \'%' + value + '%\'';
        store_eduSections.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshEduSectionData() {
        store_eduSections.reload();
    }


    if (center_panel.find('id', 'eduSection-tab') != "") {
        var p = center_panel.findById('eduSection-tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Edu Sections'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduSection-tab',
            xtype: 'grid',
            store: store_eduSections,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('EduClass'); ?>", dataIndex: 'edu_class', sortable: true},
                {header: "<?php __('EduAcademicYear'); ?>", dataIndex: 'edu_academic_year', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
            ],
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduSections" : "EduSection"]})'
            })
            ,
            listeners: {
                celldblclick: function() {
                    ViewEduSection(Ext.getCmp('eduSection-tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: "<?php __('Add'); ?>",
                        tooltip: "<?php __('<b>Add EduSections</b><br />Click here to create a new EduSection'); ?>",
                        icon: 'img/table_add.png',
                        cls: 'x-btn-text-icon',
                        handler: function(btn) {
                            AddEduSection();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Edit'); ?>",
                        id: 'edit-eduSection',
                        tooltip: "<?php __('<b>Edit EduSections</b><br />Click here to modify the selected EduSection'); ?>",
                        icon: 'img/table_edit.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                EditEduSection(sel.data.id);
                            }
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Delete'); ?>",
                        id: 'delete-eduSection',
                        tooltip: "<?php __('<b>Delete Sections(s)</b><br />Click here to remove the selected Section(s)'); ?>",
                        icon: 'img/table_delete.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelections();
                            if (sm.hasSelection()) {
                                if (sel.length == 1) {
                                    Ext.Msg.show({
                                        title: "<?php __('Remove Section'); ?>",
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: "<?php __('Remove'); ?> " + sel[0].data.name + '?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn) {
                                            if (btn == 'yes') {
                                                DeleteEduSection(sel[0].data.id);
                                            }
                                        }
                                    });
                                } else {
                                    Ext.Msg.show({
                                        title: "<?php __('Remove Section'); ?>",
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: "<?php __('Remove the selected Sections'); ?>?",
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn) {
                                            if (btn == 'yes') {
                                                var sel_ids = '';
                                                for (i = 0; i < sel.length; i++) {
                                                    if (i > 0)
                                                        sel_ids += '_';
                                                    sel_ids += sel[i].data.id;
                                                }
                                                DeleteEduSection(sel_ids);
                                            }
                                        }
                                    });
                                }
                            } else {
                                Ext.Msg.alert("<?php __('Warning'); ?>", "<?php __('Please select a record first'); ?>");
                            }
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbsplit',
                        text: "<?php __('View Section'); ?>",
                        id: 'view-eduSection',
                        tooltip: "<?php __('<b>View Section</b><br />Click here to see details of the selected Section'); ?>",
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewEduSection(sel.data.id);
                            }
                        },
                        menu: {
                            items: [
                                {
                                    text: '<?php __('View Edu Assessments'); ?>',
                                    icon: 'img/table_view.png',
                                    cls: 'x-btn-text-icon',
                                    handler: function(btn) {
                                        var sm = p.getSelectionModel();
                                        var sel = sm.getSelected();
                                        if (sm.hasSelection()) {
                                            ViewParentEduAssessments(sel.data.id);
                                        }
                                        ;
                                    }
                                }, {
                                    text: '<?php __('View Edu Assignments'); ?>',
                                    icon: 'img/table_view.png',
                                    cls: 'x-btn-text-icon',
                                    handler: function(btn) {
                                        var sm = p.getSelectionModel();
                                        var sel = sm.getSelected();
                                        if (sm.hasSelection()) {
                                            ViewParentEduAssignments(sel.data.id);
                                        }
                                    }
                                }, {
                                    text: '<?php __('View Edu Registrations'); ?>',
                                    icon: 'img/table_view.png',
                                    cls: 'x-btn-text-icon',
                                    handler: function(btn) {
                                        var sm = p.getSelectionModel();
                                        var sel = sm.getSelected();
                                        if (sm.hasSelection()) {
                                            ViewParentEduRegistrations(sel.data.id);
                                        }
                                        ;
                                    }
                                }
                            ]
                        }
                    }, ' ', '-', "<?php __('Class'); ?>: ", {
                        xtype: 'combo',
                        emptyText: 'All',
                        store: new Ext.data.ArrayStore({
                            fields: ['id', 'name'],
                            data: [
                                ['-1', 'All'],
    <?php $st = false;
    foreach ($edu_classes as $item) {
        if ($st) echo ",
							"; ?>['<?php echo $item['EduClass']['id']; ?>', '<?php echo $item['EduClass']['name']; ?>']<?php $st = true;
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
                                store_eduSections.reload({
                                    params: {
                                        start: 0,
                                        limit: list_size,
                                        educlass_id: combo.getValue()
                                    }
                                });
                            }
                        }
                    },
                    '->', {
                        xtype: 'textfield',
                        emptyText: "<?php __('[Search By Name]'); ?>",
                        id: 'eduSection_search_field',
                        listeners: {
                            specialkey: function(field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduSectionName(Ext.getCmp('eduSection_search_field').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('GO'); ?>",
                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
                        id: "eduSection_go_button",
                        handler: function() {
                            SearchByEduSectionName(Ext.getCmp('eduSection_search_field').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('Advanced Search'); ?>",
                        tooltip: "<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
                        handler: function() {
                            SearchEduSection();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_eduSections,
                displayInfo: true,
                displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
                beforePageText: "<?php __('Page'); ?>",
                afterPageText: "<?php __('of {0}'); ?>",
                emptyMsg: "<?php __('No data to display'); ?>"
            })
        });
        p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            p.getTopToolbar().findById('edit-eduSection').enable();
            p.getTopToolbar().findById('delete-eduSection').enable();
            p.getTopToolbar().findById('view-eduSection').enable();
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit-eduSection').disable();
                p.getTopToolbar().findById('view-eduSection').disable();
            }
        });
        p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit-eduSection').disable();
                p.getTopToolbar().findById('view-eduSection').disable();
                p.getTopToolbar().findById('delete-eduSection').enable();
            }
            else if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('edit-eduSection').enable();
                p.getTopToolbar().findById('view-eduSection').enable();
                p.getTopToolbar().findById('delete-eduSection').enable();
            }
            else {
                p.getTopToolbar().findById('edit-eduSection').disable();
                p.getTopToolbar().findById('view-eduSection').disable();
                p.getTopToolbar().findById('delete-eduSection').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_eduSections.load({
            params: {
                start: 0,
                limit: list_size
            }
        });

    }