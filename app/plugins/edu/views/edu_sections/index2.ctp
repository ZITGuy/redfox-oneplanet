//<script>
    var store_parent_eduSections = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'edu_class', 'edu_academic_year', 'created', 'modified'
            ]
	}),
	proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'eduSections', 'action' => 'list_data', $parent_id)); ?>'})
    });

    function EditParentEduSection(id) {
		Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'eduSections', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
            success: function(response, opts) {
                var parent_eduSection_data = response.responseText;
			
                eval(parent_eduSection_data);
			
                EduSectionEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>',
                    '<?php __('Cannot get the eduSection edit form. Error code'); ?>: ' + response.status);
            }
		});
    }

    function ViewEduSection(id) {
		Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduSections', 'action' => 'view')); ?>/'+id,
            success: function(response, opts) {
                var eduSection_data = response.responseText;

                eval(eduSection_data);

                EduSectionViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>',
                    '<?php __('Cannot get the eduSection view form. Error code'); ?>: ' + response.status);
            }
		});
    }

    function ViewEduSectionEduAssessments(id) {
		Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduAssessments', 'action' => 'index2')); ?>/'+id,
            success: function(response, opts) {
                var parent_eduAssessments_data = response.responseText;

                eval(parent_eduAssessments_data);

                parentEduAssessmentsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>',
                    '<?php __('Cannot get the section view form. Error code'); ?>: ' + response.status);
            }
		});
    }

    function ViewEduSectionEduAssignments(id) {
		Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'eduAssignments', 'action' => 'index2')); ?>/'+id,
            success: function(response, opts) {
                var parent_eduAssignments_data = response.responseText;

                eval(parent_eduAssignments_data);

                parentEduAssignmentsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>',
                    '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
            }
		});
    }

    function ViewEduSectionEduRegistrations(id) {
		Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrations', 'action' => 'index2')); ?>/'+id,
            success: function(response, opts) {
                var parent_eduRegistrations_data = response.responseText;

                eval(parent_eduRegistrations_data);

                parentEduRegistrationsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>',
                    '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
            }
		});
    }


    function DeleteParentEduSection(id) {
		Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduSections', 'action' => 'delete')); ?>/'+id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduSection(s) successfully deleted!'); ?>');
                RefreshParentEduSectionData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>',
                    '<?php __('Cannot get the eduSection to be deleted. Error code'); ?>: ' + response.status);
            }
		});
    }

    function SearchByParentEduSectionName(value){
		var conditions = '\'EduSection.name LIKE\' => \'%' + value + '%\'';
		store_parent_eduSections.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
			}
		});
    }

    function RefreshParentEduSectionData() {
		store_parent_eduSections.reload();
    }



    var g = new Ext.grid.GridPanel({
		title: '<?php __('EduSections'); ?>',
		store: store_parent_eduSections,
		loadMask: true,
		stripeRows: true,
		height: 300,
		anchor: '100%',
        id: 'eduSectionGrid',
		columns: [
            {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
            {header: "<?php __('Class'); ?>", dataIndex: 'edu_class', sortable: true},
            {header: "<?php __('Academic Year'); ?>", dataIndex: 'edu_academic_year', sortable: true},
            {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
            {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}	],
		sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
		}),
		viewConfig: {
            forceFit: true
		},
        listeners: {
            celldblclick: function(){
                ViewEduSection(Ext.getCmp('eduSectionGrid').getSelectionModel().getSelected().data.id);
            }
        },
	tbar: new Ext.Toolbar({
            items: [{
                    xtype: 'tbbutton',
                    text: '<?php __('Edit'); ?>',
                    id: 'edit-parent-eduSection',
                    tooltip:'<?php __('<b>Edit Section</b><br />Click here to modify the selected Section'); ?>',
                    icon: 'img/table_edit.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()){
                            EditParentEduSection(sel.data.id);
                        };
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Delete'); ?>',
                    id: 'delete-parent-eduSection',
                    tooltip:'<?php __('<b>Delete Section(s)</b><br />Click here to remove the selected(s)'); ?>',
                    icon: 'img/table_delete.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelections();
                        if (sm.hasSelection()){
                            if(sel.length==1){
                                Ext.Msg.show({
                                    title: '<?php __('Remove EduSection'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
                                        if (btn == 'yes'){
                                            DeleteParentEduSection(sel[0].data.id);
                                        }
                                    }
                                });
                            } else {
                                Ext.Msg.show({
                                    title: '<?php __('Remove EduSection'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove the selected EduSection'); ?>?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
                                        if (btn == 'yes'){
                                            var sel_ids = '';
                                            for(i=0;i<sel.length;i++){
                                                if(i>0)
                                                    sel_ids += '_';
                                                sel_ids += sel[i].data.id;
                                            }
                                            DeleteParentEduSection(sel_ids);
                                        }
                                    }
                                });
                            }
                        } else {
                            Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                        };
                    }
                }, ' ','-',' ', {
                    xtype: 'tbsplit',
                    text: '<?php __('View EduSection'); ?>',
                    id: 'view-eduSection2',
                    tooltip:'<?php __('<b>View EduSection</b><br />Click here to see details of the selected EduSection'); ?>',
                    icon: 'img/table_view.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()){
                            ViewEduSection(sel.data.id);
                        };
                    },
                    menu : {
                        items: [
                            {
                                text: '<?php __('View Edu Assessments'); ?>',
                                icon: 'img/table_view.png',
                                cls: 'x-btn-text-icon',
                                handler: function(btn) {
                                    var sm = g.getSelectionModel();
                                    var sel = sm.getSelected();
                                    if (sm.hasSelection()){
                                        ViewEduSectionEduAssessments(sel.data.id);
                                    };
                                }
                            }
                            , {
                                text: '<?php __('View Edu Assignments'); ?>',
                                icon: 'img/table_view.png',
                                cls: 'x-btn-text-icon',
                                handler: function(btn) {
                                    var sm = g.getSelectionModel();
                                    var sel = sm.getSelected();
                                    if (sm.hasSelection()){
                                        ViewEduSectionEduAssignments(sel.data.id);
                                    };
                                }
                            }
                            , {
                                text: '<?php __('View Edu Registrations'); ?>',
                                icon: 'img/table_view.png',
                                cls: 'x-btn-text-icon',
                                handler: function(btn) {
                                    var sm = g.getSelectionModel();
                                    var sel = sm.getSelected();
                                    if (sm.hasSelection()){
                                        ViewEduSectionEduRegistrations(sel.data.id);
                                    };
                                }
                            }
                        ]
                    }

                }, ' ', '->', {
                    xtype: 'textfield',
                    emptyText: '<?php __('[Search By Name]'); ?>',
                    id: 'parent_eduSection_search_field',
                    listeners: {
                        specialkey: function(field, e){
                            if (e.getKey() == e.ENTER) {
                                SearchByParentEduSectionName(Ext.getCmp('parent_eduSection_search_field').getValue());
                            }
                        }

                    }
                }, {
                    xtype: 'tbbutton',
                    icon: 'img/search.png',
                    cls: 'x-btn-text-icon',
                    text: 'GO',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
                    id: 'parent_eduSection_go_button',
                    handler: function(){
                        SearchByParentEduSectionName(Ext.getCmp('parent_eduSection_search_field').getValue());
                    }
                }, ' '
            ]}),
	bbar: new Ext.PagingToolbar({
            pageSize: list_size,
            store: store_parent_eduSections,
            displayInfo: true,
            displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
            beforePageText: '<?php __('Page'); ?>',
            afterPageText: '<?php __('of {0}'); ?>',
            emptyMsg: '<?php __('No data to display'); ?>'
	})
    });
    g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduSection').enable();
	g.getTopToolbar().findById('delete-parent-eduSection').enable();
        g.getTopToolbar().findById('view-eduSection2').enable();
	if(this.getSelections().length > 1){
            g.getTopToolbar().findById('edit-parent-eduSection').disable();
            g.getTopToolbar().findById('view-eduSection2').disable();
	}
    });
    g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
            g.getTopToolbar().findById('edit-parent-eduSection').disable();
            g.getTopToolbar().findById('delete-parent-eduSection').enable();
            g.getTopToolbar().findById('view-eduSection2').disable();
	}
	else if(this.getSelections().length == 1){
            g.getTopToolbar().findById('edit-parent-eduSection').enable();
            g.getTopToolbar().findById('delete-parent-eduSection').enable();
            g.getTopToolbar().findById('view-eduSection2').enable();
	}
	else{
            g.getTopToolbar().findById('edit-parent-eduSection').disable();
            g.getTopToolbar().findById('delete-parent-eduSection').disable();
            g.getTopToolbar().findById('view-eduSection2').disable();
	}
    });



    var parentEduSectionsViewWindow = new Ext.Window({
	title: 'EduSection Under the selected Item',
	width: 700,
	height:375,
	minWidth: 700,
	minHeight: 400,
	resizable: false,
	plain:true,
	bodyStyle:'padding:5px;',
	buttonAlign:'center',
        modal: true,
	items: [
            g
	],

	buttons: [{
		text: 'Close',
		handler: function(btn){
                    parentEduSectionsViewWindow.close();
		}
            }]
    });

    store_parent_eduSections.load({
        params: {
            start: 0,    
            limit: list_size
        }
    });
