//<script>
var store_eduDays = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','date','week_day','edu_quarter','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: "<?php echo $this->Html->url(array('controller' => 'eduDays', 'action' => 'list_data')); ?>"
	})
        ,	sortInfo:{field: 'date', direction: "ASC"},
	groupField: 'week_day'
});


function AddEduDay() {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'eduDays', 'action' => 'add')); ?>",
		success: function(response, opts) {
			var eduDay_data = response.responseText;
			
			eval(eduDay_data);
			
			EduDayAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduDay add form. Error code'); ?>: " + response.status);
		}
	});
}

function EditEduDay(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'eduDays', 'action' => 'edit')); ?>/"+id,
		success: function(response, opts) {
			var eduDay_data = response.responseText;
			
			eval(eduDay_data);
			
			EduDayEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduDay edit form. Error code'); ?>: " + response.status);
		}
	});
}

function ViewEduDay(id) {
    Ext.Ajax.request({
        url: "<?php echo $this->Html->url(array('controller' => 'eduDays', 'action' => 'view')); ?>/"+id,
        success: function(response, opts) {
            var eduDay_data = response.responseText;

            eval(eduDay_data);

            EduDayViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduDay view form. Error code'); ?>: " + response.status);
        }
    });
}

function DeleteEduDay(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'eduDays', 'action' => 'delete')); ?>/"+id,
		success: function(response, opts) {
			Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('EduDay successfully deleted!'); ?>");
			RefreshEduDayData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduDay add form. Error code'); ?>: " + response.status);
		}
	});
}

function SearchEduDay(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduDays', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduDay_data = response.responseText;

			eval(eduDay_data);

			eduDaySearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduDay search form. Error Code'); ?>: " + response.status);
		}
	});
}

function SearchByEduDayName(value){
	var conditions = '\'EduDay.name LIKE\' => \'%' + value + '%\'';
	store_eduDays.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduDayData() {
	store_eduDays.reload();
}


if(center_panel.find('id', 'eduDay-tab') != "") {
	var p = center_panel.findById('eduDay-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Days'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduDay-tab',
		xtype: 'grid',
		store: store_eduDays,
		columns: [
			{header: "<?php __('Date'); ?>", dataIndex: 'date', sortable: true},
			{header: "<?php __('Week Day'); ?>", dataIndex: 'week_day', sortable: true},
			{header: "<?php __('EduQuarter'); ?>", dataIndex: 'edu_quarter', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduDays" : "EduDay"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduDay(Ext.getCmp('eduDay-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: "<?php __('Add'); ?>",
					tooltip: "<?php __('<b>Add EduDays</b><br />Click here to create a new EduDay'); ?>",
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduDay();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Edit'); ?>",
					id: 'edit-eduDay',
					tooltip: "<?php __('<b>Edit EduDays</b><br />Click here to modify the selected EduDay'); ?>",
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduDay(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Delete'); ?>",
					id: 'delete-eduDay',
					tooltip: "<?php __('<b>Delete EduDays(s)</b><br />Click here to remove the selected EduDay(s)'); ?>",
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: "<?php __('Remove EduDay'); ?>",
									buttons: Ext.MessageBox.YESNO,
									msg: "<?php __('Remove'); ?> "+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduDay(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: "<?php __('Remove EduDay'); ?>",
									buttons: Ext.MessageBox.YESNO,
									msg: "<?php __('Remove the selected EduDays'); ?>?",
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduDay(sel_ids);
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
					text: "<?php __('View EduDay'); ?>",
					id: 'view-eduDay',
					tooltip: "<?php __('<b>View EduDay</b><br />Click here to see details of the selected EduDay'); ?>",
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduDay(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  "<?php __('EduQuarter'); ?>: ", {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($eduquarters as $item){if($st) echo ",
							";?>['<?php echo $item['EduQuarter']['id']; ?>' ,'<?php echo $item['EduQuarter']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_eduDays.reload({
								params: {
									start: 0,
									limit: list_size,
									eduquarter_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: "<?php __('[Search By Name]'); ?>",
					id: 'eduDay_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduDayName(Ext.getCmp('eduDay_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('GO'); ?>",
                                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
					id: "eduDay_go_button",
					handler: function(){
						SearchByEduDayName(Ext.getCmp('eduDay_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('Advanced Search'); ?>",
                                        tooltip:"<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
					handler: function(){
						SearchEduDay();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduDays,
			displayInfo: true,
			displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
			beforePageText: "<?php __('Page'); ?>",
			afterPageText: "<?php __('of {0}'); ?>",
			emptyMsg: "<?php __('No data to display'); ?>"
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduDay').enable();
		p.getTopToolbar().findById('delete-eduDay').enable();
		p.getTopToolbar().findById('view-eduDay').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduDay').disable();
			p.getTopToolbar().findById('view-eduDay').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduDay').disable();
			p.getTopToolbar().findById('view-eduDay').disable();
			p.getTopToolbar().findById('delete-eduDay').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduDay').enable();
			p.getTopToolbar().findById('view-eduDay').enable();
			p.getTopToolbar().findById('delete-eduDay').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduDay').disable();
			p.getTopToolbar().findById('view-eduDay').disable();
			p.getTopToolbar().findById('delete-eduDay').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduDays.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}