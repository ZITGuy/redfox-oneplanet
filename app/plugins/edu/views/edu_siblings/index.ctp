//<script>
var store_siblings= new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','age','sex','grade'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_siblings', 'action' => 'list_data')); ?>'	})
});


function AddSibling() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_siblings', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var parent_eduSibling_data = response.responseText;
			
			eval(parent_eduSibling_data);
			
			EduSiblingAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the add form. Error code'); ?>: ' + response.status);
		}
	});
}

function DeleteSibling(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_siblings', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Data deleted successfully !'); ?>');
			RefreshEduSiblingData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot be deleted. Error code'); ?>: ' + response.status);
		}
	});
}


function RefreshEduSiblingData() {
	store_siblings.reload();
}

var g = new Ext.grid.GridPanel({
	title: '<?php __('Siblings'); ?>',
	store: store_siblings,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduParentGrid',
	columns: [
		{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
		{header: "<?php __('Age'); ?>", dataIndex: 'age', sortable: true},
		{header: "<?php __('Sex'); ?>", dataIndex: 'sex', sortable: true},
		{header: "<?php __('Grade'); ?>", dataIndex: 'grade', sortable: true}
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
				tooltip:'<?php __('<b>Add Sibling</b><br />Click here to create a new Sibling'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddSibling();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduSibling',
				tooltip:'<?php __('<b>Delete Sibling(s)</b><br />Click here to remove the selected Sibling(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
								title: '<?php __('Remove Sibling'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes'){
										DeleteSibling(sel[0].data.id);
									}
								}
							});
						} else {
							Ext.Msg.show({
								title: '<?php __('Remove Sibling'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove the selected Sibling'); ?>?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes'){
										var sel_ids = '';
										for(i=0;i<sel.length;i++){
											if(i>0)
												sel_ids += '_';
											sel_ids += sel[i].data.id;
										}
										DeleteSibling(sel_ids);
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
		pageSize: 5,
		store: store_siblings,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('delete-parent-eduSibling').enable();
});

g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 0){
		g.getTopToolbar().findById('delete-parent-eduSibling').enable();
    }
	else{
		g.getTopToolbar().findById('delete-parent-eduSibling').disable();
    }
});

var SiblingWindow = new Ext.Window({
	title: 'Siblings List',
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
			SiblingWindow.close();
		}
	}]
});

store_siblings.load({
    params: {
        start: 0,    
        limit: 5
    }
});
