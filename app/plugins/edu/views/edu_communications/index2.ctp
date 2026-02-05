//<script>
var store_parent_eduCommunications = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_student','edu_section','post_date','teacher_comment','parent_comment','user','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCommunications', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduCommunication() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCommunications', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduCommunication_data = response.responseText;
			
			eval(parent_eduCommunication_data);
			
			EduCommunicationAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCommunication add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduCommunication(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCommunications', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduCommunication_data = response.responseText;
			
			eval(parent_eduCommunication_data);
			
			EduCommunicationEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCommunication edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduCommunication(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCommunications', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduCommunication_data = response.responseText;

			eval(eduCommunication_data);

			EduCommunicationViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCommunication view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentEduCommunication(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCommunications', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduCommunication(s) successfully deleted!'); ?>');
			RefreshParentEduCommunicationData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCommunication to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduCommunicationName(value){
	var conditions = '\'EduCommunication.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduCommunications.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduCommunicationData() {
	store_parent_eduCommunications.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('EduCommunications'); ?>',
	store: store_parent_eduCommunications,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduCommunicationGrid',
	columns: [
		{header:"<?php __('edu_student'); ?>", dataIndex: 'edu_student', sortable: true},
		{header:"<?php __('edu_section'); ?>", dataIndex: 'edu_section', sortable: true},
		{header: "<?php __('Post Date'); ?>", dataIndex: 'post_date', sortable: true},
		{header: "<?php __('Teacher Comment'); ?>", dataIndex: 'teacher_comment', sortable: true},
		{header: "<?php __('Parent Comment'); ?>", dataIndex: 'parent_comment', sortable: true},
		{header:"<?php __('user'); ?>", dataIndex: 'user', sortable: true},
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
            ViewEduCommunication(Ext.getCmp('eduCommunicationGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add EduCommunication</b><br />Click here to create a new EduCommunication'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduCommunication();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduCommunication',
				tooltip:'<?php __('<b>Edit EduCommunication</b><br />Click here to modify the selected EduCommunication'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduCommunication(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduCommunication',
				tooltip:'<?php __('<b>Delete EduCommunication(s)</b><br />Click here to remove the selected EduCommunication(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove EduCommunication'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduCommunication(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove EduCommunication'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected EduCommunication'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentEduCommunication(sel_ids);
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
				text: '<?php __('View EduCommunication'); ?>',
				id: 'view-eduCommunication2',
				tooltip:'<?php __('<b>View EduCommunication</b><br />Click here to see details of the selected EduCommunication'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduCommunication(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduCommunication_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduCommunicationName(Ext.getCmp('parent_eduCommunication_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduCommunication_go_button',
				handler: function(){
					SearchByParentEduCommunicationName(Ext.getCmp('parent_eduCommunication_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduCommunications,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduCommunication').enable();
	g.getTopToolbar().findById('delete-parent-eduCommunication').enable();
        g.getTopToolbar().findById('view-eduCommunication2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduCommunication').disable();
                g.getTopToolbar().findById('view-eduCommunication2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduCommunication').disable();
		g.getTopToolbar().findById('delete-parent-eduCommunication').enable();
                g.getTopToolbar().findById('view-eduCommunication2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduCommunication').enable();
		g.getTopToolbar().findById('delete-parent-eduCommunication').enable();
                g.getTopToolbar().findById('view-eduCommunication2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduCommunication').disable();
		g.getTopToolbar().findById('delete-parent-eduCommunication').disable();
                g.getTopToolbar().findById('view-eduCommunication2').disable();
	}
});



var parentEduCommunicationsViewWindow = new Ext.Window({
	title: 'EduCommunication Under the selected Item',
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
			parentEduCommunicationsViewWindow.close();
		}
	}]
});

store_parent_eduCommunications.load({
    params: {
        start: 0,    
        limit: list_size
    }
});