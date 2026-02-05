//<script>
    var store_eduStudents = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'identity_number', 'registration_date', 
                'edu_parent', 'edu_parent_id', 'status'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'list_data')); ?>"
        }),
        sortInfo: {field: 'name', direction: "ASC"},
        groupField: 'status'
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
	
	function EditEduStudent(id) {
		Ext.Ajax.request({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'edit')); ?>/'+id,
			success: function(response, opts) {
				var eduStudent_data = response.responseText;
				
				eval(eduStudent_data);
				
				EduStudentEditWindow.show();
			},
			failure: function(response, opts) {
				Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Student edit form. Error code'); ?>: ' + response.status);
			}
		});
	}
	
	function EditParentDetails(id, stud_id) {
		Ext.Ajax.request({
			url: "<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'index_2_student')); ?>/"+id+"/"+stud_id,
			success: function(response, opts) {
				var parent_eduParentDetails_data = response.responseText;

				eval(parent_eduParentDetails_data);

				parentEduParentDetailsViewWindow.show();
			},
			failure: function(response, opts) {
				Ext.Msg.alert("<?php __('Error'); ?><?php __('Cannot get the Parent Detail view form. Error code'); ?>: " + response.status);
			}
		});
	}
	
	function DeleteStudent(id, reason) {
		Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'delete')); ?>/" + id + "/" + reason,
            success: function(response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Student successfully deleted!'); ?>");
                RefreshEduStudentData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot delete Student. Error code'); ?>: " + response.status);
            }
        });
	}
	
	function ChangeStudentStatus(id) {
		Ext.Ajax.request({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'change_student_status')); ?>/'+id,
			success: function(response, opts) {
				var edu_student_status_data = response.responseText;
				
				eval(edu_student_status_data);
				
				EduChangeStudentStatusWindow.show();
			},
			failure: function(response, opts) {
				Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Student Exemption form. Error code'); ?>: ' + response.status);
			}
		});
	}
	
	function ManageExemptions(id) {
		Ext.Ajax.request({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_exemptions', 'action' => 'index2')); ?>/'+id,
			success: function(response, opts) {
				var eduExemption_data = response.responseText;
				
				eval(eduExemption_data);
				
				parentEduExemptionsViewWindow.show();
			},
			failure: function(response, opts) {
				Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Student Exemption form. Error code'); ?>: ' + response.status);
			}
		});
	}
	
	function UploadEduStudentPhoto(id) {
		Ext.Ajax.request({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'upload_photo')); ?>/'+id,
			success: function(response, opts) {
				var eduStudent_data = response.responseText;
				
				eval(eduStudent_data);
				
				EduStudentUploadPhotoWindow.show();
			},
			failure: function(response, opts) {
				Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Student edit form. Error code'); ?>: ' + response.status);
			}
		});
	}
    
	function SearchByEduStudentName(value) {
        var conditions = '\'OR\' => array(\'EduStudent.identity_number LIKE\' => \'%' + value + '%\', \'EduStudent.name LIKE\' => \'%' + value + '%\')';
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
    
    if (center_panel.find('id', 'eduStudent_o-tab') != "") {
        var p = center_panel.findById('eduStudent_o-tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<font color=#00aa00><?php __('All Students'); ?><sup>o</sup></font>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduStudent-tab_o',
            xtype: 'grid',
            store: store_eduStudents,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('ID No'); ?>", dataIndex: 'identity_number', sortable: true},
                {header: "<?php __('Registration Date'); ?>", dataIndex: 'registration_date', sortable: true},
                {header: "<?php __('Parent'); ?>", dataIndex: 'edu_parent', sortable: true},
                {header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true}
            ],
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Students" : "Student"]})'
            }),
            listeners: {
                celldblclick: function() {
                    ViewEduStudent(Ext.getCmp('eduStudent-tab_o').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: true
            }),
            tbar: new Ext.Toolbar({
                items: [{
						xtype: 'tbbutton',
						text: '<?php __('Manage'); ?>',
						id: 'edit-eduStudent_o',
						tooltip:'<?php __('<b>Manage Student</b><br />Click here to modify the selected Student'); ?>',
						icon: 'img/table_edit.png',
						cls: 'x-btn-text-icon',
						disabled: true,
						menu: {
                            items: [{
								text: '<?php __('Profile'); ?>',
								tooltip:'<?php __('<b>Edit Student</b><br />Click here to modify the selected Student Profile'); ?>',
								icon: 'img/table_edit.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									var sm = p.getSelectionModel();
									var sel = sm.getSelected();
									if (sm.hasSelection()){
										EditEduStudent(sel.data.id);
									}
								}
							}, {
								text: '<?php __('Parent'); ?>',
								tooltip:'<?php __('<b>Change Student Parent</b><br />Click here to modify the selected Student Parent'); ?>',
								icon: 'img/table_edit.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									var sm = p.getSelectionModel();
									var sel = sm.getSelected();
									if (sm.hasSelection()){
										EditParentDetails(sel.data.edu_parent_id, sel.data.id);
									}
								}
							}, {
								text: '<?php __('Status'); ?>',
								tooltip:'<?php __('<b>Change Student Status</b><br />Click here to manage student status for the selected Student'); ?>',
								icon: 'img/table_edit.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									var sm = p.getSelectionModel();
									var sel = sm.getSelected();
									if (sm.hasSelection()){
										ChangeStudentStatus(sel.data.id);
									}
								}
							}, {
								text: '<?php __('Exemptions'); ?>',
								tooltip:'<?php __('<b>Course Exemptions</b><br />Click here to manage course exemptions for the selected Student'); ?>',
								icon: 'img/table_edit.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									var sm = p.getSelectionModel();
									var sel = sm.getSelected();
									if (sm.hasSelection()){
										ManageExemptions(sel.data.id);
									}
								}
							}, '-', {
								text: '<?php __('Payment Preferences'); ?>',
								tooltip:'<?php __('<b>Registration Payment Preferences</b><br />Click here to manage student registration payment preferences for the selected Student'); ?>',
								icon: 'img/table_edit.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									var sm = p.getSelectionModel();
									var sel = sm.getSelected();
									if (sm.hasSelection()){
										//ManageRegPreferences(sel.data.id);
										ShowErrorBox("Ooops! This feature is under development.", "DEV-001-0001");
									}
								}
							}, '-', {
								text: '<?php __('Delete'); ?>',
								tooltip:'<?php __('<b>Delete Student Softly</b><br/>Click here to delete the selected Student'); ?>',
								icon: 'img/table_delete.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									var sm = p.getSelectionModel();
									var sel = sm.getSelected();
									if (sm.hasSelection()){
										Ext.Msg.show({
                                            title: "<?php __('Remove Student'); ?>",
                                            buttons: Ext.MessageBox.YESNO,
                                            msg: "<?php __('Are you sure to delete student '); ?> " + sel.data.name + '?',
                                            icon: Ext.MessageBox.QUESTION,
                                            fn: function (btn) {
                                                if (btn == 'yes') {
                                                    // Prompt for user data and process the result using a callback:
                                                    Ext.Msg.prompt('Reason', 'Please give a good reason:', function(btn, text){
                                                        if (btn == 'ok' && text != ''){
                                                            // process text value and close...
                                                            DeleteStudent(sel.data.id, text);
                                                        } else {
															alert('Student not deleted.');
														}
                                                    });
                                                }
                                            }
                                        });
										//DeleteStudent(sel.data.id);
										//ShowErrorBox("Ooops! This feature is under development.", "DEV-001-0001");
									}
								}
							}]
						}
					}, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Upload'); ?>",
                        id: 'upload_eduStudent_o',
                        tooltip: "<?php __('<b>Upload</b><br />Click here to Upload photo and documents'); ?>",
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        menu: {
                            items: [{
								text: '<?php __('Photo'); ?>',
								icon: 'img/table_edit.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									var sm = p.getSelectionModel();
									var sel = sm.getSelected();
									if (sm.hasSelection()){
										UploadEduStudentPhoto(sel.data.id);
									}
								}
							}, {
								text: '<?php __('Documents'); ?>',
								icon: 'img/table_edit.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									var sm = p.getSelectionModel();
									var sel = sm.getSelected();
									if (sm.hasSelection()){
										//EditEduStudent(sel.data.id);
										ShowErrorBox("Ooops! This feature is under development.", "DEV-001-0001");
									}
								}
							}]
						}
					}/*, ' ', '-', ' ', {
						xtype: 'tbbutton',
						text: '<?php __('Export'); ?>',
						tooltip:'<?php __('<b>Change Student Parent</b><br />Click here to modify the selected Student Parent'); ?>',
						icon: 'img/table_edit.png',
						cls: 'x-btn-text-icon',
						menu: {
                            items: [{
								text: '<?php __('PDF'); ?>',
								icon: 'img/table_edit.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									ShowErrorBox("Ooops! This feature is under development.", "DEV-001-0001");
								}
							}, {
								text: '<?php __('Excel'); ?>',
								icon: 'img/table_edit.png',
								cls: 'x-btn-text-icon',
								handler: function(btn) {
									ShowErrorBox("Ooops! This feature is under development.", "DEV-001-0001");
								}
							}]
						}
					}*/, '->', ' ', '-', "<?php __('Filter'); ?>: ", {
                        xtype: 'combo',
                        emptyText: 'No Filter',
                        store: new Ext.data.ArrayStore({
                            fields: ['id', 'name'],
                            data: [
                                ['-1', 'No Filter'],
								['1', 'With Current Photo'],
								['2', 'Without Current Photo'],
								['3', 'Without Photo']
							]
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
                                        photo_status: combo.getValue()
                                    }
                                });
                            }
                        }
                    }, ' ', '-', "<?php __('Parent'); ?>: ", {
                        xtype: 'combo',
                        emptyText: 'All',
                        store: new Ext.data.ArrayStore({
                            fields: ['id', 'name'],
                            data: [
                                ['-1', 'All'],
    <?php $st = false;
	$primary_parents = array();
	if(isset($edu_parents)) {
	    foreach ($edu_parents as $item) {
		if(count($item['EduParentDetail']) == 0) continue;
		$primary = $item['EduParent']['primary_parent']; // M - Mother, F - Father, G - Guardian
		$primary_parent = $item['EduParentDetail'][0];
		foreach($item['EduParentDetail'] as $pd) {
			if($pd['family_type'] == $primary) {
				$primary_parent = $pd;
				break;
			}
		}
		$primary_parents[$item['EduParent']['id']] = strtoupper(trim($primary_parent['first_name'] . ' ' . $primary_parent['middle_name'] . ' ' . $primary_parent['last_name']));
	    }
	}
	asort($primary_parents);
	
    foreach ($primary_parents as $itemk => $itemv) {
		if ($st) echo ","; ?>['<?php echo $itemk; ?>', '<?php echo $itemv; ?>']<?php $st = true;
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
                    }, ' ', '-', "<?php __('Status'); ?>: ", {
                        xtype: 'combo',
                        emptyText: 'All',
                        store: new Ext.data.ArrayStore({
                            fields: ['id', 'name'],
                            data: [
                                ['-1', 'All'],
                                ['1', 'Active'],
                                ['2', 'Inactive'],
                                ['3', 'Dismissed'],
                                ['4', 'Withdrawn'],
                                ['5', 'Transferred'],
                                ['6', 'Incomplete'],
                                ['7', 'Enrolled but not registered'],
                                ['8', 'Other']
                            ]
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
                                        status: combo.getValue()
                                    }
                                });
                            }
                        }
                    }, ' ', '-', ' Search By: ', {
                        xtype: 'textfield',
                        emptyText: "<?php __('[Name or ID]'); ?>",
                        id: 'eduStudent_search_field_o',
                        listeners: {
                            specialkey: function(field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduStudentName(field.getValue());
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
                            SearchByEduStudentName(Ext.getCmp('eduStudent_search_field_o').getValue());
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
            p.getTopToolbar().findById('edit-eduStudent_o').enable();
            p.getTopToolbar().findById('upload_eduStudent_o').enable();
        });
        p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('edit-eduStudent_o').enable();
                p.getTopToolbar().findById('upload_eduStudent_o').enable();
            }
            else {
                p.getTopToolbar().findById('edit-eduStudent_o').disable();
                p.getTopToolbar().findById('upload_eduStudent_o').disable();
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
