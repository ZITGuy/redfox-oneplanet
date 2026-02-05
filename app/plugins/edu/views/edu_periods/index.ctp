
var store_eduPeriods = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_section','edu_course_Id','edu_schedule','day','period'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPeriods', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'edu_section_id', direction: "ASC"},
	groupField: 'edu_course_Id'
});


function AddEduPeriod() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPeriods', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduPeriod_data = response.responseText;
			
			eval(eduPeriod_data);
			
			EduPeriodAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPeriod add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduPeriod(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPeriods', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduPeriod_data = response.responseText;
			
			eval(eduPeriod_data);
			
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

function DeleteEduPeriod(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPeriods', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduPeriod successfully deleted!'); ?>');
			RefreshEduPeriodData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPeriod add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduPeriod(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPeriods', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduPeriod_data = response.responseText;

			eval(eduPeriod_data);

			eduPeriodSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the eduPeriod search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduPeriodName(value){
	var conditions = '\'EduPeriod.name LIKE\' => \'%' + value + '%\'';
	store_eduPeriods.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduPeriodData() {
	store_eduPeriods.reload();
}


if(center_panel.find('id', 'eduPeriod-tab') != "") {
	var p = center_panel.findById('eduPeriod-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Periods'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduPeriod-tab',
		xtype: 'grid',
		store: store_eduPeriods,
		columns: [
			{header: "<?php __('EduSection'); ?>", dataIndex: 'edu_section', sortable: true},
			{header: "<?php __('Edu Course Id'); ?>", dataIndex: 'edu_course_Id', sortable: true},
			{header: "<?php __('EduSchedule'); ?>", dataIndex: 'edu_schedule', sortable: true},
			{header: "<?php __('Day'); ?>", dataIndex: 'day', sortable: true},
			{header: "<?php __('Period'); ?>", dataIndex: 'period', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduPeriods" : "EduPeriod"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduPeriod(Ext.getCmp('eduPeriod-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add EduPeriods</b><br />Click here to create a new EduPeriod'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduPeriod();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduPeriod',
					tooltip:'<?php __('<b>Edit EduPeriods</b><br />Click here to modify the selected EduPeriod'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduPeriod(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduPeriod',
					tooltip:'<?php __('<b>Delete EduPeriods(s)</b><br />Click here to remove the selected EduPeriod(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove EduPeriod'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduPeriod(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove EduPeriod'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected EduPeriods'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduPeriod(sel_ids);
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
					text: '<?php __('View EduPeriod'); ?>',
					id: 'view-eduPeriod',
					tooltip:'<?php __('<b>View EduPeriod</b><br />Click here to see details of the selected EduPeriod'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduPeriod(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '<?php __('EduSection'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($edusections as $item){if($st) echo ",
							";?>['<?php echo $item['EduSection']['id']; ?>' ,'<?php echo $item['EduSection']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_eduPeriods.reload({
								params: {
									start: 0,
									limit: list_size,
									edusection_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'eduPeriod_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduPeriodName(Ext.getCmp('eduPeriod_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduPeriod_go_button',
					handler: function(){
						SearchByEduPeriodName(Ext.getCmp('eduPeriod_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduPeriod();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduPeriods,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduPeriod').enable();
		p.getTopToolbar().findById('delete-eduPeriod').enable();
		p.getTopToolbar().findById('view-eduPeriod').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduPeriod').disable();
			p.getTopToolbar().findById('view-eduPeriod').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduPeriod').disable();
			p.getTopToolbar().findById('view-eduPeriod').disable();
			p.getTopToolbar().findById('delete-eduPeriod').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduPeriod').enable();
			p.getTopToolbar().findById('view-eduPeriod').enable();
			p.getTopToolbar().findById('delete-eduPeriod').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduPeriod').disable();
			p.getTopToolbar().findById('view-eduPeriod').disable();
			p.getTopToolbar().findById('delete-eduPeriod').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduPeriods.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
