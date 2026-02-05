//<script>
    var store_eduClasses = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', {name: 'cvalue', type: 'int'}, 
                'courses', 'min_for_promotion',
                'sections', 'payment_schedules', 'class_level', 
                'uni_teacher', 'grading_type', 'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_classes', 'action' => 'list_data')); ?>"
        }),
        sortInfo: {field: 'cvalue', direction: "ASC"}
    });

    function ViewEduClass(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_classes', 'action' => 'view')); ?>/" + id,
            success: function(response, opts) {
                var eduClass_data = response.responseText;

                eval(eduClass_data);

                EduClassViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Class view form. Error code'); ?>: " + response.status);
            }
        });
    }
    
    function ViewAuditTrailForClass(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'audit_trails', 'action' => 'index2', 'plugin' => '')); ?>/'+id+'/EduClass',
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
    
    function SearchEduClass() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_classes', 'action' => 'search')); ?>',
            success: function(response, opts) {
                var eduClass_data = response.responseText;

                eval(eduClass_data);

                eduClassSearchWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Class search form. Error Code'); ?>: " + response.status);
            }
        });
    }

    function SearchByEduClassName(value) {
        var conditions = '\'EduClass.name LIKE\' => \'%' + value + '%\'';
        store_eduClasses.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshEduClassData() {
        store_eduClasses.reload();
    }

    if (center_panel.find('id', 'eduClass-tab-v') != "") {
        var p = center_panel.findById('eduClass-tab-v');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Classes'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduClass-tab-v',
            xtype: 'grid',
            store: store_eduClasses,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Class Order'); ?>", dataIndex: 'cvalue', align: 'right', sortable: true},
                {header: "<?php __('Class Level'); ?>", dataIndex: 'class_level', sortable: true},
                {header: "<?php __('Is Uni-Teacher?'); ?>", dataIndex: 'uni_teacher', sortable: true},
                {header: "<?php __('Grading Type'); ?>", dataIndex: 'grading_type', sortable: true},
                {header: "<?php __('Minimum Average Mark for Promotion'); ?>", dataIndex: 'min_for_promotion', align: 'right', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true},
                {
                    header:'<?php __('Actions'); ?>',
                    xtype: 'actioncolumn',
                    width: 50,
                    items: [{
                        icon   : 'img/search.png',  // Use a URL in the icon config
                        tooltip: 'View',
                        handler: function(grid, rowIndex, colIndex) {
                            var rec = store_eduClasses.getAt(rowIndex);
                            ViewEduClass(rec.get('id'));
                        }
                    }, ' ', ' ', ' ', ' ', '|', ' ', ' ', ' ', ' ', {
                        icon   : 'img/at.png',
                        tooltip: 'Audit Trail',
                        handler: function(grid, rowIndex, colIndex) {
                            var rec = store_eduClasses.getAt(rowIndex);
                            ViewAuditTrailForClass(rec.get('id'));
                        }
                    }]
                }
            ],
            viewConfig: {
                forceFit: true
            },
            listeners: {
                celldblclick: function() {
                    ViewEduClass(Ext.getCmp('eduClass-tab-v').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: true
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: "<?php __('View Class'); ?>",
                        id: 'view-eduClass',
                        tooltip: "<?php __('<b>View Class</b><br />Click here to see details of the selected Class'); ?>",
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewEduClass(sel.data.id);
                            }
                        }
                    }, ' ', '-', '->', {
                        xtype: 'textfield',
                        emptyText: "<?php __('[Search By Name]'); ?>",
                        id: 'eduClass_search_field-v',
                        listeners: {
                            specialkey: function(field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduClassName(Ext.getCmp('eduClass_search_field-v').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('GO'); ?>",
                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
                        id: "eduClass_go_button-v",
                        handler: function() {
                            SearchByEduClassName(Ext.getCmp('eduClass_search_field-v').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('Advanced Search'); ?>",
                        tooltip: "<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
                        handler: function() {
                            SearchEduClass();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_eduClasses,
                displayInfo: true,
                displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
                beforePageText: "<?php __('Page'); ?>",
                afterPageText: "<?php __('of {0}'); ?>",
                emptyMsg: "<?php __('No data to display'); ?>"
            })
        });
        p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            p.getTopToolbar().findById('view-eduClass').enable();
        });
        p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('view-eduClass').enable();
            }
            else {
                p.getTopToolbar().findById('view-eduClass').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_eduClasses.load({
            params: {
                start: 0,
                limit: list_size
            }
        });

    }