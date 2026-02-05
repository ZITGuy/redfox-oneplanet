//<script>
    var store_restore_points = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'created', 'modified']
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'restore_points', 'action' => 'list_data')); ?>'
        }),
        sortInfo: {field: 'name', direction: "ASC"}
    });

    function AddRestorePoint() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'restore_points', 'action' => 'add')); ?>',
            success: function (response, opts) {
                var restore_point_data = response.responseText;

                eval(restore_point_data);

                RestorePointAddWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Restore Point add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function ViewRestorePoint(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'restore_points', 'action' => 'view')); ?>/' + id,
            success: function (response, opts) {
                var restore_point_data = response.responseText;

                eval(restore_point_data);

                RestorePointViewWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Restore Point view form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function SearchRestorePoint() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'restore_points', 'action' => 'search')); ?>',
            success: function (response, opts) {
                var restore_point_data = response.responseText;

                eval(restore_point_data);

                restorePointSearchWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Restore Point search form. Error Code'); ?>: ' + response.status);
            }
        });
    }

    function SearchByRestorePointName(value) {
        var conditions = '\'RestorePoint.name LIKE\' => \'%' + value + '%\'';
        store_restore_points.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshRestorePointData() {
        store_restore_points.reload();
    }


    if (center_panel.find('id', 'restore_point_tab') != "") {
        var p = center_panel.findById('restore_point_tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Restore Points'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'restore_point_tab',
            xtype: 'grid',
            store: store_restore_points,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
            ],
            viewConfig: {
                forceFit: true
            },
            listeners: {
                celldblclick: function () {
                    ViewRestorePoint(Ext.getCmp('restore_point_tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: true
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: '<?php __('Take Backup'); ?>',
                        tooltip: '<?php __('<b>Create Restore Point</b><br />Click here to create a Restore Point'); ?>',
                        icon: 'img/layout_images/backup.jpg',
                        cls: 'x-btn-text-icon',
                        handler: function (btn) {
                            AddRestorePoint();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('Restore'); ?>',
                        id: 'run_restore_point',
                        tooltip: '<?php __('<b>Run DB Restore</b><br />Click here to run the selected Restore Point'); ?>',
                        icon: 'img/layout_images/restore.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            Ext.Msg.show({
                                title: '<?php __('Restore Database'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: '<?php __('Restoring DB is not a trivial process. Please contact the administrators. '); ?>',
                                icon: Ext.MessageBox.ERROR
                            });
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('View Restore Point'); ?>',
                        id: 'view_restore_point',
                        tooltip: '<?php __('<b>View Restore Point</b><br />Click here to see details of the selected Restore Point'); ?>',
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewRestorePoint(sel.data.id);
                            }
                        }
                    }, ' ', '-', '->', {
                        xtype: 'textfield',
                        emptyText: '<?php __('[Search By Name]'); ?>',
                        id: 'restore_point_search_field',
                        listeners: {
                            specialkey: function (field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByRestorePointName(Ext.getCmp('restore_point_search_field').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('GO'); ?>',
                        tooltip: '<?php __('<b>GO</b><br />Click here to get search results'); ?>',
                        id: 'restore_point_go_button',
                        handler: function () {
                            SearchByRestorePointName(Ext.getCmp('restore_point_search_field').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('Advanced Search'); ?>',
                        tooltip: '<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
                        handler: function () {
                            SearchRestorePoint();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_restore_points,
                displayInfo: true,
                displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
                beforePageText: '<?php __('Page'); ?>',
                afterPageText: '<?php __('of {0}'); ?>',
                emptyMsg: '<?php __('No data to display'); ?>'
            })
        });
        p.getSelectionModel().on('rowselect', function (sm, rowIdx, r) {
            p.getTopToolbar().findById('run_restore_point').enable();
            p.getTopToolbar().findById('view_restore_point').enable();
        });
        p.getSelectionModel().on('rowdeselect', function (sm, rowIdx, r) {
            if (this.getSelections().length > 0) {
                p.getTopToolbar().findById('run_restore_point').enable();
                p.getTopToolbar().findById('view_restore_point').enable();
            } else {
                p.getTopToolbar().findById('run_restore_point').disable();
                p.getTopToolbar().findById('view_restore_point').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_restore_points.load({
            params: {
                start: 0,
                limit: list_size
            }
        });
    }
