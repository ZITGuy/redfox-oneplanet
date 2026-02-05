//<script>
var store_eduRegistrations = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','edu_student','edu_section','created','modified'		
                ]
	}),
	proxy: new Ext.data.HttpProxy({
		url: "<?php echo $this->Html->url(array('controller' => 'eduRegistrations', 'action' => 'list_data')); ?>"
	}),	
        sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'edu_student_id'
});


function AddEduRegistration() {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'eduRegistrations', 'action' => 'add')); ?>",
		success: function(response, opts) {
			var eduRegistration_data = response.responseText;
			
			eval(eduRegistration_data);
			
			EduRegistrationAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduRegistration add form. Error code'); ?>: " + response.status);
		}
	});
}

function EditEduRegistration(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'eduRegistrations', 'action' => 'edit')); ?>/"+id,
		success: function(response, opts) {
			var eduRegistration_data = response.responseText;
			
			eval(eduRegistration_data);
			
			EduRegistrationEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduRegistration edit form. Error code'); ?>: " + response.status);
		}
	});
}

function ViewEduRegistration(id) {
    Ext.Ajax.request({
        url: "<?php echo $this->Html->url(array('controller' => 'eduRegistrations', 'action' => 'view')); ?>/"+id,
        success: function(response, opts) {
            var eduRegistration_data = response.responseText;

            eval(eduRegistration_data);

            EduRegistrationViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduRegistration view form. Error code'); ?>: " + response.status);
        }
    });
}
function ViewParentEduResults(id) {
    Ext.Ajax.request({
        url: "<?php echo $this->Html->url(array('controller' => 'eduResults', 'action' => 'index2')); ?>/"+id,
        success: function(response, opts) {
            var parent_eduResults_data = response.responseText;

            eval(parent_eduResults_data);

            parentEduResultsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert("<?php __('Error'); ?><?php __('Cannot get the campus view form. Error code'); ?>: " + response.status);
        }
    });
}


function DeleteEduRegistration(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'eduRegistrations', 'action' => 'delete')); ?>/"+id,
		success: function(response, opts) {
			Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('EduRegistration successfully deleted!'); ?>");
			RefreshEduRegistrationData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduRegistration add form. Error code'); ?>: " + response.status);
		}
	});
}

function SearchEduRegistration(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrations', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduRegistration_data = response.responseText;

			eval(eduRegistration_data);

			eduRegistrationSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduRegistration search form. Error Code'); ?>: " + response.status);
		}
	});
}

function SearchByEduRegistrationName(value){
	var conditions = '\'EduRegistration.name LIKE\' => \'%' + value + '%\'';
	store_eduRegistrations.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduRegistrationData() {
	store_eduRegistrations.reload();
}


if(center_panel.find('id', 'eduRegistration-tab') != "") {
	var p = center_panel.findById('eduRegistration-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Registrations'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduRegistration-tab',
		xtype: 'grid',
		store: store_eduRegistrations,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('EduStudent'); ?>", dataIndex: 'edu_student', sortable: true},
			{header: "<?php __('EduSection'); ?>", dataIndex: 'edu_section', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduRegistrations" : "EduRegistration"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduRegistration(Ext.getCmp('eduRegistration-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: "<?php __('Add'); ?>",
					tooltip: "<?php __('<b>Add EduRegistrations</b><br />Click here to create a new EduRegistration'); ?>",
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduRegistration();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Edit'); ?>",
					id: 'edit-eduRegistration',
					tooltip: "<?php __('<b>Edit EduRegistrations</b><br />Click here to modify the selected EduRegistration'); ?>",
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduRegistration(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Delete'); ?>",
					id: 'delete-eduRegistration',
					tooltip: "<?php __('<b>Delete EduRegistrations(s)</b><br />Click here to remove the selected EduRegistration(s)'); ?>",
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: "<?php __('Remove EduRegistration'); ?>",
									buttons: Ext.MessageBox.YESNO,
									msg: "<?php __('Remove'); ?> "+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduRegistration(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: "<?php __('Remove EduRegistration'); ?>",
									buttons: Ext.MessageBox.YESNO,
									msg: "<?php __('Remove the selected EduRegistrations'); ?>?",
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduRegistration(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert("<?php __('Warning'); ?>", "<?php __('Please select a record first'); ?>");
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbsplit',
					text: "<?php __('View EduRegistration'); ?>",
					id: 'view-eduRegistration',
					tooltip: "<?php __('<b>View EduRegistration</b><br />Click here to see details of the selected EduRegistration'); ?>",
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduRegistration(sel.data.id);
						};
					},
					menu : {
						items: [
{
							text: '<?php __('View Edu Results'); ?>',
                                                        icon: 'img/table_view.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ViewParentEduResults(sel.data.id);
								};
							}
						}
						]
					}
				}, ' ', '-',  "<?php __('EduStudent'); ?>: ", {
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
							store_eduRegistrations.reload({
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
					emptyText: "<?php __('[Search By Name]'); ?>",
					id: 'eduRegistration_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduRegistrationName(Ext.getCmp('eduRegistration_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('GO'); ?>",
                                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
					id: "eduRegistration_go_button",
					handler: function(){
						SearchByEduRegistrationName(Ext.getCmp('eduRegistration_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('Advanced Search'); ?>",
                                        tooltip:"<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
					handler: function(){
						SearchEduRegistration();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduRegistrations,
			displayInfo: true,
			displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
			beforePageText: "<?php __('Page'); ?>",
			afterPageText: "<?php __('of {0}'); ?>",
			emptyMsg: "<?php __('No data to display'); ?>"
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduRegistration').enable();
		p.getTopToolbar().findById('delete-eduRegistration').enable();
		p.getTopToolbar().findById('view-eduRegistration').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduRegistration').disable();
			p.getTopToolbar().findById('view-eduRegistration').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduRegistration').disable();
			p.getTopToolbar().findById('view-eduRegistration').disable();
			p.getTopToolbar().findById('delete-eduRegistration').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduRegistration').enable();
			p.getTopToolbar().findById('view-eduRegistration').enable();
			p.getTopToolbar().findById('delete-eduRegistration').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduRegistration').disable();
			p.getTopToolbar().findById('view-eduRegistration').disable();
			p.getTopToolbar().findById('delete-eduRegistration').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduRegistrations.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}