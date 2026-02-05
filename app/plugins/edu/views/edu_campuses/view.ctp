//<script>
    var store_eduCampus_user = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'username', 'person', 'email', 'is_active'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('plugin' => '', 'controller' => 'users', 'action' => 'list_data', $edu_campus['EduCampus']['id'])); ?>'})
    });
    
<?php 
    $number_of_students = 0;
    foreach ($edu_campus['EduSection'] as $section) {
        if(in_array($section['id'], $current_section_ids)){
            $number_of_students += count($section['EduRegistration']);
        }
    }
    $eduCampus_html = "<table width=100% cellspacing=3 class=viewtable>" . 		
        "<tr><th align=right>" . __('Campus Name', true) . ":</th><td><b>" . $edu_campus['EduCampus']['name'] . "</b></td>" . 
        "    <th align=right>" . __('No of Students', true) . ":</th><td><b>" . $number_of_students . "</b></td></tr>" . 
        "<tr><th align=right>" . __('Address', true) . ":</th><td><b>" . $edu_campus['EduCampus']['address'] . "</b></td>" . 
        "    <th align=right>" . __('Last Modified', true) . ":</th><td><b>" . $edu_campus['EduCampus']['modified'] . "</b></td></tr>" . 
        "<tr><th align=right>" . __('Campus Principal', true) . ":</th><td><b>" . $edu_campus['EduCampus']['principal'] . "</b></td>" . 
        "    <th align=right>" . __('Tel', true) . ":</th><td><b>" . $edu_campus['EduCampus']['telephone'] . "</b></td></tr>" . 
"</table>"; 
?>
    var eduCampus_view_panel_1 = {
        html : '<?php echo $eduCampus_html; ?>',
        frame : true,
        height: 120
    }
    var eduCampus_view_panel_2 = new Ext.TabPanel({
        activeTab: 0,
        anchor: '100%',
        height: 340,
        plain:true,
        defaults:{autoScroll: true},
        items:[{
                xtype: 'grid',
                loadMask: true,
                stripeRows: true,
                store: store_eduCampus_user,
                title: '<?php __('Users in the Campus'); ?>',
                enableColumnMove: false,
                listeners: {
                    activate: function () {
                        if (store_eduCampus_user.getCount() == '')
                            store_eduCampus_user.reload();
                    }
                },
                columns: [
                    {header: "<?php __('Username'); ?>", dataIndex: 'username', sortable: true},
                    {header: "<?php __('Full name'); ?>", dataIndex: 'person', sortable: true},
                    {header: "<?php __('Email'); ?>", dataIndex: 'email', sortable: true},
                    {header: "<?php __('Is Active?'); ?>", dataIndex: 'is_active', sortable: true}
                ],
                viewConfig: {
                    forceFit: true
                },
                bbar: new Ext.PagingToolbar({
                    pageSize: view_list_size,
                    store: store_eduCampus_user,
                    displayInfo: true,
                    displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
                    beforePageText: '<?php __('Page'); ?>',
                    afterPageText: '<?php __('of'); ?> {0}',
                    emptyMsg: '<?php __('No data to display'); ?>'
                })
            }
        ]
    });

    var EduCampusViewWindow = new Ext.Window({
        title: '<?php __('View Campus'); ?>: <?php echo $edu_campus['EduCampus']['name']; ?>',
        width: 700,
        height: 535,
        resizable: false,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        modal: true,
        items: [ 
            eduCampus_view_panel_1,
            eduCampus_view_panel_2
        ],

        buttons: [{
            text: '<?php __('Close'); ?>',
            handler: function(btn){
                    EduCampusViewWindow.close();
            }
        }]
    });
