//<script>
var store_eduEvaluationAreas = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','edu_evaluation_category','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluation_areas', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'edu_evaluation_category'
});


function AddEduEvaluationArea() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluation_areas', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduEvaluationArea_data = response.responseText;
			
			eval(eduEvaluationArea_data);
			
			EduEvaluationAreaAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Evaluation Area add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduEvaluationArea(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluation_areas', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduEvaluationArea_data = response.responseText;
			
			eval(eduEvaluationArea_data);
			
			EduEvaluationAreaEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Evaluation Area edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduEvaluationArea(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluation_areas', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var eduEvaluationArea_data = response.responseText;

            eval(eduEvaluationArea_data);

            EduEvaluationAreaViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Evaluation Area view form. Error code'); ?>: ' + response.status);
        }
    });
}
function ViewParentEduEvaluations(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluations', 'action' => 'index2')); ?>/'+id,
        success: function(response, opts) {
            var parent_eduEvaluations_data = response.responseText;

            eval(parent_eduEvaluations_data);

            parentEduEvaluationsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Evaluation view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteEduEvaluationArea(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluation_areas', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Evaluation Area successfully deleted!'); ?>');
			RefreshEduEvaluationAreaData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Evaluation Area add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduEvaluationArea(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluation_areas', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduEvaluationArea_data = response.responseText;

			eval(eduEvaluationArea_data);

			eduEvaluationAreaSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the Evaluation Area search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduEvaluationAreaName(value){
	var conditions = '\'EduEvaluationArea.name LIKE\' => \'%' + value + '%\'';
	store_eduEvaluationAreas.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduEvaluationAreaData() {
	store_eduEvaluationAreas.reload();
}


if(center_panel.find('id', 'eduEvaluationArea-tab') != "") {
	var p = center_panel.findById('eduEvaluationArea-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Evaluation Areas'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduEvaluationArea-tab',
		xtype: 'grid',
		store: store_eduEvaluationAreas,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('Evaluation Category'); ?>", dataIndex: 'edu_evaluation_category', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Evaluation Areas" : "Evaluation Area"]})'
        }),
		listeners: {
			celldblclick: function(){
				ViewEduEvaluationArea(Ext.getCmp('eduEvaluationArea-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Evaluation Areas</b><br />Click here to create a new Evaluation Area'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduEvaluationArea();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduEvaluationArea',
					tooltip:'<?php __('<b>Edit Evaluation Areas</b><br />Click here to modify the selected Evaluation Area'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduEvaluationArea(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduEvaluationArea',
					tooltip:'<?php __('<b>Delete Evaluation Areas(s)</b><br />Click here to remove the selected Evaluation Area(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Evaluation Area'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduEvaluationArea(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Evaluation Area'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Evaluation Areas'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduEvaluationArea(sel_ids);
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
					text: '<?php __('View Evaluation Area'); ?>',
					id: 'view-eduEvaluationArea',
					tooltip:'<?php __('<b>View Evaluation Area</b><br />Click here to see details of the selected Evaluation Area'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduEvaluationArea(sel.data.id);
						}
					},
					menu : {
						items: [{
							text: '<?php __('View Evaluations'); ?>',
                            icon: 'img/table_view.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ShowErrorBox("This feature is under development", "DEV-001-01");
									//ViewParentEduEvaluations(sel.data.id);
								};
							}
						}
						]
					}
				}, ' ', '-',  '<?php __('Evaluation Category'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($edu_evaluation_categories as $item){if($st) echo ",
							";?>['<?php echo $item['EduEvaluationCategory']['id']; ?>' ,'<?php echo $item['EduEvaluationCategory']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_eduEvaluationAreas.reload({
								params: {
									start: 0,
									limit: list_size,
									eduevaluationcategory_id : combo.getValue()
								}
							});
						}
					}
				}, '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'eduEvaluationArea_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduEvaluationAreaName(Ext.getCmp('eduEvaluationArea_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduEvaluationArea_go_button',
					handler: function(){
						SearchByEduEvaluationAreaName(Ext.getCmp('eduEvaluationArea_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduEvaluationArea();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduEvaluationAreas,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduEvaluationArea').enable();
		p.getTopToolbar().findById('delete-eduEvaluationArea').enable();
		p.getTopToolbar().findById('view-eduEvaluationArea').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduEvaluationArea').disable();
			p.getTopToolbar().findById('view-eduEvaluationArea').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduEvaluationArea').disable();
			p.getTopToolbar().findById('view-eduEvaluationArea').disable();
			p.getTopToolbar().findById('delete-eduEvaluationArea').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduEvaluationArea').enable();
			p.getTopToolbar().findById('view-eduEvaluationArea').enable();
			p.getTopToolbar().findById('delete-eduEvaluationArea').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduEvaluationArea').disable();
			p.getTopToolbar().findById('view-eduEvaluationArea').disable();
			p.getTopToolbar().findById('delete-eduEvaluationArea').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduEvaluationAreas.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
