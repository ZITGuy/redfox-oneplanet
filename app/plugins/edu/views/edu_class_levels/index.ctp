//<script>
    var store_eduClassLevels = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','name', 'remark'		]
	}),
	proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_class_levels', 'action' => 'list_data')); ?>'
	})
    });


    function AddEduClassLevel() {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_class_levels', 'action' => 'add')); ?>',
            success: function(response, opts) {
                var eduClassLevel_data = response.responseText;
			
                eval(eduClassLevel_data);
			
                EduClassLevelAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Class Level add form. Error code'); ?>: ' + response.status);
            }
	});
    }

    function EditEduClassLevel(id) {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_class_levels', 'action' => 'edit')); ?>/'+id,
            success: function(response, opts) {
                var eduClassLevel_data = response.responseText;
			
                eval(eduClassLevel_data);
			
                EduClassLevelEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Class Level edit form. Error code'); ?>: ' + response.status);
            }
	});
    }

    function ViewEduClassLevel(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_class_levels', 'action' => 'view')); ?>/'+id,
            success: function(response, opts) {
                var eduClassLevel_data = response.responseText;

                eval(eduClassLevel_data);

                EduClassLevelViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Class Level view form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function DeleteEduClassLevel(id) {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_class_levels', 'action' => 'delete')); ?>/'+id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Class Level successfully deleted!'); ?>');
                RefreshEduClassLevelData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Class Level add form. Error code'); ?>: ' + response.status);
            }
	});
    }

    function SearchEduClassLevel(){
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_class_levels', 'action' => 'search')); ?>',
            success: function(response, opts){
                var eduClassLevel_data = response.responseText;

                eval(eduClassLevel_data);

                eduClassLevelSearchWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the Class Level search form. Error Code'); ?>: ' + response.status);
            }
	});
    }

    function SearchByEduClassLevelName(value){
	var conditions = '\'EduClassLevel.name LIKE\' => \'%' + value + '%\'';
	store_eduClassLevels.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
	    }
	});
    }

    function RefreshEduClassLevelData() {
	store_eduClassLevels.reload();
    }


    if(center_panel.find('id', 'eduClassLevel-tab') != "") {
	var p = center_panel.findById('eduClassLevel-tab');
	center_panel.setActiveTab(p);
    } else {
	var p = center_panel.add({
            title: '<?php __('Edu Class Levels'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduClassLevel-tab',
            xtype: 'grid',
            store: store_eduClassLevels,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Remark'); ?>", dataIndex: 'remark', sortable: true}
            ],
            viewConfig: {
                forceFit: true
            },
            listeners: {
                celldblclick: function(){
                    ViewEduClassLevel(Ext.getCmp('eduClassLevel-tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
            tbar: new Ext.Toolbar({
			
                items: [{
                        xtype: 'tbbutton',
                        text: '<?php __('Add'); ?>',
                        tooltip:'<?php __('<b>Add Class Level</b><br />Click here to create a new EduClassLevel'); ?>',
                        icon: 'img/table_add.png',
                        cls: 'x-btn-text-icon',
                        handler: function(btn) {
                            AddEduClassLevel();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('Edit'); ?>',
                        id: 'edit-eduClassLevel',
                        tooltip:'<?php __('<b>Edit Class Level</b><br />Click here to modify the selected EduClassLevel'); ?>',
                        icon: 'img/table_edit.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()){
                                EditEduClassLevel(sel.data.id);
                            };
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('Delete'); ?>',
                        id: 'delete-eduClassLevel',
                        tooltip:'<?php __('<b>Delete Class Levels</b><br />Click here to remove the selected EduClassLevel(s)'); ?>',
                        icon: 'img/table_delete.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelections();
                            if (sm.hasSelection()){
                                if(sel.length==1){
                                    Ext.Msg.show({
                                        title: '<?php __('Remove Class Level'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn){
                                            if (btn == 'yes'){
                                                DeleteEduClassLevel(sel[0].data.id);
                                            }
                                        }
                                    });
                                }else{
                                    Ext.Msg.show({
                                        title: '<?php __('Remove Class Level'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove the selected Class Levels'); ?>?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn){
                                            if (btn == 'yes'){
                                                var sel_ids = '';
                                                for(i=0;i<sel.length;i++){
                                                    if(i>0)
                                                        sel_ids += '_';
                                                    sel_ids += sel[i].data.id;
                                                }
                                                DeleteEduClassLevel(sel_ids);
                                            }
                                        }
                                    });
                                }
                            } else {
                                Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                            };
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbsplit',
                        text: '<?php __('View Class Level'); ?>',
                        id: 'view-eduClassLevel',
                        tooltip:'<?php __('<b>View EduClassLevel</b><br />Click here to see details of the selected EduClassLevel'); ?>',
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()){
                                ViewEduClassLevel(sel.data.id);
                            };
                        },
                        menu : {
                            items: [
                            ]
                        }
                    }, ' ', '-',  '->', {
                        xtype: 'textfield',
                        emptyText: '<?php __('[Search By Name]'); ?>',
                        id: 'eduClassLevel_search_field',
                        listeners: {
                            specialkey: function(field, e){
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduClassLevelName(Ext.getCmp('eduClassLevel_search_field').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('GO'); ?>',
                        tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
                        id: 'eduClassLevel_go_button',
                        handler: function(){
                            SearchByEduClassLevelName(Ext.getCmp('eduClassLevel_search_field').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('Advanced Search'); ?>',
                        tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
                        handler: function(){
                            SearchEduClassLevel();
                        }
                    }
		]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_eduClassLevels,
                displayInfo: true,
                displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
                beforePageText: '<?php __('Page'); ?>',
                afterPageText: '<?php __('of {0}'); ?>',
                emptyMsg: '<?php __('No data to display'); ?>'
            })
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            p.getTopToolbar().findById('edit-eduClassLevel').enable();
            p.getTopToolbar().findById('delete-eduClassLevel').enable();
            p.getTopToolbar().findById('view-eduClassLevel').enable();
            if(this.getSelections().length > 1){
                p.getTopToolbar().findById('edit-eduClassLevel').disable();
                p.getTopToolbar().findById('view-eduClassLevel').disable();
            }
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            if(this.getSelections().length > 1){
                p.getTopToolbar().findById('edit-eduClassLevel').disable();
                p.getTopToolbar().findById('view-eduClassLevel').disable();
                p.getTopToolbar().findById('delete-eduClassLevel').enable();
            }
            else if(this.getSelections().length == 1){
                p.getTopToolbar().findById('edit-eduClassLevel').enable();
                p.getTopToolbar().findById('view-eduClassLevel').enable();
                p.getTopToolbar().findById('delete-eduClassLevel').enable();
            }
            else{
                p.getTopToolbar().findById('edit-eduClassLevel').disable();
                p.getTopToolbar().findById('view-eduClassLevel').disable();
                p.getTopToolbar().findById('delete-eduClassLevel').disable();
            }
	});
	center_panel.setActiveTab(p);
	
	store_eduClassLevels.load({
            params: {
                start: 0,          
                limit: list_size
            }
	});
	
    }
