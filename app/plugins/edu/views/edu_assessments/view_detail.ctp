//<script>
	var section_id = <?php echo $edu_section_id; ?>;
	var course_id = <?php echo $edu_course_id; ?>;

	var store_assessment_records = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
			totalProperty: 'results',
			fields: [
				'id','student_name' <?php foreach ($fields as $field) { echo ", '$field'"; } ?>
			]
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?php echo $this->Html->url(array(
				'controller' => 'edu_assessments', 'action' => 'list_data_records_detail', $edu_section_id, $edu_course_id)); ?>'
		})
	});
		
	var assessment_view_panel = new Ext.TabPanel({
		activeTab: 0,
		anchor: '100%',
		height: 380,
		plain: true,
		defaults:{autoScroll: true},
		items:[{
			xtype: 'grid',
			loadMask: true,
			stripeRows: true,
			store: store_assessment_records,
			title: '<?php __('Assessments'); ?>',
			enableColumnMove: false,
			listeners: {
				activate: function(){
					if(store_assessment_records.getCount() == '')
						store_assessment_records.reload();
				}
			},
			columns: [
				{header: "<?php __('Student'); ?>", dataIndex: 'student_name', width: 300, sortable: true}
				<?php foreach ($fields as $field) { ?>
				<?php
					$parts = explode('-', $field);
					$fl = $parts[0];
				?>
				,{header: "<?php echo $fl; ?>", dataIndex: '<?php echo $field; ?>', align: 'center', sortable: true}
				<?php } ?>
			],
			viewConfig: {
				forceFit: true
			},
			bbar: new Ext.PagingToolbar({
				pageSize: 200,
				store: store_assessment_records,
				displayInfo: true,
				displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
				beforePageText: '<?php __('Page'); ?>',
				afterPageText: '<?php __('of'); ?> {0}',
				emptyMsg: '<?php __('No data to display'); ?>'
			})
		}]
	});

	var AssessmentRecordDetailViewWindow = new Ext.Window({
		title: '<?php __('Assessment Records Detail'); ?>',
		width: 750,
        height: 455,
		resizable: false,
		plain: true,
		bodyStyle:'padding:5px;',
		buttonAlign:'center',
		modal: true,
		items: [
			assessment_view_panel
		],

		buttons: [{
			text: '<?php __('Print'); ?>',
			handler: function(btn){
				printAssessmentRecordDetail();
			}
		}, {
			text: '<?php __('Close'); ?>',
			handler: function(btn){
				AssessmentRecordDetailViewWindow.close();
			}
		}]
	});

	var popUpWin_AssessmentRecordDetail=0;
    
    function popUpWindowAssessmentRecordDetail(URLStr, left, top, width, height) {
        if(popUpWin_AssessmentRecordDetail){
            if(!popUpWin_AssessmentRecordDetail.closed) popUpWin_AssessmentRecordDetail.close();
        }
        popUpWin_AssessmentRecordDetail = open(URLStr, 'popUpWin',
			'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,'+
			'copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
    }

    function printAssessmentRecordDetail() {
        url = "<?php echo $this->Html->url(array(
			'controller' => 'edu_assessments', 'action' => 'view_detail_print', 'plugin' => 'edu')); ?>/" +
			section_id + "/" + course_id;
        popUpWindowAssessmentRecordDetail(url, 200, 200, 700, 1000);
    }
