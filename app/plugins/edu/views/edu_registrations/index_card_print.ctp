//<script>// Students Per Section
    var store_eduRegistrations = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'edu_student', 'edu_section', 'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array(
                'controller' => 'edu_registrations', 'action' => 'list_data_report')); ?>"
        }),
        sortInfo: {field: 'edu_student', direction: "ASC"},
        groupField: 'edu_section',
        listeners: {
            'load': function() {
                if(store_eduRegistrations.getRange().length > 0){
                    Ext.getCmp('btn_print_card').enable();
                    Ext.getCmp('btn_print_id_card').enable();
                    Ext.getCmp('btn_print_roster').enable();
                } else {
                    Ext.getCmp('btn_print_card').disable();
					Ext.getCmp('btn_print_id_card').disable();
                    Ext.getCmp('btn_print_roster').disable();
                }
            }
        }
    });

    var popUpWin_1=0;
    var gsection_id = -1;
    
    function popUpWindow(URLStr, left, top, width, height) {
        if(popUpWin_1){
            if(!popUpWin_1.closed) popUpWin_1.close();
        }
        popUpWin_1 = open(URLStr, 'popUpWin',
            'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,'+
            'resizable=yes,copyhistory=yes,width='+width+',height='+height+',left='+left+
            ', top='+top+',screenX='+left+',screenY='+top+'');
    }

    function PrintCards(mode) {
        url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_registrations', 'action' => 'print_cards')); ?>/" + gsection_id + '/' + mode;

        popUpWindow(url, 0, 0, 1200, 1200);
    }
	
	function PrintAchievementReport(student_id) {
        url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_registrations', 'action' => 'print_achievement_report')); ?>/" + student_id;

        popUpWindow(url, 0, 0, 800, 1200);
    }

    function PrintQuarterAttendance() {
        url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_registrations', 'action' => 'student_quarter_attendance_list')); ?>/" +
            gsection_id;
        
        popUpWindow(url, 0, 0, 1200, 1200);
    }

    function PrintCardForStudent(mode, student_id) {
        url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_registrations', 'action' => 'print_cards')); ?>/" +
            gsection_id + '/' + mode + '/' + student_id;

        popUpWindow(url, 0, 0, 1200, 1200);
    }
	
	function PrintTranscriptForStudent(mode, student_id) {
        url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_registrations', 'action' => 'print_cards')); ?>/" +
            gsection_id + '/' + mode + '/' + student_id;

        popUpWindow(url, 0, 0, 1200, 1200);
    }
	
	function PrintRoster() {
        url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_registrations', 'action' => 'print_roster')); ?>/" + gsection_id + "/PDF";
		
        popUpWindow(url, 0, 0, 1200, 1200);
    }

    function PrintRosterHTML() {
        url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_registrations', 'action' => 'print_roster')); ?>/" + gsection_id + "/HTML";
		
        popUpWindow(url, 0, 0, 1200, 1200);
    }

    function PrintRosterExcel() {
        url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_registrations', 'action' => 'print_roster')); ?>/" + gsection_id + "/EXCEL";
		
        popUpWindow(url, 0, 0, 1200, 1200);
    }

    function PrintStudents() {
        url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_registrations', 'action' => 'print_students_per_section')); ?>/" + gsection_id;
		
        popUpWindow(url, 0, 0, 1200, 1200);
    }

    function RefreshEduRegistrationData() {
        store_eduRegistrations.reload();
    }
    
    if (center_panel.find('id', 'eduRegistrationCardPrint-tab') != "") {
        var p = center_panel.findById('eduRegistrationCardPrint-tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Students Per Section'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduRegistration-tab',
            xtype: 'grid',
            store: store_eduRegistrations,
            columns: [
                {header: "<?php __('Student'); ?>", dataIndex: 'edu_student', sortable: true},
                {header: "<?php __('Section'); ?>", dataIndex: 'edu_section', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
            ],
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Students" : "Student"]})'
            }),
            listeners: {
                celldblclick: function() {
                    ViewEduRegistration(
                        Ext.getCmp('eduRegistrationCardPrint-tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbsplit',
                        text: "<?php __('Report Cards'); ?>",
                        tooltip: "<?php __('<b>Print Grade Report Cards</b><br />Click here to Print Cards'); ?>",
                        icon: 'img/layout_images/document.png',
                        id: 'btn_print_card',
                        disabled: true,
                        cls: 'x-btn-text-icon',
                        menu : {
                            items: [{
                                text: '<?php __('Inner Report Card Pages'); ?>',
                                icon: 'img/table_view.png',
                                cls: 'x-btn-text-icon',
                                handler: function(btn) {
                                    PrintCards('inner');
                                }
                            }, {
                                text: '<?php __('Outer Report Card Pages'); ?>',
                                icon: 'img/table_view.png',
                                cls: 'x-btn-text-icon',
                                handler: function(btn) {
                                    PrintCards('outer');
                                }
                            }]
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('ID Cards'); ?>",
                        tooltip: "<?php __('<b>Print ID Cards</b><br/>Click here to Print Cards for the students'); ?>",
                        icon: 'img/layout_images/registration.png',
                        id: 'btn_print_id_card',
                        disabled: true,
                        cls: 'x-btn-text-icon',
                        handler: function(btn) {
                            PrintIDCards();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbsplit',
                        text: "<?php __('Roster'); ?>",
                        tooltip: "<?php __('<b>Per Student Tasks</b><br />Select to perform tasks per student'); ?>",
                        icon: 'img/table_print.png',
                        id: 'btn_print_roster',
                        disabled: true,
                        cls: 'x-btn-text-icon',
                        menu : {
                            items: [{
								text: "<?php __('HTML'); ?>",
								icon: 'img/table_view.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									PrintRosterHTML();
								}
							}, {
								text: "<?php __('PDF'); ?>",
								icon: 'img/table_view.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									PrintRoster();
								}
							}, {
								text: "<?php __('EXCEL'); ?>",
								icon: 'img/table_view.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									PrintRosterExcel();
								}
							}]
						}
					}, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Sheet'); ?>",
                        tooltip: "<?php __('<b>Print per Section</b><br />Click here to Print the students'); ?>",
                        icon: 'img/table_view.png',
                        id: 'btn_print_students',
                        disabled: true,
                        cls: 'x-btn-text-icon',
                        handler: function(btn) {
                            PrintStudents();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Attendance List'); ?>",
                        tooltip: "<?php __('<b>Print per Section</b><br />Click here to Print'); ?>",
                        icon: 'img/table_view.png',
                        id: 'btn_print_quarter_attendance',
                        cls: 'x-btn-text-icon',
                        handler: function(btn) {
                            PrintQuarterAttendance();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbsplit',
                        text: "<?php __('Per Student'); ?>",
                        tooltip: "<?php __('<b>Per Student Tasks</b><br />Select to perform tasks per student'); ?>",
                        icon: 'img/table_print.png',
                        id: 'btn_per_student',
                        disabled: true,
                        cls: 'x-btn-text-icon',
                        menu : {
                            items: [{
								text: "<?php __('Report Card'); ?>",
								icon: 'img/table_view.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									var sm = p.getSelectionModel();
									var sel = sm.getSelected();
									if (sm.hasSelection()) {
										PrintCardForStudent('inner', sel.data.id);
									}
								}
							}, {
                                text: '<?php __('Student Achievement Report'); ?>',
                                icon: 'img/table_view.png',
                                cls: 'x-btn-text-icon',
                                handler: function(btn) {
									var sm = p.getSelectionModel();
									var sel = sm.getSelected();
									if (sm.hasSelection()) {
										PrintAchievementReport(sel.data.id);
									}
                                }
                            }, {
								text: "<?php __('Transcript'); ?>",
								icon: 'img/table_view.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									var sm = p.getSelectionModel();
									var sel = sm.getSelected();
									if (sm.hasSelection()) {
										PrintTranscriptForStudent(sel.data.id);
									}
								}
							}, {
								text: "<?php __('ID Card'); ?>",
								icon: 'img/table_view.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									var sm = p.getSelectionModel();
									var sel = sm.getSelected();
									if (sm.hasSelection()) {
										ViewEduRegistration(sel.data.id);
									}
								}
							}]
						}
					}, ' ', '-', '-', ' ', "<?php __('Sections'); ?>: ", {
                        xtype: 'combo',
                        emptyText: 'All',
                        store: new Ext.data.ArrayStore({
                            fields: ['id', 'name'],
                            data: [
                                ['0', 'Not Sectioned'],
                                <?php
                                    $st = false;
									$ss = array();
									foreach ($sections as $section) {
										$c = $section['EduClass']['name'];
										$ss[$section['EduSection']['id']] =
                                            $section['EduAcademicYear']['name'] . ' - ' . $c .
                                            ' - ' . $section['EduSection']['name'];
									}
									asort($ss);
                                    foreach ($ss as $k => $v) { if ($st) { echo ",";} ?>
                                        ['<?php echo $k; ?>', '<?php echo $v; ?>']
                                        <?php $st = true;
                                    } ?>
                            ]
                        }),
                        displayField: 'name',
                        valueField: 'id',
                        mode: 'local',
                        value: '0',
                        disableKeyFilter: true,
                        triggerAction: 'all',
                        listeners: {
                            select: function(combo, record, index) {
                                if(combo.getValue() != -1){
                                    gsection_id = combo.getValue();
                                } else {
                                    gsection_id = combo.getValue();
                                }
                                store_eduRegistrations.reload({
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
                store: store_eduRegistrations,
                displayInfo: true,
                displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
                beforePageText: "<?php __('Page'); ?>",
                afterPageText: "<?php __('of {0}'); ?>",
                emptyMsg: "<?php __('No data to display'); ?>"
            })
        });
        p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('btn_per_student').enable();
            }
            else {
                p.getTopToolbar().findById('btn_per_student').disable();
            }
        });
        p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('btn_per_student').enable();
            }
            else {
                p.getTopToolbar().findById('btn_per_student').disable();
            }
        });
        center_panel.setActiveTab(p);

    }