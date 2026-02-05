//<script>
var store_parent_eduLessonPlans = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_course','edu_section','maker','checker','is_posted','posts','status','reason','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduLessonPlans', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduLessonPlan() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduLessonPlans', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduLessonPlan_data = response.responseText;
			
			eval(parent_eduLessonPlan_data);
			
			EduLessonPlanAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduLessonPlan add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduLessonPlan(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduLessonPlans', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduLessonPlan_data = response.responseText;
			
			eval(parent_eduLessonPlan_data);
			
			EduLessonPlanEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduLessonPlan edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduLessonPlan(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduLessonPlans', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduLessonPlan_data = response.responseText;

			eval(eduLessonPlan_data);

			EduLessonPlanViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduLessonPlan view form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduLessonPlanEduLessonPlanItems(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduLessonPlanItems', 'action' => 'index2')); ?>/'+id,
		success: function(response, opts) {
			var parent_eduLessonPlanItems_data = response.responseText;

			eval(parent_eduLessonPlanItems_data);

			parentEduLessonPlanItemsViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentEduLessonPlan(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduLessonPlans', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduLessonPlan(s) successfully deleted!'); ?>');
			RefreshParentEduLessonPlanData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduLessonPlan to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduLessonPlanName(value){
	var conditions = '\'EduLessonPlan.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduLessonPlans.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduLessonPlanData() {
	store_parent_eduLessonPlans.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('EduLessonPlans'); ?>',
	store: store_parent_eduLessonPlans,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduLessonPlanGrid',
	columns: [
		{header:"<?php __('edu_course'); ?>", dataIndex: 'edu_course', sortable: true},
		{header:"<?php __('edu_section'); ?>", dataIndex: 'edu_section', sortable: true},
		{header: "<?php __('Maker'); ?>", dataIndex: 'maker', sortable: true},
		{header: "<?php __('Checker'); ?>", dataIndex: 'checker', sortable: true},
		{header: "<?php __('Is Posted'); ?>", dataIndex: 'is_posted', sortable: true},
		{header: "<?php __('Posts'); ?>", dataIndex: 'posts', sortable: true},
		{header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true},
		{header: "<?php __('Reason'); ?>", dataIndex: 'reason', sortable: true},
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
            ViewEduLessonPlan(Ext.getCmp('eduLessonPlanGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add EduLessonPlan</b><br />Click here to create a new EduLessonPlan'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduLessonPlan();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduLessonPlan',
				tooltip:'<?php __('<b>Edit EduLessonPlan</b><br />Click here to modify the selected EduLessonPlan'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduLessonPlan(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduLessonPlan',
				tooltip:'<?php __('<b>Delete EduLessonPlan(s)</b><br />Click here to remove the selected EduLessonPlan(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove EduLessonPlan'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduLessonPlan(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove EduLessonPlan'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected EduLessonPlan'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentEduLessonPlan(sel_ids);
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
				text: '<?php __('View EduLessonPlan'); ?>',
				id: 'view-eduLessonPlan2',
				tooltip:'<?php __('<b>View EduLessonPlan</b><br />Click here to see details of the selected EduLessonPlan'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduLessonPlan(sel.data.id);
					};
				},
				menu : {
					items: [
 {
						text: '<?php __('View Edu Lesson Plan Items'); ?>',
                        icon: 'img/table_view.png',
						cls: 'x-btn-text-icon',
						handler: function(btn) {
							var sm = g.getSelectionModel();
							var sel = sm.getSelected();
							if (sm.hasSelection()){
								ViewEduLessonPlanEduLessonPlanItems(sel.data.id);
							};
						}
					}
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduLessonPlan_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduLessonPlanName(Ext.getCmp('parent_eduLessonPlan_search_field').getValue());
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
				id: 'parent_eduLessonPlan_go_button',
				handler: function(){
					SearchByParentEduLessonPlanName(Ext.getCmp('parent_eduLessonPlan_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduLessonPlans,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduLessonPlan').enable();
	g.getTopToolbar().findById('delete-parent-eduLessonPlan').enable();
        g.getTopToolbar().findById('view-eduLessonPlan2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduLessonPlan').disable();
                g.getTopToolbar().findById('view-eduLessonPlan2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduLessonPlan').disable();
		g.getTopToolbar().findById('delete-parent-eduLessonPlan').enable();
                g.getTopToolbar().findById('view-eduLessonPlan2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduLessonPlan').enable();
		g.getTopToolbar().findById('delete-parent-eduLessonPlan').enable();
                g.getTopToolbar().findById('view-eduLessonPlan2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduLessonPlan').disable();
		g.getTopToolbar().findById('delete-parent-eduLessonPlan').disable();
                g.getTopToolbar().findById('view-eduLessonPlan2').disable();
	}
});



var parentEduLessonPlansViewWindow = new Ext.Window({
	title: 'EduLessonPlan Under the selected Item',
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
			parentEduLessonPlansViewWindow.close();
		}
	}]
});

store_parent_eduLessonPlans.load({
    params: {
        start: 0,    
        limit: list_size
    }
});
