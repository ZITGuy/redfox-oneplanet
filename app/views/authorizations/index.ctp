//<script>
var store_authorizations = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','maker','created'		
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'authorizations', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'maker', direction: "ASC"},
	groupField: 'name'
});

function EditAuthorization(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'authorizations', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var authorization_data = response.responseText;
			
			eval(authorization_data);
			
			AuthorizationEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the authorization edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByAuthorizationName(value){
	var conditions = '\'Authorization.name LIKE\' => \'%' + value + '%\'';
	store_authorizations.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshAuthorizationData() {
	store_authorizations.reload();
}


if(center_panel.find('id', 'authorization-tab') != "") {
	var p = center_panel.findById('authorization-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Authorizations'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'authorization-tab',
		xtype: 'grid',
		store: store_authorizations,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('Maker'); ?>", dataIndex: 'maker', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
		],
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Authorizations" : "Authorization"]})'
        }),
		listeners: {
			celldblclick: function(){
				ViewAuthorization(Ext.getCmp('authorization-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Authorize'); ?>',
					id: 'accept-authorization',
					tooltip:'<?php __('<b>Authorize Record</b><br />Click here to modify the selected Authorization'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditAuthorization(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Reject'); ?>',
					id: 'reject-authorization',
					tooltip:'<?php __('<b>Reject Record</b><br />Click here to modify the selected Authorization'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditAuthorization(sel.data.id);
						};
					}
				}, ' ', '-',  '<?php __('Maker'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($makers as $item){if($st) echo ",
							";?>['<?php echo $item['Maker']['id']; ?>' ,'<?php echo $item['Maker']['username']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_authorizations.reload({
								params: {
									start: 0,
									limit: list_size,
									maker_id : combo.getValue()
								}
							});
						}
					}
				}, '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'authorization_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByAuthorizationName(Ext.getCmp('authorization_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'authorization_go_button',
					handler: function(){
						SearchByAuthorizationName(Ext.getCmp('authorization_search_field').getValue());
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_authorizations,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('accept-authorization').enable();
		p.getTopToolbar().findById('reject-authorization').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('accept-authorization').disable();
			p.getTopToolbar().findById('reject-authorization').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('accept-authorization').disable();
			p.getTopToolbar().findById('reject-authorization').disable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('accept-authorization').enable();
			p.getTopToolbar().findById('reject-authorization').enable();
		}
		else{
			p.getTopToolbar().findById('accept-authorization').disable();
			p.getTopToolbar().findById('reject-authorization').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_authorizations.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
