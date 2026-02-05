//<script>
    var store_eduStudent_enrollments = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'identity_number', 'registration_date', 
                'edu_parent', 'status', 'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'list_data_students_per_ay')); ?>"
        }),
        sortInfo: {field: 'name', direction: "ASC"},
        groupField: 'registration_date'
    });

    function ViewEduStudentEnrollment(id) {
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

    function SearchEduStudentEnrollment() {
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

    function SearchByEduStudentNameEnrollment(value) {
        var conditions = '\'EduStudent.name LIKE\' => \'%' + value + '%\'';
        store_eduStudent_enrollments.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }
    
    if (center_panel.find('id', 'eduStudentRegistrationEnrollment-tab') != "") {
        var p = center_panel.findById('eduStudentRegistrationEnrollment-tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Enrolled Students per AY'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduStudentRegistrationEnrollment-tab',
            xtype: 'grid',
            store: store_eduStudent_enrollments,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('ID No'); ?>", dataIndex: 'identity_number', sortable: true},
                {header: "<?php __('Registration Date'); ?>", dataIndex: 'registration_date', sortable: true},
                {header: "<?php __('Parent'); ?>", dataIndex: 'edu_parent', sortable: true},
                {header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true},
                {
                    header:'<?php __('Actions'); ?>',
                    xtype: 'actioncolumn',
                    width: 50,
                    items: [{
                        icon   : 'img/pdf.png',  // Use a URL in the icon config
                        tooltip: 'View Profile',
                        handler: function(grid, rowIndex, colIndex) {
                            var rec = store_eduStudent_enrollments.getAt(rowIndex);
                            ViewEduStudentPDF(rec.get('id'));
                        }
                    }]
                }
            ],
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Students" : "Student"]})'
            }),
            listeners: {
                celldblclick: function() {
                    ViewEduStudentEnrollment(Ext.getCmp('eduStudentRegistrationEnrollment-tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: true
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: "<?php __('View Profile'); ?>",
                        id: 'view-eduStudent',
                        tooltip: "<?php __('<b>View Student Profile</b><br />Click here to see details of the selected Student'); ?>",
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewEduStudentEnrollment(sel.data.id);
                            }
                        }
                    }, '->', ' ', '-', "<?php __('Academic Year/Batch'); ?>: ", {
                        xtype: 'combo',
                        emptyText: 'All',
                        store: new Ext.data.ArrayStore({
                            fields: ['id', 'name'],
                            data: [
                                ['-1', 'All'],
    <?php $st = false;
    foreach ($edu_academic_years as $item) {
        if ($st) echo ","; ?>['<?php echo $item['EduAcademicYear']['id']; ?>', '<?php echo $item['EduAcademicYear']['name']; ?>']<?php $st = true;
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
                                store_eduStudent_enrollments.reload({
                                    params: {
                                        start: 0,
                                        limit: list_size,
                                        edu_academic_year_id: combo.getValue()
                                    }
                                });
                            }
                        }
                    }, ' ', '-', ' Search By: ', {
                        xtype: 'textfield',
                        emptyText: "<?php __('[Name or ID]'); ?>",
                        id: 'eduStudent_search_field',
                        listeners: {
                            specialkey: function(field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduStudentNameEnrollment(Ext.getCmp('eduStudent_search_field').getValue());
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
                            SearchByEduStudentNameEnrollment(Ext.getCmp('eduStudent_search_field').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('Advanced Search'); ?>",
                        tooltip: "<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
                        handler: function() {
                            SearchEduStudentEnrollment();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_eduStudent_enrollments,
                displayInfo: true,
                displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
                beforePageText: "<?php __('Page'); ?>",
                afterPageText: "<?php __('of {0}'); ?>",
                emptyMsg: "<?php __('No data to display'); ?>"
            })
        });
        p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            p.getTopToolbar().findById('view-eduStudent').enable();
        });
        p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            if (this.getSelections().length > 0) {
                p.getTopToolbar().findById('view-eduStudent').enable();
            }
            else {
                p.getTopToolbar().findById('view-eduStudent').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_eduStudent_enrollments.load({
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