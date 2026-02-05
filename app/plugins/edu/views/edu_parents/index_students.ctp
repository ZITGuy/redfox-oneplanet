//<script>
var store_parent_students = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root: 'rows',
		totalProperty: 'results',
		fields: [
			'id', 'name', 'identity_number', 'registration_date', 'gender', 'photo_file_name'		
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'list_data_parent_students', $parent_id)); ?>'
	}),
	sortInfo: {field: 'name', direction: "ASC"}
});

function ViewEduStudent(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'view')); ?>/" + id,
		success: function (response, opts) {
			var eduStudent_data = response.responseText;

			eval(eduStudent_data);

			EduStudentViewWindow.show();
		},
		failure: function (response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Student view form. Error code'); ?>: " + response.status);
		}
	});
}
	

var g = new Ext.grid.GridPanel({
	title: '<?php __('EduParents'); ?>',
	store: store_parent_students,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduParentStudentsGrid',
	columns: [
		{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
		{header: "<?php __('Identity Number'); ?>", dataIndex: 'identity_number', sortable: true},
		{header: "<?php __('Date of Registration'); ?>", dataIndex: 'registration_date', sortable: true},
		{header: "<?php __('Gender'); ?>", dataIndex: 'gender', sortable: true}	
	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewEduStudent(Ext.getCmp('eduParentStudentsGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('View Student Detail'); ?>',
				id: 'view-eduStudent',
				tooltip:'<?php __('View Student Detail - Click here to see details of the selected Student'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduStudent(sel.data.id);
					}
				}
            }, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_students,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('view-eduStudent').enable();
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('view-eduStudent').disable();
});

var parentEduParentStudentsViewWindow = new Ext.Window({
	title: 'Student Parent',
	width: 700,
	height: 375,
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
			parentEduParentStudentsViewWindow.close();
		}
	}]
});

store_parent_students.load({
    params: {
        start: 0,    
        limit: list_size
    }
});