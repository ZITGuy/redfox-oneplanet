//<script>
    var store_eduTeachers = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'teacher', 'identity_number', 'telephone', 'mobile'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'list_data')); ?>"
        }),
        sortInfo: {field: 'teacher', direction: "ASC"}
    });

    function ViewEduTeacher(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'view')); ?>/" + id,
            success: function (response, opts) {
                var eduTeacher_data = response.responseText;

                eval(eduTeacher_data);

                EduTeacherViewWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Teacher view form. Error code'); ?>: " + response.status);
            }
        });
    }

    function SearchEduTeacher() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'search')); ?>',
            success: function (response, opts) {
                var eduTeacher_data = response.responseText;

                eval(eduTeacher_data);

                eduTeacherSearchWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Teacher search form. Error Code'); ?>: " + response.status);
            }
        });
    }

    function SearchByEduTeacherName(value) {
        var conditions = '\'EduTeacher.name LIKE\' => \'%' + value + '%\'';
        store_eduTeachers.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshEduTeacherData() {
        store_eduTeachers.reload();
    }


    if (center_panel.find('id', 'eduTeacherV-tab') != "") {
        var p = center_panel.findById('eduTeacherV-tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Teachers'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduTeacherV-tab',
            xtype: 'grid',
            store: store_eduTeachers,
            columns: [
                {header: "<?php __('Teacher'); ?>", dataIndex: 'teacher', sortable: true},
                {header: "<?php __('ID Number'); ?>", dataIndex: 'identity_number', sortable: true},
                {header: "<?php __('Telephone'); ?>", dataIndex: 'telephone', sortable: true},
                {header: "<?php __('Mobile'); ?>", dataIndex: 'mobile', sortable: true}
            ],
            viewConfig: {
                forceFit: true
            },
            listeners: {
                celldblclick: function () {
                    ViewEduTeacher(p.getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: true
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: "<?php __('View'); ?>",
                        id: 'view-eduTeacher',
                        tooltip: "<?php __('<b>View Teacher</b><br />Click here to see details of the selected Teacher'); ?>",
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewEduTeacher(sel.data.id);
                            }
                        }
                    }, '->', {
                        xtype: 'textfield',
                        emptyText: "<?php __('[Search By Name]'); ?>",
                        id: 'eduTeacher_search_field',
                        listeners: {
                            specialkey: function (field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduTeacherName(Ext.getCmp('eduTeacher_search_field').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('GO'); ?>",
                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
                        id: "eduTeacher_go_button",
                        handler: function () {
                            SearchByEduTeacherName(Ext.getCmp('eduTeacher_search_field').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('Advanced Search'); ?>",
                        tooltip: "<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
                        handler: function () {
                            SearchEduTeacher();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_eduTeachers,
                displayInfo: true,
                displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
                beforePageText: "<?php __('Page'); ?>",
                afterPageText: "<?php __('of {0}'); ?>",
                emptyMsg: "<?php __('No data to display'); ?>"
            })
        });
        p.getSelectionModel().on('rowselect', function (sm, rowIdx, r) {
            p.getTopToolbar().findById('view-eduTeacher').enable();
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('view-eduTeacher').disable();
            }
        });
        p.getSelectionModel().on('rowdeselect', function (sm, rowIdx, r) {
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('view-eduTeacher').disable();
            } else if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('view-eduTeacher').enable();
            } else {
                p.getTopToolbar().findById('view-eduTeacher').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_eduTeachers.load({
            params: {
                start: 0,
                limit: list_size
            }
        });
    }