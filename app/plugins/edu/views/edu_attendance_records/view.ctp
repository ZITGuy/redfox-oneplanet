//<script>
var store_attendanceRecord_absentees = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','student','status','reason'		
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_absentees', 'action' => 'list_data', $edu_attendance_record['EduAttendanceRecord']['id'])); ?>'	})
});
		
<?php $attendanceRecord_html = "<table width=100% cellspacing=3 class=viewtable>" . 		
		"<tr><th align=right>" . __('Section', true) . ":</th><td><b>" . $edu_attendance_record['EduSection']['EduClass']['name'] . ' - ' . $edu_attendance_record['EduSection']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Date', true) . ":</th><td><b>" . $edu_attendance_record['EduDay']['date'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Status', true) . ":</th><td><b>" . ($edu_attendance_record['EduAttendanceRecord']['status'] == 'N'? 'Not Submitted Yet': 'Submitted') . "</b></td></tr>" . 
"</table>"; 
?>
		var attendanceRecord_view_panel_1 = {
			html : '<?php echo $attendanceRecord_html; ?>',
			frame : true,
			height: 80
		}
		var attendanceRecord_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:290,
			plain:true,
			defaults:{autoScroll: true},
			items:[
			{
				xtype: 'grid',
				loadMask: true,
				stripeRows: true,
				store: store_attendanceRecord_absentees,
				title: '<?php __('Absentees'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_attendanceRecord_absentees.getCount() == '')
							store_attendanceRecord_absentees.reload();
					}
				},
				columns: [
					{header: "<?php __('Student'); ?>", dataIndex: 'student', sortable: true},
					{header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true},
					{header: "<?php __('Reason'); ?>", dataIndex: 'reason', sortable: true}
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: 100,
					store: store_attendanceRecord_absentees,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}]
		});

		var AttendanceRecordViewWindow = new Ext.Window({
			title: '<?php __('View Attendance Record'); ?>: <?php echo $edu_attendance_record['EduDay']['date']; ?>',
			width: 500,
			height: 445,
			resizable: false,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
                        modal: true,
			items: [ 
				attendanceRecord_view_panel_1,
				attendanceRecord_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					AttendanceRecordViewWindow.close();
				}
			}]
		});
