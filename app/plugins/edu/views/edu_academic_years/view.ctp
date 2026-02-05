//<script>
    var store_eduAcademicYear_eduSections = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','name','edu_class','edu_academic_year','created','modified'		
            ]
	}),
	proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_sections', 'action' => 'list_data_for_ay', $edu_academic_year['EduAcademicYear']['id'])); ?>'	})
    });
    
    var store_eduAcademicYear_eduQuarters = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','name', 'short_name', 'start_date','end_date',
                'edu_academic_year','status',
                'openable',
                'created','modified'	
            ]
	}),
	proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_quarters', 'action' => 'list_data', $edu_academic_year['EduAcademicYear']['id'])); ?>'	})
    });
    
    var store_eduAcademicYear_eduCalendarEvents = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','edu_calendar_event_type','start_date','end_date','edu_quarter','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_calendar_events', 'action' => 'list_data2', $edu_academic_year['EduAcademicYear']['id'])); ?>'
	}),	
        sortInfo:{field: 'start_date', direction: "ASC"},
	groupField: 'edu_quarter'
    });
	
<?php $eduAcademicYear_html = "<table width=100% cellspacing=3 class=viewtable>" . 		
        "<tr><th align=right>Name:</th><td><b>" . $edu_academic_year['EduAcademicYear']['name'] . "</b></td>" . 
        "<td rowspan=3 align=right>" . $this->Html->image('gauge.png', array('height' => '100px')) . "</td></tr>" . 
        "<tr><th align=right>Start Date:</th><td><b>" . $edu_academic_year['EduAcademicYear']['start_date'] . "</b></td></tr>" . 
        "<tr><th align=right>End Date:</th><td><b>" . $edu_academic_year['EduAcademicYear']['end_date'] . "</b></td></tr>" . 
"</table>"; 
?>
    var eduAcademicYear_view_panel_1 = {
        html : '<?php echo $eduAcademicYear_html; ?>',
        frame : true,
        height: 120
    }
    var eduAcademicYear_view_panel_2 = new Ext.TabPanel({
        activeTab: 0,
        anchor: '100%',
        height:240,
        plain:true,
        defaults:{autoScroll: true},
        items:[{
            xtype: 'grid',
            loadMask: true,
            stripeRows: true,
            store: store_eduAcademicYear_eduSections,
            title: '<?php __('Sections'); ?>',
            enableColumnMove: false,
            listeners: {
                activate: function(){
                    if(store_eduAcademicYear_eduSections.getCount() == '')
                        store_eduAcademicYear_eduSections.reload();
                }
            },
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Class'); ?>", dataIndex: 'edu_class', sortable: true},
                {header: "<?php __('Academic Year'); ?>", dataIndex: 'edu_academic_year', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
            ],
            viewConfig: {
                forceFit: true
            },
            bbar: new Ext.PagingToolbar({
                pageSize: view_list_size,
                store: store_eduAcademicYear_eduSections,
                displayInfo: true,
                displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
                beforePageText: '<?php __('Page'); ?>',
                afterPageText: '<?php __('of'); ?> {0}',
                emptyMsg: '<?php __('No data to display'); ?>'
            })
        }, {
            xtype: 'grid',
            loadMask: true,
            stripeRows: true,
            store: store_eduAcademicYear_eduQuarters,
            title: '<?php __('Quarters'); ?>',
            enableColumnMove: false,
            listeners: {
                activate: function(){
                    if(store_eduAcademicYear_eduQuarters.getCount() == '')
                        store_eduAcademicYear_eduQuarters.reload();
                }
            },
            columns: [
                {header: "<?php __('Quarter Name'); ?>", dataIndex: 'name', width: 50, sortable: true},
                {header: "<?php __('Short Name'); ?>", dataIndex: 'short_name', width: 35, sortable: true},
                {header: "<?php __('Start Date'); ?>", dataIndex: 'start_date', width: 50, sortable: true},
                {header: "<?php __('End Date'); ?>", dataIndex: 'end_date', width: 50, sortable: true},
                {header: "<?php __('Academic Year'); ?>", dataIndex: 'edu_academic_year', sortable: true, hidden: true},
                {header: "<?php __('Status'); ?>", dataIndex: 'status', width: 80, sortable: true},
                {header: "<?php __('Openable'); ?>", dataIndex: 'openable', sortable: true, hidden: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
            ],
            viewConfig: {
                forceFit: true
            },
            bbar: new Ext.PagingToolbar({
                pageSize: view_list_size,
                store: store_eduAcademicYear_eduQuarters,
                displayInfo: true,
                displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
                beforePageText: '<?php __('Page'); ?>',
                afterPageText: '<?php __('of'); ?> {0}',
                emptyMsg: '<?php __('No data to display'); ?>'
            })
        }, {
            xtype: 'grid',
            loadMask: true,
            stripeRows: true,
            store: store_eduAcademicYear_eduCalendarEvents,
            title: '<?php __('Calendar Events'); ?>',
            enableColumnMove: false,
            listeners: {
                activate: function(){
                    if(store_eduAcademicYear_eduCalendarEvents.getCount() == '')
                        store_eduAcademicYear_eduCalendarEvents.reload();
                }
            },
            columns: [
                {header: "<?php __('Event'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Event Type'); ?>", dataIndex: 'edu_calendar_event_type', sortable: true},
                {header: "<?php __('Start Date'); ?>", dataIndex: 'start_date', sortable: true},
                {header: "<?php __('End Date'); ?>", dataIndex: 'end_date', sortable: true},
                {header: "<?php __('Quarter'); ?>", dataIndex: 'edu_quarter', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
            ],
            view: new Ext.grid.GroupingView({
                forceFit:true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Events" : "Event"]})'
            }),
            bbar: new Ext.PagingToolbar({
                pageSize: 500,
                store: store_eduAcademicYear_eduCalendarEvents,
                displayInfo: true,
                displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
                beforePageText: '<?php __('Page'); ?>',
                afterPageText: '<?php __('of'); ?> {0}',
                emptyMsg: '<?php __('No data to display'); ?>'
            })
        }]
    });

    var EduAcademicYearViewWindow = new Ext.Window({
        title: '<?php __('View Academic Year'); ?>: <?php echo $edu_academic_year['EduAcademicYear']['name']; ?>',
        width: 700,
        height: 435,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [ 
            eduAcademicYear_view_panel_1,
            eduAcademicYear_view_panel_2
        ],

        buttons: [{
            text: '<?php __('Print AY Summary'); ?>',
			hidden: <?php echo ($edu_academic_year['EduAcademicYear']['status_id'] == 1)? 'false': 'true' ?>,
            handler: function(btn){
                printAYSummary();
            }
        }, {
            text: '<?php __('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Close&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'); ?>',
            handler: function(btn){
                EduAcademicYearViewWindow.close();
            }
        }]
    });
    
    var popUpWin_reg=0;
	
    function popUpWindowAY(URLStr, left, top, width, height) {
        if(popUpWin_reg){
            if(!popUpWin_reg.closed) popUpWin_reg.close();
        }
        popUpWin_reg = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
    }

    function printAYSummary() {
        url = "<?php echo $this->Html->url(array('controller' => 'edu_academic_years', 'action' => 'print_ay_summary', 'plugin' => 'edu')); ?>";
        popUpWindowAY(url, 200, 200, 700, 1000);
    }
    
