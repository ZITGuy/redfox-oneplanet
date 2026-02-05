//<script>
    var store_auditTrails = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'user', 'session_name', 'work_done', 'action_made', 
                'table_name', 'old_value', 'new_value', 'record_id', 'created'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'auditTrails', 'action' => 'list_data')); ?>'
        }), 
        sortInfo: {field: 'user', direction: "ASC"},
        groupField: 'user'
    });

    function ViewAuditTrail(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'auditTrails', 'action' => 'view')); ?>/' + id,
            success: function(response, opts) {
                var auditTrail_data = response.responseText;

                eval(auditTrail_data);

                AuditTrailViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the auditTrail view form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function SearchAuditTrail() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'auditTrails', 'action' => 'search')); ?>',
            success: function(response, opts) {
                var auditTrail_data = response.responseText;

                eval(auditTrail_data);

                auditTrailSearchWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the auditTrail search form. Error Code'); ?>: ' + response.status);
            }
        });
    }

    function SearchByAuditTrailName(value) {
        var conditions = '\'AuditTrail.name LIKE\' => \'%' + value + '%\'';
        store_auditTrails.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshAuditTrailData() {
        store_auditTrails.reload();
    }


    if (center_panel.find('id', 'auditTrail-tab') != "") {
        var p = center_panel.findById('auditTrail-tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Audit Trails'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'auditTrail-tab',
            xtype: 'grid',
            store: store_auditTrails,
            columns: [
                {header: "<?php __('User'); ?>", dataIndex: 'user', sortable: true, width: 60},
                {header: "<?php __('Work Description'); ?>", dataIndex: 'work_done', sortable: true},
                {header: "<?php __('Action Made'); ?>", dataIndex: 'action_made', sortable: true, hidden: true},
                {header: "<?php __('Table Name'); ?>", dataIndex: 'table_name', sortable: true, hidden: true},
                {header: "<?php __('Record Id'); ?>", dataIndex: 'record_id', sortable: true, hidden: true},
                {header: "<?php __('Date and Time'); ?>", dataIndex: 'created', sortable: true, width: 60}
            ],
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Audit Trails" : "Audit Trail"]})'
            }),
            listeners: {
                celldblclick: function() {
                    ViewAuditTrail(Ext.getCmp('auditTrail-tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: '<?php __('View Audit Trail'); ?>',
                        id: 'view-auditTrail',
                        tooltip: '<?php __('<b>View Audit Trail</b><br />Click here to see details of the selected AuditTrail'); ?>',
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewAuditTrail(sel.data.id);
                            }
                            ;
                        }
                    }, ' ', '-', '<?php __('User'); ?>: ', {
                        xtype: 'combo',
                        emptyText: 'All',
                        store: new Ext.data.ArrayStore({
                            fields: ['id', 'name'],
                            data: [
                                ['-1', 'All'],
<?php $st = false;
    foreach ($users as $item) {
        if ($st) echo ",
			"; ?>['<?php echo $item['User']['id']; ?>', '<?php echo $item['User']['username']; ?>']<?php $st = true;
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
                                store_auditTrails.reload({
                                    params: {
                                        start: 0,
                                        limit: list_size,
                                        user_id: combo.getValue()
                                    }
                                });
                            }
                        }
                    },
                    '->', {
                        xtype: 'textfield',
                        emptyText: '<?php __('[Search By Name]'); ?>',
                        id: 'auditTrail_search_field',
                        listeners: {
                            specialkey: function(field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByAuditTrailName(Ext.getCmp('auditTrail_search_field').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('GO'); ?>',
                        tooltip: '<?php __('<b>GO</b><br />Click here to get search results'); ?>',
                        id: 'auditTrail_go_button',
                        handler: function() {
                            SearchByAuditTrailName(Ext.getCmp('auditTrail_search_field').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('Advanced Search'); ?>',
                        tooltip: '<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
                        handler: function() {
                            SearchAuditTrail();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_auditTrails,
                displayInfo: true,
                displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
                beforePageText: '<?php __('Page'); ?>',
                afterPageText: '<?php __('of {0}'); ?>',
                emptyMsg: '<?php __('No data to display'); ?>'
            })
        });
        p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            p.getTopToolbar().findById('view-auditTrail').enable();
        });
        p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('view-auditTrail').enable();
            }
            else {
                p.getTopToolbar().findById('view-auditTrail').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_auditTrails.load({
            params: {
                start: 0,
                limit: list_size
            }
        });

    }
