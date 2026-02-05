var store_parent_eduPeriods = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_section','edu_course_Id','edu_schedule','day','period'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPeriods', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduPeriod() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPeriods', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduPeriod_data = response.responseText;
			
			eval(parent_eduPeriod_data);
			
			EduPeriodAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPeriod add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduPeriod(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPeriods', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduPeriod_data = response.responseText;
			
			eval(parent_eduPeriod_data);
			
			EduPeriodEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPeriod edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduPeriod(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPeriods', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduPeriod_data = response.responseText;

			eval(eduPeriod_data);

			EduPeriodViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPeriod view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentEduPeriod(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPeriods', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduPeriod(s) successfully deleted!'); ?>');
			RefreshParentEduPeriodData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPeriod to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduPeriodName(value){
	var conditions = '\'EduPeriod.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduPeriods.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduPeriodData() {
	store_parent_eduPeriods.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('EduPeriods'); ?>',
	store: store_parent_eduPeriods,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduPeriodGrid',
	columns: [
		{header:"<?php __('edu_section'); ?>", dataIndex: 'edu_section', sortable: true},
		{header: "<?php __('Edu Course Id'); ?>", dataIndex: 'edu_course_Id', sortable: true},
		{header:"<?php __('edu_schedule'); ?>", dataIndex: 'edu_schedule', sortable: true},
		{header: "<?php __('Day'); ?>", dataIndex: 'day', sortable: true},
		{header: "<?php __('Period'); ?>", dataIndex: 'period', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewEduPeriod(Ext.getCmp('eduPeriodGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add EduPeriod</b><br />Click here to create a new EduPeriod'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduPeriod();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduPeriod',
				tooltip:'<?php __('<b>Edit EduPeriod</b><br />Click here to modify the selected EduPeriod'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduPeriod(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduPeriod',
				tooltip:'<?php __('<b>Delete EduPeriod(s)</b><br />Click here to remove the selected EduPeriod(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove EduPeriod'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduPeriod(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove EduPeriod'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected EduPeriod'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentEduPeriod(sel_ids);
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
				text: '<?php __('View EduPeriod'); ?>',
				id: 'view-eduPeriod2',
				tooltip:'<?php __('<b>View EduPeriod</b><br />Click here to see details of the selected EduPeriod'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduPeriod(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduPeriod_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduPeriodName(Ext.getCmp('parent_eduPeriod_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduPeriod_go_button',
				handler: function(){
					SearchByParentEduPeriodName(Ext.getCmp('parent_eduPeriod_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduPeriods,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduPeriod').enable();
	g.getTopToolbar().findById('delete-parent-eduPeriod').enable();
        g.getTopToolbar().findById('view-eduPeriod2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduPeriod').disable();
                g.getTopToolbar().findById('view-eduPeriod2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduPeriod').disable();
		g.getTopToolbar().findById('delete-parent-eduPeriod').enable();
                g.getTopToolbar().findById('view-eduPeriod2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduPeriod').enable();
		g.getTopToolbar().findById('delete-parent-eduPeriod').enable();
                g.getTopToolbar().findById('view-eduPeriod2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduPeriod').disable();
		g.getTopToolbar().findById('delete-parent-eduPeriod').disable();
                g.getTopToolbar().findById('view-eduPeriod2').disable();
	}
});



var parentEduPeriodsViewWindow = new Ext.Window({
	title: 'EduPeriod Under the selected Item',
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
			parentEduPeriodsViewWindow.close();
		}
	}]
});

store_parent_eduPeriods.load({
    params: {
        start: 0,    
        limit: list_size
    }
});