
var store_eduCourseItems = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','description','edu_course','max_mark','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCourseItems', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'description'
});


function AddEduCourseItem() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCourseItems', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduCourseItem_data = response.responseText;
			
			eval(eduCourseItem_data);
			
			EduCourseItemAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCourseItem add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduCourseItem(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCourseItems', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduCourseItem_data = response.responseText;
			
			eval(eduCourseItem_data);
			
			EduCourseItemEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCourseItem edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduCourseItem(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduCourseItems', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var eduCourseItem_data = response.responseText;

            eval(eduCourseItem_data);

            EduCourseItemViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCourseItem view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteEduCourseItem(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCourseItems', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduCourseItem successfully deleted!'); ?>');
			RefreshEduCourseItemData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCourseItem add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduCourseItem(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCourseItems', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduCourseItem_data = response.responseText;

			eval(eduCourseItem_data);

			eduCourseItemSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the eduCourseItem search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduCourseItemName(value){
	var conditions = '\'EduCourseItem.name LIKE\' => \'%' + value + '%\'';
	store_eduCourseItems.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduCourseItemData() {
	store_eduCourseItems.reload();
}


if(center_panel.find('id', 'eduCourseItem-tab') != "") {
	var p = center_panel.findById('eduCourseItem-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Course Items'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduCourseItem-tab',
		xtype: 'grid',
		store: store_eduCourseItems,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('Description'); ?>", dataIndex: 'description', sortable: true},
			{header: "<?php __('EduCourse'); ?>", dataIndex: 'edu_course', sortable: true},
			{header: "<?php __('Max Mark'); ?>", dataIndex: 'max_mark', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduCourseItems" : "EduCourseItem"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduCourseItem(Ext.getCmp('eduCourseItem-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add EduCourseItems</b><br />Click here to create a new EduCourseItem'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduCourseItem();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduCourseItem',
					tooltip:'<?php __('<b>Edit EduCourseItems</b><br />Click here to modify the selected EduCourseItem'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduCourseItem(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduCourseItem',
					tooltip:'<?php __('<b>Delete EduCourseItems(s)</b><br />Click here to remove the selected EduCourseItem(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove EduCourseItem'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduCourseItem(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove EduCourseItem'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected EduCourseItems'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduCourseItem(sel_ids);
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
					text: '<?php __('View EduCourseItem'); ?>',
					id: 'view-eduCourseItem',
					tooltip:'<?php __('<b>View EduCourseItem</b><br />Click here to see details of the selected EduCourseItem'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduCourseItem(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '<?php __('EduCourse'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($educourses as $item){if($st) echo ",
							";?>['<?php echo $item['EduCourse']['id']; ?>' ,'<?php echo $item['EduCourse']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_eduCourseItems.reload({
								params: {
									start: 0,
									limit: list_size,
									educourse_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'eduCourseItem_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduCourseItemName(Ext.getCmp('eduCourseItem_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduCourseItem_go_button',
					handler: function(){
						SearchByEduCourseItemName(Ext.getCmp('eduCourseItem_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduCourseItem();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduCourseItems,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduCourseItem').enable();
		p.getTopToolbar().findById('delete-eduCourseItem').enable();
		p.getTopToolbar().findById('view-eduCourseItem').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduCourseItem').disable();
			p.getTopToolbar().findById('view-eduCourseItem').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduCourseItem').disable();
			p.getTopToolbar().findById('view-eduCourseItem').disable();
			p.getTopToolbar().findById('delete-eduCourseItem').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduCourseItem').enable();
			p.getTopToolbar().findById('view-eduCourseItem').enable();
			p.getTopToolbar().findById('delete-eduCourseItem').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduCourseItem').disable();
			p.getTopToolbar().findById('view-eduCourseItem').disable();
			p.getTopToolbar().findById('delete-eduCourseItem').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduCourseItems.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
