//<script>
    var store_eduClass_eduCourses = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'edu_class', 'edu_subject', 'description', 'min_for_pass', 'is_mandatory', 'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'list_data', $edu_class['EduClass']['id'])); ?>'
        })
    });

    var store_eduClass_eduSections = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'edu_class', 'students', 'edu_campus', 'homeroom', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_sections', 'action' => 'list_data2', $edu_class['EduClass']['id'])); ?>'})
    });
	
	var store_eduClass_eduPrevSections = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'edu_class', 'students', 'edu_campus', 'homeroom', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_sections', 'action' => 'list_data_prev', $edu_class['EduClass']['id'])); ?>'})
    });
    
    var store_eduClass_eduEvaluation = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id','edu_evaluation_area','edu_evaluation_category','order_level','modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluations', 'action' => 'list_data', $edu_class['EduClass']['id'])); ?>'})
    });
    
    var store_eduClass_eduPaymentSchedule = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'month', 'amount', 'edu_academic_year'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_payment_schedules', 'action' => 'list_data', $edu_class['EduClass']['id'])); ?>'})
    });
    
    var store_eduClass_eduExtraPayment = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'amount', 'edu_academic_year'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_extra_payment_settings', 'action' => 'list_data', $edu_class['EduClass']['id'])); ?>'})
    });
    
    <?php $grading_types = array('N' => 'Numeric', 'A' => 'Alphabetic', 'G' => 'Evaluation Value', 'M' => 'Mixed'); ?>
    <?php
    $eduClass_html = "<table width=100% cellspacing=3 class=viewtable>" .
            "<tr><th align=right>" . __('Cycle', true) . ":</th><td><b>" . $edu_class['EduClassLevel']['name'] . "</b></td>" .
            "    <th align=right></th><td><b></b></td></tr>" .
            "<tr><th align=right>" . __('Grading Type', true) . ":</th><td><b>" . $grading_types[$edu_class['EduClass']['grading_type']] . "</b></td>" .
            "    <th align=right></th><td><b></b></td></tr>" .
            "<tr><th align=right>" . __('Rank Display Status', true) . ":</th><td><b>" . ($edu_class['EduClass']['rank_display'] == 'D' ? 'Display All' : ($edu_class['EduClass']['rank_display'] == 'N' ? 'Dont Display': 'Upto ' . $edu_class['EduClass']['rank_display_upto'])) . "</b></td>" .
            "    <th align=right>" . __('Is Uni-Teacher?', true) . ":</th><td><b>" . ($edu_class['EduClass']['uni_teacher'] ? 'Yes' : 'No') . "</b></td></tr>" .
            "<tr><th align=right>" . __('Min. Mark to Pass', true) . ":</th><td><b>" . $edu_class['EduClass']['min_for_promotion'] . "</b></td>" .
            "    <th align=right>" . __('Last Modified', true) . ":</th><td><b>" . $edu_class['EduClass']['modified'] . "</b></td></tr>" .
            "</table>";
    ?>
    var eduClass_view_panel_1 = {
        html: '<?php echo $eduClass_html; ?>',
        frame: true,
        height: 100
    }
    var eduClass_view_panel_2 = new Ext.TabPanel({
        activeTab: 0,
        anchor: '100%',
        height: 290,
        plain: true,
        defaults: {autoScroll: true},
        items: [{
                xtype: 'grid',
                loadMask: true,
                stripeRows: true,
                store: store_eduClass_eduCourses,
                title: '<?php __('Courses'); ?>',
                enableColumnMove: false,
                listeners: {
                    activate: function () {
                        if (store_eduClass_eduCourses.getCount() == '')
                            store_eduClass_eduCourses.reload();
                    }
                },
                columns: [
                    {header: "<?php __('Tilte'); ?>", dataIndex: 'edu_subject', sortable: true},
                    {header: "<?php __('Min. Mark'); ?>", dataIndex: 'min_for_pass', sortable: true},
                    {header: "<?php __('Is Mandatory?'); ?>", dataIndex: 'is_mandatory', sortable: true},
                    {header: "<?php __('Last Modified'); ?>", dataIndex: 'modified', sortable: true}
                ],
                viewConfig: {
                    forceFit: true
                },
                bbar: new Ext.PagingToolbar({
                    pageSize: view_list_size,
                    store: store_eduClass_eduCourses,
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
                store: store_eduClass_eduSections,
                title: '<?php __('Current Sections'); ?>',
                enableColumnMove: false,
                listeners: {
                    activate: function () {
                        if (store_eduClass_eduSections.getCount() == '')
                            store_eduClass_eduSections.reload();
                    }
                },
                columns: [
                    {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                    {header: "<?php __('Homeroom Teacher'); ?>", dataIndex: 'homeroom', sortable: true},
                    {header: "<?php __('# of Students'); ?>", dataIndex: 'students', sortable: true},
                    {header: "<?php __('Campus'); ?>", dataIndex: 'edu_campus', sortable: true},
                    {header: "<?php __('Last Modified'); ?>", dataIndex: 'modified', sortable: true}
                ],
                viewConfig: {
                    forceFit: true
                },
                bbar: new Ext.PagingToolbar({
                    pageSize: view_list_size,
                    store: store_eduClass_eduSections,
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
                store: store_eduClass_eduPrevSections,
                title: '<?php __('Prev Sections'); ?>',
                enableColumnMove: false,
                listeners: {
                    activate: function () {
                        if (store_eduClass_eduPrevSections.getCount() == '')
                            store_eduClass_eduPrevSections.reload();
                    }
                },
                columns: [
                    {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                    {header: "<?php __('Homeroom Teacher'); ?>", dataIndex: 'homeroom', sortable: true},
                    {header: "<?php __('# of Students'); ?>", dataIndex: 'students', sortable: true},
                    {header: "<?php __('Campus'); ?>", dataIndex: 'edu_campus', sortable: true},
                    {header: "<?php __('Last Modified'); ?>", dataIndex: 'modified', sortable: true}
                ],
                viewConfig: {
                    forceFit: true
                },
                bbar: new Ext.PagingToolbar({
                    pageSize: view_list_size,
                    store: store_eduClass_eduPrevSections,
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
                store: store_eduClass_eduEvaluation,
                title: '<?php __('Evaluations'); ?>',
                enableColumnMove: false,
                listeners: {
                    activate: function () {
                        if (store_eduClass_eduEvaluation.getCount() == '')
                            store_eduClass_eduEvaluation.reload();
                    }
                },
                columns: [
                    {header: "<?php __('Eval. Area'); ?>", dataIndex: 'edu_evaluation_area', sortable: true},
                    {header: "<?php __('Eval. Category'); ?>", dataIndex: 'edu_evaluation_category', sortable: true},
                    {header: "<?php __('Order'); ?>", dataIndex: 'order_level', sortable: true},
                    {header: "<?php __('Last Modified'); ?>", dataIndex: 'modified', sortable: true}
                ],
                viewConfig: {
                    forceFit: true
                },
                bbar: new Ext.PagingToolbar({
                    pageSize: view_list_size,
                    store: store_eduClass_eduEvaluation,
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
                store: store_eduClass_eduPaymentSchedule,
                title: '<?php __('Payment Schedules'); ?>',
                enableColumnMove: false,
                listeners: {
                    activate: function () {
                        if (store_eduClass_eduPaymentSchedule.getCount() == '')
                            store_eduClass_eduPaymentSchedule.reload();
                    }
                },
                columns: [
                    {header: "<?php __($payment_schedule_method == 'M' ? 'Month' : 'Quarter'); ?>", dataIndex: 'month', sortable: true},
                    {header: "<?php __('Amount'); ?>", dataIndex: 'amount', sortable: true},
                    {header: "<?php __('Academic Year'); ?>", dataIndex: 'edu_academic_year', sortable: true}
                ],
                viewConfig: {
                    forceFit: true
                },
                bbar: new Ext.PagingToolbar({
                    pageSize: view_list_size,
                    store: store_eduClass_eduPaymentSchedule,
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
                store: store_eduClass_eduExtraPayment,
                title: '<?php __('Extra Payments'); ?>',
                enableColumnMove: false,
                listeners: {
                    activate: function () {
                        if (store_eduClass_eduExtraPayment.getCount() == '')
                            store_eduClass_eduExtraPayment.reload();
                    }
                },
                columns: [
                    {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                    {header: "<?php __('Amount'); ?>", dataIndex: 'amount', sortable: true},
                    {header: "<?php __('Academic Year'); ?>", dataIndex: 'edu_academic_year', sortable: true}
                ],
                viewConfig: {
                    forceFit: true
                },
                bbar: new Ext.PagingToolbar({
                    pageSize: view_list_size,
                    store: store_eduClass_eduExtraPayment,
                    displayInfo: true,
                    displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
                    beforePageText: '<?php __('Page'); ?>',
                    afterPageText: '<?php __('of'); ?> {0}',
                    emptyMsg: '<?php __('No data to display'); ?>'
                })
            }]
    });

    var EduClassViewWindow = new Ext.Window({
        title: '<?php __('View Class'); ?>: <?php echo $edu_class['EduClass']['name']; ?>',
        width: 650,
        height: 465,
        resizable: false,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        modal: true,
        items: [
            eduClass_view_panel_1,
            eduClass_view_panel_2
        ],
        buttons: [{
            text: '<?php __('Close'); ?>',
            handler: function (btn) {
                EduClassViewWindow.close();
            }
        }]
    });
