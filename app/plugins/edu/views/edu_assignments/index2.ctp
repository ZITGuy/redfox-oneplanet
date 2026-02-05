//<script>
var store_parent_eduAssignments = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_teacher','edu_course','edu_section','start_date','end_date','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduAssignments', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduAssignment() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduAssignments', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduAssignment_data = response.responseText;
			
			eval(parent_eduAssignment_data);
			
			EduAssignmentAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduAssignment add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduAssignment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduAssignments', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduAssignment_data = response.responseText;
			
			eval(parent_eduAssignment_data);
			
			EduAssignmentEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduAssignment edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduAssignment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduAssignments', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduAssignment_data = response.responseText;

			eval(eduAssignment_data);

			EduAssignmentViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduAssignment view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentEduAssignment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduAssignments', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduAssignment(s) successfully deleted!'); ?>');
			RefreshParentEduAssignmentData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduAssignment to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduAssignmentName(value){
	var conditions = '\'EduAssignment.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduAssignments.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduAssignmentData() {
	store_parent_eduAssignments.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('EduAssignments'); ?>',
	store: store_parent_eduAssignments,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduAssignmentGrid',
	columns: [
		{header:"<?php __('edu_teacher'); ?>", dataIndex: 'edu_teacher', sortable: true},
		{header:"<?php __('edu_course'); ?>", dataIndex: 'edu_course', sortable: true},
		{header:"<?php __('edu_section'); ?>", dataIndex: 'edu_section', sortable: true},
		{header: "<?php __('Start Date'); ?>", dataIndex: 'start_date', sortable: true},
		{header: "<?php __('End Date'); ?>", dataIndex: 'end_date', sortable: true},
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
            ViewEduAssignment(Ext.getCmp('eduAssignmentGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add EduAssignment</b><br />Click here to create a new EduAssignment'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduAssignment();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduAssignment',
				tooltip:'<?php __('<b>Edit EduAssignment</b><br />Click here to modify the selected EduAssignment'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduAssignment(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduAssignment',
				tooltip:'<?php __('<b>Delete EduAssignment(s)</b><br />Click here to remove the selected EduAssignment(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove EduAssignment'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduAssignment(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove EduAssignment'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected EduAssignment'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentEduAssignment(sel_ids);
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
				text: '<?php __('View EduAssignment'); ?>',
				id: 'view-eduAssignment2',
				tooltip:'<?php __('<b>View EduAssignment</b><br />Click here to see details of the selected EduAssignment'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduAssignment(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduAssignment_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduAssignmentName(Ext.getCmp('parent_eduAssignment_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduAssignment_go_button',
				handler: function(){
					SearchByParentEduAssignmentName(Ext.getCmp('parent_eduAssignment_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduAssignments,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduAssignment').enable();
	g.getTopToolbar().findById('delete-parent-eduAssignment').enable();
        g.getTopToolbar().findById('view-eduAssignment2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduAssignment').disable();
                g.getTopToolbar().findById('view-eduAssignment2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduAssignment').disable();
		g.getTopToolbar().findById('delete-parent-eduAssignment').enable();
                g.getTopToolbar().findById('view-eduAssignment2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduAssignment').enable();
		g.getTopToolbar().findById('delete-parent-eduAssignment').enable();
                g.getTopToolbar().findById('view-eduAssignment2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduAssignment').disable();
		g.getTopToolbar().findById('delete-parent-eduAssignment').disable();
                g.getTopToolbar().findById('view-eduAssignment2').disable();
	}
});



var parentEduAssignmentsViewWindow = new Ext.Window({
	title: 'EduAssignment Under the selected Item',
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
			parentEduAssignmentsViewWindow.close();
		}
	}]
});

store_parent_eduAssignments.load({
    params: {
        start: 0,    
        limit: list_size
    }
});
