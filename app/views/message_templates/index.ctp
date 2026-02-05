//<script>
var store_message_templates = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','body','default_body','placeholders','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'message_templates', 'action' => 'list_data')); ?>'
	}),
	sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'body'

});


function AddMessageTemplate() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'message_templates', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var message_template_data = response.responseText;
			
			eval(message_template_data);
			
			MessageTemplateAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Message Template add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditMessageTemplate(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'message_templates', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var message_template_data = response.responseText;
			
			eval(message_template_data);
			
			MessageTemplateEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Message Template edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewMessageTemplate(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'message_templates', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var message_template_data = response.responseText;

            eval(message_template_data);

            MessageTemplateViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Message Template view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteMessageTemplate(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'message_templates', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Message Template successfully deleted!'); ?>');
			RefreshMessageTemplateData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Message Template add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchMessageTemplate(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'message_templates', 'action' => 'search')); ?>',
		success: function(response, opts){
			var message_template_data = response.responseText;

			eval(message_template_data);

			messageTemplateSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the Message Template search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByMessageTemplateName(value){
	var conditions = '\'MessageTemplate.name LIKE\' => \'%' + value + '%\'';
	store_message_templates.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshMessageTemplateData() {
	store_message_templates.reload();
}


if(center_panel.find('id', 'message_template_tab') != "") {
	var p = center_panel.findById('message_template_tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Message Templates'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'message_template_tab',
		xtype: 'grid',
		store: store_message_templates,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('Body'); ?>", dataIndex: 'body', sortable: true},
			{header: "<?php __('Default Body'); ?>", dataIndex: 'default_body', sortable: true},
			{header: "<?php __('Placeholders'); ?>", dataIndex: 'placeholders', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Message Templates" : "Message Template"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewMessageTemplate(Ext.getCmp('message_template_tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Message Templates</b><br />Click here to create a new Message Template'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddMessageTemplate();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit_message_template',
					tooltip:'<?php __('<b>Edit Message Templates</b><br />Click here to modify the selected Message Template'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditMessageTemplate(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete_message_template',
					tooltip:'<?php __('<b>Delete Message Template(s)</b><br />Click here to remove the selected Message Template(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Message Template'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteMessageTemplate(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Message Template'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Message Templates'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteMessageTemplate(sel_ids);
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
					text: '<?php __('View Message Template'); ?>',
					id: 'view_message_template',
					tooltip:'<?php __('<b>View Message Template</b><br />Click here to see details of the selected Message Template'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewMessageTemplate(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'message_template_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByMessageTemplateName(Ext.getCmp('message_template_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'message_template_go_button',
					handler: function(){
						SearchByMessageTemplateName(Ext.getCmp('message_template_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchMessageTemplate();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_message_templates,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit_message_template').enable();
		p.getTopToolbar().findById('delete_message_template').enable();
		p.getTopToolbar().findById('view_message_template').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit_message_template').disable();
			p.getTopToolbar().findById('view_message_template').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit_message_template').disable();
			p.getTopToolbar().findById('view_message_template').disable();
			p.getTopToolbar().findById('delete_message_template').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit_message_template').enable();
			p.getTopToolbar().findById('view_message_template').enable();
			p.getTopToolbar().findById('delete_message_template').enable();
		}
		else{
			p.getTopToolbar().findById('edit_message_template').disable();
			p.getTopToolbar().findById('view_message_template').disable();
			p.getTopToolbar().findById('delete_message_template').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_message_templates.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
