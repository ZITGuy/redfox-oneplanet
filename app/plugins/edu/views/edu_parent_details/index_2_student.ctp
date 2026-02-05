//<script>
var parentId = <?php echo $parent_id ?>;
var store_parent_eduParentDetails = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id', 'short_name', 'first_name', 'middle_name','last_name','residence_address',
			'nationality','relationship','occupation','academic_qualification',
			'employment_status','employer','work_address','work_telephone','mobile',
			'email','photo_file','family_type','edu_parent','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'list_data')); ?>/' + parentId
	})
});

function ChangeStudentParent() {
	Ext.Ajax.request({
		url: <?php echo "'" . $this->Html->url(array('controller' => 'edu_students', 'action' => 'change_student_parent', $student_id)); ?>',
		success: function(response, opts) {
			var parent_eduParentChange_data = response.responseText;
			
			eval(parent_eduParentChange_data);
			
			EduParentChangeWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Parent Change Form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduParent() {
	Ext.Ajax.request({
		url: <?php echo "'" . $this->Html->url(array('controller' => 'edu_parents', 'action' => 'view', $parent_id)); ?>',
		success: function(response, opts) {
			var eduParent_data = response.responseText;

            eval(eduParent_data);

            EduParentViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Parent Detail view form. Error code'); ?>: ' + response.status);
		}
	});
}

function RefreshParentEduParentDetailData() {
	store_parent_eduParentDetails.reload();
}


var g = new Ext.grid.GridPanel({
	title: '<?php __('...'); ?>',
	store: store_parent_eduParentDetails,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduParentDetailGrid',
	columns: [
		{header: "<?php __('Short Name'); ?>", dataIndex: 'short_name', sortable: true, hidden: true},
		{header: "<?php __('First Name'); ?>", dataIndex: 'first_name', sortable: true},
		{header: "<?php __('Middle Name'); ?>", dataIndex: 'middle_name', sortable: true},
		{header: "<?php __('Last Name'); ?>", dataIndex: 'last_name', sortable: true},
		{header: "<?php __('Residence Address'); ?>", dataIndex: 'residence_address', sortable: true, hidden: true},
		{header: "<?php __('Nationality'); ?>", dataIndex: 'nationality', sortable: true, hidden: true},
		{header: "<?php __('Role'); ?>", dataIndex: 'family_type', sortable: true},
		{header: "<?php __('Occupation'); ?>", dataIndex: 'occupation', sortable: true},
		{header: "<?php __('Academic Qualification'); ?>", dataIndex: 'academic_qualification', sortable: true, hidden: true},
		{header: "<?php __('Employment Status'); ?>", dataIndex: 'employment_status', sortable: true, hidden: true},
		{header: "<?php __('Employer'); ?>", dataIndex: 'employer', sortable: true, hidden: true},
		{header: "<?php __('Work Address'); ?>", dataIndex: 'work_address', sortable: true, hidden: true},
		{header: "<?php __('Work Telephone'); ?>", dataIndex: 'work_telephone', sortable: true, hidden: true},
		{header: "<?php __('Mobile'); ?>", dataIndex: 'mobile', sortable: true},
		{header: "<?php __('Email'); ?>", dataIndex: 'email', sortable: true, hidden: true},
		{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
		{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewEduParentDetail(Ext.getCmp('eduParentDetailGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Change Parent'); ?>',
				tooltip:'<?php __('<b>Change Parent For Student</b><br />Click here to change Parent for the selected student'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					ChangeStudentParent();
				}
			}, ' ','-',' ', {
				xtype: 'tbbutton',
				text: '<?php __('View Parent'); ?>',
				id: 'view-eduParentForStudent',
				tooltip:'<?php __('<b>View Parent</b><br />Click here to see details of the selected Parent'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					ViewEduParent();
				}
            }, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduParentDetails,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});

var parentEduParentDetailsViewWindow = new Ext.Window({
	title: 'Parent Details of the selected Parent',
	width: 800,
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
			parentEduParentDetailsViewWindow.close();
		}
	}]
});

store_parent_eduParentDetails.load({
    params: {
        start: 0,    
        limit: list_size
    }
});