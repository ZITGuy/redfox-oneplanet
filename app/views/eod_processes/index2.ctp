var store_parent_eodProcesses = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','process_date','user','task1_backup_taken','task2_portal_updated','task3_ftp_sent','backup_type','incremental_count','backup_incremental_file','backup_full_file','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eodProcesses', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEodProcess() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eodProcesses', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eodProcess_data = response.responseText;
			
			eval(parent_eodProcess_data);
			
			EodProcessAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eodProcess add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEodProcess(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eodProcesses', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eodProcess_data = response.responseText;
			
			eval(parent_eodProcess_data);
			
			EodProcessEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eodProcess edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEodProcess(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eodProcesses', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eodProcess_data = response.responseText;

			eval(eodProcess_data);

			EodProcessViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eodProcess view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentEodProcess(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eodProcesses', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EodProcess(s) successfully deleted!'); ?>');
			RefreshParentEodProcessData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eodProcess to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEodProcessName(value){
	var conditions = '\'EodProcess.name LIKE\' => \'%' + value + '%\'';
	store_parent_eodProcesses.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEodProcessData() {
	store_parent_eodProcesses.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('EodProcesses'); ?>',
	store: store_parent_eodProcesses,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eodProcessGrid',
	columns: [
		{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
		{header: "<?php __('Process Date'); ?>", dataIndex: 'process_date', sortable: true},
		{header:"<?php __('user'); ?>", dataIndex: 'user', sortable: true},
		{header: "<?php __('Task1 Backup Taken'); ?>", dataIndex: 'task1_backup_taken', sortable: true},
		{header: "<?php __('Task2 Portal Updated'); ?>", dataIndex: 'task2_portal_updated', sortable: true},
		{header: "<?php __('Task3 Ftp Sent'); ?>", dataIndex: 'task3_ftp_sent', sortable: true},
		{header: "<?php __('Backup Type'); ?>", dataIndex: 'backup_type', sortable: true},
		{header: "<?php __('Incremental Count'); ?>", dataIndex: 'incremental_count', sortable: true},
		{header: "<?php __('Backup Incremental File'); ?>", dataIndex: 'backup_incremental_file', sortable: true},
		{header: "<?php __('Backup Full File'); ?>", dataIndex: 'backup_full_file', sortable: true},
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
            ViewEodProcess(Ext.getCmp('eodProcessGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add EodProcess</b><br />Click here to create a new EodProcess'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEodProcess();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eodProcess',
				tooltip:'<?php __('<b>Edit EodProcess</b><br />Click here to modify the selected EodProcess'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEodProcess(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eodProcess',
				tooltip:'<?php __('<b>Delete EodProcess(s)</b><br />Click here to remove the selected EodProcess(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove EodProcess'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEodProcess(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove EodProcess'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected EodProcess'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentEodProcess(sel_ids);
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
				text: '<?php __('View EodProcess'); ?>',
				id: 'view-eodProcess2',
				tooltip:'<?php __('<b>View EodProcess</b><br />Click here to see details of the selected EodProcess'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEodProcess(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eodProcess_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEodProcessName(Ext.getCmp('parent_eodProcess_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eodProcess_go_button',
				handler: function(){
					SearchByParentEodProcessName(Ext.getCmp('parent_eodProcess_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eodProcesses,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eodProcess').enable();
	g.getTopToolbar().findById('delete-parent-eodProcess').enable();
        g.getTopToolbar().findById('view-eodProcess2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eodProcess').disable();
                g.getTopToolbar().findById('view-eodProcess2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eodProcess').disable();
		g.getTopToolbar().findById('delete-parent-eodProcess').enable();
                g.getTopToolbar().findById('view-eodProcess2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eodProcess').enable();
		g.getTopToolbar().findById('delete-parent-eodProcess').enable();
                g.getTopToolbar().findById('view-eodProcess2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eodProcess').disable();
		g.getTopToolbar().findById('delete-parent-eodProcess').disable();
                g.getTopToolbar().findById('view-eodProcess2').disable();
	}
});



var parentEodProcessesViewWindow = new Ext.Window({
	title: 'EodProcess Under the selected Item',
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
			parentEodProcessesViewWindow.close();
		}
	}]
});

store_parent_eodProcesses.load({
    params: {
        start: 0,    
        limit: list_size
    }
});