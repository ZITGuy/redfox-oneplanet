//<script>
    var store_eduLessonPlans = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'edu_course', 'edu_section', 'maker', 'checker', 'is_posted',
                'posts', 'status', 'reason', 'created', 'modified']
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array(
                'controller' => 'edu_lesson_plans', 'action' => 'list_data_checker')); ?>"
        }),
        sortInfo: {field: 'edu_course', direction: "ASC"},
        groupField: 'edu_section'
    });

    function RejectEduLessonPlan(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array(
                'controller' => 'edu_lesson_plans', 'action' => 'reject_lesson_plan')); ?>/" + id,
            success: function (response, opts) {
                var eduLessonPlan_data = response.responseText;

                eval(eduLessonPlan_data);

                RejectLessonPlanWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the Lesson Plan Reject form. Error code'); ?>: " + response.status);
            }
        });
    }

    function ViewEduLessonPlan(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_lesson_plans', 'action' => 'view')); ?>/" + id,
            success: function (response, opts) {
                var eduLessonPlan_data = response.responseText;

                eval(eduLessonPlan_data);

                EduLessonPlanViewWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the Lesson Plan view form. Error code'); ?>: " + response.status);
            }
        });
    }
    
    function ApproveEduLessonPlan(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array(
                'controller' => 'edu_lesson_plans', 'action' => 'approve_lesson_plan')); ?>/" + id,
            success: function (response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Lesson Plan successfully approved!'); ?>");
                RefreshEduLessonPlanData();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the Lesson Plan add form. Error code'); ?>: " + response.status);
            }
        });
    }
	
    function RefreshEduLessonPlanData() {
        store_eduLessonPlans.reload();
        var p = center_panel.findById('eduLessonPlan_Checker_tab');
        
        p.getTopToolbar().findById('approve-eduLessonPlan').disable();
        p.getTopToolbar().findById('reject-eduLessonPlan').disable();
        p.getTopToolbar().findById('view-eduLessonPlan').disable();
    }


    if (center_panel.find('id', 'eduLessonPlan_Checker_tab') != "") {
        var p = center_panel.findById('eduLessonPlan_Checker_tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Lesson Plans'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduLessonPlan_Checker_tab',
            xtype: 'grid',
            store: store_eduLessonPlans,
            columns: [
                {header: "<?php __('Course'); ?>", dataIndex: 'edu_course', sortable: true},
                {header: "<?php __('Section'); ?>", dataIndex: 'edu_section', sortable: true},
                {header: "<?php __('Maker'); ?>", dataIndex: 'maker', sortable: true},
                {header: "<?php __('Checker'); ?>", dataIndex: 'checker', sortable: true},
                {header: "<?php __('Is Posted'); ?>", dataIndex: 'is_posted', sortable: true},
                {header: "<?php __('# of Posts'); ?>", dataIndex: 'posts', sortable: true, hidden: true},
                {header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true},
                {header: "<?php __('Reason'); ?>", dataIndex: 'reason', sortable: true, hidden: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
            ],
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Lesson Plans" : "Lesson Plan"]})'
            }),
            listeners: {
                celldblclick: function () {
                    ViewEduLessonPlan(
                        Ext.getCmp('eduLessonPlan_Checker_tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: true
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: "<?php __('Approve'); ?>",
                        id: 'approve-eduLessonPlan',
                        tooltip: "<?php __('<b>Approve Lesson Plans</b><br />Click to Approve the selected LP'); ?>",
                        icon: 'img/table_edit.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                Ext.Msg.show({
                                    title: "<?php __('Approve Lesson Plan'); ?>",
                                    buttons: Ext.MessageBox.YESNO,
                                    msg: "<?php __('Are you sure to approve the selected lesson plan'); ?>?",
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function (btn) {
                                        if (btn == 'yes') {
                                            ApproveEduLessonPlan(sel.data.id);
                                        }
                                    }
                                });
                            }
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Reject'); ?>",
                        id: 'reject-eduLessonPlan',
                        tooltip: "<?php __('<b>Reject Lesson Plans</b><br />Click to reject the selected LP'); ?>",
                        icon: 'img/table_edit.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                RejectEduLessonPlan(sel.data.id);
                            }
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('View Lesson Plan'); ?>",
                        id: 'view-eduLessonPlan',
                        tooltip: "<?php __('<b>View Lesson Plan</b><br />Click to see details of the selected LP'); ?>",
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewEduLessonPlan(sel.data.id);
                            }
                        }
                    }, ' ', '-', "<?php __('Section'); ?>: ", {
                        xtype: 'combo',
                        emptyText: 'All',
                        store: new Ext.data.ArrayStore({
                            fields: ['id', 'name'],
                            data: [
                                ['-1', 'All'],
<?php $st = false;
    foreach ($edu_sections as $item) {
        if ($st) { echo ", ";} ?>
        ['<?php echo $item['EduSection']['id']; ?>', '<?php echo $item['EduSection']['name']; ?>']<?php $st = true;
} ?>]
                        }),
                        displayField: 'name',
                        valueField: 'id',
                        mode: 'local',
                        value: '-1',
                        disableKeyFilter: true,
                        triggerAction: 'all',
                        listeners: {
                            select: function (combo, record, index) {
                                store_eduLessonPlans.reload({
                                    params: {
                                        start: 0,
                                        limit: list_size,
                                        edu_section_id: combo.getValue()
                                    }
                                });
                            }
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_eduLessonPlans,
                displayInfo: true,
                displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
                beforePageText: "<?php __('Page'); ?>",
                afterPageText: "<?php __('of {0}'); ?>",
                emptyMsg: "<?php __('No data to display'); ?>"
            })
        });
        p.getSelectionModel().on('rowselect', function (sm, rowIdx, r) {
            record = store_eduLessonPlans.getAt(rowIdx);
            p.getTopToolbar().findById('approve-eduLessonPlan').disable();
            p.getTopToolbar().findById('reject-eduLessonPlan').disable();
            if(record.get('status') == 'Posted') {
                p.getTopToolbar().findById('approve-eduLessonPlan').enable();
                p.getTopToolbar().findById('reject-eduLessonPlan').enable();
            }
            p.getTopToolbar().findById('view-eduLessonPlan').enable();
        });
        p.getSelectionModel().on('rowdeselect', function (sm, rowIdx, r) {
            p.getTopToolbar().findById('approve-eduLessonPlan').disable();
            p.getTopToolbar().findById('reject-eduLessonPlan').disable();
            p.getTopToolbar().findById('view-eduLessonPlan').disable();
        });
        center_panel.setActiveTab(p);

        store_eduLessonPlans.load({
            params: {
                start: 0,
                limit: list_size
            }
        });
    }