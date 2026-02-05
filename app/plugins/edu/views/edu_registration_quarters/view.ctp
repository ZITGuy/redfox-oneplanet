
var store_eduRegistrationQuarter_eduRegistrationQuarterResults = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_registration_quarter','edu_course','course_result','result_indicator','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarterResults', 'action' => 'list_data', $eduRegistrationQuarter['EduRegistrationQuarter']['id'])); ?>'	})
});
		
<?php $eduRegistrationQuarter_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Edu Registration', true) . ":</th><td><b>" . $eduRegistrationQuarter['EduRegistration']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Quarter', true) . ":</th><td><b>" . $eduRegistrationQuarter['EduQuarter']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Quarter Average', true) . ":</th><td><b>" . $eduRegistrationQuarter['EduRegistrationQuarter']['quarter_average'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Quarter Rank', true) . ":</th><td><b>" . $eduRegistrationQuarter['EduRegistrationQuarter']['quarter_rank'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduRegistrationQuarter['EduRegistrationQuarter']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduRegistrationQuarter['EduRegistrationQuarter']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduRegistrationQuarter_view_panel_1 = {
			html : '<?php echo $eduRegistrationQuarter_html; ?>',
			frame : true,
			height: 80
		}
		var eduRegistrationQuarter_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
			{
				xtype: 'grid',
				loadMask: true,
				stripeRows: true,
				store: store_eduRegistrationQuarter_eduRegistrationQuarterResults,
				title: '<?php __('EduRegistrationQuarterResults'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_eduRegistrationQuarter_eduRegistrationQuarterResults.getCount() == '')
							store_eduRegistrationQuarter_eduRegistrationQuarterResults.reload();
					}
				},
				columns: [
					{header: "<?php __('Edu Registration Quarter'); ?>", dataIndex: 'edu_registration_quarter', sortable: true}
,					{header: "<?php __('Edu Course'); ?>", dataIndex: 'edu_course', sortable: true}
,					{header: "<?php __('Course Result'); ?>", dataIndex: 'course_result', sortable: true}
,					{header: "<?php __('Result Indicator'); ?>", dataIndex: 'result_indicator', sortable: true}
,					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
,					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_eduRegistrationQuarter_eduRegistrationQuarterResults,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var EduRegistrationQuarterViewWindow = new Ext.Window({
			title: '<?php __('View EduRegistrationQuarter'); ?>: <?php echo $eduRegistrationQuarter['EduRegistrationQuarter']['id']; ?>',
			width: 500,
			height:345,
			minWidth: 500,
			minHeight: 345,
			resizable: false,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
                        modal: true,
			items: [ 
				eduRegistrationQuarter_view_panel_1,
				eduRegistrationQuarter_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduRegistrationQuarterViewWindow.close();
				}
			}]
		});
