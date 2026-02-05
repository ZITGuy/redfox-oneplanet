//<script>
    var store_eduCourses = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'edu_class', 'edu_subject', 'description', 'created', 'modified']
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'list_data')); ?>"
        }),
        sortInfo: {field: 'edu_subject', direction: "ASC"},
        groupField: 'edu_class'
    });
    
    function ViewEduCourse(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'view')); ?>/' + id,
            success: function(response, opts) {
                var eduCourse_data = response.responseText;

                eval(eduCourse_data);

                EduCourseViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Course view form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function SearchEduCourse() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'search')); ?>',
            success: function (response, opts) {
                var eduCourse_data = response.responseText;

                eval(eduCourse_data);

                eduCourseSearchWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduCourse search form. Error Code'); ?>: " + response.status);
            }
        });
    }

    function SearchByEduCourseName(value) {
        var conditions = '\'EduCourse.description LIKE\' => \'%' + value + '%\'';
        store_eduCourses.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshEduCourseData() {
        store_eduCourses.reload();
    }


    if (center_panel.find('id', 'eduCourse-tab-v') != "") {
        var p = center_panel.findById('eduCourse-tab-v');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Courses'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduCourse-tab-v',
            xtype: 'grid',
            store: store_eduCourses,
            columns: [
                {header: "<?php __('Class'); ?>", dataIndex: 'edu_class', sortable: true},
                {header: "<?php __('Subject'); ?>", dataIndex: 'edu_subject', sortable: true},
                {header: "<?php __('Description'); ?>", dataIndex: 'description', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
            ],
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Courses" : "Course"]})'
            }),
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
            listeners: {
                celldblclick: function () {
                    ViewEduCourse(Ext.getCmp('eduCourse-tab-v').getSelectionModel().getSelected().data.id);
                }
            },
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: "<?php __('View Course'); ?>",
                        id: 'view-eduCourse-v',
                        tooltip: "<?php __('<b>View Course</b><br />Click here to see details of the selected Course'); ?>",
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewEduCourse(sel.data.id);
                            }
                        }
                    }, ' ', '-', ' ', "<?php __('Filter by Class'); ?>: ", {
                        xtype: 'combo',
                        emptyText: 'All',
                        store: new Ext.data.ArrayStore({
                            fields: ['id', 'name'],
                            data: [
                                ['-1', 'All'],
    <?php $st = false;
    foreach ($edu_classes as $item) {
        if ($st) echo ",
		"; ?>['<?php echo $item['EduClass']['id']; ?>', '<?php echo $item['EduClass']['name']; ?>']<?php $st = true;
} ?>]
                        }),
                        displayField: 'name',
                        valueField: 'id',
                        mode: 'local',
                        value: '-1',
                        disableKeyFilter: true,
                        triggerAction: 'all',
                        listeners: {
                            select: function (combo, record, index) {
                                store_eduCourses.reload({
                                    params: {
                                        start: 0,
                                        limit: list_size,
                                        edu_class_id: combo.getValue()
                                    }
                                });
                            }
                        }
                    }, '->', {
                        xtype: 'textfield',
                        emptyText: "<?php __('[Search By Name]'); ?>",
                        id: 'eduCourse_search_field-v',
                        listeners: {
                            specialkey: function (field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduCourseName(Ext.getCmp('eduCourse_search_field-v').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('GO'); ?>",
                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
                        id: "eduCourse_go_button-v",
                        handler: function () {
                            SearchByEduCourseName(Ext.getCmp('eduCourse_search_field-v').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('Advanced Search'); ?>",
                        tooltip: "<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
                        handler: function () {
                            SearchEduCourse();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_eduCourses,
                displayInfo: true,
                displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
                beforePageText: "<?php __('Page'); ?>",
                afterPageText: "<?php __('of {0}'); ?>",
                emptyMsg: "<?php __('No data to display'); ?>"
            })
        });
        
        p.getSelectionModel().on('rowselect', function (sm, rowIdx, r) {
            p.getTopToolbar().findById('view-eduCourse-v').enable();
        });
        p.getSelectionModel().on('rowdeselect', function (sm, rowIdx, r) {
            if (this.getSelections().length > 0) {
                p.getTopToolbar().findById('view-eduCourse-v').enable();
            } else {
                p.getTopToolbar().findById('view-eduCourse-v').disable();
            }
        });
        
        center_panel.setActiveTab(p);

        store_eduCourses.load({
            params: {
                start: 0,
                limit: list_size
            }
        });

    }