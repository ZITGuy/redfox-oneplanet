//<script>
    var store_eduSubjects = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'description', 'courses',
                'min_for_pass', 'is_mandatory', 'color',
                'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_subjects', 'action' => 'list_data')); ?>"
        }),
        sortInfo: {field: 'name', direction: "ASC"},
        groupField: 'description'
    });
    
    function ViewEduSubject(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_subjects', 'action' => 'view')); ?>/" + id,
            success: function(response, opts) {
                var eduSubject_data = response.responseText;

                eval(eduSubject_data);

                EduSubjectViewWindow.show();
            },
            failure: function(response, opts) {
                var obj = Ext.decode(Ext.decode(JSON.stringify(response)).responseText);
                ShowErrorBox(obj.errormsg, obj.helpcode);
            }
        });
    }

    function SearchEduSubject() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_subjects', 'action' => 'search')); ?>',
            success: function(response, opts) {
                var eduSubject_data = response.responseText;

                eval(eduSubject_data);

                eduSubjectSearchWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Subject search form. Error Code'); ?>: " + response.status);
            }
        });
    }

    function SearchByEduSubjectName(value) {
        var conditions = '\'EduSubject.name LIKE\' => \'%' + value + '%\'';
        store_eduSubjects.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }
    
    function ViewAuditTrailForSubject(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'audit_trails', 'action' => 'index2', 'plugin' => '')); ?>/'+id+'/EduSubject',
            success: function(response, opts) {
                var audit_trail_data = response.responseText;

                eval(audit_trail_data);

                parentAuditTrailsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Audit Trail view form. Error code'); ?>: ' + response.status);
            }
        });
    }

    if (center_panel.find('id', 'eduSubject-tab-v') != "") {
        var p = center_panel.findById('eduSubject-tab-v');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Subjects'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduSubject-tab-v',
            iconCls: 'icon-subject',
            xtype: 'grid',
            store: store_eduSubjects,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Description'); ?>", dataIndex: 'description', sortable: true},
                {header: "<?php __('Min. Mark to Pass'); ?>", dataIndex: 'min_for_pass', sortable: true},
                {header: "<?php __('Is Mandatory?'); ?>", dataIndex: 'is_mandatory', sortable: true},
                {header: "<?php __('Theme'); ?>", dataIndex: 'color', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true},
                {
                    header:'<?php __('Actions'); ?>',
                    xtype: 'actioncolumn',
                    width: 50,
                    items: [{
                        icon   : 'img/table_edit.png',  // Use a URL in the icon config
                        tooltip: 'Edit Subject',
                        handler: function(grid, rowIndex, colIndex) {
                            var rec = store_eduSubjects.getAt(rowIndex);
                            EditEduSubject(rec.get('id'));
                        }
                    }, ' ', ' ', ' ', ' ', '-', ' ', ' ', ' ', ' ', {
                        icon   : 'img/search.png',  // Use a URL in the icon config
                        tooltip: 'View',
                        handler: function(grid, rowIndex, colIndex) {
                            var rec = store_eduSubjects.getAt(rowIndex);
                            ViewEduSubject(rec.get('id'));
                        }
                    }, ' ', ' ', ' ', ' ', '|', ' ', ' ', ' ', ' ', {
                        icon   : 'img/calendar_add.png',
                        tooltip: 'Manage Subject Courses',
                        handler: function(grid, rowIndex, colIndex) {
                            var rec = store_eduSubjects.getAt(rowIndex);
                            ViewParentEduCourses(rec.get('id'));
                        }
                    }, ' ', ' ', ' ', ' ', '|', ' ', ' ', ' ', ' ', {
                        icon   : 'img/at.png',
                        tooltip: 'Audit Trail',
                        handler: function(grid, rowIndex, colIndex) {
                            var rec = store_eduSubjects.getAt(rowIndex);
                            ViewAuditTrailForSubject(rec.get('id'));
                        }
                    }]
                }
            ],
            viewConfig: {
                forceFit: true
            },
            listeners: {
                celldblclick: function() {
                    ViewEduSubject(Ext.getCmp('eduSubject-tab-v').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: true
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: "<?php __('View Subject'); ?>",
                        id: 'view-eduSubject',
                        tooltip: "<?php __('<b>View Subject</b><br />Click here to see details of the selected Subject'); ?>",
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewEduSubject(sel.data.id);
                            }
                        }
                    }, ' ', '-', '->', {
                        xtype: 'textfield',
                        emptyText: "<?php __('[Search By Name]'); ?>",
                        id: 'eduSubject_search_field',
                        listeners: {
                            specialkey: function(field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduSubjectName(Ext.getCmp('eduSubject_search_field').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('GO'); ?>",
                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
                        id: "eduSubject_go_button",
                        handler: function() {
                            SearchByEduSubjectName(Ext.getCmp('eduSubject_search_field').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('Advanced Search'); ?>",
                        tooltip: "<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
                        handler: function() {
                            SearchEduSubject();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_eduSubjects,
                displayInfo: true,
                displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
                beforePageText: "<?php __('Page'); ?>",
                afterPageText: "<?php __('of {0}'); ?>",
                emptyMsg: "<?php __('No data to display'); ?>"
            })
        });
        p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            p.getTopToolbar().findById('view-eduSubject').enable();
        });
        p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('view-eduSubject').enable();
            }
            else {
                p.getTopToolbar().findById('view-eduSubject').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_eduSubjects.load({
            params: {
                start: 0,
                limit: list_size
            }
        });
    }