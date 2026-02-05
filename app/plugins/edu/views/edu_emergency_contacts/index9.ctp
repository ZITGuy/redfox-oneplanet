//<script>
var store_emergency_contacts= new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','first_name','middle_name','last_name','relationship','phone_number'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_emergency_contacts', 'action' => 'list_data')); ?>'	})
});


function AddEmergencyContact() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_emergency_contacts', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var parent_eduEmergencyContact_data = response.responseText;
			
			eval(parent_eduEmergencyContact_data);
			
			EduEmergencyContactAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the add form. Error code'); ?>: ' + response.status);
		}
	});
}

function DeleteEmergencyContact(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_emergency_contacts', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Data deleted successfully !'); ?>');
			RefreshEduEmergencyContactData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot be deleted. Error code'); ?>: ' + response.status);
		}
	});
}


function RefreshEduEmergencyContactData() {
	store_emergency_contacts.reload();
}

var g = new Ext.grid.GridPanel({
	title: '<?php __('Emergency Contacts'); ?>',
	store: store_emergency_contacts,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduParentGrid',
	columns: [
		{header: "<?php __('First Name'); ?>", dataIndex: 'first_name', sortable: true},
		{header: "<?php __('Middle Name'); ?>", dataIndex: 'middle_name', sortable: true},
		{header: "<?php __('Last Name'); ?>", dataIndex: 'last_name', sortable: true},
		{header: "<?php __('Relationship'); ?>", dataIndex: 'relationship', sortable: true},
		{header: "<?php __('Phone Number'); ?>", dataIndex: 'phone_number', sortable: true}	
	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add Emergency Contact</b><br />Click here to create a new Emergency Contact'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddEmergencyContact();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduEmergencyContact',
				tooltip:'<?php __('<b>Delete Emergency Contact(s)</b><br />Click here to remove the selected Emergency Contact(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
								title: '<?php __('Remove Emergency Contact'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove'); ?> '+sel[0].data.country+'?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes'){
										DeleteEmergencyContact(sel[0].data.id);
									}
								}
							});
						} else {
							Ext.Msg.show({
								title: '<?php __('Remove Emergency Contact'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove the selected Emergency Contact'); ?>?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes'){
										var sel_ids = '';
										for(i=0;i<sel.length;i++){
											if(i>0)
												sel_ids += '_';
											sel_ids += sel[i].data.id;
										}
										DeleteEmergencyContact(sel_ids);
									}
								}
							});
						}
					} else {
						Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
					}
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_emergency_contacts,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('delete-parent-eduEmergencyContact').enable();
});

g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 0){
		g.getTopToolbar().findById('delete-parent-eduEmergencyContact').enable();
    }
	else{
		g.getTopToolbar().findById('delete-parent-eduEmergencyContact').disable();
    }
});

var EmergencyContactWindow = new Ext.Window({
	title: 'Emergency Contacts List',
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
		text: 'Continue',
		handler: function(btn){
			EmergencyContactWindow.close();
		}
	}]
});

store_emergency_contacts.load({
    params: {
        start: 0,    
        limit: list_size
    }
});
