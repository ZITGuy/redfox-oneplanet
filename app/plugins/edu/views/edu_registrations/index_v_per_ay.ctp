//<script>
    var store_eduStudents = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'identity_number', 'registration_date', 
                'status', 'edu_class', 'edu_section'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_registrations', 'action' => 'list_data_students_per_ay')); ?>"
        }),
        sortInfo: {field: 'name', direction: "ASC"},
        groupField: 'edu_class'
    });

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
    
    if (center_panel.find('id', 'eduStudentRegistration-tab') != "") {
        var p = center_panel.findById('eduStudentRegistration-tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Students per AY'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduStudentRegistration-tab',
            xtype: 'grid',
            store: store_eduStudents,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('ID No'); ?>", dataIndex: 'identity_number', sortable: true},
                {header: "<?php __('Registration Date'); ?>", dataIndex: 'registration_date', sortable: true},
				{header: "<?php __('Class / Grade'); ?>", dataIndex: 'edu_class', sortable: true},
                {header: "<?php __('Section'); ?>", dataIndex: 'edu_section', sortable: true},
                {header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true},
                {
                    header:'<?php __('Actions'); ?>',
                    xtype: 'actioncolumn',
                    width: 50,
                    items: [{
                        icon   : 'img/pdf.png',  // Use a URL in the icon config
                        tooltip: 'View Profile',
                        handler: function(grid, rowIndex, colIndex) {
                            var rec = store_eduStudents.getAt(rowIndex);
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
                    ViewEduStudent(Ext.getCmp('eduStudentRegistration-tab').getSelectionModel().getSelected().data.id);
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
                                ViewEduStudent(sel.data.id);
                            }
                        }
                    }, '->', ' ', '-', "<?php __('Academic Year'); ?>: ", {
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
                                store_eduStudents.reload({
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