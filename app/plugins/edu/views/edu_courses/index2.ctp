//<script>
    var store_parent_eduCourses = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'edu_class', 'edu_subject', 'description',
                'min_for_pass', 'is_mandatory', 'is_scale_based', 
                'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'list_data', $parent_id)); ?>'})
    });

    function AddParentEduCourse() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'add', $parent_id)); ?>',
            success: function(response, opts) {
                var parent_eduCourse_data = response.responseText;

                eval(parent_eduCourse_data);

                EduCourseAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCourse add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function EditParentEduCourse(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'edit')); ?>/' + id + '/<?php echo $parent_id; ?>',
            success: function(response, opts) {
                var parent_eduCourse_data = response.responseText;

                eval(parent_eduCourse_data);

                EduCourseEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCourse edit form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function ViewEduCourse(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'view')); ?>/' + id,
            success: function(response, opts) {
                var eduCourse_data = response.responseText;

                eval(eduCourse_data);

                EduCourseViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCourse view form. Error code'); ?>: ' + response.status);
            }
        });
    }
    
    function ViewEduCourseEduOutlines(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_outlines', 'action' => 'index2')); ?>/' + id,
            success: function(response, opts) {
                var parent_eduAssessments_data = response.responseText;

                eval(parent_eduAssessments_data);

                parentEduOutlinesViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Outlines view form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function ViewEduCourseEduAssessments(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_assessments', 'action' => 'index2')); ?>/' + id,
            success: function(response, opts) {
                var parent_eduAssessments_data = response.responseText;

                eval(parent_eduAssessments_data);

                parentEduAssessmentsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function ViewEduCourseEduAssignments(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_assignments', 'action' => 'index2')); ?>/' + id,
            success: function(response, opts) {
                var parent_eduAssignments_data = response.responseText;

                eval(parent_eduAssignments_data);

                parentEduAssignmentsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function MaintainCourseItems(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_course_items', 'action' => 'index2')); ?>/" + id,
            success: function (response, opts) {
                var parent_eduCourseItems_data = response.responseText;

                eval(parent_eduCourseItems_data);

                parentEduCourseItemsViewWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?><?php __('Cannot get the Course Item Maintenance form. Error code'); ?>: " + response.status);
            }
        });
    }

    function DeleteParentEduCourse(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'delete')); ?>/' + id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Course(s) successfully deleted!'); ?>');
                RefreshParentEduCourseData();
            },
            failure: function(response, opts) {
                var obj = Ext.decode(Ext.decode(JSON.stringify(response)).responseText);
                ShowErrorBox(obj.errormsg, obj.helpcode);
            }
        });
    }

    function SearchByParentEduCourseName(value) {
        var conditions = '\'EduCourse.description LIKE\' => \'%' + value + '%\'';
        store_parent_eduCourses.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }
    
    function ViewAuditTrailForCourse(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'audit_trails', 'action' => 'index2', 'plugin' => '')); ?>/'+id+'/EduCourse',
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

    function RefreshParentEduCourseData() {
        store_parent_eduCourses.reload();
    }



    var g = new Ext.grid.GridPanel({
        title: '<?php __('Courses'); ?>',
        store: store_parent_eduCourses,
        loadMask: true,
        stripeRows: true,
        height: 300,
        anchor: '100%',
        id: 'eduCourseGrid',
        columns: [
            {header: "<?php __('Class'); ?>", dataIndex: 'edu_class', sortable: true, hidden: true},
            {header: "<?php __('Subject'); ?>", dataIndex: 'edu_subject', sortable: true},
            {header: "<?php __('Description'); ?>", dataIndex: 'description', sortable: true},
            {header: "<?php __('Min. Mark to Pass'); ?>", dataIndex: 'min_for_pass', align: 'right', sortable: true},
            {header: "<?php __('Mandatory?'); ?>", dataIndex: 'is_mandatory', sortable: true},
            {header: "<?php __('Scale Based?'); ?>", dataIndex: 'is_scale_based', sortable: true},
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
                        var rec = store_parent_eduCourses.getAt(rowIndex);
                        ViewEduCourse(rec.get('id'));
                    }
                }, ' ', ' ', ' ', ' ', ' ', '-', ' ', ' ', ' ', ' ', ' ', {
                    icon   : 'img/at.png',
                    tooltip: 'Audit Trail',
                    handler: function(grid, rowIndex, colIndex) {
                        var rec = store_parent_eduCourses.getAt(rowIndex);
                        ViewAuditTrailForCourse(rec.get('id'));
                    }
                }]
            }
        ],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
        }),
        viewConfig: {
            forceFit: true
        },
        listeners: {
            celldblclick: function() {
                ViewEduCourse(Ext.getCmp('eduCourseGrid').getSelectionModel().getSelected().data.id);
            }
        },
        tbar: new Ext.Toolbar({
            items: [{
                    xtype: 'tbbutton',
                    text: '<?php __('Add'); ?>',
                    tooltip: '<?php __('<b>Add Course</b><br />Click here to create a new EduCourse'); ?>',
                    icon: 'img/table_add.png',
                    cls: 'x-btn-text-icon',
                    handler: function(btn) {
                        AddParentEduCourse();
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Edit'); ?>',
                    id: 'edit-parent-eduCourse',
                    tooltip: '<?php __('<b>Edit Course</b><br />Click here to modify the selected Course'); ?>',
                    icon: 'img/table_edit.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()) {
                            EditParentEduCourse(sel.data.id);
                        }
                        ;
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Delete'); ?>',
                    id: 'delete-parent-eduCourse',
                    tooltip: '<?php __('<b>Delete Course(s)</b><br />Click here to remove the selected Course(s)'); ?>',
                    icon: 'img/table_delete.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelections();
                        if (sm.hasSelection()) {
                            if (sel.length == 1) {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Course'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove'); ?> ' + sel[0].data.description + '?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn) {
                                        if (btn == 'yes') {
                                            DeleteParentEduCourse(sel[0].data.id);
                                        }
                                    }
                                });
                            } else {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Course'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove the selected Courses'); ?>?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn) {
                                        if (btn == 'yes') {
                                            var sel_ids = '';
                                            for (i = 0; i < sel.length; i++) {
                                                if (i > 0)
                                                    sel_ids += '_';
                                                sel_ids += sel[i].data.id;
                                            }
                                            DeleteParentEduCourse(sel_ids);
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
                    xtype: 'tbbutton',
                    text: '<?php __('View Course'); ?>',
                    id: 'view-eduCourse2',
                    tooltip: '<?php __('<b>View Course</b><br />Click here to see details of the selected Course'); ?>',
                    icon: 'img/table_view.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()) {
                            ViewEduCourse(sel.data.id);
                        }
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Manage Outlines'); ?>',
                    icon: 'img/table_view.png',
                    id: 'manage-eduOutlines',
                    disabled: true,
                    cls: 'x-btn-text-icon',
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()) {
                            ViewEduCourseEduOutlines(sel.data.id);
                        }
                    }
                }, {
                    xtype: 'tbbutton',
                    text: '<?php __('Manage Course Items'); ?>',
                    icon: 'img/table_view.png',
                    id: 'manage-eduCourseItems',
                    disabled: true,
                    cls: 'x-btn-text-icon',
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()) {
                            MaintainCourseItems(sel.data.id);
                        }
                    }
                }, ' ', '->', {
                    xtype: 'textfield',
                    emptyText: '<?php __('[Search By Name]'); ?>',
                    id: 'parent_eduCourse_search_field',
                    listeners: {
                        specialkey: function(field, e) {
                            if (e.getKey() == e.ENTER) {
                                SearchByParentEduCourseName(Ext.getCmp('parent_eduCourse_search_field').getValue());
                            }
                        }
                    }
                }, {
                    xtype: 'tbbutton',
                    icon: 'img/search.png',
                    cls: 'x-btn-text-icon',
                    text: 'GO',
                    tooltip: '<?php __('<b>GO</b><br />Click here to get search results'); ?>',
                    id: 'parent_eduCourse_go_button',
                    handler: function() {
                        SearchByParentEduCourseName(Ext.getCmp('parent_eduCourse_search_field').getValue());
                    }
                }, ' '
            ]}),
        bbar: new Ext.PagingToolbar({
            pageSize: list_size,
            store: store_parent_eduCourses,
            displayInfo: true,
            displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
            beforePageText: '<?php __('Page'); ?>',
            afterPageText: '<?php __('of {0}'); ?>',
            emptyMsg: '<?php __('No data to display'); ?>'
        })
    });
    g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
        g.getTopToolbar().findById('edit-parent-eduCourse').enable();
        g.getTopToolbar().findById('delete-parent-eduCourse').enable();
        g.getTopToolbar().findById('view-eduCourse2').enable();
        g.getTopToolbar().findById('manage-eduOutlines').enable();
        g.getTopToolbar().findById('manage-eduCourseItems').enable();
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('edit-parent-eduCourse').disable();
            g.getTopToolbar().findById('view-eduCourse2').disable();
            g.getTopToolbar().findById('manage-eduOutlines').disable(); 
            g.getTopToolbar().findById('manage-eduCourseItems').disable(); 
        }
    });
    g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('edit-parent-eduCourse').disable();
            g.getTopToolbar().findById('delete-parent-eduCourse').enable();
            g.getTopToolbar().findById('view-eduCourse2').disable();
            g.getTopToolbar().findById('manage-eduOutlines').disable();
            g.getTopToolbar().findById('manage-eduCourseItems').disable();
        }
        else if (this.getSelections().length == 1) {
            g.getTopToolbar().findById('edit-parent-eduCourse').enable();
            g.getTopToolbar().findById('delete-parent-eduCourse').enable();
            g.getTopToolbar().findById('view-eduCourse2').enable();
            g.getTopToolbar().findById('manage-eduOutlines').enable();
            g.getTopToolbar().findById('manage-eduCourseItems').enable();
        }
        else {
            g.getTopToolbar().findById('edit-parent-eduCourse').disable();
            g.getTopToolbar().findById('delete-parent-eduCourse').disable();
            g.getTopToolbar().findById('view-eduCourse2').disable();
            g.getTopToolbar().findById('manage-eduOutlines').disable();
            g.getTopToolbar().findById('manage-eduCourseItems').disable();
        }
    });



    var parentEduCoursesViewWindow = new Ext.Window({
        title: 'Course Under the selected Class',
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
                    parentEduCoursesViewWindow.close();
                }
            }]
    });

    store_parent_eduCourses.load({
        params: {
            start: 0,
            limit: list_size
        }
    });
