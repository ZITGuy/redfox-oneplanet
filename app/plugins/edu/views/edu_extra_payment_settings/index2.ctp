//<script>
    var store_parent_eduExtraPaymentSettings = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'edu_extra_payment_type', 'edu_class', 'amount', 'edu_academic_year'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPaymentSettings', 'action' => 'list_data', $parent_id)); ?>'})
    });

    function AddParentEduExtraPaymentSetting() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPaymentSettings', 'action' => 'add', $parent_id)); ?>',
            success: function(response, opts) {
                var parent_eduExtraPaymentSetting_data = response.responseText;

                eval(parent_eduExtraPaymentSetting_data);

                EduExtraPaymentSettingAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Extra Payment Setting add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function EditParentEduExtraPaymentSetting(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPaymentSettings', 'action' => 'edit')); ?>/' + id + '/<?php echo $parent_id; ?>',
            success: function(response, opts) {
                var parent_eduExtraPaymentSetting_data = response.responseText;

                eval(parent_eduExtraPaymentSetting_data);

                EduExtraPaymentSettingEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Extra Payment Setting edit form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function DeleteParentEduExtraPaymentSetting(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPaymentSettings', 'action' => 'delete')); ?>/' + id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Extra Payment Setting(s) successfully deleted!'); ?>');
                RefreshParentEduExtraPaymentSettingData();
            },
            failure: function(response, opts) {
                var obj = Ext.decode(Ext.decode(JSON.stringify(response)).responseText);
                ShowErrorBox(obj.errormsg, obj.helpcode);
            }
        });
    }

    function RefreshParentEduExtraPaymentSettingData() {
        store_parent_eduExtraPaymentSettings.reload();
    }

    var g = new Ext.grid.GridPanel({
        title: '<?php __('Extra Payment Settings'); ?>',
        store: store_parent_eduExtraPaymentSettings,
        loadMask: true,
        stripeRows: true,
        height: 300,
        anchor: '100%',
        id: 'eduExtraPaymentSettingGrid',
        columns: [
            {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
            {header: "<?php __('Class'); ?>", dataIndex: 'edu_class', sortable: true},
            {header: "<?php __('Type'); ?>", dataIndex: 'edu_extra_payment_type', sortable: true},
            {header: "<?php __('Amount'); ?>", dataIndex: 'amount', sortable: true},
            {header: "<?php __('Academic Year'); ?>", dataIndex: 'edu_academic_year', sortable: true}],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
        }),
        viewConfig: {
            forceFit: true
        },
        tbar: new Ext.Toolbar({
            items: [{
                    xtype: 'tbbutton',
                    text: '<?php __('Add'); ?>',
                    tooltip: '<?php __('<b>Add Extra Payment Setting</b><br />Click here to create a new Extra Payment Setting'); ?>',
                    icon: 'img/table_add.png',
                    cls: 'x-btn-text-icon',
                    handler: function(btn) {
                        AddParentEduExtraPaymentSetting();
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Edit'); ?>',
                    id: 'edit-parent-eduExtraPaymentSetting',
                    tooltip: '<?php __('<b>Edit Extra Payment Setting</b><br />Click here to modify the selected Extra Payment Setting'); ?>',
                    icon: 'img/table_edit.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()) {
                            EditParentEduExtraPaymentSetting(sel.data.id);
                        }
                        ;
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Delete'); ?>',
                    id: 'delete-parent-eduExtraPaymentSetting',
                    tooltip: '<?php __('<b>Delete Extra Payment Setting(s)</b><br />Click here to remove the selected Extra Payment Setting(s)'); ?>',
                    icon: 'img/table_delete.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelections();
                        if (sm.hasSelection()) {
                            if (sel.length == 1) {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Extra Payment Setting'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove'); ?> ' + sel[0].data.name + '?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn) {
                                        if (btn == 'yes') {
                                            DeleteParentEduExtraPaymentSetting(sel[0].data.id);
                                        }
                                    }
                                });
                            } else {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Extra Payment Setting'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove the selected Extra Payment Setting'); ?>?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn) {
                                        if (btn == 'yes') {
                                            var sel_ids = '';
                                            for (i = 0; i < sel.length; i++) {
                                                if (i > 0)
                                                    sel_ids += '_';
                                                sel_ids += sel[i].data.id;
                                            }
                                            DeleteParentEduExtraPaymentSetting(sel_ids);
                                        }
                                    }
                                });
                            }
                        } else {
                            Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                        }
                        ;
                    }
                }, ' '
            ]}),
        bbar: new Ext.PagingToolbar({
            pageSize: list_size,
            store: store_parent_eduExtraPaymentSettings,
            displayInfo: true,
            displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
            beforePageText: '<?php __('Page'); ?>',
            afterPageText: '<?php __('of {0}'); ?>',
            emptyMsg: '<?php __('No data to display'); ?>'
        })
    });
    g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
        g.getTopToolbar().findById('edit-parent-eduExtraPaymentSetting').enable();
        g.getTopToolbar().findById('delete-parent-eduExtraPaymentSetting').enable();
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('edit-parent-eduExtraPaymentSetting').disable();
        }
    });
    g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('edit-parent-eduExtraPaymentSetting').disable();
            g.getTopToolbar().findById('delete-parent-eduExtraPaymentSetting').enable();
        }
        else if (this.getSelections().length == 1) {
            g.getTopToolbar().findById('edit-parent-eduExtraPaymentSetting').enable();
            g.getTopToolbar().findById('delete-parent-eduExtraPaymentSetting').enable();
        }
        else {
            g.getTopToolbar().findById('edit-parent-eduExtraPaymentSetting').disable();
            g.getTopToolbar().findById('delete-parent-eduExtraPaymentSetting').disable();
        }
    });



    var parentEduExtraPaymentSettingsViewWindow = new Ext.Window({
        title: 'Extra Payment Settings of the Class for the current Academic Year',
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
                    parentEduExtraPaymentSettingsViewWindow.close();
                }
            }]
    });

    store_parent_eduExtraPaymentSettings.load({
        params: {
            start: 0,
            limit: list_size
        }
    });