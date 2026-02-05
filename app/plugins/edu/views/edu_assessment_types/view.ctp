//<script>
var store_assessmentType_assessments = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','assessment_type','teacher','section','max_value','date','status','detail','subject'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_assessments', 'action' => 'list_data', $edu_assessment_type['EduAssessmentType']['id'])); ?>'	})
});
		
<?php $assessmentType_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $edu_assessment_type['EduAssessmentType']['name'] . "</b></td></tr>" . 
"</table>"; 
?>
		var assessmentType_view_panel_1 = {
			html : '<?php echo $assessmentType_html; ?>',
			frame : true,
			height: 80
		}
		var assessmentType_view_panel_2 = new Ext.TabPanel({
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
				store: store_assessmentType_assessments,
				title: '<?php __('Assessments'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_assessmentType_assessments.getCount() == '')
							store_assessmentType_assessments.reload();
					}
				},
				columns: [
					{header: "<?php __('Assessment Type'); ?>", dataIndex: 'assessment_type', sortable: true}
,					{header: "<?php __('Teacher'); ?>", dataIndex: 'teacher', sortable: true}
,					{header: "<?php __('Section'); ?>", dataIndex: 'section', sortable: true}
,					{header: "<?php __('Max Value'); ?>", dataIndex: 'max_value', sortable: true}
,					{header: "<?php __('Date'); ?>", dataIndex: 'date', sortable: true}
,					{header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true}
,					{header: "<?php __('Detail'); ?>", dataIndex: 'detail', sortable: true}
,					{header: "<?php __('Subject'); ?>", dataIndex: 'subject', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_assessmentType_assessments,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var EduAssessmentTypeViewWindow = new Ext.Window({
			title: '<?php __('View Assessment Type'); ?>: <?php echo $edu_assessment_type['EduAssessmentType']['name']; ?>',
			width: 500,
			height: 345,
			minWidth: 500,
			minHeight: 345,
			resizable: false,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
            modal: true,
			items: [
				assessmentType_view_panel_1,
				assessmentType_view_panel_2
			],
			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduAssessmentTypeViewWindow.close();
				}
			}]
		});
