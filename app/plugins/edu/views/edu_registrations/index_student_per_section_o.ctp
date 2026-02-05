//<script>// Students Per Section
    var store_eduRegistrations = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'edu_student', 'edu_section', 'portal_record', 'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_registrations', 'action' => 'list_data_report')); ?>"
        }),
        sortInfo: {field: 'edu_student', direction: "ASC"},
        groupField: 'edu_section'
    });

    var gsection_id = -1;
	
	function editTeacherComment(student_id) {
        Ext.Ajax.request({
			url: "<?php echo $this->Html->url(array('controller' => 'edu_registrations', 'action' => 'edit_teacher_comment')); ?>/"+student_id,
			success: function(response, opts) {
				var eduRegistration_data = response.responseText;
				
				eval(eduRegistration_data);
				
				EditTeacherCommentWindow.show();
			},
			failure: function(response, opts) {
				Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Teacher Comment edit form. Error code'); ?>: " + response.status);
			}
		});
	}

    function DeleteEduRegistration(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_registrations', 'action' => 'delete')); ?>/"+id+"/false",
            success: function(response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Student successfully unregistered from this year registration!'); ?>");
                RefreshEduRegistrationData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Registration delete form. Error code'); ?>: " + response.status);
            }
        });
    }

    function DeleteEduStudent(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_registrations', 'action' => 'delete')); ?>/"+id+"/true",
            success: function(response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Student successfully deleted!'); ?>");
                RefreshEduRegistrationData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Registration delete form. Error code'); ?>: " + response.status);
            }
        });
    }

    function RefreshEduRegistrationData() {
        store_eduRegistrations.reload();
    }
    
    if (center_panel.find('id', 'eduRegistrationStudentPerSection-tab') != "") {
        var p = center_panel.findById('eduRegistrationStudentPerSection-tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Student Per Sec'); ?><sup>o</sup>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduRegistrationStudentPerSection-tab',
            xtype: 'grid',
            store: store_eduRegistrations,
            columns: [
                {header: "<?php __('Student'); ?>", dataIndex: 'edu_student', sortable: true},
                {header: "<?php __('Section'); ?>", dataIndex: 'edu_section', sortable: true},
                {header: "<?php __('In Portal?'); ?>", dataIndex: 'portal_record', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
            ],
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Students" : "Student"]})'
            }),
            listeners: {
                celldblclick: function() {
                    editTeacherComment(Ext.getCmp('eduRegistrationStudentPerSection-tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbsplit',
                        text: "<?php __('Per Student'); ?>",
                        tooltip: "<?php __('<b>Per Student Tasks</b><br />Select from here to perform tasks per student'); ?>",
                        icon: 'img/table_print.png',
                        id: 'btn_per_student',
                        disabled: true,
                        cls: 'x-btn-text-icon',
                        menu : {
                            items: [{
								text: "<?php __('HR Teacher Comment'); ?>",
								icon: 'img/table_view.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									var sm = p.getSelectionModel();
									var sel = sm.getSelected();
									if (sm.hasSelection()) {
										editTeacherComment(sel.data.id);
									}
								}
							}, {
								text: "<?php __('Unregister Student'); ?>",
								icon: 'img/table_delete.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									var sm = p.getSelectionModel();
									var sel = sm.getSelected();
									if (sm.hasSelection()) {
										Ext.Msg.show({
                                            title: "<?php __('Remove Registration'); ?>",
                                            buttons: Ext.MessageBox.YESNO,
                                            msg: "Are you sure to delete registartion of student "+sel.data.edu_student+'?',
                                            icon: Ext.MessageBox.QUESTION,
                                            fn: function(btn){
                                                if (btn == 'yes'){
                                                    DeleteEduRegistration(sel.data.id);
                                                }
                                            }
                                        });
									}
								}
							}, {
								text: "<?php __('Delete Student'); ?>",
								icon: 'img/table_delete.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									var sm = p.getSelectionModel();
									var sel = sm.getSelected();
									if (sm.hasSelection()) {
										Ext.Msg.show({
                                            title: "<?php __('Remove Registration'); ?>",
                                            buttons: Ext.MessageBox.YESNO,
                                            msg: "Are you sure to delete the student "+sel.data.edu_student+' completely?',
                                            icon: Ext.MessageBox.QUESTION,
                                            fn: function(btn){
                                                if (btn == 'yes'){
                                                    DeleteEduStudent(sel.data.id);
                                                }
                                            }
                                        });
									}
								}
							}]
						}
					}, ' ', '-', ' ', "<?php __('Sections'); ?>: ", {
                        xtype: 'combo',
                        emptyText: 'All',
                        store: new Ext.data.ArrayStore({
                            fields: ['id', 'name'],
                            data: [
                                ['0', 'Not Sectioned'],
                                <?php 
                                    $st = false;
									$ss = $sections;
									/*foreach ($sections as $section){
										$c = $section['EduClass']['name'];
										$c = strlen($c) > 1? $c: '0' . $c;
										$ss[$section['EduSection']['id']] = $section['EduAcademicYear']['name'] . ' - ' . $c . ' - ' . $section['EduSection']['name'];
									}*/
									asort($ss);
                                    foreach ($ss as $k => $v){
                                        if($st) echo ",";
                                ?>['<?php echo $k; ?>', '<?php echo $v; ?>']
                                <?php $st = true;}?>
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
