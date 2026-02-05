var store_parent_absentees = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','attendance_record','student','code','reason'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_absentees', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentAbsentee() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_absentees', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_absentee_data = response.responseText;
			
			eval(parent_absentee_data);
			
			AbsenteeAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the absentee add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentAbsentee(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_absentees', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_absentee_data = response.responseText;
			
			eval(parent_absentee_data);
			
			AbsenteeEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the absentee edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewAbsentee(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_absentees', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var absentee_data = response.responseText;

			eval(absentee_data);

			AbsenteeViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the absentee view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentAbsentee(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_absentees', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Absentee(s) successfully deleted!'); ?>');
			RefreshParentAbsenteeData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the absentee to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentAbsenteeName(value){
	var conditions = '\'Absentee.name LIKE\' => \'%' + value + '%\'';
	store_parent_absentees.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentAbsenteeData() {
	store_parent_absentees.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('Absentees'); ?>',
	store: store_parent_absentees,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'absenteeGrid',
	columns: [
		{header:"<?php __('student'); ?>", dataIndex: 'student', sortable: true},
		{header: "<?php __('Code'); ?>", dataIndex: 'code', sortable: true},
		{header: "<?php __('Reason'); ?>", dataIndex: 'reason', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewAbsentee(Ext.getCmp('absenteeGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add Absentee</b><br />Click here to create a new Absentee'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentAbsentee();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-absentee',
				tooltip:'<?php __('<b>Delete Absentee(s)</b><br />Click here to remove the selected Absentee(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove Absentee'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentAbsentee(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove Absentee'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected Absentee'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentAbsentee(sel_ids);
											}
									}
							});
						}
					} else {
						Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
					};
				}
			}, ' ','-',' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_absentee_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentAbsenteeName(Ext.getCmp('parent_absentee_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_absentee_go_button',
				handler: function(){
					SearchByParentAbsenteeName(Ext.getCmp('parent_absentee_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_absentees,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	
	g.getTopToolbar().findById('delete-parent-absentee').enable();
     
	if(this.getSelections().length > 1){
		
               
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		
		g.getTopToolbar().findById('delete-parent-absentee').enable();
            
	}
	else if(this.getSelections().length == 1){
		
		g.getTopToolbar().findById('delete-parent-absentee').enable();
             
	}
	else{
		
		g.getTopToolbar().findById('delete-parent-absentee').disable();
             
	}
});



var parentAbsenteesViewWindow = new Ext.Window({
	title: 'Absentee Under the selected Item',
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
			parentAbsenteesViewWindow.close();
		}
	}]
});

store_parent_absentees.load({
    params: {
        start: 0,    
        limit: list_size
    }
});