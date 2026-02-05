//<script>
    var store_eduAcademicYears = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'start_date', 'end_date', 'status', 'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_academic_years', 'action' => 'list_data')); ?>"
        }),
        sortInfo: {field: 'name', direction: "ASC"},
        groupField: 'status',
        listeners: {
            load: function(st, records, options) {
                if(st.getCount() >= 2)
                    p.getTopToolbar().findById('btn_add_academic_year').disable();
                else 
                    p.getTopToolbar().findById('btn_add_academic_year').enable();
            }
        }
    });

    function AddEduAcademicYear() {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_academic_years', 'action' => 'add')); ?>",
            success: function(response, opts) {
                var eduAcademicYear_data = response.responseText;

                eval(eduAcademicYear_data);

                EduAcademicYearAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Academic Year add form. Error code'); ?>: " + response.status);
            }
        });
    }

    function EditEduAcademicYear(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_academic_years', 'action' => 'edit')); ?>/" + id,
            success: function(response, opts) {
                var eduAcademicYear_data = response.responseText;

                eval(eduAcademicYear_data);

                EduAcademicYearEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Academic Year Edit form. Error code'); ?>: " + response.status);
            }
        });
    }
    
    function CopyEduAcademicYear(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_academic_years', 'action' => 'copy')); ?>/" + id,
            success: function(response, opts) {
                var eduAcademicYear_data = response.responseText;

                eval(eduAcademicYear_data);

                EduAcademicYearCopyWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Academic Year Copy form. Error code'); ?>: " + response.status);
            }
        });
    }

    function ViewEduAcademicYear(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_academic_years', 'action' => 'view')); ?>/" + id,
            success: function(response, opts) {
                var eduAcademicYear_data = response.responseText;

                eval(eduAcademicYear_data);

                EduAcademicYearViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Academic Year view form. Error code'); ?>: " + response.status);
            }
        });
    }

    function ViewParentEduSections(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_sections', 'action' => 'index2')); ?>/" + id,
            success: function(response, opts) {
                var parent_eduSections_data = response.responseText;

                eval(parent_eduSections_data);

                parentEduSectionsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?><?php __('Cannot get the Sections view form. Error code'); ?>: " + response.status);
            }
        });
    }

    function ViewParentEduQuarters(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_quarters', 'action' => 'index2')); ?>/" + id,
            success: function(response, opts) {
                var parent_eduSections_data = response.responseText;

                eval(parent_eduSections_data);

                parentEduQuartersViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("Error", "Cannot get the <?php echo $term_name; ?> Management form. Error code': " + response.status);
            }
        });
    }

    function OpenEduAcademicYear(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_academic_years', 'action' => 'open_academic_year')); ?>/" + id,
            success: function(response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Academic Year successfully open!'); ?>");
                RefreshEduAcademicYearData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Academic Year opening form. Error code'); ?>: " + response.status);
            }
        });
    }
    
    function CloseEduAcademicYear(id) {
        Ext.Msg.alert("<?php __('Oooops!'); ?>", "<?php __('Academic Year closing is under construncion!'); ?>");
    }

    function SearchEduAcademicYear() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_academic_years', 'action' => 'search')); ?>',
            success: function(response, opts) {
                var eduAcademicYear_data = response.responseText;

                eval(eduAcademicYear_data);

                eduAcademicYearSearchWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Academic Year search form. Error Code'); ?>: " + response.status);
            }
        });
    }

    function SearchByEduAcademicYearName(value) {
        var conditions = '\'EduAcademicYear.name LIKE\' => \'%' + value + '%\'';
        store_eduAcademicYears.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshEduAcademicYearData() {
        store_eduAcademicYears.reload();
    }

    if (center_panel.find('id', 'eduAcademicYear-tab') != "") {
        var p = center_panel.findById('eduAcademicYear-tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Academic Years'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduAcademicYear-tab',
            xtype: 'grid',
            store: store_eduAcademicYears,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Start Date'); ?>", dataIndex: 'start_date', sortable: true},
                {header: "<?php __('End Date'); ?>", dataIndex: 'end_date', sortable: true},
                {header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true},
                {
                    header:'<?php __('Actions'); ?>',
                    xtype: 'actioncolumn',
                    width: 50,
                    items: [{
                        icon   : 'img/search.png',  // Use a URL in the icon config
                        tooltip: 'View AY',
                        handler: function(grid, rowIndex, colIndex) {
                            var rec = store_eduAcademicYears.getAt(rowIndex);
                            ViewEduAcademicYear(rec.get('id'));
                        }
                    }, ' ', ' ', ' ', ' ', ' ', '-', ' ', ' ', ' ', ' ', ' ', {
                        icon   : 'img/calendar_add.png',
                        tooltip: '<?php echo Inflector::pluralize($term_name); ?>',
                        handler: function(grid, rowIndex, colIndex) {
                            var rec = store_eduAcademicYears.getAt(rowIndex);
                            if(rec.get('status') == "<font color='gray'>Closed</font>") {
                                Ext.Msg.show({
                                    title: '<?php __('Oooops!'); ?>',
                                    buttons: Ext.MessageBox.OK,
                                    msg: '<?php __('Academic year is already closed!'); ?>',
                                    icon: Ext.MessageBox.INFO
                                });
                            } else {
                                ViewParentEduQuarters(rec.get('id'));
                            }
                        }
                    }, ' ', ' ', ' ', ' ', ' ', '-', ' ', ' ', ' ', ' ', ' ', {
                        icon   : 'img/at.png',
                        tooltip: 'Audit Trail',
                        handler: function(grid, rowIndex, colIndex) {
                            var rec = store_eduAcademicYears.getAt(rowIndex);
                            ViewAuditTrailDetail(rec.get('id'), 'EduAcademicYear');
                        }
                    }]
                }
            ],
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Academic Years" : "Academic Year"]})'
            }),
            listeners: {
                celldblclick: function() {
                    ViewEduAcademicYear(Ext.getCmp('eduAcademicYear-tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: true
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: "<?php __('Create'); ?>",
                        tooltip: "<?php __('<b>Create and Open Academic Year</b><br />Click here to create a new Academic Year'); ?>",
                        icon: 'img/table_add.png',
                        cls: 'x-btn-text-icon',
                        id: 'btn_add_academic_year',
                        handler: function(btn) {
                            AddEduAcademicYear();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Edit'); ?>",
                        id: 'edit-eduAcademicYear',
                        tooltip: "<?php __('<b>Edit Academic Year</b><br />Click here to modify the selected Academic Year'); ?>",
                        icon: 'img/table_edit.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                EditEduAcademicYear(sel.data.id);
                            }
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Copy'); ?>",
                        id: 'copy-eduAcademicYear',
                        tooltip: "<?php __('<b>Copy Academic Year</b><br />Click here to copy the selected Academic Year'); ?>",
                        icon: 'img/table_copy.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                CopyEduAcademicYear(sel.data.id);
                            }
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Datails of Academic Year'); ?>",
                        id: 'view-eduAcademicYear',
                        tooltip: "<?php __('<b>View Academic Year</b><br />Click here to see details of the selected Academic Year'); ?>",
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewEduAcademicYear(sel.data.id);
                            }
                            ;
                        }
                    },' ', '-', ' ' , {
                        text: 'Manage <?php echo $term_name; ?>',
                        icon: 'img/quarter.png',
                        id: 'btn_manage_quarters',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewParentEduQuarters(sel.data.id);
                            }
                        }
                    }, ' ', '-', '<?php __('Status'); ?>: ', {
                        xtype: 'combo',
                        emptyText: 'All',
                        store: new Ext.data.ArrayStore({
                            fields: ['id', 'name'],
                            data: [
                                ['-1', 'All'],
                                ['1', 'Active'],
                                ['2', 'Inactive'],
                                ['8', 'Closed']
                            ]
                        }),
                        displayField: 'name',
                        valueField: 'id',
                        mode: 'local',
                        value: 'AC',
                        disableKeyFilter: true,
                        triggerAction: 'all',
                        listeners: {
                            select: function(combo, record, index) {
                                store_eduAcademicYears.reload({
                                    params: {
                                        start: 0,
                                        limit: list_size,
                                        status_id: combo.getValue()
                                    }
                                });
                            }
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_eduAcademicYears,
                displayInfo: true,
                displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
                beforePageText: "<?php __('Page'); ?>",
                afterPageText: "<?php __('of {0}'); ?>",
                emptyMsg: "<?php __('No data to display'); ?>"
            })
        });
        p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            if(r.get('status') == "<font color='green'>Active</font>"){
                p.getTopToolbar().findById('edit-eduAcademicYear').enable();
            } else {
                p.getTopToolbar().findById('edit-eduAcademicYear').disable();
            }
            if(<?php echo $enable_copy; ?>){
                p.getTopToolbar().findById('copy-eduAcademicYear').enable();
            } else {
                p.getTopToolbar().findById('copy-eduAcademicYear').disable();
            }

            p.getTopToolbar().findById('view-eduAcademicYear').enable();
            p.getTopToolbar().findById('btn_manage_quarters').enable();
        });
        p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            p.getTopToolbar().findById('edit-eduAcademicYear').disable();
            p.getTopToolbar().findById('copy-eduAcademicYear').disable();
            p.getTopToolbar().findById('view-eduAcademicYear').disable();
            p.getTopToolbar().findById('btn_manage_quarters').disable();
        });
        center_panel.setActiveTab(p);

        store_eduAcademicYears.load({
            params: {
                start: 0,
                limit: list_size
            }
        });

    }