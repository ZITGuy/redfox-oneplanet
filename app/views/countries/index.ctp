//<script>
var store_countries = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','code','currency','nationality','language'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'countries', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'code'
});


function AddCountry() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'countries', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var country_data = response.responseText;
			
			eval(country_data);
			
			CountryAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the country add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditCountry(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'countries', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var country_data = response.responseText;
			
			eval(country_data);
			
			CountryEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the country edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewCountry(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'countries', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var country_data = response.responseText;

            eval(country_data);

            CountryViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the country view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteCountry(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'countries', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Country successfully deleted!'); ?>');
			RefreshCountryData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the country add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchCountry(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'countries', 'action' => 'search')); ?>',
		success: function(response, opts){
			var country_data = response.responseText;

			eval(country_data);

			countrySearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the country search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByCountryName(value){
	var conditions = '\'Country.name LIKE\' => \'%' + value + '%\'';
	store_countries.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshCountryData() {
	store_countries.reload();
}


if(center_panel.find('id', 'country-tab') != "") {
	var p = center_panel.findById('country-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Countries'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'country-tab',
		xtype: 'grid',
		store: store_countries,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('Code'); ?>", dataIndex: 'code', sortable: true},
			{header: "<?php __('Currency'); ?>", dataIndex: 'currency', sortable: true},
			{header: "<?php __('Nationality'); ?>", dataIndex: 'nationality', sortable: true},
			{header: "<?php __('Language'); ?>", dataIndex: 'language', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Countries" : "Country"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewCountry(Ext.getCmp('country-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Countries</b><br />Click here to create a new Country'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddCountry();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-country',
					tooltip:'<?php __('<b>Edit Countries</b><br />Click here to modify the selected Country'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditCountry(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-country',
					tooltip:'<?php __('<b>Delete Countries(s)</b><br />Click here to remove the selected Country(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Country'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteCountry(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Country'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Countries'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteCountry(sel_ids);
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
					text: '<?php __('View Country'); ?>',
					id: 'view-country',
					tooltip:'<?php __('<b>View Country</b><br />Click here to see details of the selected Country'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewCountry(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'country_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByCountryName(Ext.getCmp('country_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'country_go_button',
					handler: function(){
						SearchByCountryName(Ext.getCmp('country_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchCountry();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_countries,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-country').enable();
		p.getTopToolbar().findById('delete-country').enable();
		p.getTopToolbar().findById('view-country').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-country').disable();
			p.getTopToolbar().findById('view-country').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-country').disable();
			p.getTopToolbar().findById('view-country').disable();
			p.getTopToolbar().findById('delete-country').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-country').enable();
			p.getTopToolbar().findById('view-country').enable();
			p.getTopToolbar().findById('delete-country').enable();
		}
		else{
			p.getTopToolbar().findById('edit-country').disable();
			p.getTopToolbar().findById('view-country').disable();
			p.getTopToolbar().findById('delete-country').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_countries.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
