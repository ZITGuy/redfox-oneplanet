//<script>
    var store_eduLessonPlan_eduLessonPlanItems = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','edu_lesson_plan','edu_period','edu_day',
                'edu_outline','activity','materials_needed'		
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'eduLessonPlanItems', 'action' => 'list_data', $edu_lesson_plan['EduLessonPlan']['id'])); ?>'	})
    });
		
    <?php $eduLessonPlan_html = "<table cellspacing=3>" . 		
            "<tr><th align=right>" . __('Course', true) . ":</th><td><b>" . $edu_lesson_plan['EduCourse']['description'] . "</b></td>" . 
                "<th>&nbsp;&nbsp;&nbsp;&nbsp;</th><th align=right>" . __('Section', true) . ":</th><td><b>" . $edu_lesson_plan['EduSection']['name'] . "</b></td></tr>" . 
            "<tr><th align=right>" . __('Maker', true) . ":</th><td><b>" . $edu_lesson_plan['Maker']['username'] . "</b></td>" . 
                "<th>&nbsp;&nbsp;&nbsp;&nbsp;</th><th align=right>" . __('Checker', true) . ":</th><td><b>" . $edu_lesson_plan['Checker']['username'] . "</b></td></tr>" . 
            "<tr><th align=right>" . __('# of Posts', true) . ":</th><td><b>" . $edu_lesson_plan['EduLessonPlan']['posts'] . "</b></td>" . 
                "<th>&nbsp;&nbsp;&nbsp;&nbsp;</th><th align=right>" . __('Status', true) . ":</th><td><b>" . $edu_lesson_plan['EduLessonPlan']['status'] . "</b></td></tr>" . 
            "<tr><th align=right>" . __('Reason', true) . ":</th><td><b>" . $edu_lesson_plan['EduLessonPlan']['reason'] . "</b></td>" . 
                "<th>&nbsp;&nbsp;&nbsp;&nbsp;</th><th align=right>" . __('Last Modified', true) . ":</th><td><b>" . date('F d, Y', strtotime($edu_lesson_plan['EduLessonPlan']['modified'])) . "</b></td></tr>" . 
        "</table>"; 
    ?>
    var eduLessonPlan_view_panel_1 = {
        html : '<?php echo $eduLessonPlan_html; ?>',
        frame : true,
        height: 100
    };
    
    var eduLessonPlan_view_panel_2 = new Ext.TabPanel({
        activeTab: 0,
        anchor: '100%',
        height: 310,
        plain:true,
        defaults:{autoScroll: true},
        items:[{
            xtype: 'grid',
            loadMask: true,
            stripeRows: true,
            store: store_eduLessonPlan_eduLessonPlanItems,
            title: '<?php __('Lesson Plan Items'); ?>',
            enableColumnMove: false,
            listeners: {
                activate: function(){
                    store_eduLessonPlan_eduLessonPlanItems.reload();
                }
            },
            columns: [
                {header: "<?php __('Day'); ?>", dataIndex: 'edu_day', sortable: true, width: 120},
                {header: "<?php __('Period'); ?>", dataIndex: 'edu_period', sortable: true, width: 40},
                {header: "<?php __('Outline'); ?>", dataIndex: 'edu_outline', sortable: true, width: 200},
                {header: "<?php __('Activity'); ?>", dataIndex: 'activity', sortable: true},
                {header: "<?php __('Materials Needed'); ?>", dataIndex: 'materials_needed', sortable: true}
            ],
            viewConfig: {
                forceFit: true
            },
            bbar: new Ext.PagingToolbar({
                pageSize: 30,
                store: store_eduLessonPlan_eduLessonPlanItems,
                displayInfo: true,
                displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
                beforePageText: '<?php __('Page'); ?>',
                afterPageText: '<?php __('of'); ?> {0}',
                emptyMsg: '<?php __('No data to display'); ?>'
            })
        }]
    });

    var EduLessonPlanViewWindow = new Ext.Window({
        title: '<?php __('View Lesson Plan'); ?>',
        width: 700,
        height: 485,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [ 
            eduLessonPlan_view_panel_1,
            eduLessonPlan_view_panel_2
        ],

        buttons: [{
            text: '<?php __('Print'); ?>',
            handler: function(btn){
                printLessonPlan();
            }
        }, {
            text: '<?php __('Close'); ?>',
            handler: function(btn){
                EduLessonPlanViewWindow.close();
            }
        }]
    });
    
    var popUpWinLessonPlan=0;
	
    function popUpWindowLessonPlan(URLStr, left, top, width, height) {
        if(popUpWinLessonPlan){
            if(!popUpWinLessonPlan.closed) popUpWinLessonPlan.close();
        }
        popUpWinLessonPlan = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
    }

    function printLessonPlan() {
        url = "<?php echo $this->Html->url(array('controller' => 'edu_lesson_plans', 'action' => 'print_lesson_plan', 'plugin' => 'edu', $edu_lesson_plan['EduLessonPlan']['id'])); ?>";
        popUpWindowLessonPlan(url, 200, 200, 700, 1000);
    }
