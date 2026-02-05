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
                'controller' => 'edu_lesson_plans', 'action' => 'list_data')); ?>"
        }),
        sortInfo: {field: 'edu_course', direction: "ASC"},
        groupField: 'edu_section'
    });


    function MaintainEduLessonPlan() {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array(
                'controller' => 'edu_lesson_plans', 'action' => 'maintain')); ?>",
            success: function (response, opts) {
                var eduLessonPlan_data = response.responseText;

                eval(eduLessonPlan_data);

                EduLessonPlanMaintainWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the Lesson Plan Maintenance form. Error code'); ?>: " + response.status);
            }
        });
    }

    function EditEduLessonPlan(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array(
                'controller' => 'edu_lesson_plans', 'action' => 'edit')); ?>/" + id,
            success: function (response, opts) {
                var eduLessonPlan_data = response.responseText;

                eval(eduLessonPlan_data);

                EduLessonPlanEditWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the Lesson Plan edit form. Error code'); ?>: " + response.status);
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
    function ViewParentEduLessonPlanItems(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array(
                'controller' => 'edu_lesson_plan_items', 'action' => 'index2')); ?>/" + id,
            success: function (response, opts) {
                var parent_eduLessonPlanItems_data = response.responseText;

                eval(parent_eduLessonPlanItems_data);

                parentEduLessonPlanItemsViewWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the Lesson Plan Items view form. Error code'); ?>: " + response.status);
            }
        });
    }
    
    function PostEduLessonPlan(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array(
                'controller' => 'edu_lesson_plans', 'action' => 'post_lesson_plan')); ?>/" + id,
            success: function (response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Lesson Plan successfully posted!'); ?>");
                RefreshEduLessonPlanData();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the Lesson Plan Post form. Error code'); ?>: " + response.status);
            }
        });
    }

    function DeleteEduLessonPlan(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array(
                'controller' => 'edu_lesson_plans', 'action' => 'delete')); ?>/" + id,
            success: function (response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Lesson Plan successfully deleted!'); ?>");
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
        var p = center_panel.findById('eduLessonPlan_Maker_tab');
        
        p.getTopToolbar().findById('post-eduLessonPlan').disable();
        p.getTopToolbar().findById('view-eduLessonPlan').disable();
        p.getTopToolbar().findById('delete-eduLessonPlan').disable();
    }


    if (center_panel.find('id', 'eduLessonPlan_Maker_tab') != "") {
        var p = center_panel.findById('eduLessonPlan_Maker_tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Lesson Plans'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduLessonPlan_Maker_tab',
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
                    ViewEduLessonPlan(Ext.getCmp('eduLessonPlan_Maker_tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: true
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: "<?php __('Maintain'); ?>",
                        tooltip: "<?php __('<b>Maintain Lesson Plans</b><br />Click to create/edit a LP'); ?>",
                        icon: 'img/table_add.png',
                        cls: 'x-btn-text-icon',
                        handler: function (btn) {
                            MaintainEduLessonPlan();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Post'); ?>",
                        id: 'post-eduLessonPlan',
                        tooltip: "<?php __('<b>Post Lesson Plans</b><br />Click to post the selected LP'); ?>",
                        icon: 'img/table_edit.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                Ext.Msg.show({
                                    title: "<?php __('Post Lesson Plan'); ?>",
                                    buttons: Ext.MessageBox.YESNO,
                                    msg: "<?php __('Are you sure to post the selectd lesson plan'); ?>?",
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function (btn) {
                                        if (btn == 'yes') {
                                            PostEduLessonPlan(sel.data.id);
                                        }
                                    }
                                });
                            }
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Delete'); ?>",
                        id: 'delete-eduLessonPlan',
                        tooltip: "<?php __('<b>Delete Lesson Plans(s)</b><br />Click to remove the selected LP'); ?>",
                        icon: 'img/table_delete.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelections();
                            if (sm.hasSelection()) {
                                Ext.Msg.show({
                                    title: "<?php __('Remove Lesson Plan'); ?>",
                                    buttons: Ext.MessageBox.YESNO,
                                    msg: "<?php __('Remove'); ?> lesson plan for " + sel[0].data.edu_course + '?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function (btn) {
                                        if (btn == 'yes') {
                                            DeleteEduLessonPlan(sel[0].data.id);
                                        }
                                    }
                                });
                            } else {
                                Ext.Msg.alert("<?php __('Warning'); ?>",
                                    "<?php __('Please select a record first'); ?>");
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
        if ($st) { echo ", "; } ?>
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
            p.getTopToolbar().findById('post-eduLessonPlan').disable();
            p.getTopToolbar().findById('delete-eduLessonPlan').disable();
            if(record.get('status') == 'Created' || record.get('status') == 'Returned') {
                p.getTopToolbar().findById('post-eduLessonPlan').enable();
            }
            if(record.get('status') == 'Created') {
                p.getTopToolbar().findById('delete-eduLessonPlan').enable();
            }
            p.getTopToolbar().findById('view-eduLessonPlan').enable();
        });
        p.getSelectionModel().on('rowdeselect', function (sm, rowIdx, r) {
            if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('post-eduLessonPlan').enable();
                p.getTopToolbar().findById('view-eduLessonPlan').enable();
                p.getTopToolbar().findById('delete-eduLessonPlan').enable();
            } else {
                p.getTopToolbar().findById('post-eduLessonPlan').disable();
                p.getTopToolbar().findById('view-eduLessonPlan').disable();
                p.getTopToolbar().findById('delete-eduLessonPlan').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_eduLessonPlans.load({
            params: {
                start: 0,
                limit: list_size
            }
        });
    }