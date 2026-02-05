//<script>
    var store_edu_campuses = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'address', 'number_of_students', 'number_of_users', 'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_campuses', 'action' => 'list_data')); ?>"
        }),
        sortInfo: {field: 'name', direction: "ASC"}
    });

    function AddEduCampus() {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_campuses', 'action' => 'add')); ?>",
            success: function (response, opts) {
                var eduCampus_data = response.responseText;

                eval(eduCampus_data);

                EduCampusAddWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Campus add form. Error code'); ?>: " + response.status);
            }
        });
    }

    function EditEduCampus(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_campuses', 'action' => 'edit')); ?>/" + id,
            success: function (response, opts) {
                var eduCampus_data = response.responseText;

                eval(eduCampus_data);

                EduCampusEditWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Campus edit form. Error code'); ?>: " + response.status);
            }
        });
    }

    function ViewEduCampus(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_campuses', 'action' => 'view')); ?>/" + id,
            success: function (response, opts) {
                var eduCampus_data = response.responseText;

                eval(eduCampus_data);

                EduCampusViewWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Campus view form. Error code'); ?>: " + response.status);
            }
        });
    }

    function DeleteEduCampus(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_campuses', 'action' => 'delete')); ?>/" + id,
            success: function (response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Campus successfully deleted!'); ?>");
                RefreshEduCampusData();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Campus add form. Error code'); ?>: " + response.status);
            }
        });
    }

    function SearchEduCampus() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_campuses', 'action' => 'search')); ?>',
            success: function (response, opts) {
                var eduCampus_data = response.responseText;

                eval(eduCampus_data);

                eduCampusSearchWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Campus search form. Error Code'); ?>: " + response.status);
            }
        });
    }

    function SearchByEduCampusName(value) {
        var conditions = '\'EduCampus.name LIKE\' => \'%' + value + '%\'';
        store_edu_campuses.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshEduCampusData() {
        store_edu_campuses.reload();
    }


    if (center_panel.find('id', 'edu_campus_tab_m') != "") {
        var p = center_panel.findById('edu_campus_tab_m');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Campuses'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'edu_campus_tab_m',
            xtype: 'grid',
            store: store_edu_campuses,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Address'); ?>", dataIndex: 'address', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
            ],
            viewConfig: {
                forceFit: true
            },
            listeners: {
                celldblclick: function () {
                    ViewEduCampus(Ext.getCmp('edu_campus_tab_m').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: true
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: "<?php __('Add'); ?>",
                        tooltip: "<?php __('<b>Add Campuses</b><br />Click here to create a new Campus'); ?>",
                        icon: 'img/table_add.png',
                        cls: 'x-btn-text-icon',
                        handler: function (btn) {
                            AddEduCampus();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Edit'); ?>",
                        id: 'edit-eduCampus',
                        tooltip: "<?php __('<b>Edit Campuses</b><br />Click here to modify the selected Campus'); ?>",
                        icon: 'img/table_edit.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                EditEduCampus(sel.data.id);
                            }
                            ;
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: "<?php __('Delete'); ?>",
                        id: 'delete-eduCampus',
                        tooltip: "<?php __('<b>Delete Campus(es)</b><br />Click here to remove the selected Campus(es)'); ?>",
                        icon: 'img/table_delete.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelections();
                            if (sm.hasSelection()) {
                                if (sel.length == 1) {
                                    Ext.Msg.show({
                                        title: "<?php __('Remove Campus'); ?>",
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: "<?php __('Remove'); ?> " + sel[0].data.name + '?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function (btn) {
                                            if (btn == 'yes') {
                                                DeleteEduCampus(sel[0].data.id);
                                            }
                                        }
                                    });
                                } else {
                                    Ext.Msg.show({
                                        title: "<?php __('Remove Campus'); ?>",
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: "<?php __('Remove the selected Campuses'); ?>?",
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function (btn) {
                                            if (btn == 'yes') {
                                                var sel_ids = '';
                                                for (i = 0; i < sel.length; i++) {
                                                    if (i > 0)
                                                        sel_ids += '_';
                                                    sel_ids += sel[i].data.id;
                                                }
                                                DeleteEduCampus(sel_ids);
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
                        xtype: 'tbbutton',
                        text: "<?php __('View Campus'); ?>",
                        id: 'view-eduCampus',
                        tooltip: "<?php __('<b>View Campus</b><br />Click here to see details of the selected Campus'); ?>",
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewEduCampus(sel.data.id);
                            }
                            ;
                        }
                    }, ' ', '-', '->', {
                        xtype: 'textfield',
                        emptyText: "<?php __('[Search By Name]'); ?>",
                        id: 'eduCampus_search_field',
                        listeners: {
                            specialkey: function (field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduCampusName(Ext.getCmp('eduCampus_search_field').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('GO'); ?>",
                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
                        id: "eduCampus_go_button",
                        handler: function () {
                            SearchByEduCampusName(Ext.getCmp('eduCampus_search_field').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('Advanced Search'); ?>",
                        tooltip: "<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
                        handler: function () {
                            SearchEduCampus();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_edu_campuses,
                displayInfo: true,
                displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
                beforePageText: "<?php __('Page'); ?>",
                afterPageText: "<?php __('of {0}'); ?>",
                emptyMsg: "<?php __('No data to display'); ?>"
            })
        });
        p.getSelectionModel().on('rowselect', function (sm, rowIdx, r) {
            p.getTopToolbar().findById('edit-eduCampus').enable();
            p.getTopToolbar().findById('delete-eduCampus').enable();
            p.getTopToolbar().findById('view-eduCampus').enable();
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit-eduCampus').disable();
                p.getTopToolbar().findById('view-eduCampus').disable();
				p.getTopToolbar().findById('delete-eduCampus').disable();
            }
			// the following is to test whether the record is deletable or not 
			// based on the existence of students registed in the campus or 
			// the number of users assigned in the selected campus 
			if(this.getSelections().length == 1) {
				var deletable = (sm.getSelected().get('number_of_students') == 0);
				deletable = deletable && (sm.getSelected().get('number_of_users') == 0);
				
				if(deletable) {
					p.getTopToolbar().findById('delete-eduCampus').enable();
				} else {
					p.getTopToolbar().findById('delete-eduCampus').disable();
				}
			}
        });
        p.getSelectionModel().on('rowdeselect', function (sm, rowIdx, r) {
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit-eduCampus').disable();
                p.getTopToolbar().findById('view-eduCampus').disable();
                p.getTopToolbar().findById('delete-eduCampus').enable();
            } else if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('edit-eduCampus').enable();
                p.getTopToolbar().findById('view-eduCampus').enable();
                p.getTopToolbar().findById('delete-eduCampus').enable();
            } else {
                p.getTopToolbar().findById('edit-eduCampus').disable();
                p.getTopToolbar().findById('view-eduCampus').disable();
                p.getTopToolbar().findById('delete-eduCampus').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_edu_campuses.load({
            params: {
                start: 0,
                limit: list_size
            }
        });

    }