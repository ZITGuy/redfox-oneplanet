//<script>
    var store_eduClasses = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', {name: 'cvalue', type: 'int'},
                'courses', 'min_for_promotion',
                'sections', 'payment_schedules', 'class_level',
                'uni_teacher', 'grading_type', 'course_item_enabled',
                'created', 'modified']
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_classes', 'action' => 'list_data')); ?>"
        }),
        sortInfo: {field: 'cvalue', direction: "ASC"}
    });

    function AddEduClass() {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_classes', 'action' => 'add')); ?>",
            success: function(response, opts) {
                var eduClass_data = response.responseText;

                eval(eduClass_data);

                EduClassAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the Class add form. Error code'); ?>: " + response.status);
            }
        });
    }

    function EditEduClass(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_classes', 'action' => 'edit')); ?>/" + id,
            success: function(response, opts) {
                var eduClass_data = response.responseText;

                eval(eduClass_data);

                EduClassEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the Class edit form. Error code'); ?>: " + response.status);
            }
        });
    }

    function ViewEduClass(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_classes', 'action' => 'view')); ?>/" + id,
            success: function(response, opts) {
                var eduClass_data = response.responseText;

                eval(eduClass_data);

                EduClassViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the Class view form. Error code'); ?>: " + response.status);
            }
        });
    }
    
    function ViewAuditTrailForClass(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'audit_trails',
                'action' => 'index2', 'plugin' => '')); ?>/'+id+'/EduClass',
            success: function(response, opts) {
                var audit_trail_data = response.responseText;

                eval(audit_trail_data);

                parentAuditTrailsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>',
                    '<?php __('Cannot get the Audit Trail view form. Error code'); ?>: ' + response.status);
            }
        });
    }
    
    function ViewParentEduCourses(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'index2')); ?>/" + id,
            success: function(response, opts) {
                var parent_eduCourses_data = response.responseText;

                eval(parent_eduCourses_data);

                parentEduCoursesViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the Courses view form. Error code'); ?>: " + response.status);
            }
        });
    }
	
    function ViewParentEduEvaluations(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_evaluations',
                'action' => 'index2')); ?>/" + id,
            success: function(response, opts) {
                var parent_eduEvaluations_data = response.responseText;
                eval(parent_eduEvaluations_data);
                parentEduEvaluationsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the Evaluations view form. Error code'); ?>: " + response.status);
            }
        });
    }

    function OpenClassSectionsDetail(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_sections',
                'action' => 'sections_detail')); ?>/" + id,
            success: function(response, opts) {
                var eduSection_data = response.responseText;
                eval(eduSection_data);
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the Section Detail form. Error code'); ?>: " + response.status);
            }
        });
    }
	
    function ViewParentEduPaymentSchedules(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_payment_schedules',
                'action' => 'index2')); ?>/" + id,
            success: function(response, opts) {
                var parent_eduPaymentSchedules_data = response.responseText;

                eval(parent_eduPaymentSchedules_data);

                parentEduPaymentSchedulesViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the Payment Schedules view form. Error code'); ?>: " + response.status);
            }
        });
    }
    
    function ViewParentEduExtraPaymentSettings(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_extra_payment_settings',
                'action' => 'index2')); ?>/" + id,
            success: function(response, opts) {
                var parent_eduExtraPaymentSettings_data = response.responseText;

                eval(parent_eduExtraPaymentSettings_data);

                parentEduExtraPaymentSettingsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the Extra Payment Settings view form. Error code'); ?>: " + response.status);
            }
        });
    }


    function DeleteEduClass(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_classes', 'action' => 'delete')); ?>/" + id,
            success: function(response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Class successfully deleted!'); ?>");
                RefreshEduClassData();
            },
            failure: function(response, opts) {
                var obj = Ext.decode(Ext.decode(JSON.stringify(response)).responseText);
                ShowErrorBox(obj.errormsg, obj.helpcode);
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
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the Class search form. Error Code'); ?>: " + response.status);
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

    if (center_panel.find('id', 'eduClass-tab-m') != "") {
        var p = center_panel.findById('eduClass-tab-m');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Classes'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduClass-tab-m',
            xtype: 'grid',
            store: store_eduClasses,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Class Order'); ?>", dataIndex: 'cvalue', align: 'right', sortable: true},
                {header: "<?php __('Class Level'); ?>", dataIndex: 'class_level', sortable: true},
                {header: "<?php __('Self Contained?'); ?>", dataIndex: 'uni_teacher', sortable: true},
                {header: "<?php __('Grading Type'); ?>", dataIndex: 'grading_type', sortable: true},
                {header: "<?php __('Min Avg Mark for Promo'); ?>", dataIndex: 'min_for_promotion',
                    align: 'right', sortable: true},
                {header: "<?php __('Course Item Enabled'); ?>", dataIndex: 'course_item_enabled', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true},
                {
                    header:'<?php __('Actions'); ?>',
                    xtype: 'actioncolumn',
                    width: 50,
                    items: [{
                        icon   : 'img/table_edit.png',  // Use a URL in the icon config
                        tooltip: 'Edit Class',
                        handler: function(grid, rowIndex, colIndex) {
                            var rec = store_eduClasses.getAt(rowIndex);
                            EditEduClass(rec.get('id'));
                        }
                    }, ' ', ' ', ' ', ' ', '-', ' ', ' ', ' ', ' ', {
                        icon   : 'img/search.png',  // Use a URL in the icon config
                        tooltip: 'View',
                        handler: function(grid, rowIndex, colIndex) {
                            var rec = store_eduClasses.getAt(rowIndex);
                            ViewEduClass(rec.get('id'));
                        }
                    }, ' ', ' ', ' ', ' ', '|', ' ', ' ', ' ', ' ', {
                        icon   : 'img/calendar_add.png',
                        tooltip: 'Manage Class Courses',
                        handler: function(grid, rowIndex, colIndex) {
                            var rec = store_eduClasses.getAt(rowIndex);
                            ViewParentEduCourses(rec.get('id'));
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
                    ViewEduClass(Ext.getCmp('eduClass-tab-m').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: "<?php __('Add'); ?>",
                        tooltip: "<?php __('<b>Add Classes</b><br />Click here to create a new Class'); ?>",
                        icon: 'img/table_add.png',
                        cls: 'x-btn-text-icon',
                        handler: function(btn) {
                            AddEduClass();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Edit'); ?>",
                        id: 'edit-eduClass-m',
                        tooltip: "<?php __('<b>Edit Classes</b><br />Click here to modify the selected Class'); ?>",
                        icon: 'img/table_edit.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                EditEduClass(sel.data.id);
                            }
                            ;
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Delete'); ?>",
                        id: 'delete-eduClass-m',
                        tooltip: "<?php __('<b>Delete Classes(s)</b><br />Remove the selected Class(s)'); ?>",
                        icon: 'img/table_delete.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelections();
                            if (sm.hasSelection()) {
                                if (sel.length == 1) {
                                    Ext.Msg.show({
                                        title: "<?php __('Remove Class'); ?>",
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: "<?php __('Remove'); ?> " + sel[0].data.name + '?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn) {
                                            if (btn == 'yes') {
                                                DeleteEduClass(sel[0].data.id);
                                            }
                                        }
                                    });
                                } else {
                                    Ext.Msg.show({
                                        title: "<?php __('Remove Class'); ?>",
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: "<?php __('Remove the selected Classes'); ?>?",
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn) {
                                            if (btn == 'yes') {
                                                var sel_ids = '';
                                                for (i = 0; i < sel.length; i++) {
                                                    if (i > 0)
                                                        sel_ids += '_';
                                                    sel_ids += sel[i].data.id;
                                                }
                                                DeleteEduClass(sel_ids);
                                            }
                                        }
                                    });
                                }
                            } else {
                                Ext.Msg.alert("<?php __('Warning'); ?>",
                                    "<?php __('Please select a record first'); ?>");
                            }
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('View'); ?>",
                        id: 'view-eduClass-m',
                        tooltip: "<?php __('<b>View Class</b><br />See details of the selected Class'); ?>",
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
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Manage'); ?>",
                        id: 'manage-eduClass-m',
                        tooltip: "<?php __('<b>Click here Maintain details of the selected Class'); ?>",
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        menu: {
                            items: [{
                                    text: '<?php __('Courses'); ?>',
                                    icon: 'img/table_view.png',
                                    cls: 'x-btn-text-icon',
                                    handler: function(btn) {
                                        var sm = p.getSelectionModel();
                                        var sel = sm.getSelected();
                                        if (sm.hasSelection()) {
                                            ViewParentEduCourses(sel.data.id);
                                        }
                                    }
                                }, {
                                    text: '<?php __('Sections'); ?>',
                                    icon: 'img/table_view.png',
                                    cls: 'x-btn-text-icon',
                                    handler: function(btn) {
                                        var sm = p.getSelectionModel();
                                        var sel = sm.getSelected();
                                        if (sm.hasSelection()) {
                                            OpenClassSectionsDetail(sel.data.id);
                                        }
                                    }
                                }, {
                                    text: '<?php __('Evaluations'); ?>',
                                    icon: 'img/table_view.png',
                                    cls: 'x-btn-text-icon',
                                    handler: function(btn) {
                                        var sm = p.getSelectionModel();
                                        var sel = sm.getSelected();
                                        if (sm.hasSelection()) {
                                            ViewParentEduEvaluations(sel.data.id);
                                        }
                                    }
                                }, {
                                    text: '<?php __('Payment Schedules'); ?>',
                                    icon: 'img/table_view.png',
                                    cls: 'x-btn-text-icon',
                                    handler: function(btn) {
                                        var sm = p.getSelectionModel();
                                        var sel = sm.getSelected();
                                        if (sm.hasSelection()) {
                                            ViewParentEduPaymentSchedules(sel.data.id);
                                        }
                                    }
                                }, {
                                    text: '<?php __('Extra Payments Settings'); ?>',
                                    icon: 'img/table_view.png',
                                    cls: 'x-btn-text-icon',
                                    handler: function(btn) {
                                        var sm = p.getSelectionModel();
                                        var sel = sm.getSelected();
                                        if (sm.hasSelection()) {
                                            ViewParentEduExtraPaymentSettings(sel.data.id);
                                        }
                                    }
                                }
                            ]
                        }
                    }, ' ', '-', '->', {
                        xtype: 'textfield',
                        emptyText: "<?php __('[Search By Name]'); ?>",
                        id: 'eduClass_search_field-m',
                        listeners: {
                            specialkey: function(field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduClassName(Ext.getCmp('eduClass_search_field-m').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('GO'); ?>",
                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
                        id: "eduClass_go_button-m",
                        handler: function() {
                            SearchByEduClassName(Ext.getCmp('eduClass_search_field-m').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('Advanced Search'); ?>",
                        tooltip: "<?php __('<b>Advanced Search...</b><br />Get the advanced search form'); ?>",
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
            p.getTopToolbar().findById('edit-eduClass-m').enable();
            p.getTopToolbar().findById('delete-eduClass-m').enable();
            p.getTopToolbar().findById('view-eduClass-m').enable();
            p.getTopToolbar().findById('manage-eduClass-m').enable();
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit-eduClass-m').disable();
                p.getTopToolbar().findById('view-eduClass-m').disable();
                p.getTopToolbar().findById('manage-eduClass-m').disable();
            }
        });
        p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit-eduClass-m').disable();
                p.getTopToolbar().findById('view-eduClass-m').disable();
                p.getTopToolbar().findById('manage-eduClass-m').disable();
                p.getTopToolbar().findById('delete-eduClass-m').enable();
            }
            else if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('edit-eduClass-m').enable();
                p.getTopToolbar().findById('view-eduClass-m').enable();
                p.getTopToolbar().findById('manage-eduClass-m').enable();
                p.getTopToolbar().findById('delete-eduClass-m').enable();
            }
            else {
                p.getTopToolbar().findById('edit-eduClass-m').disable();
                p.getTopToolbar().findById('view-eduClass-m').disable();
                p.getTopToolbar().findById('manage-eduClass-m').disable();
                p.getTopToolbar().findById('delete-eduClass-m').disable();
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
    