//<script>
var store_photos= new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','title','relationship','photo_file'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_photos', 'action' => 'list_data')); ?>'	})
});


function AddPhoto() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_photos', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var parent_eduPhoto_data = response.responseText;
			
			eval(parent_eduPhoto_data);
			
			EduPhotoAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the add form. Error code'); ?>: ' + response.status);
		}
	});
}

function DeletePhoto(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_photos', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Data deleted successfully !'); ?>');
			RefreshEduPhotoData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot be deleted. Error code'); ?>: ' + response.status);
		}
	});
}


function RefreshEduPhotoData() {
	store_photos.reload();
}

var g = new Ext.grid.GridPanel({
	title: '<?php __('Photos'); ?>',
	store: store_photos,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduParentGrid',
	columns: [
		{header: "<?php __('Title'); ?>", dataIndex: 'title', sortable: true},
		{header: "<?php __('Relationship'); ?>", dataIndex: 'relationship', sortable: true},
		{header: "<?php __('Photo'); ?>", dataIndex: 'photo_file'}	
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
				tooltip:'<?php __('<b>Add Photo</b><br />Click here to create a new Photo Attended'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddPhoto();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduPhoto',
				tooltip:'<?php __('<b>Delete Photo(s)</b><br />Click here to remove the selected Photo(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
								title: '<?php __('Remove Photo'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove'); ?> '+sel[0].data.country+'?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes'){
										DeletePhoto(sel[0].data.id);
									}
								}
							});
						} else {
							Ext.Msg.show({
								title: '<?php __('Remove Photo'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove the selected Photo'); ?>?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes'){
										var sel_ids = '';
										for(i=0;i<sel.length;i++){
											if(i>0)
												sel_ids += '_';
											sel_ids += sel[i].data.id;
										}
										DeletePhoto(sel_ids);
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
		store: store_photos,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('delete-parent-eduPhoto').enable();
});

g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 0){
		g.getTopToolbar().findById('delete-parent-eduPhoto').enable();
    }
	else{
		g.getTopToolbar().findById('delete-parent-eduPhoto').disable();
    }
});

var PhotoWindow = new Ext.Window({
	title: 'Previous School Attended',
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
			PhotoWindow.close();
		}
	}]
});

store_photos.load({
    params: {
        start: 0,    
        limit: 5
    }
});
