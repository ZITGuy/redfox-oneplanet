//<script>
    var store_eduPaymentSchedules = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'month', 'edu_class', 'amount', 'edu_academic_year'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'eduPaymentSchedules', 'action' => 'list_data')); ?>'
        }),
        sortInfo: {field: 'month', direction: "ASC"},
        groupField: 'edu_class'
    });


    function AddEduPaymentSchedule() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduPaymentSchedules', 'action' => 'add')); ?>',
            success: function(response, opts) {
                var eduPaymentSchedule_data = response.responseText;

                eval(eduPaymentSchedule_data);

                EduPaymentScheduleAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPaymentSchedule add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function AddScheduleEduPaymentSchedule() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduPaymentSchedules', 'action' => 'schedule')); ?>',
            success: function(response, opts) {
                var parent_eduPaymentSchedule_data = response.responseText;

                eval(parent_eduPaymentSchedule_data);

                EduPaymentScheduleAddScheduleWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Payment Schedule multi-add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function EditEduPaymentSchedule(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduPaymentSchedules', 'action' => 'edit')); ?>/' + id,
            success: function(response, opts) {
                var eduPaymentSchedule_data = response.responseText;

                eval(eduPaymentSchedule_data);

                EduPaymentScheduleEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPaymentSchedule edit form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function ViewEduPaymentSchedule(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduPaymentSchedules', 'action' => 'view')); ?>/' + id,
            success: function(response, opts) {
                var eduPaymentSchedule_data = response.responseText;

                eval(eduPaymentSchedule_data);

                EduPaymentScheduleViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPaymentSchedule view form. Error code'); ?>: ' + response.status);
            }
        });
    }
    function ViewParentEduPayments(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduPayments', 'action' => 'index2')); ?>/' + id,
            success: function(response, opts) {
                var parent_eduPayments_data = response.responseText;

                eval(parent_eduPayments_data);

                parentEduPaymentsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
            }
        });
    }


    function DeleteEduPaymentSchedule(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduPaymentSchedules', 'action' => 'delete')); ?>/' + id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduPaymentSchedule successfully deleted!'); ?>');
                RefreshEduPaymentScheduleData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPaymentSchedule add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function SearchEduPaymentSchedule() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduPaymentSchedules', 'action' => 'search')); ?>',
            success: function(response, opts) {
                var eduPaymentSchedule_data = response.responseText;

                eval(eduPaymentSchedule_data);

                eduPaymentScheduleSearchWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPaymentSchedule search form. Error Code'); ?>: ' + response.status);
            }
        });
    }

    function SearchByEduPaymentScheduleName(value) {
        var conditions = '\'EduPaymentSchedule.name LIKE\' => \'%' + value + '%\'';
        store_eduPaymentSchedules.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshEduPaymentScheduleData() {
        store_eduPaymentSchedules.reload();
    }


    if (center_panel.find('id', 'eduPaymentSchedule-tab') != "") {
        var p = center_panel.findById('eduPaymentSchedule-tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Pmt Schedules'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduPaymentSchedule-tab',
            xtype: 'grid',
            store: store_eduPaymentSchedules,
            columns: [
                {header: "<?php __($payment_schedule_method == 'M' ? 'Month' : 'Quarter'); ?>", dataIndex: 'month', sortable: true},
                {header: "<?php __('Class'); ?>", dataIndex: 'edu_class', sortable: true},
                {header: "<?php __('Amount'); ?>", dataIndex: 'amount', sortable: true},
                {header: "<?php __('Academic Year'); ?>", dataIndex: 'edu_academic_year', sortable: true}
            ],
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Payment Schedules" : "Payment Schedule"]})'
            }),
            listeners: {
                celldblclick: function() {
                    ViewEduPaymentSchedule(Ext.getCmp('eduPaymentSchedule-tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: '<?php __('Add'); ?>',
                        tooltip: '<?php __('<b>Add Payment Schedules</b><br />Click here to create a new Payment Schedule'); ?>',
                        icon: 'img/table_add.png',
                        cls: 'x-btn-text-icon',
                        handler: function(btn) {
                            AddEduPaymentSchedule();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('Schedule'); ?>',
                        tooltip: '<?php __('<b>Add Multiple Payment Schedules once</b><br />Click here to create a dozen of Payment Schedules'); ?>',
                        icon: 'img/table_add.png',
                        cls: 'x-btn-text-icon',
                        handler: function(btn) {
                            AddScheduleEduPaymentSchedule();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('Edit'); ?>',
                        id: 'edit-eduPaymentSchedule',
                        tooltip: '<?php __('<b>Edit Payment Schedules</b><br />Click here to modify the selected Payment Schedule'); ?>',
                        icon: 'img/table_edit.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                EditEduPaymentSchedule(sel.data.id);
                            }
                            ;
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('Delete'); ?>',
                        id: 'delete-eduPaymentSchedule',
                        tooltip: '<?php __('<b>Delete Payment Schedules(s)</b><br />Click here to remove the selected Payment Schedule(s)'); ?>',
                        icon: 'img/table_delete.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelections();
                            if (sm.hasSelection()) {
                                if (sel.length == 1) {
                                    Ext.Msg.show({
                                        title: '<?php __('Remove Payment Schedule'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove'); ?> ' + sel[0].data.name + '?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn) {
                                            if (btn == 'yes') {
                                                DeleteEduPaymentSchedule(sel[0].data.id);
                                            }
                                        }
                                    });
                                } else {
                                    Ext.Msg.show({
                                        title: '<?php __('Remove Payment Schedule'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove the selected Payment Schedules'); ?>?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn) {
                                            if (btn == 'yes') {
                                                var sel_ids = '';
                                                for (i = 0; i < sel.length; i++) {
                                                    if (i > 0)
                                                        sel_ids += '_';
                                                    sel_ids += sel[i].data.id;
                                                }
                                                DeleteEduPaymentSchedule(sel_ids);
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
                        text: '<?php __('View Payment Schedule'); ?>',
                        id: 'view-eduPaymentSchedule',
                        tooltip: '<?php __('<b>View Payment Schedule</b><br />Click here to see details of the selected Payment Schedule'); ?>',
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewEduPaymentSchedule(sel.data.id);
                            }
                            ;
                        },
                        menu: {
                            items: [
                                {
                                    text: '<?php __('View Payments'); ?>',
                                    icon: 'img/table_view.png',
                                    cls: 'x-btn-text-icon',
                                    handler: function(btn) {
                                        var sm = p.getSelectionModel();
                                        var sel = sm.getSelected();
                                        if (sm.hasSelection()) {
                                            ViewParentEduPayments(sel.data.id);
                                        }
                                        ;
                                    }
                                }
                            ]
                        }
                    }, ' ', '-', '<?php __('Class'); ?>: ', {
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
                                store_eduPaymentSchedules.reload({
                                    params: {
                                        start: 0,
                                        limit: list_size,
                                        edu_class_id: combo.getValue()
                                    }
                                });
                            }
                        }
                    }, '->', {
                        xtype: 'textfield',
                        emptyText: '<?php __('[Search By Name]'); ?>',
                        id: 'eduPaymentSchedule_search_field',
                        listeners: {
                            specialkey: function(field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduPaymentScheduleName(Ext.getCmp('eduPaymentSchedule_search_field').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('GO'); ?>',
                        tooltip: '<?php __('<b>GO</b><br />Click here to get search results'); ?>',
                        id: 'eduPaymentSchedule_go_button',
                        handler: function() {
                            SearchByEduPaymentScheduleName(Ext.getCmp('eduPaymentSchedule_search_field').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('Advanced Search'); ?>',
                        tooltip: '<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
                        handler: function() {
                            SearchEduPaymentSchedule();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_eduPaymentSchedules,
                displayInfo: true,
                displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
                beforePageText: '<?php __('Page'); ?>',
                afterPageText: '<?php __('of {0}'); ?>',
                emptyMsg: '<?php __('No data to display'); ?>'
            })
        });
        p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            p.getTopToolbar().findById('edit-eduPaymentSchedule').enable();
            p.getTopToolbar().findById('delete-eduPaymentSchedule').enable();
            p.getTopToolbar().findById('view-eduPaymentSchedule').enable();
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit-eduPaymentSchedule').disable();
                p.getTopToolbar().findById('view-eduPaymentSchedule').disable();
            }
        });
        p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit-eduPaymentSchedule').disable();
                p.getTopToolbar().findById('view-eduPaymentSchedule').disable();
                p.getTopToolbar().findById('delete-eduPaymentSchedule').enable();
            }
            else if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('edit-eduPaymentSchedule').enable();
                p.getTopToolbar().findById('view-eduPaymentSchedule').enable();
                p.getTopToolbar().findById('delete-eduPaymentSchedule').enable();
            }
            else {
                p.getTopToolbar().findById('edit-eduPaymentSchedule').disable();
                p.getTopToolbar().findById('view-eduPaymentSchedule').disable();
                p.getTopToolbar().findById('delete-eduPaymentSchedule').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_eduPaymentSchedules.load({
            params: {
                start: 0,
                limit: list_size
            }
        });

    }
