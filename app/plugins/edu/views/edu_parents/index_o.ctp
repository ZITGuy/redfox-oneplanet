//<script>
var store_eduParents = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','authorized_person','marital_status','primary_parent','secret_code',
			'sms_phone_number','created','modified'		
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: "<?php echo $this->Html->url(array('controller' => 'edu_parents', 'action' => 'list_data2')); ?>"
	}),	
	sortInfo:{field: 'authorized_person', direction: "ASC"}
});

function AddEduParent() {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'edu_parents', 'action' => 'add')); ?>",
		success: function(response, opts) {
			var eduParent_data = response.responseText;
			
			eval(eduParent_data);
			
			EduParentAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduParent add form. Error code'); ?>: " + response.status);
		}
	});
}

function EditEduParent(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'edu_parents', 'action' => 'edit')); ?>/"+id,
		success: function(response, opts) {
			var eduParent_data = response.responseText;
			
			eval(eduParent_data);
			
			EduParentEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Parent edit form. Error code'); ?>: " + response.status);
		}
	});
}

function ViewEduParent(id) {
    Ext.Ajax.request({
        url: "<?php echo $this->Html->url(array('controller' => 'edu_parents', 'action' => 'view')); ?>/"+id,
        success: function(response, opts) {
            var eduParent_data = response.responseText;

            eval(eduParent_data);

            EduParentViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the edu_parents view form. Error code'); ?>: " + response.status);
        }
    });
}

function ViewParentEduStudents(id) {
    Ext.Ajax.request({
        url: "<?php echo $this->Html->url(array('controller' => 'edu_parents', 'action' => 'index2')); ?>/"+id,
        success: function(response, opts) {
            var parent_eduStudents_data = response.responseText;

            eval(parent_eduStudents_data);

            parentEduStudentsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert("<?php __('Error'); ?><?php __('Cannot get the campus view form. Error code'); ?>: " + response.status);
        }
    });
}

function ViewParentEduParentDetails(id) {
    Ext.Ajax.request({
        url: "<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'index2')); ?>/"+id,
        success: function(response, opts) {
            var parent_eduParentDetails_data = response.responseText;

            eval(parent_eduParentDetails_data);

            parentEduParentDetailsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert("<?php __('Error'); ?><?php __('Cannot get the Parent Detail view form. Error code'); ?>: " + response.status);
        }
    });
}

function DeleteEduParent(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'edu_parents', 'action' => 'delete')); ?>/"+id,
		success: function(response, opts) {
			Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Parent successfully deleted!'); ?>");
			RefreshEduParentData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Parent add form. Error code'); ?>: " + response.status);
		}
	});
}

function SearchEduParent(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduParents', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduParent_data = response.responseText;

			eval(eduParent_data);

			eduParentSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduParent search form. Error Code'); ?>: " + response.status);
		}
	});
}

function SearchByEduParentName(value){
	var conditions = '\'EduParent.name LIKE\' => \'%' + value + '%\'';
	store_eduParents.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduParentData() {
	store_eduParents.reload();
}


if(center_panel.find('id', 'eduParent-tab') != "") {
	var p = center_panel.findById('eduParent-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<font color=#00aa00><?php __('All Parents'); ?><sup>o</sup></font>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduParent-tab',
		xtype: 'grid',
		store: store_eduParents,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'authorized_person', sortable: true},
			{header: "<?php __('Marital Status'); ?>", dataIndex: 'marital_status', sortable: true},
			{header: "<?php __('Primary Parent'); ?>", dataIndex: 'primary_parent', sortable: true},
			{header: "<?php __('Secret Code'); ?>", dataIndex: 'secret_code', sortable: true},
			{header: "<?php __('Phone Number'); ?>", dataIndex: 'sms_phone_number', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
		],
		viewConfig:{
            forceFit:true
		},
		listeners: {
			celldblclick: function(){
				ViewEduParent(Ext.getCmp('eduParent-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: "<?php __('Add'); ?>",
					tooltip: "<?php __('<b>Edit Parents</b><br />Click here to modify the selected Parent'); ?>",
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduParent();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Edit'); ?>",
					id: 'edit-eduParent',
					tooltip: "<?php __('<b>Edit Parents</b><br />Click here to modify the selected Parent'); ?>",
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduParent(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Delete'); ?>",
					id: 'delete-eduParent',
					tooltip: "<?php __('<b>Delete Parent(s)</b><br />Click here to remove the selected Parent(s)'); ?>",
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: "<?php __('Remove Parent'); ?>",
									buttons: Ext.MessageBox.YESNO,
									msg: "<?php __('Remove'); ?> "+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduParent(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: "<?php __('Remove Parent'); ?>",
									buttons: Ext.MessageBox.YESNO,
									msg: "<?php __('Remove the selected Parents'); ?>?",
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduParent(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert("<?php __('Warning'); ?>", "<?php __('Please select a record first'); ?>");
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Parent Detail'); ?>",
					id: 'edit-eduParentDetails',
					tooltip: "<?php __('<b>Edit Parent Details</b><br />Click here to modify the selected Parent Details'); ?>",
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewParentEduParentDetails(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Students'); ?>",
					id: 'edit-eduParentStudents',
					tooltip: "<?php __('<b>Students under selected Parent</b><br />Click here to modify the selected Parent Students'); ?>",
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduParent(sel.data.id);
						}
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('View Parent'); ?>",
					id: 'view-eduParent',
					tooltip: "<?php __('<b>View Parent</b><br />Click here to see details of the selected Parent'); ?>",
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduParent(sel.data.id);
						}
					}
				}, ' ', '-', ' ', {
					xtype: 'tbsplit',
					text: "<?php __('Messages'); ?>",
					id: 'message-eduParent',
					tooltip: "<?php __('<b>View Parent</b><br />Click here to see details of the selected Parent'); ?>",
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					menu: {
						items: [{
							text: '<?php __('SMS'); ?>',
							icon: 'img/table_edit.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									//UploadEduStudentPhoto(sel.data.id);
								}
							}
						}, {
							text: '<?php __('Email'); ?>',
							icon: 'img/table_edit.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									//EditEduStudent(sel.data.id);
								}
							}
						}]
					}
				}, '->', {
					xtype: 'textfield',
					emptyText: "<?php __('[Search By Name]'); ?>",
					id: 'eduParent_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduParentName(Ext.getCmp('eduParent_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('GO'); ?>",
                    tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
					id: "eduParent_go_button",
					handler: function(){
						SearchByEduParentName(Ext.getCmp('eduParent_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('Advanced Search'); ?>",
                    tooltip: "<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
					handler: function(){
						SearchEduParent();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduParents,
			displayInfo: true,
			displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
			beforePageText: "<?php __('Page'); ?>",
			afterPageText: "<?php __('of {0}'); ?>",
			emptyMsg: "<?php __('No data to display'); ?>"
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduParent').enable();
		p.getTopToolbar().findById('edit-eduParentDetails').enable();
		p.getTopToolbar().findById('delete-eduParent').enable();
		p.getTopToolbar().findById('view-eduParent').enable();
		p.getTopToolbar().findById('message-eduParent').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduParent').disable();
			p.getTopToolbar().findById('edit-eduParentDetails').disable();
			p.getTopToolbar().findById('view-eduParent').disable();
			p.getTopToolbar().findById('message-eduParent').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduParent').disable();
			p.getTopToolbar().findById('edit-eduParentDetails').disable();
			p.getTopToolbar().findById('view-eduParent').disable();
			p.getTopToolbar().findById('message-eduParent').disable();
			p.getTopToolbar().findById('delete-eduParent').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduParent').enable();
			p.getTopToolbar().findById('edit-eduParentDetails').enable();
			p.getTopToolbar().findById('view-eduParent').enable();
			p.getTopToolbar().findById('message-eduParent').enable();
			p.getTopToolbar().findById('delete-eduParent').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduParent').disable();
			p.getTopToolbar().findById('edit-eduParentDetails').disable();
			p.getTopToolbar().findById('view-eduParent').disable();
			p.getTopToolbar().findById('message-eduParent').disable();
			p.getTopToolbar().findById('delete-eduParent').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduParents.load({
		params: {
			start: 0,     
			limit: list_size
		}
	});
	
}
