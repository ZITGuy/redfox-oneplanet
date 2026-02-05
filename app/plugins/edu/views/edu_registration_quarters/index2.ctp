var store_parent_eduRegistrationQuarters = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_registration','edu_quarter','quarter_average','quarter_rank','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarters', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduRegistrationQuarter() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarters', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduRegistrationQuarter_data = response.responseText;
			
			eval(parent_eduRegistrationQuarter_data);
			
			EduRegistrationQuarterAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationQuarter add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduRegistrationQuarter(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarters', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduRegistrationQuarter_data = response.responseText;
			
			eval(parent_eduRegistrationQuarter_data);
			
			EduRegistrationQuarterEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationQuarter edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduRegistrationQuarter(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarters', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduRegistrationQuarter_data = response.responseText;

			eval(eduRegistrationQuarter_data);

			EduRegistrationQuarterViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationQuarter view form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduRegistrationQuarterEduRegistrationQuarterResults(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarterResults', 'action' => 'index2')); ?>/'+id,
		success: function(response, opts) {
			var parent_eduRegistrationQuarterResults_data = response.responseText;

			eval(parent_eduRegistrationQuarterResults_data);

			parentEduRegistrationQuarterResultsViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentEduRegistrationQuarter(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarters', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduRegistrationQuarter(s) successfully deleted!'); ?>');
			RefreshParentEduRegistrationQuarterData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationQuarter to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduRegistrationQuarterName(value){
	var conditions = '\'EduRegistrationQuarter.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduRegistrationQuarters.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduRegistrationQuarterData() {
	store_parent_eduRegistrationQuarters.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('EduRegistrationQuarters'); ?>',
	store: store_parent_eduRegistrationQuarters,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduRegistrationQuarterGrid',
	columns: [
		{header:"<?php __('edu_registration'); ?>", dataIndex: 'edu_registration', sortable: true},
		{header:"<?php __('edu_quarter'); ?>", dataIndex: 'edu_quarter', sortable: true},
		{header: "<?php __('Quarter Average'); ?>", dataIndex: 'quarter_average', sortable: true},
		{header: "<?php __('Quarter Rank'); ?>", dataIndex: 'quarter_rank', sortable: true},
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
            ViewEduRegistrationQuarter(Ext.getCmp('eduRegistrationQuarterGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add EduRegistrationQuarter</b><br />Click here to create a new EduRegistrationQuarter'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduRegistrationQuarter();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduRegistrationQuarter',
				tooltip:'<?php __('<b>Edit EduRegistrationQuarter</b><br />Click here to modify the selected EduRegistrationQuarter'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduRegistrationQuarter(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduRegistrationQuarter',
				tooltip:'<?php __('<b>Delete EduRegistrationQuarter(s)</b><br />Click here to remove the selected EduRegistrationQuarter(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove EduRegistrationQuarter'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduRegistrationQuarter(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove EduRegistrationQuarter'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected EduRegistrationQuarter'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentEduRegistrationQuarter(sel_ids);
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
				text: '<?php __('View EduRegistrationQuarter'); ?>',
				id: 'view-eduRegistrationQuarter2',
				tooltip:'<?php __('<b>View EduRegistrationQuarter</b><br />Click here to see details of the selected EduRegistrationQuarter'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduRegistrationQuarter(sel.data.id);
					};
				},
				menu : {
					items: [
 {
						text: '<?php __('View Edu Registration Quarter Results'); ?>',
                        icon: 'img/table_view.png',
						cls: 'x-btn-text-icon',
						handler: function(btn) {
							var sm = g.getSelectionModel();
							var sel = sm.getSelected();
							if (sm.hasSelection()){
								ViewEduRegistrationQuarterEduRegistrationQuarterResults(sel.data.id);
							};
						}
					}
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduRegistrationQuarter_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduRegistrationQuarterName(Ext.getCmp('parent_eduRegistrationQuarter_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduRegistrationQuarter_go_button',
				handler: function(){
					SearchByParentEduRegistrationQuarterName(Ext.getCmp('parent_eduRegistrationQuarter_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduRegistrationQuarters,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduRegistrationQuarter').enable();
	g.getTopToolbar().findById('delete-parent-eduRegistrationQuarter').enable();
        g.getTopToolbar().findById('view-eduRegistrationQuarter2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduRegistrationQuarter').disable();
                g.getTopToolbar().findById('view-eduRegistrationQuarter2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduRegistrationQuarter').disable();
		g.getTopToolbar().findById('delete-parent-eduRegistrationQuarter').enable();
                g.getTopToolbar().findById('view-eduRegistrationQuarter2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduRegistrationQuarter').enable();
		g.getTopToolbar().findById('delete-parent-eduRegistrationQuarter').enable();
                g.getTopToolbar().findById('view-eduRegistrationQuarter2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduRegistrationQuarter').disable();
		g.getTopToolbar().findById('delete-parent-eduRegistrationQuarter').disable();
                g.getTopToolbar().findById('view-eduRegistrationQuarter2').disable();
	}
});



var parentEduRegistrationQuartersViewWindow = new Ext.Window({
	title: 'EduRegistrationQuarter Under the selected Item',
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
			parentEduRegistrationQuartersViewWindow.close();
		}
	}]
});

store_parent_eduRegistrationQuarters.load({
    params: {
        start: 0,    
        limit: list_size
    }
});