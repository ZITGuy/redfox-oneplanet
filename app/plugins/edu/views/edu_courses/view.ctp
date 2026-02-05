//<script>
    var store_edu_course_outlines = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'list_order', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_outlines', 'action' => 'list_data', $edu_course['EduCourse']['id'])); ?>'})
    });

    var store_edu_course_teachers = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'teacher', 'identity_number', 'class_level', 'telephone', 'mobile'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'list_data', $edu_course['EduCourse']['id'])); ?>'})
    });

    <?php
    $edu_course_html = "<table width=100% cellspacing=3 class=viewtable>" . 
            "<tr><th align=right>" . __('Course Description', true) . ":</th><td><b>" . $edu_course['EduCourse']['description'] . "</b></td>" .
            "    <th align=right>" . __('Min. Mark to Pass', true) . ":</th><td><b>" . $edu_course['EduCourse']['min_for_pass'] . "</b></td></tr>" .
            "<tr><th align=right>" . __('Last Modified', true) . ":</th><td><b>" . $edu_course['EduCourse']['modified'] . "</b></td>" .
            "    <th align=right>" . __('Is Mandatory?', true) . ":</th><td><b>" . ($edu_course['EduCourse']['is_mandatory']? 'Yes': 'No') . "</b></td></tr>" .
            "</table>";
    ?>
    var edu_course_view_panel_1 = {
        html: '<?php echo $edu_course_html; ?>',
        frame: true,
        height: 80
    }
    var edu_course_view_panel_2 = new Ext.TabPanel({
        activeTab: 0,
        anchor: '100%',
        height: 330,
        plain: true,
        defaults: {autoScroll: true},
        items: [{
                xtype: 'grid',
                loadMask: true,
                stripeRows: true,
                store: store_edu_course_outlines,
                title: '<?php __('Course Outlines'); ?>',
                enableColumnMove: false,
                listeners: {
                    activate: function () {
                        if (store_edu_course_outlines.getCount() == '')
                            store_edu_course_outlines.reload();
                    }
                },
                columns: [
                    {header: "<?php __('Title'); ?>", dataIndex: 'name', sortable: true},
                    {header: "<?php __('List Order'); ?>", dataIndex: 'list_order', sortable: true},
                    {header: "<?php __('Last Modified'); ?>", dataIndex: 'modified', sortable: true}
                ],
                viewConfig: {
                    forceFit: true
                },
                bbar: new Ext.PagingToolbar({
                    pageSize: view_list_size,
                    store: store_edu_course_outlines,
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
                store: store_edu_course_teachers,
                title: '<?php __('Teachers'); ?>',
                enableColumnMove: false,
                listeners: {
                    activate: function () {
                        if (store_edu_course_teachers.getCount() == '')
                            store_edu_course_teachers.reload();
                    }
                },
                columns: [
                    {header: "<?php __('Name'); ?>", dataIndex: 'teacher', sortable: true},
                    {header: "<?php __('ID Number'); ?>", dataIndex: 'identity_number', sortable: true},
                    {header: "<?php __('Cycle'); ?>", dataIndex: 'class_level', sortable: true},
                    {header: "<?php __('Telephone'); ?>", dataIndex: 'telephone', sortable: true},
                    {header: "<?php __('Mobile'); ?>", dataIndex: 'mobile', sortable: true}
                ],
                viewConfig: {
                    forceFit: true
                },
                bbar: new Ext.PagingToolbar({
                    pageSize: view_list_size,
                    store: store_edu_course_teachers,
                    displayInfo: true,
                    displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
                    beforePageText: '<?php __('Page'); ?>',
                    afterPageText: '<?php __('of'); ?> {0}',
                    emptyMsg: '<?php __('No data to display'); ?>'
                })
            }]
    });

    var EduCourseViewWindow = new Ext.Window({
        title: '<?php __('View Course'); ?>: <font color=green><strong><?php echo $edu_course['EduCourse']['description']; ?></strong></font>',
        width: 700,
        height: 485,
        resizable: false,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        modal: true,
        items: [
            edu_course_view_panel_1,
            edu_course_view_panel_2
        ],
        buttons: [{
                text: '<?php __('Close'); ?>',
                handler: function (btn) {
                    EduCourseViewWindow.close();
                }
            }]
    });
