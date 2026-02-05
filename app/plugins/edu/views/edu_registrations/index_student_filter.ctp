//<script>
var store_student_filter = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id', 'name', 'edu_student', 'edu_section', 
			'grand_total_average', 'rank', 'allowed'		
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: "<?php echo $this->Html->url(array('controller' => 'edu_registrations', 'action' => 'list_data_student_filter')); ?>"
	}),	
    sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'edu_section',
	listeners: {
		'load': function(s, r, o) {
			if(r.length == 0) {
				Ext.Msg.alert("<?php __('Oooops!'); ?>", "<?php __('No data to display!'); ?>");
			}
		}
	}
});

function SaveStudentFilter() {
	var records = store_student_filter.getRange();
	var pars = '';
	for(var i = 0; i < store_student_filter.getCount(); i++) {
		var rec = store_student_filter.getAt(i);
		var status = (rec.get('allowed') == '<font color=green>Allowed</font>'? 'A': 'N');
		pars += rec.get('id') + '_' + status + '__';
	}
	
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'edu_registrations', 'action' => 'save_changes')); ?>/"+pars,
		success: function(response, opts) {
			Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Saved successfully!'); ?>");
			RefreshEduRegistrationData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot save the changes. Error code'); ?>: " + response.status);
		}
	});
}

function RefreshEduRegistrationData() {
	store_student_filter.reload();
}


if(center_panel.find('id', 'eduRegistrationStudentFilter-tab') != "") {
	var p = center_panel.findById('eduRegistrationStudentFilter-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Student Filter'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduRegistrationStudentFilter-tab',
		xtype: 'grid',
		store: store_student_filter,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('Section'); ?>", dataIndex: 'edu_section', sortable: true},
			{header: "<?php __('Grand Total Average'); ?>", dataIndex: 'grand_total_average', sortable: true},
			{header: "<?php __('Rank'); ?>", dataIndex: 'rank', sortable: true},
			{header: "<?php __('Is Allowed?'); ?>", dataIndex: 'allowed', sortable: true},
            {
                xtype: 'actioncolumn',
                width: 50,
                items: [{
                    getClass: function(v, meta, rec) {          // Or return a class from a function
                        if (rec.get('allowed') != '<font color=green>Allowed</font>') {
                            this.items[0].tooltip = 'Allow';
                            return 'allow-col';
                        } else {
                            this.items[0].tooltip = 'Do not Allow';
                            return 'disallow-col';
                        }
                    },
                    handler: function(grid, rowIndex, colIndex) {
                        var rec = store_student_filter.getAt(rowIndex);
                        var status = rec.get('allowed');
						if(status == '<font color=green>Allowed</font>') {
							rec.set('allowed', '<font color=red>Not Allowed</font>');
						} else {
							rec.set('allowed', '<font color=green>Allowed</font>');
						}
                    }
                }]
            }
			
		],
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Student Registrations" : "Student Registration"]})'
        }),
		listeners: {
			
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			items: [{
					xtype: 'tbbutton',
					text: "<?php __('Save Changes'); ?>",
					tooltip: "<?php __('<b>Save the changes to allow or disallow Students for Registration</b><br />Click here to save the changes to allow or disallow Student for Registration'); ?>",
					icon: 'img/save_changes.png',
					id: 'btn_allow_student',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						SaveStudentFilter();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_student_filter,
			displayInfo: true,
			displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
			beforePageText: "<?php __('Page'); ?>",
			afterPageText: "<?php __('of {0}'); ?>",
			emptyMsg: "<?php __('No data to display'); ?>"
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('btn_allow_student').enable();
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('btn_allow_student').disable();
		if(this.getSelections().length >= 1){
			p.getTopToolbar().findById('btn_allow_student').enable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_student_filter.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}