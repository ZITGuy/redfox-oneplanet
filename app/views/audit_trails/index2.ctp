//<script>
    var store_parent_auditTrails = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'user', 'session_name', 'work_done', 'action_made', 
                'table_name', 'old_value', 'new_value', 'record_id', 'created'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'auditTrails', 'action' => 'list_data', $parent_id, $audited_model)); ?>'})
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

    function SearchByParentAuditTrailName(value) {
        var conditions = '\'AuditTrail.name LIKE\' => \'%' + value + '%\'';
        store_parent_auditTrails.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshParentAuditTrailData() {
        store_parent_auditTrails.reload();
    }

    var g = new Ext.grid.GridPanel({
        title: '<?php __('AuditTrails'); ?>',
        store: store_parent_auditTrails,
        loadMask: true,
        stripeRows: true,
        height: 300,
        anchor: '100%',
        id: 'auditTrailGrid',
        columns: [
            {header: "<?php __('User'); ?>", dataIndex: 'user', sortable: true, width: 60},
            {header: "<?php __('Work Description'); ?>", dataIndex: 'work_done', sortable: true},
            {header: "<?php __('Action Made'); ?>", dataIndex: 'action_made', sortable: true, hidden: true},
            {header: "<?php __('Table Name'); ?>", dataIndex: 'table_name', sortable: true, hidden: true},
            {header: "<?php __('Record Id'); ?>", dataIndex: 'record_id', sortable: true, hidden: true},
            {header: "<?php __('Date and Time'); ?>", dataIndex: 'created', sortable: true, width: 60}
        ],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
        }),
        viewConfig: {
            forceFit: true
        },
        listeners: {
            celldblclick: function() {
                ViewAuditTrail(Ext.getCmp('auditTrailGrid').getSelectionModel().getSelected().data.id);
            }
        },
        tbar: new Ext.Toolbar({
            items: [{
                    xtype: 'tbbutton',
                    text: '<?php __('View Audit Trail'); ?>',
                    id: 'view-auditTrail2',
                    tooltip: '<?php __('<b>View Audit Trail</b><br />Click here to see details of the selected Audit Trail'); ?>',
                    icon: 'img/table_view.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()) {
                            ViewAuditTrail(sel.data.id);
                        }
                    }
                }, ' ', '->', {
                    xtype: 'textfield',
                    emptyText: '<?php __('[Search By ...]'); ?>',
                    id: 'parent_auditTrail_search_field',
                    listeners: {
                        specialkey: function(field, e) {
                            if (e.getKey() == e.ENTER) {
                                SearchByParentAuditTrailName(Ext.getCmp('parent_auditTrail_search_field').getValue());
                            }
                        }

                    }
                }, {
                    xtype: 'tbbutton',
                    icon: 'img/search.png',
                    cls: 'x-btn-text-icon',
                    text: 'GO',
                    tooltip: '<?php __('<b>GO</b><br />Click here to get search results'); ?>',
                    id: 'parent_auditTrail_go_button',
                    handler: function() {
                        SearchByParentAuditTrailName(Ext.getCmp('parent_auditTrail_search_field').getValue());
                    }
                }, ' '
            ]}),
        bbar: new Ext.PagingToolbar({
            pageSize: list_size,
            store: store_parent_auditTrails,
            displayInfo: true,
            displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
            beforePageText: '<?php __('Page'); ?>',
            afterPageText: '<?php __('of {0}'); ?>',
            emptyMsg: '<?php __('No data to display'); ?>'
        })
    });
    g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
        g.getTopToolbar().findById('view-auditTrail2').enable();
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('view-auditTrail2').disable();
        }
    });
    g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
        if (this.getSelections().length == 1) {
            g.getTopToolbar().findById('view-auditTrail2').enable();
        }
        else {
            g.getTopToolbar().findById('view-auditTrail2').disable();
        }
    });



    var parentAuditTrailsViewWindow = new Ext.Window({
        title: 'AuditTrail Under the selected Item',
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
                    parentAuditTrailsViewWindow.close();
                }
            }]
    });

    store_parent_auditTrails.load({
        params: {
            start: 0,
            limit: list_size
        }
    });