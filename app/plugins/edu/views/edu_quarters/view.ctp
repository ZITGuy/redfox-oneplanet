//<script>
<?php //pr($edu_quarter); ?>
var store_eduQuarter_eduCalendarEvents = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','start_date','end_date','edu_quarter','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCalendarEvents', 'action' => 'list_data', $edu_quarter['EduQuarter']['id'])); ?>'	})
});
		
<?php 
$statuses = array(
    9 => "<b>Created / Not Started</b>", 
    8 => "<font color=red><b>Closed</b></font>", 
    1 => "<font color=darkgreen><b> Active / Open</b></font>"
);
$edu_quarter_html = "<table width=100% cellspacing=3 class=viewtable>" . 		
    "<tr><th align=right>Name:</th><td><b>" . $edu_quarter['EduQuarter']['name'] . "</b></td>" . 
	"    <th align=right>Start Date:</th><td><b>" . date('D. M. d, Y', strtotime($edu_quarter['EduQuarter']['start_date'])) . "</b></td></tr>" . 
	"<tr><th align=right>Status:</th><td><b>" . $statuses[$edu_quarter['EduQuarter']['status_id']] . "</b></td>" . 
	"    <th align=right>End Date:</th><td><b>" . date('D. M. d, Y', strtotime($edu_quarter['EduQuarter']['end_date'])) . "</b></td></tr>" . 
	"<tr><th align=right>Created By:</th><td><b>" . $edu_quarter['Maker']['username'] . "</b></td>" . 
	"   <th align=right>Last Modified:</th><td><b>" . date('D. M. d, Y', strtotime($edu_quarter['EduQuarter']['modified'])) . "</b></td></tr>" . 
"</table>"; 
?>
	var eduQuarter_view_panel_1 = {
		html : '<?php echo $edu_quarter_html; ?>',
		frame : true,
		height: 80
	}
	var eduQuarter_view_panel_2 = new Ext.TabPanel({
		activeTab: 0,
		anchor: '100%',
		height:290,
		plain:true,
		defaults:{autoScroll: true},
		items:[{
            xtype: 'grid',
            loadMask: true,
            stripeRows: true,
            store: store_eduQuarter_eduCalendarEvents,
            title: '<?php __('Calendar Events'); ?>',
            enableColumnMove: false,
            listeners: {
                activate: function(){
                    if(store_eduQuarter_eduCalendarEvents.getCount() == '')
                        store_eduQuarter_eduCalendarEvents.reload();
                }
            },
            columns: [
                {header: "Event", dataIndex: 'name', sortable: true},
				{header: "Start Date", dataIndex: 'start_date', sortable: true},
				{header: "End Date", dataIndex: 'end_date', sortable: true},
				{header: "Created", dataIndex: 'created', sortable: true, hidden: true},
				{header: "Modified", dataIndex: 'modified', sortable: true, hidden: true}

            ],
            viewConfig: {
                forceFit: true
            },
            bbar: new Ext.PagingToolbar({
                pageSize: view_list_size,
                store: store_eduQuarter_eduCalendarEvents,
                displayInfo: true,
                displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
                beforePageText: '<?php __('Page'); ?>',
                afterPageText: '<?php __('of'); ?> {0}',
                emptyMsg: '<?php __('No data to display'); ?>'
            })
		}]
	});

		var EduQuarterViewWindow = new Ext.Window({
			title: 'View <?php echo $term_name; ?>: <i><?php echo $edu_quarter['EduQuarter']['name']; ?></i>',
			width: 700,
			height: 445,
			minWidth: 700,
			minHeight: 445,
			resizable: false,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
            modal: true,
			items: [ 
				eduQuarter_view_panel_1,
				eduQuarter_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduQuarterViewWindow.close();
				}
			}]
		});
