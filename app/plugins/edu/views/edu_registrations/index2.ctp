//<script>
var store_parent_eduRegistrations = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','edu_student', 'edu_class','edu_section','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_registrations', 'action' => 'list_data', $parent_id)); ?>'	})
});

function EditParentEduRegistration(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_registrations', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduRegistration_data = response.responseText;
			
			eval(parent_eduRegistration_data);
			
			EduRegistrationEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistration edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduRegistration(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrations', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduRegistration_data = response.responseText;

			eval(eduRegistration_data);

			EduRegistrationViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistration view form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduRegistrationEduResults(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduResults', 'action' => 'index2')); ?>/'+id,
		success: function(response, opts) {
			var parent_eduResults_data = response.responseText;

			eval(parent_eduResults_data);

			parentEduResultsViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
		}
	});
}

function DeleteParentEduRegistration(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrations', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduRegistration(s) successfully deleted!'); ?>');
			RefreshParentEduRegistrationData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistration to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function RefreshParentEduRegistrationData() {
	store_parent_eduRegistrations.reload();
}

var g = new Ext.grid.GridPanel({
	title: '<?php __('Registrations'); ?>',
	store: store_parent_eduRegistrations,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduRegistrationGrid',
	columns: [
		{header: "<?php __('Student'); ?>", dataIndex: 'edu_student', sortable: true},
		{header: "<?php __('Class/Grade'); ?>", dataIndex: 'edu_class', sortable: true},
		{header: "<?php __('Section'); ?>", dataIndex: 'edu_section', sortable: true},
		{header: "<?php __('Date Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
		{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}	
	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewEduRegistration(Ext.getCmp('eduRegistrationGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduRegistration',
				tooltip:'<?php __('<b>Edit Registration</b><br />Click here to modify the selected EduRegistration'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduRegistration(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduRegistration',
				tooltip:'<?php __('<b>Delete Registration(s)</b><br />Click here to remove the selected Registration(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove Registration'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduRegistration(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove Registration'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected Registration'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentEduRegistration(sel_ids);
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
				text: '<?php __('View Registration'); ?>',
				id: 'view-eduRegistration2',
				tooltip:'<?php __('<b>View Registration</b><br />Click here to see details of the selected Registration'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduRegistration(sel.data.id);
					};
				},
				menu : {
					items: [{
						text: '<?php __('View Results'); ?>',
                        icon: 'img/table_view.png',
						cls: 'x-btn-text-icon',
						handler: function(btn) {
							var sm = g.getSelectionModel();
							var sel = sm.getSelected();
							if (sm.hasSelection()){
								ViewEduRegistrationEduResults(sel.data.id);
							};
						}
					}
					]
				}

            }, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduRegistrations,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduRegistration').enable();
	g.getTopToolbar().findById('delete-parent-eduRegistration').enable();
        g.getTopToolbar().findById('view-eduRegistration2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduRegistration').disable();
                g.getTopToolbar().findById('view-eduRegistration2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduRegistration').disable();
		g.getTopToolbar().findById('delete-parent-eduRegistration').enable();
                g.getTopToolbar().findById('view-eduRegistration2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduRegistration').enable();
		g.getTopToolbar().findById('delete-parent-eduRegistration').enable();
                g.getTopToolbar().findById('view-eduRegistration2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduRegistration').disable();
		g.getTopToolbar().findById('delete-parent-eduRegistration').disable();
                g.getTopToolbar().findById('view-eduRegistration2').disable();
	}
});



var parentEduRegistrationsViewWindow = new Ext.Window({
	title: 'Registration of the selected Student',
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
		text: 'Close',
		handler: function(btn){
			parentEduRegistrationsViewWindow.close();
		}
	}]
});

store_parent_eduRegistrations.load({
    params: {
        start: 0,    
        limit: list_size
    }
});
