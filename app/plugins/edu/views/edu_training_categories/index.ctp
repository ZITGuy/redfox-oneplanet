//<script>
var store_eduTrainingCategories = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduTrainingCategories', 'action' => 'list_data')); ?>'
	}),	
	sortInfo:{field: 'name', direction: "ASC"}
});


function AddEduTrainingCategory() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduTrainingCategories', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduTrainingCategory_data = response.responseText;
			
			eval(eduTrainingCategory_data);
			
			EduTrainingCategoryAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduTrainingCategory add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduTrainingCategory(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduTrainingCategories', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduTrainingCategory_data = response.responseText;
			
			eval(eduTrainingCategory_data);
			
			EduTrainingCategoryEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduTrainingCategory edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduTrainingCategory(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduTrainingCategories', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var eduTrainingCategory_data = response.responseText;

            eval(eduTrainingCategory_data);

            EduTrainingCategoryViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduTrainingCategory view form. Error code'); ?>: ' + response.status);
        }
    });
}
function ViewParentEduTrainings(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduTrainings', 'action' => 'index2')); ?>/'+id,
        success: function(response, opts) {
            var parent_eduTrainings_data = response.responseText;

            eval(parent_eduTrainings_data);

            parentEduTrainingsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
        }
    });
}


function DeleteEduTrainingCategory(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduTrainingCategories', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduTrainingCategory successfully deleted!'); ?>');
			RefreshEduTrainingCategoryData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduTrainingCategory add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduTrainingCategory(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduTrainingCategories', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduTrainingCategory_data = response.responseText;

			eval(eduTrainingCategory_data);

			eduTrainingCategorySearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the eduTrainingCategory search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduTrainingCategoryName(value){
	var conditions = '\'EduTrainingCategory.name LIKE\' => \'%' + value + '%\'';
	store_eduTrainingCategories.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduTrainingCategoryData() {
	store_eduTrainingCategories.reload();
}


if(center_panel.find('id', 'eduTrainingCategory-tab') != "") {
	var p = center_panel.findById('eduTrainingCategory-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Training Categories'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduTrainingCategory-tab',
		xtype: 'grid',
		store: store_eduTrainingCategories,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
		],
		viewConfig: {
            forceFit:true
        },
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Training Categories</b><br />Click here to create a new Training Category'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduTrainingCategory();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduTrainingCategory',
					tooltip:'<?php __('<b>Edit EduTrainingCategories</b><br />Click here to modify the selected EduTrainingCategory'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduTrainingCategory(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduTrainingCategory',
					tooltip:'<?php __('<b>Delete Training Categories(s)</b><br />Click here to remove the selected Training Category(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Training Category'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduTrainingCategory(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Training Category'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Training Categories'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduTrainingCategory(sel_ids);
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
					id: 'eduTrainingCategory_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduTrainingCategoryName(Ext.getCmp('eduTrainingCategory_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduTrainingCategory_go_button',
					handler: function(){
						SearchByEduTrainingCategoryName(Ext.getCmp('eduTrainingCategory_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduTrainingCategory();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduTrainingCategories,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduTrainingCategory').enable();
		p.getTopToolbar().findById('delete-eduTrainingCategory').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduTrainingCategory').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduTrainingCategory').disable();
			p.getTopToolbar().findById('delete-eduTrainingCategory').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduTrainingCategory').enable();
			p.getTopToolbar().findById('delete-eduTrainingCategory').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduTrainingCategory').disable();
			p.getTopToolbar().findById('delete-eduTrainingCategory').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduTrainingCategories.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
