//<script>
var store_parent_eduDays = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','date','week_day','edu_quarter','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduDays', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduDay() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduDays', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduDay_data = response.responseText;
			
			eval(parent_eduDay_data);
			
			EduDayAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduDay add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduDay(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduDays', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduDay_data = response.responseText;
			
			eval(parent_eduDay_data);
			
			EduDayEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduDay edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduDay(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduDays', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduDay_data = response.responseText;

			eval(eduDay_data);

			EduDayViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduDay view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentEduDay(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduDays', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduDay(s) successfully deleted!'); ?>');
			RefreshParentEduDayData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduDay to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduDayName(value){
	var conditions = '\'EduDay.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduDays.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduDayData() {
	store_parent_eduDays.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('EduDays'); ?>',
	store: store_parent_eduDays,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduDayGrid',
	columns: [
		{header: "<?php __('Date'); ?>", dataIndex: 'date', sortable: true},
		{header: "<?php __('Week Day'); ?>", dataIndex: 'week_day', sortable: true},
		{header:"<?php __('edu_quarter'); ?>", dataIndex: 'edu_quarter', sortable: true},
		{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
		{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewEduDay(Ext.getCmp('eduDayGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add EduDay</b><br />Click here to create a new EduDay'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduDay();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduDay',
				tooltip:'<?php __('<b>Edit EduDay</b><br />Click here to modify the selected EduDay'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduDay(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduDay',
				tooltip:'<?php __('<b>Delete EduDay(s)</b><br />Click here to remove the selected EduDay(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove EduDay'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduDay(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove EduDay'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected EduDay'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentEduDay(sel_ids);
											}
									}
							});
						}
					} else {
						Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
					};
				}
			}, ' ','-',' ', {
				xtype: 'tbsplit',
				text: '<?php __('View EduDay'); ?>',
				id: 'view-eduDay2',
				tooltip:'<?php __('<b>View EduDay</b><br />Click here to see details of the selected EduDay'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduDay(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduDay_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduDayName(Ext.getCmp('parent_eduDay_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: '
Strict Standards: Non-static method I18n::translate() should not be called statically in C:\wamp\www\smmp_new\cake\basics.php on line 655

Strict Standards: Non-static method I18n::getInstance() should not be called statically in C:\wamp\www\smmp_new\cake\libs\i18n.php on line 130

Strict Standards: Non-static method Configure::read() should not be called statically in C:\wamp\www\smmp_new\cake\libs\i18n.php on line 142

Strict Standards: Non-static method Configure::getInstance() should not be called statically in C:\wamp\www\smmp_new\cake\libs\configure.php on line 154
GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduDay_go_button',
				handler: function(){
					SearchByParentEduDayName(Ext.getCmp('parent_eduDay_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduDays,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduDay').enable();
	g.getTopToolbar().findById('delete-parent-eduDay').enable();
        g.getTopToolbar().findById('view-eduDay2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduDay').disable();
                g.getTopToolbar().findById('view-eduDay2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduDay').disable();
		g.getTopToolbar().findById('delete-parent-eduDay').enable();
                g.getTopToolbar().findById('view-eduDay2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduDay').enable();
		g.getTopToolbar().findById('delete-parent-eduDay').enable();
                g.getTopToolbar().findById('view-eduDay2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduDay').disable();
		g.getTopToolbar().findById('delete-parent-eduDay').disable();
                g.getTopToolbar().findById('view-eduDay2').disable();
	}
});



var parentEduDaysViewWindow = new Ext.Window({
	title: 'EduDay Under the selected Item',
	width: 700,
	height:375,
	minWidth: 700,
	minHeight: 400,
	resizable: false,
	plain:true,
	bodyStyle:'padding:5px;',
	buttonAlign:'center',
        modal: true,
	items: [
		g
	],

	buttons: [{
		text: '
Strict Standards: Non-static method I18n::translate() should not be called statically in C:\wamp\www\smmp_new\cake\basics.php on line 655

Strict Standards: Non-static method I18n::getInstance() should not be called statically in C:\wamp\www\smmp_new\cake\libs\i18n.php on line 130

Strict Standards: Non-static method Configure::read() should not be called statically in C:\wamp\www\smmp_new\cake\libs\i18n.php on line 142

Strict Standards: Non-static method Configure::getInstance() should not be called statically in C:\wamp\www\smmp_new\cake\libs\configure.php on line 154
Close',
		handler: function(btn){
			parentEduDaysViewWindow.close();
		}
	}]
});

store_parent_eduDays.load({
    params: {
        start: 0,    
        limit: list_size
    }
});
