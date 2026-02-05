var store_parent_eduRegistrationQuarterResults = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_registration_quarter','edu_course','course_result','result_indicator','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarterResults', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduRegistrationQuarterResult() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarterResults', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduRegistrationQuarterResult_data = response.responseText;
			
			eval(parent_eduRegistrationQuarterResult_data);
			
			EduRegistrationQuarterResultAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationQuarterResult add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduRegistrationQuarterResult(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarterResults', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduRegistrationQuarterResult_data = response.responseText;
			
			eval(parent_eduRegistrationQuarterResult_data);
			
			EduRegistrationQuarterResultEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationQuarterResult edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduRegistrationQuarterResult(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarterResults', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduRegistrationQuarterResult_data = response.responseText;

			eval(eduRegistrationQuarterResult_data);

			EduRegistrationQuarterResultViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationQuarterResult view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentEduRegistrationQuarterResult(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarterResults', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduRegistrationQuarterResult(s) successfully deleted!'); ?>');
			RefreshParentEduRegistrationQuarterResultData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationQuarterResult to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduRegistrationQuarterResultName(value){
	var conditions = '\'EduRegistrationQuarterResult.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduRegistrationQuarterResults.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduRegistrationQuarterResultData() {
	store_parent_eduRegistrationQuarterResults.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('EduRegistrationQuarterResults'); ?>',
	store: store_parent_eduRegistrationQuarterResults,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduRegistrationQuarterResultGrid',
	columns: [
		{header:"<?php __('edu_registration_quarter'); ?>", dataIndex: 'edu_registration_quarter', sortable: true},
		{header:"<?php __('edu_course'); ?>", dataIndex: 'edu_course', sortable: true},
		{header: "<?php __('Course Result'); ?>", dataIndex: 'course_result', sortable: true},
		{header: "<?php __('Result Indicator'); ?>", dataIndex: 'result_indicator', sortable: true},
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
            ViewEduRegistrationQuarterResult(Ext.getCmp('eduRegistrationQuarterResultGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add EduRegistrationQuarterResult</b><br />Click here to create a new EduRegistrationQuarterResult'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduRegistrationQuarterResult();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduRegistrationQuarterResult',
				tooltip:'<?php __('<b>Edit EduRegistrationQuarterResult</b><br />Click here to modify the selected EduRegistrationQuarterResult'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduRegistrationQuarterResult(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduRegistrationQuarterResult',
				tooltip:'<?php __('<b>Delete EduRegistrationQuarterResult(s)</b><br />Click here to remove the selected EduRegistrationQuarterResult(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove EduRegistrationQuarterResult'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduRegistrationQuarterResult(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove EduRegistrationQuarterResult'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected EduRegistrationQuarterResult'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentEduRegistrationQuarterResult(sel_ids);
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
				text: '<?php __('View EduRegistrationQuarterResult'); ?>',
				id: 'view-eduRegistrationQuarterResult2',
				tooltip:'<?php __('<b>View EduRegistrationQuarterResult</b><br />Click here to see details of the selected EduRegistrationQuarterResult'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduRegistrationQuarterResult(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduRegistrationQuarterResult_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduRegistrationQuarterResultName(Ext.getCmp('parent_eduRegistrationQuarterResult_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduRegistrationQuarterResult_go_button',
				handler: function(){
					SearchByParentEduRegistrationQuarterResultName(Ext.getCmp('parent_eduRegistrationQuarterResult_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduRegistrationQuarterResults,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduRegistrationQuarterResult').enable();
	g.getTopToolbar().findById('delete-parent-eduRegistrationQuarterResult').enable();
        g.getTopToolbar().findById('view-eduRegistrationQuarterResult2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduRegistrationQuarterResult').disable();
                g.getTopToolbar().findById('view-eduRegistrationQuarterResult2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduRegistrationQuarterResult').disable();
		g.getTopToolbar().findById('delete-parent-eduRegistrationQuarterResult').enable();
                g.getTopToolbar().findById('view-eduRegistrationQuarterResult2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduRegistrationQuarterResult').enable();
		g.getTopToolbar().findById('delete-parent-eduRegistrationQuarterResult').enable();
                g.getTopToolbar().findById('view-eduRegistrationQuarterResult2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduRegistrationQuarterResult').disable();
		g.getTopToolbar().findById('delete-parent-eduRegistrationQuarterResult').disable();
                g.getTopToolbar().findById('view-eduRegistrationQuarterResult2').disable();
	}
});



var parentEduRegistrationQuarterResultsViewWindow = new Ext.Window({
	title: 'EduRegistrationQuarterResult Under the selected Item',
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
			parentEduRegistrationQuarterResultsViewWindow.close();
		}
	}]
});

store_parent_eduRegistrationQuarterResults.load({
    params: {
        start: 0,    
        limit: list_size
    }
});