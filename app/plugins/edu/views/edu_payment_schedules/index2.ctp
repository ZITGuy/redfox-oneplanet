//<script>
    var store_parent_eduPaymentSchedules = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'month', 'edu_class', 'edu_academic_year', 'due_date'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_payment_schedules', 'action' => 'list_data', $parent_id)); ?>'})
    });

    function AddParentEduPaymentSchedule() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_payment_schedules', 'action' => 'add', $parent_id)); ?>',
            success: function(response, opts) {
                var parent_eduPaymentSchedule_data = response.responseText;

                eval(parent_eduPaymentSchedule_data);

                EduPaymentScheduleAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Payment Schedule add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function AddScheduleParentEduPaymentSchedule() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_payment_schedules', 'action' => 'schedule', $parent_id)); ?>',
            success: function(response, opts) {
                var parent_eduPaymentSchedule_data = response.responseText;

                eval(parent_eduPaymentSchedule_data);

                EduPaymentScheduleAddScheduleWindow.show();
            },
            failure: function(response, opts) {
                var obj = Ext.decode(Ext.decode(JSON.stringify(response)).responseText);
                ShowErrorBox(obj.errormsg, obj.helpcode);
            }
        });
    }

    function EditParentEduPaymentSchedule(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_payment_schedules', 'action' => 'edit')); ?>/' + id + '/<?php echo $parent_id; ?>',
            success: function(response, opts) {
                var parent_eduPaymentSchedule_data = response.responseText;

                eval(parent_eduPaymentSchedule_data);

                EduPaymentScheduleEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Payment Schedule edit form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function ViewEduPaymentSchedule(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_payment_schedules', 'action' => 'view')); ?>/' + id,
            success: function(response, opts) {
                var eduPaymentSchedule_data = response.responseText;

                eval(eduPaymentSchedule_data);

                EduPaymentScheduleViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Payment Schedule view form. Error code'); ?>: ' + response.status);
            }
        });
    }


    function DeleteParentEduPaymentSchedule(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_payment_schedules', 'action' => 'delete')); ?>/' + id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Payment Schedule(s) successfully deleted!'); ?>');
                RefreshParentEduPaymentScheduleData();
            },
            failure: function(response, opts) {
                var obj = Ext.decode(Ext.decode(JSON.stringify(response)).responseText);
                ShowErrorBox(obj.errormsg, obj.helpcode);
            }
        });
    }

    function RefreshParentEduPaymentScheduleData() {
        store_parent_eduPaymentSchedules.reload();
    }

    var g = new Ext.grid.GridPanel({
        title: '<?php __('Payment Schedules'); ?>',
        store: store_parent_eduPaymentSchedules,
        loadMask: true,
        stripeRows: true,
        height: 300,
        anchor: '100%',
        id: 'eduPaymentScheduleGrid',
        columns: [
            {header: "<?php __($payment_schedule_method == 'M' ? 'Month' : 'Term'); ?>", dataIndex: 'month', sortable: true},
            {header: "<?php __('Class'); ?>", dataIndex: 'edu_class', sortable: true},
            {header: "<?php __('Academic Year'); ?>", dataIndex: 'edu_academic_year', sortable: true},
            {header: "<?php __('Due Date'); ?>", dataIndex: 'due_date', sortable: true}
        ],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
        }),
        viewConfig: {
            forceFit: true
        },
        tbar: new Ext.Toolbar({
            items: [{
                    xtype: 'tbbutton',
                    text: '<?php __('Schedule'); ?>',
                    tooltip: '<?php __('<b>Add Multiple Payment Schedules once</b><br />Click here to create multiple Payment Schedules'); ?>',
                    icon: 'img/table_add.png',
                    cls: 'x-btn-text-icon',
                    handler: function(btn) {
                        AddScheduleParentEduPaymentSchedule();
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Edit'); ?>',
                    id: 'edit-parent-eduPaymentSchedule',
                    tooltip: '<?php __('<b>Edit Payment Schedule</b><br />Click here to modify the selected Payment Schedule'); ?>',
                    icon: 'img/table_edit.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()) {
                            EditParentEduPaymentSchedule(sel.data.id);
                        }
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Delete'); ?>',
                    id: 'delete-parent-eduPaymentSchedule',
                    tooltip: '<?php __('<b>Delete Payment Schedule(s)</b><br />Click here to remove the selected Payment Schedule(s)'); ?>',
                    icon: 'img/table_delete.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelections();
                        if (sm.hasSelection()) {
                            if (sel.length == 1) {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Payment Schedule'); ?>',
                                    buttons: Ext.MessageBox.YESNO,
                                    msg: '<?php __('Remove Payment Schedule'); ?> of ' + sel[0].data.month + '?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn) {
                                        if (btn == 'yes') {
                                            DeleteParentEduPaymentSchedule(sel[0].data.id);
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
                                            DeleteParentEduPaymentSchedule(sel_ids);
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
            store: store_parent_eduPaymentSchedules,
            displayInfo: true,
            displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
            beforePageText: '<?php __('Page'); ?>',
            afterPageText: '<?php __('of {0}'); ?>',
            emptyMsg: '<?php __('No data to display'); ?>'
        })
    });
    g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
        g.getTopToolbar().findById('edit-parent-eduPaymentSchedule').enable();
        g.getTopToolbar().findById('delete-parent-eduPaymentSchedule').enable();
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('edit-parent-eduPaymentSchedule').disable();
        }
    });
    g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('edit-parent-eduPaymentSchedule').disable();
            g.getTopToolbar().findById('delete-parent-eduPaymentSchedule').enable();
        }
        else if (this.getSelections().length == 1) {
            g.getTopToolbar().findById('edit-parent-eduPaymentSchedule').enable();
            g.getTopToolbar().findById('delete-parent-eduPaymentSchedule').enable();
        }
        else {
            g.getTopToolbar().findById('edit-parent-eduPaymentSchedule').disable();
            g.getTopToolbar().findById('delete-parent-eduPaymentSchedule').disable();
        }
    });


    var parentEduPaymentSchedulesViewWindow = new Ext.Window({
        title: 'Payment Schedule of Class <i><?php echo $edu_class['EduClass']['name']; ?></i> for the Active Academic Year',
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
                    parentEduPaymentSchedulesViewWindow.close();
                }
            }]
    });

    store_parent_eduPaymentSchedules.load({
        params: {
            start: 0,
            limit: list_size
        }
    });