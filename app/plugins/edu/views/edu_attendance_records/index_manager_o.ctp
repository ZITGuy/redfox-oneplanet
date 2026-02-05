//<script>
var store_attendanceRecords = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id', 'user', 'section', 'quarter', 'status', 'date', 'week', 'created'
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_attendance_records',
			'action' => 'list_data_for_manager')); ?>'
	}),
	sortInfo:{field: 'date', direction: "ASC"},
	groupField: 'week'
});

function DailyAttendanceRecord(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'edu_attendance_records', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var attendanceRecord_data = response.responseText;

            eval(attendanceRecord_data);

            AttendanceRecordViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>',
				'<?php __('Cannot get the attendance Record view form. Error code'); ?>: ' + response.status);
        }
    });
}

function ReturnAttendanceRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array(
			'controller' => 'edu_attendance_records', 'action' => 'return_attendance_record')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Attendance Record returned successfully!'); ?>');
			RefreshAttendanceRecordData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>',
				'<?php __('Cannot return the selected attendance record. Error code'); ?>: ' + response.status);
		}
	});
}


function WeeklyAttendanceRecord(id) {
    printAttendanceSheet(id);
}

var popUpWin_AttendanceSheet=0;
    
function popUpWindowAttendanceSheet(URLStr, left, top, width, height) {
	if(popUpWin_AttendanceSheet){
		if(!popUpWin_AttendanceSheet.closed) popUpWin_AttendanceSheet.close();
	}
	popUpWin_AttendanceSheet = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
}

function printAttendanceSheet(id) {
	url = "<?php echo $this->Html->url(array('controller' => 'edu_attendance_records', 'action' => 'print_attendance_sheet', 'plugin' => 'edu')); ?>/" + id;
	popUpWindowAttendanceSheet(url, 200, 200, 700, 1000);
}


function RefreshAttendanceRecordData() {
	store_attendanceRecords.reload();
}

if(center_panel.find('id', 'attendanceRecord-tab') != "") {
	var p = center_panel.findById('attendanceRecord-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Attendance Records'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'attendanceRecord-tab',
		xtype: 'grid',
		store: store_attendanceRecords,
		columns: [
			{header: "<?php __('User/Taker'); ?>", dataIndex: 'user', sortable: true},
			{header: "<?php __('Section'); ?>", dataIndex: 'section', sortable: true},
			{header: "<?php __('Term'); ?>", dataIndex: 'quarter', sortable: true},
			{header: "<?php __('Date'); ?>", dataIndex: 'date', sortable: true},
			{header: "<?php __('Week'); ?>", dataIndex: 'week', sortable: true},
			{header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
		],
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Attendance Records" : "Attendance Record"]})'
        }),
		listeners: {
			celldblclick: function(){
				ViewAttendanceRecord(Ext.getCmp('attendanceRecord-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: true
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('View'); ?>',
					id: 'view-attendanceRecord',
					tooltip:'<?php __('<b>View Attendance Record</b><br />Click here to see details of the selected AttendanceRecord'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							Ext.MessageBox.buttonText.yes = "<b>Sheet</b>";
							Ext.MessageBox.buttonText.no = "Absentees";
							Ext.MessageBox.buttonText.cancel = "Close";
							Ext.Msg.show({
								title: "<?php __('Which one?'); ?>",
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: "<?php __('Do you want to display ... ?'); ?> ",
								icon: Ext.MessageBox.QUESTION,
								fn: function (btn) {
									if (btn == 'yes') {   // to mean weekly
										WeeklyAttendanceRecord(sel.data.id);
									} else if(btn == 'no') { // to mean daily
										DailyAttendanceRecord(sel.data.id);
									} else {
										// cancel is clicked
									}
								}
							});
							Ext.MessageBox.buttonText.yes = "Yes";
							Ext.MessageBox.buttonText.no = "No";
						}
					}
				}, ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Return'); ?>',
					id: 'return-attendanceRecord',
					tooltip:'<?php __('<b>Return Attendance Record</b><br />Click here to return the selected Attendance Record to the Maker for edition'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							Ext.MessageBox.buttonText.yes = "<b>Yes</b>";
							Ext.MessageBox.buttonText.no = "No";
							Ext.Msg.show({
								title: "<?php __('Are you sure?'); ?>",
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: "<?php __('Are you sure want to return the selected Attendance Record to its maker?'); ?> ",
								icon: Ext.MessageBox.QUESTION,
								fn: function (btn) {
									if (btn == 'yes') {   // to mean weekly
										ReturnAttendanceRecord(sel.data.id);
									}
								}
							});
							Ext.MessageBox.buttonText.yes = "Yes";
							Ext.MessageBox.buttonText.no = "No";
						}
					}
				}, ' ', '-',  '<?php __('Section'); ?>: ', {
					xtype : 'combo',
					emptyText: '[Select One]',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							<?php $st = false; foreach ($sections as $item){if($st) echo ",
							";?>['<?php echo $item['EduSection']['id']; ?>' ,'<?php echo $item['EduClass']['name'] . ' - ' . $item['EduSection']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '[Select One]',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_attendanceRecords.reload({
								params: {
									start: 0,
									limit: list_size,
									edu_section_id : combo.getValue()
								}
							});
						}
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_attendanceRecords,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('view-attendanceRecord').enable();
		p.getTopToolbar().findById('return-attendanceRecord').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('view-attendanceRecord').disable();
			p.getTopToolbar().findById('return-attendanceRecord').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('view-attendanceRecord').disable();
			p.getTopToolbar().findById('return-attendanceRecord').disable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('view-attendanceRecord').enable();
			p.getTopToolbar().findById('return-attendanceRecord').enable();
		}
		else{
			p.getTopToolbar().findById('view-attendanceRecord').disable();
			p.getTopToolbar().findById('return-attendanceRecord').disable();
		}
	});
	center_panel.setActiveTab(p);
	
}
