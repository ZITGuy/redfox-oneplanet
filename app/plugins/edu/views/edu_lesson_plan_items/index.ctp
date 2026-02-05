//<script>
var store_eduLessonPlanItems = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_lesson_plan','edu_period','edu_day','edu_outline','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: "<?php echo $this->Html->url(array('controller' => 'eduLessonPlanItems', 'action' => 'list_data')); ?>"
	})
        ,	sortInfo:{field: 'edu_lesson_plan_id', direction: "ASC"},
	groupField: 'edu_period_id'
});


function AddEduLessonPlanItem() {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'eduLessonPlanItems', 'action' => 'add')); ?>",
		success: function(response, opts) {
			var eduLessonPlanItem_data = response.responseText;
			
			eval(eduLessonPlanItem_data);
			
			EduLessonPlanItemAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduLessonPlanItem add form. Error code'); ?>: " + response.status);
		}
	});
}

function EditEduLessonPlanItem(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'eduLessonPlanItems', 'action' => 'edit')); ?>/"+id,
		success: function(response, opts) {
			var eduLessonPlanItem_data = response.responseText;
			
			eval(eduLessonPlanItem_data);
			
			EduLessonPlanItemEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduLessonPlanItem edit form. Error code'); ?>: " + response.status);
		}
	});
}

function ViewEduLessonPlanItem(id) {
    Ext.Ajax.request({
        url: "<?php echo $this->Html->url(array('controller' => 'eduLessonPlanItems', 'action' => 'view')); ?>/"+id,
        success: function(response, opts) {
            var eduLessonPlanItem_data = response.responseText;

            eval(eduLessonPlanItem_data);

            EduLessonPlanItemViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduLessonPlanItem view form. Error code'); ?>: " + response.status);
        }
    });
}

function DeleteEduLessonPlanItem(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'eduLessonPlanItems', 'action' => 'delete')); ?>/"+id,
		success: function(response, opts) {
			Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('EduLessonPlanItem successfully deleted!'); ?>");
			RefreshEduLessonPlanItemData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduLessonPlanItem add form. Error code'); ?>: " + response.status);
		}
	});
}

function SearchEduLessonPlanItem(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduLessonPlanItems', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduLessonPlanItem_data = response.responseText;

			eval(eduLessonPlanItem_data);

			eduLessonPlanItemSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduLessonPlanItem search form. Error Code'); ?>: " + response.status);
		}
	});
}

function SearchByEduLessonPlanItemName(value){
	var conditions = '\'EduLessonPlanItem.name LIKE\' => \'%' + value + '%\'';
	store_eduLessonPlanItems.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduLessonPlanItemData() {
	store_eduLessonPlanItems.reload();
}


if(center_panel.find('id', 'eduLessonPlanItem-tab') != "") {
	var p = center_panel.findById('eduLessonPlanItem-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Lesson Plan Items'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduLessonPlanItem-tab',
		xtype: 'grid',
		store: store_eduLessonPlanItems,
		columns: [
			{header: "<?php __('EduLessonPlan'); ?>", dataIndex: 'edu_lesson_plan', sortable: true},
			{header: "<?php __('EduPeriod'); ?>", dataIndex: 'edu_period', sortable: true},
			{header: "<?php __('EduDay'); ?>", dataIndex: 'edu_day', sortable: true},
			{header: "<?php __('EduOutline'); ?>", dataIndex: 'edu_outline', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduLessonPlanItems" : "EduLessonPlanItem"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduLessonPlanItem(Ext.getCmp('eduLessonPlanItem-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: "<?php __('Add'); ?>",
					tooltip: "<?php __('<b>Add EduLessonPlanItems</b><br />Click here to create a new EduLessonPlanItem'); ?>",
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduLessonPlanItem();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Edit'); ?>",
					id: 'edit-eduLessonPlanItem',
					tooltip: "<?php __('<b>Edit EduLessonPlanItems</b><br />Click here to modify the selected EduLessonPlanItem'); ?>",
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduLessonPlanItem(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Delete'); ?>",
					id: 'delete-eduLessonPlanItem',
					tooltip: "<?php __('<b>Delete EduLessonPlanItems(s)</b><br />Click here to remove the selected EduLessonPlanItem(s)'); ?>",
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: "<?php __('Remove EduLessonPlanItem'); ?>",
									buttons: Ext.MessageBox.YESNO,
									msg: "<?php __('Remove'); ?> "+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduLessonPlanItem(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: "<?php __('Remove EduLessonPlanItem'); ?>",
									buttons: Ext.MessageBox.YESNO,
									msg: "<?php __('Remove the selected EduLessonPlanItems'); ?>?",
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduLessonPlanItem(sel_ids);
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
					text: "<?php __('View EduLessonPlanItem'); ?>",
					id: 'view-eduLessonPlanItem',
					tooltip: "<?php __('<b>View EduLessonPlanItem</b><br />Click here to see details of the selected EduLessonPlanItem'); ?>",
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduLessonPlanItem(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  "<?php __('EduLessonPlan'); ?>: ", {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($edulessonplans as $item){if($st) echo ",
							";?>['<?php echo $item['EduLessonPlan']['id']; ?>' ,'<?php echo $item['EduLessonPlan']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_eduLessonPlanItems.reload({
								params: {
									start: 0,
									limit: list_size,
									edulessonplan_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: "<?php __('[Search By Name]'); ?>",
					id: 'eduLessonPlanItem_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduLessonPlanItemName(Ext.getCmp('eduLessonPlanItem_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('GO'); ?>",
                                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
					id: "eduLessonPlanItem_go_button",
					handler: function(){
						SearchByEduLessonPlanItemName(Ext.getCmp('eduLessonPlanItem_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('Advanced Search'); ?>",
                                        tooltip:"<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
					handler: function(){
						SearchEduLessonPlanItem();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduLessonPlanItems,
			displayInfo: true,
			displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
			beforePageText: "<?php __('Page'); ?>",
			afterPageText: "<?php __('of {0}'); ?>",
			emptyMsg: "<?php __('No data to display'); ?>"
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduLessonPlanItem').enable();
		p.getTopToolbar().findById('delete-eduLessonPlanItem').enable();
		p.getTopToolbar().findById('view-eduLessonPlanItem').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduLessonPlanItem').disable();
			p.getTopToolbar().findById('view-eduLessonPlanItem').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduLessonPlanItem').disable();
			p.getTopToolbar().findById('view-eduLessonPlanItem').disable();
			p.getTopToolbar().findById('delete-eduLessonPlanItem').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduLessonPlanItem').enable();
			p.getTopToolbar().findById('view-eduLessonPlanItem').enable();
			p.getTopToolbar().findById('delete-eduLessonPlanItem').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduLessonPlanItem').disable();
			p.getTopToolbar().findById('view-eduLessonPlanItem').disable();
			p.getTopToolbar().findById('delete-eduLessonPlanItem').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduLessonPlanItems.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}