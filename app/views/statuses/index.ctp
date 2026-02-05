//<script>
    var store_statuses = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                {'name': 'id', 'type': 'int'},'name','tables','remark'		]
	}),
	proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'statuses', 'action' => 'list_data')); ?>'
	}),	
        sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'tables'
    });

    function AddStatus() {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'statuses', 'action' => 'add')); ?>',
            success: function(response, opts) {
                var status_data = response.responseText;
			
                eval(status_data);
			
                StatusAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the status add form. Error code'); ?>: ' + response.status);
            }
	});
    }

    function EditStatus(id) {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'statuses', 'action' => 'edit')); ?>/'+id,
            success: function(response, opts) {
                var status_data = response.responseText;
			
                eval(status_data);
			
                StatusEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the status edit form. Error code'); ?>: ' + response.status);
            }
	});
    }

    function ViewStatus(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'statuses', 'action' => 'view')); ?>/'+id,
            success: function(response, opts) {
                var status_data = response.responseText;

                eval(status_data);

                StatusViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the status view form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function DeleteStatus(id) {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'statuses', 'action' => 'delete')); ?>/'+id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Status successfully deleted!'); ?>');
                RefreshStatusData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the status add form. Error code'); ?>: ' + response.status);
            }
	});
    }

    function SearchStatus(){
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'statuses', 'action' => 'search')); ?>',
            success: function(response, opts){
                var status_data = response.responseText;

                eval(status_data);

                statusSearchWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the status search form. Error Code'); ?>: ' + response.status);
            }
	});
    }

    function SearchByStatusName(value){
	var conditions = '\'Status.name LIKE\' => \'%' + value + '%\'';
	store_statuses.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
	    }
	});
    }

    function RefreshStatusData() {
	store_statuses.reload();
    }


    if(center_panel.find('id', 'status-tab') != "") {
	var p = center_panel.findById('status-tab');
	center_panel.setActiveTab(p);
    } else {
	var p = center_panel.add({
            title: '<?php __('Status Lookup'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'status-tab',
            xtype: 'grid',
            store: store_statuses,
            columns: [
                {header: "<?php __('S. No'); ?>", dataIndex: 'id', sortable: true},
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Tables'); ?>", dataIndex: 'tables', sortable: true},
                {header: "<?php __('Remark'); ?>", dataIndex: 'remark', sortable: true}
            ],
            view: new Ext.grid.GroupingView({
                forceFit:true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Statuses" : "Status"]})'
            }),
            listeners: {
                celldblclick: function(){
                    ViewStatus(Ext.getCmp('status-tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
            tbar: new Ext.Toolbar({	
                items: [{
                        xtype: 'tbbutton',
                        text: '<?php __('Add'); ?>',
                        tooltip:'<?php __('<b>Add Statuses</b><br />Click here to create a new Status'); ?>',
                        icon: 'img/table_add.png',
                        cls: 'x-btn-text-icon',
                        handler: function(btn) {
                            AddStatus();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('Edit'); ?>',
                        id: 'edit-status',
                        tooltip:'<?php __('<b>Edit Statuses</b><br />Click here to modify the selected Status'); ?>',
                        icon: 'img/table_edit.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()){
                                EditStatus(sel.data.id);
                            };
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('Delete'); ?>',
                        id: 'delete-status',
                        tooltip:'<?php __('<b>Delete Statuses(s)</b><br />Click here to remove the selected Status(s)'); ?>',
                        icon: 'img/table_delete.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelections();
                            if (sm.hasSelection()){
                                if(sel.length==1){
                                    Ext.Msg.show({
                                        title: '<?php __('Remove Status'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn){
                                            if (btn == 'yes'){
                                                DeleteStatus(sel[0].data.id);
                                            }
                                        }
                                    });
                                }else{
                                    Ext.Msg.show({
                                        title: '<?php __('Remove Status'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove the selected Statuses'); ?>?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn){
                                            if (btn == 'yes'){
                                                var sel_ids = '';
                                                for(i=0;i<sel.length;i++){
                                                    if(i>0)
                                                        sel_ids += '_';
                                                    sel_ids += sel[i].data.id;
                                                }
                                                DeleteStatus(sel_ids);
                                            }
                                        }
                                    });
                                }
                            } else {
                                Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                            };
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbsplit',
                        text: '<?php __('View Status'); ?>',
                        id: 'view-status',
                        tooltip:'<?php __('<b>View Status</b><br />Click here to see details of the selected Status'); ?>',
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()){
                                ViewStatus(sel.data.id);
                            };
                        },
                        menu : {
                            items: [
                            ]
                        }
                    }, ' ', '-',  '->', {
                        xtype: 'textfield',
                        emptyText: '<?php __('[Search By Name]'); ?>',
                        id: 'status_search_field',
                        listeners: {
                            specialkey: function(field, e){
                                if (e.getKey() == e.ENTER) {
                                    SearchByStatusName(Ext.getCmp('status_search_field').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('GO'); ?>',
                        tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
                        id: 'status_go_button',
                        handler: function(){
                            SearchByStatusName(Ext.getCmp('status_search_field').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('Advanced Search'); ?>',
                        tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
                        handler: function(){
                            SearchStatus();
                        }
                    }
		]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_statuses,
                displayInfo: true,
                displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
                beforePageText: '<?php __('Page'); ?>',
                afterPageText: '<?php __('of {0}'); ?>',
                emptyMsg: '<?php __('No data to display'); ?>'
            })
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            p.getTopToolbar().findById('edit-status').enable();
            p.getTopToolbar().findById('delete-status').enable();
            p.getTopToolbar().findById('view-status').enable();
            if(this.getSelections().length > 1){
                p.getTopToolbar().findById('edit-status').disable();
                p.getTopToolbar().findById('view-status').disable();
            }
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            if(this.getSelections().length > 1){
                p.getTopToolbar().findById('edit-status').disable();
                p.getTopToolbar().findById('view-status').disable();
                p.getTopToolbar().findById('delete-status').enable();
            }
            else if(this.getSelections().length == 1){
                p.getTopToolbar().findById('edit-status').enable();
                p.getTopToolbar().findById('view-status').enable();
                p.getTopToolbar().findById('delete-status').enable();
            }
            else{
                p.getTopToolbar().findById('edit-status').disable();
                p.getTopToolbar().findById('view-status').disable();
                p.getTopToolbar().findById('delete-status').disable();
            }
	});
	center_panel.setActiveTab(p);
	
	store_statuses.load({
            params: {
                start: 0,          
                limit: list_size
            }
	});
    }
