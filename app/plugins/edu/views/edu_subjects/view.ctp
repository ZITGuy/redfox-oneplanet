//<script>
    var store_edu_subject_eduCourses = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','edu_class','edu_subject',
                'description','created','modified'		
            ]
	}),
	proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'list_data_subject', $edu_subject['EduSubject']['id'])); ?>'	})
    });
    
    var store_edu_subject_teachers = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','name','identity_number'		
            ]
	}),
	proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'list_data_subject', $edu_subject['EduSubject']['id'])); ?>'	})
    });
		
<?php $edu_subject_html = "<table width=100% cellspacing=3 class=viewtable>" . 		
        "<tr><th width=25% align=right>" . __('Name', true) . ":</th><td><b>" . $edu_subject['EduSubject']['name'] . "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>" . 
        "    <th align=right>" . __('Min. Mark To Pass', true) . ":</th><td><b>" . $edu_subject['EduSubject']['min_for_pass'] . "</b></td></tr>" . 
        "<tr><th align=right>" . __('Description', true) . ":</th><td><b>" . $edu_subject['EduSubject']['description'] . "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>" . 
        "    <th align=right>" . __('Is Mandatory?', true) . ":</th><td><b>" . ($edu_subject['EduSubject']['is_mandatory']? 'YES': 'NO') . "</b></td></tr>" . 
        "<tr><th align=right>" . __('Color Code', true) . ":</th><td><b>" . $edu_subject['EduSubject']['color'] . "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>" . 
        "    <th align=right>" . __('Last Modified', true) . ":</th><td><b>" . date('F d, Y', strtotime($edu_subject['EduSubject']['modified'])) . "</b></td></tr>" . 
    "</table>"; 
?>
    var edu_subject_view_panel_1 = {
        html : '<?php echo $edu_subject_html; ?>',
        frame : true,
        height: 80
    }
    var edu_subject_view_panel_2 = new Ext.TabPanel({
        activeTab: 0,
        anchor: '100%',
        height:310,
        plain:true,
        defaults:{autoScroll: true},
        items:[{
            xtype: 'grid',
            loadMask: true,
            stripeRows: true,
            store: store_edu_subject_eduCourses,
            title: '<?php __('Courses'); ?>',
            enableColumnMove: false,
            listeners: {
                activate: function(){
                    if(store_edu_subject_eduCourses.getCount() == '')
                        store_edu_subject_eduCourses.reload();
                }
            },
            columns: [
                {header: "<?php __('Class'); ?>", dataIndex: 'edu_class', sortable: true},
                {header: "<?php __('Description'); ?>", dataIndex: 'description', sortable: true},
                {header: "<?php __('Last Modified'); ?>", dataIndex: 'modified', sortable: true}
            ],
            viewConfig: {
                forceFit: true
            },
            bbar: new Ext.PagingToolbar({
                pageSize: view_list_size,
                store: store_edu_subject_eduCourses,
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
            store: store_edu_subject_teachers,
            title: '<?php __('Teachers'); ?>',
            enableColumnMove: false,
            listeners: {
                activate: function(){
                    if(store_edu_subject_teachers.getCount() == '')
                        store_edu_subject_teachers.reload();
                }
            },
            columns: [
                {header: "<?php __('ID Number'); ?>", dataIndex: 'identity_number', sortable: true},
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true}
            ],
            viewConfig: {
                forceFit: true
            },
            bbar: new Ext.PagingToolbar({
                pageSize: view_list_size,
                store: store_edu_subject_teachers,
                displayInfo: true,
                displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
                beforePageText: '<?php __('Page'); ?>',
                afterPageText: '<?php __('of'); ?> {0}',
                emptyMsg: '<?php __('No data to display'); ?>'
            })
        }]
    });

    var EduSubjectViewWindow = new Ext.Window({
        title: '<?php __('View Subject'); ?>: <?php echo $edu_subject['EduSubject']['name']; ?>',
        width: 500,
        height: 465,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [ 
            edu_subject_view_panel_1,
            edu_subject_view_panel_2
        ],
        buttons: [{
            text: '<?php __('Close'); ?>',
            handler: function(btn){
                EduSubjectViewWindow.close();
            }
        }]
    });
