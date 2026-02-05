//<script>
    var store_eduStudents = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'birth_date', 'registration_date', 'edu_parent', 'user', 'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'list_data')); ?>"
        }),
        sortInfo: {field: 'name', direction: "ASC"},
        groupField: 'registration_date'
    });

    function AddEduStudent() {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'enrollment')); ?>",
            success: function(response, opts) {
                var eduStudent_data = response.responseText;
                eval(eduStudent_data);
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Student enrollment form. Error code'); ?>: " + response.status);
            }
        });
    }

    function EditEduStudent(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'edit')); ?>/" + id,
            success: function(response, opts) {
                var eduStudent_data = response.responseText;

                eval(eduStudent_data);

                EduStudentEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Student edit form. Error code'); ?>: " + response.status);
            }
        });
    }

    function ViewEduStudent(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'view')); ?>/" + id,
            success: function(response, opts) {
                var eduStudent_data = response.responseText;

                eval(eduStudent_data);

                EduStudentViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Student view form. Error code'); ?>: " + response.status);
            }
        });
    }

    function ViewParentEduRegistrations(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_registrations', 'action' => 'index2')); ?>/" + id,
            success: function(response, opts) {
                var parent_eduRegistrations_data = response.responseText;

                eval(parent_eduRegistrations_data);

                parentEduRegistrationsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?><?php __('Cannot get the registration view form. Error code'); ?>: " + response.status);
            }
        });
    }

    function DeleteEduStudent(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'delete')); ?>/" + id,
            success: function(response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Student successfully deleted!'); ?>");
                RefreshEduStudentData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Student add form. Error code'); ?>: " + response.status);
            }
        });
    }

    function SearchEduStudent() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'search')); ?>',
            success: function(response, opts) {
                var eduStudent_data = response.responseText;

                eval(eduStudent_data);

                eduStudentSearchWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Student search form. Error Code'); ?>: " + response.status);
            }
        });
    }

    function SearchByEduStudentName(value) {
        var conditions = '\'EduStudent.name LIKE\' => \'%' + value + '%\'';
        store_eduStudents.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshEduStudentData() {
        store_eduStudents.reload();
    }
    
    function showMenu(grid, index, event) {
        event.stopEvent();
        var record = grid.getStore().getAt(index);
        var menu = new Ext.menu.Menu({
            items: [{
                    text: '<b>Details of ' + record.get('name') + '</b>',
                    icon: 'img/table_view.png',
                    handler: function() {
                        ViewEduStudent(record.get('id'));
                    }
                }, '-', {
                    text: 'Edit Student Info',
                    icon: 'img/table_edit.png',
                    handler: function() {
                        EditEduStudent(record.get('id'));
                    }
                }, {
                    text: 'Edit Parent Info',
                    icon: 'img/table_view.png',
                    handler: function() {
                        EditEduParent(record.get('id'));
                    }
                }, {
                    text: 'Manage Registrations',
                    icon: 'img/table_view.png',
                    handler: function() {
                        ViewParentEduRegistrations(record.get('id'));
                    }
                }
            ]
        }).showAt(event.xy);
    }


    if (center_panel.find('id', 'eduStudent-tab') != "") {
        var p = center_panel.findById('eduStudent-tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Students'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduStudent-tab',
            xtype: 'grid',
            store: store_eduStudents,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Birth Date'); ?>", dataIndex: 'birth_date', sortable: true},
                {header: "<?php __('Registration Date'); ?>", dataIndex: 'registration_date', sortable: true},
                {header: "<?php __('Parent'); ?>", dataIndex: 'edu_parent', sortable: true},
                {header: "<?php __('Username'); ?>", dataIndex: 'user', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
            ],
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduStudents" : "EduStudent"]})'
            }),
            listeners: {
                celldblclick: function() {
                    ViewEduStudent(Ext.getCmp('eduStudent-tab').getSelectionModel().getSelected().data.id);
                },
                'rowcontextmenu': function(grid, index, event) {
                    showMenu(grid, index, event);
                    this.getSelectionModel().selectRow(index);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: "<?php __('Enrollment'); ?>",
                        tooltip: "<?php __('<b>Enroll Student</b><br />Click here to enroll a new Student'); ?>",
                        icon: 'img/table_add.png',
                        cls: 'x-btn-text-icon',
                        handler: function(btn) {
                            AddEduStudent();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Edit'); ?>",
                        id: 'edit-eduStudent',
                        tooltip: "<?php __('<b>Edit Student</b><br />Click here to modify the selected Student'); ?>",
                        icon: 'img/table_edit.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                EditEduStudent(sel.data.id);
                            }
                            ;
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Delete'); ?>",
                        id: 'delete-eduStudent',
                        tooltip: "<?php __('<b>Delete Student(s)</b><br />Click here to remove the selected Student(s)'); ?>",
                        icon: 'img/table_delete.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelections();
                            if (sm.hasSelection()) {
                                if (sel.length == 1) {
                                    Ext.Msg.show({
                                        title: "<?php __('Remove Student'); ?>",
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: "<?php __('Remove'); ?> " + sel[0].data.name + '?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn) {
                                            if (btn == 'yes') {
                                                DeleteEduStudent(sel[0].data.id);
                                            }
                                        }
                                    });
                                } else {
                                    Ext.Msg.show({
                                        title: "<?php __('Remove Student'); ?>",
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: "<?php __('Remove the selected Students'); ?>?",
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn) {
                                            if (btn == 'yes') {
                                                var sel_ids = '';
                                                for (i = 0; i < sel.length; i++) {
                                                    if (i > 0)
                                                        sel_ids += '_';
                                                    sel_ids += sel[i].data.id;
                                                }
                                                DeleteEduStudent(sel_ids);
                                            }
                                        }
                                    });
                                }
                            } else {
                                Ext.Msg.alert("<?php __('Warning'); ?>", "<?php __('Please select a record first'); ?>");
                            }
                            ;
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbsplit',
                        text: "<?php __('View Student'); ?>",
                        id: 'view-eduStudent',
                        tooltip: "<?php __('<b>View Student</b><br />Click here to see details of the selected Student'); ?>",
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewEduStudent(sel.data.id);
                            }
                        },
                        menu: {
                            items: [{
                                    text: '<?php __('Manage Registrations'); ?>',
                                    icon: 'img/table_view.png',
                                    cls: 'x-btn-text-icon',
                                    handler: function(btn) {
                                        var sm = p.getSelectionModel();
                                        var sel = sm.getSelected();
                                        if (sm.hasSelection()) {
                                            ViewParentEduRegistrations(sel.data.id);
                                        }
                                    }
                                }, {
                                    text: '<?php __('Enrollment Certificate'); ?>',
                                    icon: 'img/table_view.png',
                                    cls: 'x-btn-text-icon',
                                    handler: function(btn) {
                                        var sm = p.getSelectionModel();
                                        var sel = sm.getSelected();
                                        if (sm.hasSelection()) {
                                            printEnrollmentCertificate(sel.data.id);
                                        }
                                    }
                                }
                            ]
                        }
                    }, ' ', '-', "<?php __('Parent'); ?>: ", {
                        xtype: 'combo',
                        emptyText: 'All',
                        store: new Ext.data.ArrayStore({
                            fields: ['id', 'name'],
                            data: [
                                ['-1', 'All'],
    <?php $st = false;
    foreach ($edu_parents as $item) {
        if ($st) echo ","; ?>['<?php echo $item['EduParent']['id']; ?>', '<?php echo $item['EduParentDetail'][0]['first_name']; ?>']<?php $st = true;
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
                                store_eduStudents.reload({
                                    params: {
                                        start: 0,
                                        limit: list_size,
                                        edu_parent_id: combo.getValue()
                                    }
                                });
                            }
                        }
                    }, '->', {
                        xtype: 'textfield',
                        emptyText: "<?php __('[Search By Name]'); ?>",
                        id: 'eduStudent_search_field',
                        listeners: {
                            specialkey: function(field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduStudentName(Ext.getCmp('eduStudent_search_field').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('GO'); ?>",
                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
                        id: "eduStudent_go_button",
                        handler: function() {
                            SearchByEduStudentName(Ext.getCmp('eduStudent_search_field').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('Advanced Search'); ?>",
                        tooltip: "<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
                        handler: function() {
                            SearchEduStudent();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_eduStudents,
                displayInfo: true,
                displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
                beforePageText: "<?php __('Page'); ?>",
                afterPageText: "<?php __('of {0}'); ?>",
                emptyMsg: "<?php __('No data to display'); ?>"
            })
        });
        p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            p.getTopToolbar().findById('edit-eduStudent').enable();
            p.getTopToolbar().findById('delete-eduStudent').enable();
            p.getTopToolbar().findById('view-eduStudent').enable();
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit-eduStudent').disable();
                p.getTopToolbar().findById('view-eduStudent').disable();
            }
        });
        p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit-eduStudent').disable();
                p.getTopToolbar().findById('view-eduStudent').disable();
                p.getTopToolbar().findById('delete-eduStudent').enable();
            }
            else if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('edit-eduStudent').enable();
                p.getTopToolbar().findById('view-eduStudent').enable();
                p.getTopToolbar().findById('delete-eduStudent').enable();
            }
            else {
                p.getTopToolbar().findById('edit-eduStudent').disable();
                p.getTopToolbar().findById('view-eduStudent').disable();
                p.getTopToolbar().findById('delete-eduStudent').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_eduStudents.load({
            params: {
                start: 0,
                limit: list_size
            }
        });
    }
    
    var popUpWin_EnrollmentCertificate=0;
	
    function popUpWindowEnrollmentCertificate(URLStr, left, top, width, height) {
        if(popUpWin_EnrollmentCertificate){
            if(!popUpWin_EnrollmentCertificate.closed) popUpWin_EnrollmentCertificate.close();
        }
        popUpWin_EnrollmentCertificate = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
    }

    function printEnrollmentCertificate(id) {
        url = "<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'enrollment_certificate', 'plugin' => 'edu')); ?>/" + id;
        popUpWindowEnrollmentCertificate(url, 200, 200, 700, 1000);
    }