//<script>
    var store_parent_eduQuarters = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','name', 'short_name', 'start_date','end_date',
                'edu_academic_year','status', 'status_id',
                'openable', 'summarizable', 'quarter_type',
                'created','modified'	
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_quarters', 'action' => 'list_data', $parent_id)); ?>'	
        })
    });

    function AddParentEduQuarter() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_quarters', 'action' => 'add', $parent_id)); ?>',
            success: function(response, opts) {
                var parent_eduQuarter_data = response.responseText;

                eval(parent_eduQuarter_data);

                EduQuarterAddWindow.show();
                initializeDateMargins();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', 'Cannot get the <?php echo $term_name; ?> add form. Error code: ' + response.status);
            }
        });
    }

    function EditParentEduQuarter(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_quarters', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
            success: function(response, opts) {
                var parent_eduQuarter_data = response.responseText;

                eval(parent_eduQuarter_data);

                EduQuarterEditWindow.show();
                initializeDateMargins();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', 'Cannot get the <?php echo $term_name; ?> edit form. Error code: ' + response.status);
            }
        });
    }

    function ViewEduQuarter(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_quarters', 'action' => 'view')); ?>/'+id,
            success: function(response, opts) {
                var eduQuarter_data = response.responseText;

                eval(eduQuarter_data);

                EduQuarterViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', 'Cannot get the <?php echo $term_name; ?> view form. Error code: ' + response.status);
            }
        });
    }

    function ViewEduQuarterEduCalendarEvents(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_calendar_events', 'action' => 'index2')); ?>/'+id,
            success: function(response, opts) {
                var parent_eduCalendarEvents_data = response.responseText;

                eval(parent_eduCalendarEvents_data);

                parentEduCalendarEventsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Calendar view form. Error code'); ?>: ' + response.status);
            }
        });
    }
	
    function ViewEduQuarterCheckClosing(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_quarters', 'action' => 'check_for_closing')); ?>/'+id,
            success: function(response, opts) {
                var parent_check_for_quarter_closing_data = response.responseText;

                eval(parent_check_for_quarter_closing_data);

                parentEduQuartersCheckForClosingWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Calendar view form. Error code'); ?>: ' + response.status);
            }
        });
    }
    
    function ViewAuditTrailForQuarter(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'audit_trails', 'action' => 'index2', 'plugin' => '')); ?>/'+id+'/EduQuarter',
            success: function(response, opts) {
                var audit_trail_data = response.responseText;

                eval(audit_trail_data);

                parentAuditTrailsViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Audit Trail view form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function DeleteParentEduQuarter(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_quarters', 'action' => 'delete')); ?>/'+id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php echo $term_name; ?> successfully deleted!');
                RefreshParentEduQuarterData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', 'Cannot get the <?php echo $term_name; ?> to be deleted. Error code: ' + response.status);
            }
        });
    }

    function MaintainEducationDays(id, regenerate) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_quarters', 'action' => 'maintain_edu_days')); ?>/'+id+'/'+regenerate,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php echo $term_name; ?> Education days are maintained successfully!');
                RefreshParentEduQuarterData();
            },
            failure: function(response, opts) {
                var obj = Ext.decode(Ext.decode(JSON.stringify(response)).responseText);
                ShowErrorBox(obj.errormsg, obj.helpcode);
            }
        });
    }

    function SearchByParentEduQuarterName(value){
        var conditions = '\'EduQuarter.name LIKE\' => \'%' + value + '%\'';
        store_parent_eduQuarters.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshParentEduQuarterData() {
        store_parent_eduQuarters.reload();
    }
    
    function OpenQuarter(id, name) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_quarters', 'action' => 'open_quarter')); ?>/'+id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', name + ' <?php __('opened successfully!'); ?>');
                RefreshParentEduQuarterData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', 'Cannot open the <?php echo $term_name; ?>. Error code: ' + response.status);
            }
        });
    }

    function RunEOQ(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_quarters', 'action' => 'check_for_closing')); ?>/'+id,
            success: function(response, opts) {
                var parent_check_for_quarter_closing_data = response.responseText;

                eval(parent_check_for_quarter_closing_data);

                parentEduQuartersCheckForClosingWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Calendar view form. Error code'); ?>: ' + response.status);
            }
        });
    }
	
	function SummerizeQuarterResults(id) {
		Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_quarter_summaries', 'action' => 'index2')); ?>/'+id,
            success: function(response, opts) {
                var eduQuartersSummaryData = response.responseText;

                eval(eduQuartersSummaryData);

                eduQuartersSummaryWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', 'Cannot get Summarize <?php echo $term_name; ?> Window. Error code: ' + response.status);
            }
        });
    }

    var quarter_g = new Ext.grid.GridPanel({
        title: '<?php echo Inflector::pluralize($term_name); ?>',
        store: store_parent_eduQuarters,
        loadMask: true,
        stripeRows: true,
        height: 380,
        anchor: '100%',
        //id: 'eduQuarterGrid2',
        columns: [
            {header: "<?php echo $term_name; ?> Name", dataIndex: 'name', width: 50, sortable: true},
            {header: "<?php __('Short Name'); ?>", dataIndex: 'short_name', width: 35, sortable: true},
            {header: "<?php __('Start Date'); ?>", dataIndex: 'start_date', width: 50, sortable: true},
            {header: "<?php __('End Date'); ?>", dataIndex: 'end_date', width: 50, sortable: true},
            {header: "<?php __('Academic Year'); ?>", dataIndex: 'edu_academic_year', sortable: true, hidden: true},
            {header: "<?php __('Status'); ?>", dataIndex: 'status', width: 80, sortable: true},
            {header: "<?php __('Openable'); ?>", dataIndex: 'openable', sortable: true, hidden: true},
            {header: "<?php __('Type'); ?>", dataIndex: 'quarter_type', width: 80, sortable: true},
            {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
            {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true},
            {
                header:'<?php __('Actions'); ?>',
                xtype: 'actioncolumn',
                width: 50,
                items: [{
                    icon   : 'img/search.png',  // Use a URL in the icon config
                    tooltip: 'View',
                    handler: function(grid, rowIndex, colIndex) {
                        var rec = store_parent_eduQuarters.getAt(rowIndex);
                        ViewEduQuarter(rec.get('id'));
                    }
                }, ' ', ' ', ' ', ' ', ' ', '-', ' ', ' ', ' ', ' ', ' ', {
                    icon   : 'img/calendar_add.png',
                    tooltip: 'Add Calendar Events',
                    handler: function(grid, rowIndex, colIndex) {
                        var rec = store_parent_eduQuarters.getAt(rowIndex);
                        ViewEduQuarterEduCalendarEvents(rec.get('id'));
                    }
                }, ' ', ' ', ' ', ' ', ' ', '-', ' ', ' ', ' ', ' ', ' ', {
                    icon   : 'img/at.png',
                    tooltip: 'Audit Trail',
                    handler: function(grid, rowIndex, colIndex) {
                        var rec = store_parent_eduQuarters.getAt(rowIndex);
                        ViewAuditTrailForQuarter(rec.get('id'));
                    }
                }]
            }
        ],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        viewConfig: {
            forceFit: true
        },
        listeners: {
            celldblclick: function(){
                ViewEduQuarter(Ext.getCmp('eduQuarterGrid').getSelectionModel().getSelected().data.id);
            }
        },
        tbar: new Ext.Toolbar({
            items: [{
                    xtype: 'tbbutton',
                    text: '<?php __('Add'); ?>',
                    tooltip:'<b>Add <?php echo $term_name; ?></b><br />Click here to create a new <?php echo $term_name; ?>',
                    icon: 'img/table_add.png',
                    cls: 'x-btn-text-icon',
                    disabled: <?php echo ($edu_academic_year['EduAcademicYear']['status_id'] == 8)? 'true': 'false'; ?>,
                    handler: function(btn) {
                        AddParentEduQuarter();
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Edit'); ?>',
                    id: 'edit-parent-eduQuarter-beh',
                    tooltip:'<b>Edit <?php echo $term_name; ?></b><br />Click here to modify the selected <?php echo $term_name; ?>',
                    icon: 'img/table_edit.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = quarter_g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()){
                            EditParentEduQuarter(sel.data.id);
                        };
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Maintain Days'); ?>',
                    id: 'btn_maintain_edu_days',
                    tooltip:'<?php __('<b>Maintain Education Days</b><br />Click here to create the Education days for the selected Quarter'); ?>',
                    icon: 'img/table_edit.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = quarter_g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()){
							Ext.Msg.show({
								title: 'Want to regenerate days?',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: 'Do you want to regenerate if days are already maintained?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes') {
										MaintainEducationDays(sel.data.id, 1);
									} else if(btn == 'no') {
										MaintainEducationDays(sel.data.id, 0);
									}
								}
							});
                        };
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Delete'); ?>',
                    id: 'delete-parent-eduQuarter',
                    tooltip:'<b>Delete <?php echo $term_name; ?></b><br />Click here to remove the selected <?php echo $term_name; ?>',
                    icon: 'img/table_delete.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = quarter_g.getSelectionModel();
                        var sel = sm.getSelections();
                        if (sm.hasSelection()){
                            if(sel.length==1){
                                Ext.Msg.show({
                                    title: 'Remove <?php echo $term_name; ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
                                        if (btn == 'yes'){
                                            DeleteParentEduQuarter(sel[0].data.id);
                                        }
                                    }
                                });
                            } else {
                                Ext.Msg.show({
                                    title: 'Remove <?php echo $term_name; ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: 'Remove the selected <?php echo $term_name; ?>?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
                                        if (btn == 'yes'){
                                            var sel_ids = '';
                                            for(i=0;i<sel.length;i++){
                                                if(i>0)
                                                    sel_ids += '_';
                                                sel_ids += sel[i].data.id;
                                            }
                                            DeleteParentEduQuarter(sel_ids);
                                        }
                                    }
                                });
                            }
                        } else {
                            Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                        };
                    }
                }, ' ','-',' ', {
                    xtype: 'tbbutton',
                    text: 'View',
                    id: 'btn_ViewQuarter',
                    tooltip:'<b>View <?php echo $term_name; ?></b><br />Click here to see details of the selected <?php echo $term_name; ?>',
                    icon: 'img/search.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = quarter_g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()){
                            ViewEduQuarter(sel.data.id);
                        };
                    }
                }, ' ', '-', ' ', {
                    text: '<?php __('Events'); ?>',
                    icon: 'img/calendar_add.png',
                    cls: 'x-btn-text-icon',
                    id: 'btn_add_calendar_events',
                    disabled: true,
                    handler: function(btn) {
                        var sm = quarter_g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()){
                            ViewEduQuarterEduCalendarEvents(sel.data.id);
                        }
                    }
                }, ' ', '-', ' ', {
                    text: '<?php __('Summarize Results'); ?>',
                    icon: 'img/calendar_add.png',
                    cls: 'x-btn-text-icon',
                    id: 'btn_summarize_result',
                    disabled: true,
                    handler: function(btn) {
						var sm = quarter_g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()){
                            SummerizeQuarterResults(sel.data.id);
                        } 
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: 'Run EOQ',
                    id: 'close-open-eduQuarter',
                    tooltip:'<b>Run EOQ on the selected <?php echo $term_name; ?></b><br />Click here to Run EOQ on the selected <?php echo $term_name; ?>',
                    icon: 'img/quarter_open.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = quarter_g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()){
                            RunEOQ(sel.data.id, sel.data.name);
                            Ext.getCmp('close-open-eduQuarter').disable();
                        }
                    }
                }, ' '
            ]}),
        bbar: new Ext.PagingToolbar({
            pageSize: list_size,
            store: store_parent_eduQuarters,
            displayInfo: true,
            displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
            beforePageText: '<?php __('Page'); ?>',
            afterPageText: '<?php __('of {0}'); ?>',
            emptyMsg: '<?php __('No data to display'); ?>'
        })
    });
    var ac = "<img src='/smis/img/symbol_check.png' alt='' /> <font color='lightgreen'><b> Active / Open</b></font>"; 
    quarter_g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
        quarter_g.getTopToolbar().findById('edit-parent-eduQuarter-beh').enable();
        quarter_g.getTopToolbar().findById('close-open-eduQuarter').enable();
        quarter_g.getTopToolbar().findById('delete-parent-eduQuarter').enable();
        quarter_g.getTopToolbar().findById('btn_ViewQuarter').enable();
        quarter_g.getTopToolbar().findById('btn_add_calendar_events').enable();
        quarter_g.getTopToolbar().findById('btn_summarize_result').enable();

        quarter_g.getTopToolbar().findById('btn_maintain_edu_days').disable();
        
        if(this.getSelections().length == 1){
            record = quarter_g.getStore().getAt(rowIdx);
            quarter_g.getTopToolbar().findById('close-open-eduQuarter').enable();
            if(record.get('status_id') != 1 || record.get('end_date') != '<?php echo $today; ?>') {
                quarter_g.getTopToolbar().findById('close-open-eduQuarter').disable();
            }

            if(record.get('quarter_type') == 'Non-Educational'){
                quarter_g.getTopToolbar().findById('btn_maintain_edu_days').disable();
			} else if(record.get('status_id') == 9) { // on created status
				quarter_g.getTopToolbar().findById('btn_maintain_edu_days').enable();
			}
			
            if(record.get('status_id') != 9){
                quarter_g.getTopToolbar().findById('delete-parent-eduQuarter').disable();
                quarter_g.getTopToolbar().findById('edit-parent-eduQuarter-beh').disable();
                if(record.get('status_id') == 1 && record.get('end_date') > '<?php echo $today; ?>'){
                    quarter_g.getTopToolbar().findById('edit-parent-eduQuarter-beh').enable();
                }
            }
			
			if(record.get('status_id') == 1 && record.get('summarizable') == 1) { // on active status
				quarter_g.getTopToolbar().findById('btn_summarize_result').enable();
			} else {
				quarter_g.getTopToolbar().findById('btn_summarize_result').disable();
			}
        }
    });
        
    quarter_g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
        if(this.getSelections().length > 0){
            record = quarter_g.getStore().getAt(rowIdx);
            quarter_g.getTopToolbar().findById('edit-parent-eduQuarter-beh').enable();
            quarter_g.getTopToolbar().findById('delete-parent-eduQuarter').enable();
            quarter_g.getTopToolbar().findById('btn_ViewQuarter').enable();
            quarter_g.getTopToolbar().findById('btn_add_calendar_events').enable();
            quarter_g.getTopToolbar().findById('btn_summarize_result').enable();
            quarter_g.getTopToolbar().findById('btn_maintain_edu_days').disable();
            if(record.get('quarter_type') == 'Non-Educational'){
                quarter_g.getTopToolbar().findById('btn_summarize_result').disable();
            } else if(record.get('status_id') == 9) { // on created status
				quarter_g.getTopToolbar().findById('btn_maintain_edu_days').enable();
			}
			
			if(record.get('status_id') == 1 && record.get('summarizable') == 1) { // on active status
				quarter_g.getTopToolbar().findById('btn_summarize_result').enable(); 
			} else {
				quarter_g.getTopToolbar().findById('btn_summarize_result').disable();
			}
			
        } else {
            quarter_g.getTopToolbar().findById('edit-parent-eduQuarter-beh').disable();
            quarter_g.getTopToolbar().findById('delete-parent-eduQuarter').disable();
            quarter_g.getTopToolbar().findById('btn_ViewQuarter').disable();
            quarter_g.getTopToolbar().findById('btn_add_calendar_events').disable();
            quarter_g.getTopToolbar().findById('btn_summarize_result').disable();
            quarter_g.getTopToolbar().findById('btn_maintain_edu_days').disable();
        }
    });

    var parentEduQuartersViewWindow = new Ext.Window({
        title: '<?php echo $term_name; ?> of the <b><i><?php echo $edu_academic_year['EduAcademicYear']['name']; ?></i></b>',
        width: 800,
        height: 455,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [
            quarter_g
        ],

        buttons: [{
                text: 'Close',
                handler: function(btn){
                    parentEduQuartersViewWindow.close();
                }
            }]
    });

    store_parent_eduQuarters.load({
        params: {
            start: 0,    
            limit: list_size
        }
    });