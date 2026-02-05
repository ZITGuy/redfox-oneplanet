//<script>
    var store_eduTeachers_o = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'teacher', 'identity_number', 'telephone', 'mobile'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'list_data')); ?>"
        }),
        sortInfo: {field: 'teacher', direction: "ASC"}
    });

    function EditEduTeacherO(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'edit')); ?>/" + id,
            success: function (response, opts) {
                var eduTeacher_data = response.responseText;

                eval(eduTeacher_data);

                EduTeacherEditWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Teacher edit form. Error code'); ?>: " + response.status);
            }
        });
    }

    function ViewEduTeacherO(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'view')); ?>/" + id,
            success: function (response, opts) {
                var eduTeacher_data = response.responseText;

                eval(eduTeacher_data);

                EduTeacherViewWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Teacher view form. Error code'); ?>: " + response.status);
            }
        });
    }
	
	function ViewEduTeacherTraining(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_teachers_trainings', 'action' => 'index2')); ?>/" + id,
            success: function (response, opts) {
                var eduTeacherTraining_data = response.responseText;

                eval(eduTeacherTraining_data);

                parentEduTeachersTrainingsViewWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Teacher Training form. Error code'); ?>: " + response.status);
            }
        });
    }
	
	function UploadTeacherOPhoto(id) {
		Ext.Ajax.request({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'upload_photo')); ?>/'+id,
			success: function(response, opts) {
				var eduTeacher_data = response.responseText;
				eval(eduTeacher_data);
				EduTeacherUploadPhotoWindow.show();
			},
			failure: function(response, opts) {
				Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Teacher Photo Upload form. Error code'); ?>: ' + response.status);
			}
		});
	}

    function DeleteEduTeacherO(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'delete')); ?>/" + id,
            success: function (response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Teacher successfully deleted!'); ?>");
                RefreshEduTeacherData();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Teacher add form. Error code'); ?>: " + response.status);
            }
        });
    }

    function SearchEduTeacherO() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'search')); ?>',
            success: function (response, opts) {
                var eduTeacher_data = response.responseText;

                eval(eduTeacher_data);

                eduTeacherSearchWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Teacher search form. Error Code'); ?>: " + response.status);
            }
        });
    }

    function SearchByEduTeacherNameO(value) {
        var conditions = '\'EduTeacher.name LIKE\' => \'%' + value + '%\'';
        store_eduTeachers.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshEduTeacherDataO() {
        store_eduTeachers.reload();
    }


    if (center_panel.find('id', 'eduTeacher-tab-o') != "") {
        var p = center_panel.findById('eduTeacher-tab-o');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Teachers'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduTeacher-tab-o',
            xtype: 'grid',
            store: store_eduTeachers_o,
            columns: [
                {header: "<?php __('Teacher'); ?>", dataIndex: 'teacher', sortable: true},
                {header: "<?php __('ID Number'); ?>", dataIndex: 'identity_number', sortable: true},
                {header: "<?php __('Telephone'); ?>", dataIndex: 'telephone', sortable: true},
                {header: "<?php __('Mobile'); ?>", dataIndex: 'mobile', sortable: true}
            ],
            viewConfig: {
                forceFit: true
            },
            listeners: {
                celldblclick: function () {
                    ViewEduTeacherO(Ext.getCmp('eduTeacher-tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: true
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbsplit',
                        text: "<?php __('Manage'); ?>",
                        id: 'manage-eduTeacher-o',
                        tooltip: "<?php __('<b>View Teacher</b><br />Click here to see details of the selected Teacher'); ?>",
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        menu: {
                            items: [{
                                    text: '<?php __('Academic Profile'); ?>',
                                    icon: 'img/table_view.png',
                                    cls: 'x-btn-text-icon',
                                    handler: function (btn) {
                                        var sm = p.getSelectionModel();
                                        var sel = sm.getSelected();
                                        if (sm.hasSelection()) {
                                            ShowErrorBox("Ooops! This feature is under development.", "ERR-000-00");
                                        }
                                    }
                                }, {
									text: "<?php __('Personal Profile '); ?>",
									icon: 'img/table_edit.png',
									cls: 'x-btn-text-icon',
									handler: function (btn) {
										var sm = p.getSelectionModel();
										var sel = sm.getSelected();
										if (sm.hasSelection()) {
											EditEduTeacherO(sel.data.id);
										}
									}
								}, {
                                    text: '<?php __('Trainings Taken'); ?>',
                                    icon: 'img/table_edit.png',
                                    cls: 'x-btn-text-icon',
                                    handler: function (btn) {
                                        var sm = p.getSelectionModel();
                                        var sel = sm.getSelected();
                                        if (sm.hasSelection()) {
                                            ViewEduTeacherTraining(sel.data.id);
                                        }
                                    }
                                }
                            ]
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Delete'); ?>",
                        id: 'delete-eduTeacher-o',
                        tooltip: "<?php __('<b>Delete Teachers(s)</b><br />Click here to remove the selected Teacher(s)'); ?>",
                        icon: 'img/table_delete.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelections();
                            if (sm.hasSelection()) {
                                if (sel.length == 1) {
                                    Ext.Msg.show({
                                        title: "<?php __('Remove Teacher'); ?>",
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: "<?php __('Remove'); ?> <b><i>" + sel[0].data.teacher + '</i></b>?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function (btn) {
                                            if (btn == 'yes') {
                                                DeleteEduTeacherO(sel[0].data.id);
                                            }
                                        }
                                    });
                                }
                            } else {
                                Ext.Msg.alert("<?php __('Warning'); ?>", "<?php __('Please select a record first'); ?>");
                            }
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Terminate'); ?>",
                        id: 'terminate-eduTeacher-o',
                        tooltip: "<?php __('<b>Terminate Teachers</b><br />Click here to terminate the selected Teacher'); ?>",
                        icon: 'img/table_delete.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                //EditEduTeacher(sel.data.id);
								ShowErrorBox("Ooops! This feature is under development.", "DEV-001-0001");
                            }
                        }
                    }, '->', {
                        xtype: 'textfield',
                        emptyText: "<?php __('[Search By Name]'); ?>",
                        id: 'eduTeacher_search_field_o',
                        listeners: {
                            specialkey: function (field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduTeacherNameO(Ext.getCmp('eduTeacher_search_field_o').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('GO'); ?>",
                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
                        id: "eduTeacher_go_button_o",
                        handler: function () {
                            SearchByEduTeacherNameO(Ext.getCmp('eduTeacher_search_field_o').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('Advanced Search'); ?>",
                        tooltip: "<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
                        handler: function () {
                            SearchEduTeacherO();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_eduTeachers_o,
                displayInfo: true,
                displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
                beforePageText: "<?php __('Page'); ?>",
                afterPageText: "<?php __('of {0}'); ?>",
                emptyMsg: "<?php __('No data to display'); ?>"
            })
        });
        p.getSelectionModel().on('rowselect', function (sm, rowIdx, r) {
            p.getTopToolbar().findById('delete-eduTeacher-o').enable();
            p.getTopToolbar().findById('terminate-eduTeacher-o').enable();
            p.getTopToolbar().findById('manage-eduTeacher-o').enable();
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('terminate-eduTeacher-o').disable();
                p.getTopToolbar().findById('manage-eduTeacher-o').disable();
            }
        });
        p.getSelectionModel().on('rowdeselect', function (sm, rowIdx, r) {
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('terminate-eduTeacher-o').disable();
                p.getTopToolbar().findById('manage-eduTeacher-o').disable();
                p.getTopToolbar().findById('delete-eduTeacher-o').enable();
            } else if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('terminate-eduTeacher-o').enable();
                p.getTopToolbar().findById('manage-eduTeacher-o').enable();
                p.getTopToolbar().findById('delete-eduTeacher').enable();
            } else {
                p.getTopToolbar().findById('terminate-eduTeacher-o').disable();
                p.getTopToolbar().findById('manage-eduTeacher-o').disable();
                p.getTopToolbar().findById('delete-eduTeacher-o').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_eduTeachers_o.load({
            params: {
                start: 0,
                limit: list_size
            }
        });
    }