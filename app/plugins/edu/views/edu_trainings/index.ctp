//<script>
var store_eduTrainings = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','category','deleted','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_trainings', 'action' => 'list_data')); ?>'
	}),	
	sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'category'
});


function AddEduTraining() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_trainings', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduTraining_data = response.responseText;
			
			eval(eduTraining_data);
			
			EduTrainingAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Training add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduTraining(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_trainings', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduTraining_data = response.responseText;
			
			eval(eduTraining_data);
			
			EduTrainingEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Training edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function DeleteEduTraining(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_trainings', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Training successfully deleted!'); ?>');
			RefreshEduTrainingData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Training add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduTraining(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_trainings', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduTraining_data = response.responseText;

			eval(eduTraining_data);

			eduTrainingSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the Training search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduTrainingName(value){
	var conditions = '\'EduTraining.name LIKE\' => \'%' + value + '%\'';
	store_eduTrainings.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduTrainingData() {
	store_eduTrainings.reload();
}

if(center_panel.find('id', 'eduTraining-tab') != "") {
	var p = center_panel.findById('eduTraining-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Trainings'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduTraining-tab',
		xtype: 'grid',
		store: store_eduTrainings,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('Category'); ?>", dataIndex: 'category', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
		],
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Trainings" : "Training"]})'
        }),
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Trainings</b><br />Click here to create a new Training'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduTraining();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduTraining',
					tooltip:'<?php __('<b>Edit Trainings</b><br />Click here to modify the selected Training'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduTraining(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduTraining',
					tooltip:'<?php __('<b>Delete Trainings(s)</b><br />Click here to remove the selected Training(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove EduTraining'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduTraining(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Training'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Trainings'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduTraining(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
						};
					}
				}, ' ', '-',  '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'eduTraining_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduTrainingName(Ext.getCmp('eduTraining_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduTraining_go_button',
					handler: function(){
						SearchByEduTrainingName(Ext.getCmp('eduTraining_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduTraining();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduTrainings,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduTraining').enable();
		p.getTopToolbar().findById('delete-eduTraining').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduTraining').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduTraining').disable();
			p.getTopToolbar().findById('delete-eduTraining').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduTraining').enable();
			p.getTopToolbar().findById('delete-eduTraining').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduTraining').disable();
			p.getTopToolbar().findById('delete-eduTraining').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduTrainings.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
