//<script>
var store_edu_communications = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_student','edu_section','post_date','teacher_comment','parent_comment','user','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_communications', 'action' => 'list_data')); ?>'
	}),
	sortInfo:{field: '', direction: "ASC"},
	groupField: 'edu_student'

});


function AddEduCommunication() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_communications', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var edu_communication_data = response.responseText;
			
			eval(edu_communication_data);
			
			EduCommunicationAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Edu Communication add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduCommunication(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_communications', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var edu_communication_data = response.responseText;
			
			eval(edu_communication_data);
			
			EduCommunicationEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Edu Communication edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduCommunication(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'edu_communications', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var edu_communication_data = response.responseText;

            eval(edu_communication_data);

            EduCommunicationViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Edu Communication view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteEduCommunication(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_communications', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Edu Communication successfully deleted!'); ?>');
			RefreshEduCommunicationData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Edu Communication add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduCommunication(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_communications', 'action' => 'search')); ?>',
		success: function(response, opts){
			var edu_communication_data = response.responseText;

			eval(edu_communication_data);

			eduCommunicationSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the Edu Communication search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduCommunicationName(value){
	var conditions = '\'EduCommunication.name LIKE\' => \'%' + value + '%\'';
	store_edu_communications.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduCommunicationData() {
	store_edu_communications.reload();
}


if(center_panel.find('id', 'edu_communication_tab') != "") {
	var p = center_panel.findById('edu_communication_tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Communications'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'edu_communication_tab',
		xtype: 'grid',
		store: store_edu_communications,
		columns: [
			{header: "<?php __('EduStudent'); ?>", dataIndex: 'edu_student', sortable: true},
			{header: "<?php __('EduSection'); ?>", dataIndex: 'edu_section', sortable: true},
			{header: "<?php __('Post Date'); ?>", dataIndex: 'post_date', sortable: true},
			{header: "<?php __('Teacher Comment'); ?>", dataIndex: 'teacher_comment', sortable: true},
			{header: "<?php __('Parent Comment'); ?>", dataIndex: 'parent_comment', sortable: true},
			{header: "<?php __('User'); ?>", dataIndex: 'user', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Edu Communications" : "Edu Communication"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduCommunication(Ext.getCmp('edu_communication_tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Edu Communications</b><br />Click here to create a new Edu Communication'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduCommunication();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit_edu_communication',
					tooltip:'<?php __('<b>Edit Edu Communications</b><br />Click here to modify the selected Edu Communication'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduCommunication(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete_edu_communication',
					tooltip:'<?php __('<b>Delete Edu Communication(s)</b><br />Click here to remove the selected Edu Communication(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Edu Communication'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduCommunication(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Edu Communication'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Edu Communications'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduCommunication(sel_ids);
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
					text: '<?php __('View Edu Communication'); ?>',
					id: 'view_edu_communication',
					tooltip:'<?php __('<b>View Edu Communication</b><br />Click here to see details of the selected Edu Communication'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduCommunication(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '<?php __('EduStudent'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($edustudents as $item){if($st) echo ",
							";?>['<?php echo $item['EduStudent']['id']; ?>' ,'<?php echo $item['EduStudent']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_edu_communications.reload({
								params: {
									start: 0,
									limit: list_size,
									edustudent_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'edu_communication_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduCommunicationName(Ext.getCmp('edu_communication_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'edu_communication_go_button',
					handler: function(){
						SearchByEduCommunicationName(Ext.getCmp('edu_communication_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduCommunication();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_edu_communications,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit_edu_communication').enable();
		p.getTopToolbar().findById('delete_edu_communication').enable();
		p.getTopToolbar().findById('view_edu_communication').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit_edu_communication').disable();
			p.getTopToolbar().findById('view_edu_communication').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit_edu_communication').disable();
			p.getTopToolbar().findById('view_edu_communication').disable();
			p.getTopToolbar().findById('delete_edu_communication').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit_edu_communication').enable();
			p.getTopToolbar().findById('view_edu_communication').enable();
			p.getTopToolbar().findById('delete_edu_communication').enable();
		}
		else{
			p.getTopToolbar().findById('edit_edu_communication').disable();
			p.getTopToolbar().findById('view_edu_communication').disable();
			p.getTopToolbar().findById('delete_edu_communication').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_edu_communications.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
