
var store_gradeRule_assessments = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','assessment_type','teacher','section','klass','academic_year','semester','quarter','max_value','date','detail','grade_rule'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'assessments', 'action' => 'list_data', $gradeRule['GradeRule']['id'])); ?>'	})
});
var store_gradeRule_gradeRuleValues = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','min','max','code','grade_rule'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'gradeRuleValues', 'action' => 'list_data', $gradeRule['GradeRule']['id'])); ?>'	})
});
		
<?php $gradeRule_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $gradeRule['GradeRule']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Type', true) . ":</th><td><b>" . $gradeRule['GradeRule']['type'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created Date', true) . ":</th><td><b>" . $gradeRule['GradeRule']['created_date'] . "</b></td></tr>" . 
"</table>"; 
?>
		var gradeRule_view_panel_1 = {
			html : '<?php echo $gradeRule_html; ?>',
			frame : true,
			height: 80
		}
		var gradeRule_view_panel_2 = new Ext.TabPanel({
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
				store: store_gradeRule_assessments,
				title: '<?php __('Assessments'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_gradeRule_assessments.getCount() == '')
							store_gradeRule_assessments.reload();
					}
				},
				columns: [
					{header: "<?php __('Assessment Type'); ?>", dataIndex: 'assessment_type', sortable: true}
,					{header: "<?php __('Teacher'); ?>", dataIndex: 'teacher', sortable: true}
,					{header: "<?php __('Section'); ?>", dataIndex: 'section', sortable: true}
,					{header: "<?php __('Klass'); ?>", dataIndex: 'klass', sortable: true}
,					{header: "<?php __('Academic Year'); ?>", dataIndex: 'academic_year', sortable: true}
,					{header: "<?php __('Semester'); ?>", dataIndex: 'semester', sortable: true}
,					{header: "<?php __('Quarter'); ?>", dataIndex: 'quarter', sortable: true}
,					{header: "<?php __('Max Value'); ?>", dataIndex: 'max_value', sortable: true}
,					{header: "<?php __('Date'); ?>", dataIndex: 'date', sortable: true}
,					{header: "<?php __('Detail'); ?>", dataIndex: 'detail', sortable: true}
,					{header: "<?php __('Grade Rule'); ?>", dataIndex: 'grade_rule', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_gradeRule_assessments,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			},
{
				xtype: 'grid',
				loadMask: true,
				stripeRows: true,
				store: store_gradeRule_gradeRuleValues,
				title: '<?php __('GradeRuleValues'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_gradeRule_gradeRuleValues.getCount() == '')
							store_gradeRule_gradeRuleValues.reload();
					}
				},
				columns: [
					{header: "<?php __('Min'); ?>", dataIndex: 'min', sortable: true}
,					{header: "<?php __('Max'); ?>", dataIndex: 'max', sortable: true}
,					{header: "<?php __('Code'); ?>", dataIndex: 'code', sortable: true}
,					{header: "<?php __('Grade Rule'); ?>", dataIndex: 'grade_rule', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_gradeRule_gradeRuleValues,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var GradeRuleViewWindow = new Ext.Window({
			title: '<?php __('View GradeRule'); ?>: <?php echo $gradeRule['GradeRule']['name']; ?>',
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
				gradeRule_view_panel_1,
				gradeRule_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					GradeRuleViewWindow.close();
				}
			}]
		});
