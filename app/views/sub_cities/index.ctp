
var store_subCities = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'subCities', 'action' => 'list_data')); ?>'
	})
});


function AddSubCity() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'subCities', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var subCity_data = response.responseText;
			
			eval(subCity_data);
			
			SubCityAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the subCity add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditSubCity(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'subCities', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var subCity_data = response.responseText;
			
			eval(subCity_data);
			
			SubCityEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the subCity edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewSubCity(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'subCities', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var subCity_data = response.responseText;

            eval(subCity_data);

            SubCityViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the subCity view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteSubCity(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'subCities', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('SubCity successfully deleted!'); ?>');
			RefreshSubCityData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the subCity add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchSubCity(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'subCities', 'action' => 'search')); ?>',
		success: function(response, opts){
			var subCity_data = response.responseText;

			eval(subCity_data);

			subCitySearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the subCity search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchBySubCityName(value){
	var conditions = '\'SubCity.name LIKE\' => \'%' + value + '%\'';
	store_subCities.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshSubCityData() {
	store_subCities.reload();
}


if(center_panel.find('id', 'subCity-tab') != "") {
	var p = center_panel.findById('subCity-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Sub Cities'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'subCity-tab',
		xtype: 'grid',
		store: store_subCities,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true}
		],
		viewConfig: {
			forceFit: true
		}
,
		listeners: {
			celldblclick: function(){
				ViewSubCity(Ext.getCmp('subCity-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add SubCities</b><br />Click here to create a new SubCity'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddSubCity();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-subCity',
					tooltip:'<?php __('<b>Edit SubCities</b><br />Click here to modify the selected SubCity'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditSubCity(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-subCity',
					tooltip:'<?php __('<b>Delete Sub Cities(s)</b><br />Click here to remove the selected SubCity(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove SubCity'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteSubCity(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove SubCity'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected SubCities'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteSubCity(sel_ids);
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
					text: '<?php __('View SubCity'); ?>',
					id: 'view-subCity',
					tooltip:'<?php __('<b>View SubCity</b><br />Click here to see details of the selected SubCity'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewSubCity(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'subCity_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchBySubCityName(Ext.getCmp('subCity_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'subCity_go_button',
					handler: function(){
						SearchBySubCityName(Ext.getCmp('subCity_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchSubCity();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_subCities,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-subCity').enable();
		p.getTopToolbar().findById('delete-subCity').enable();
		p.getTopToolbar().findById('view-subCity').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-subCity').disable();
			p.getTopToolbar().findById('view-subCity').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-subCity').disable();
			p.getTopToolbar().findById('view-subCity').disable();
			p.getTopToolbar().findById('delete-subCity').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-subCity').enable();
			p.getTopToolbar().findById('view-subCity').enable();
			p.getTopToolbar().findById('delete-subCity').enable();
		}
		else{
			p.getTopToolbar().findById('edit-subCity').disable();
			p.getTopToolbar().findById('view-subCity').disable();
			p.getTopToolbar().findById('delete-subCity').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_subCities.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
