//<script>
    var selsection='';
    var selteacher = '0';
	
    var store_periods = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','day' <?php for($i = 1; $i <=$num_periods; $i++){ echo ", 'period$i'"; } ?>
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_schedules', 'action' => 'list_data_periods')); ?>'
        })
    });
    
    function RefreshPeriodsData() {
        if(selsection != ''){
            store_periods.reload({
                params: {
                    start: 0,
                    selsection: selsection,
                    selteacher: selteacher
                }
            });
        }
    }
    
    <?php
        $this->ExtForm->create('EduSchedule');
    ?>
        
    var store_sections = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_sections', 'action' => 'list_data')); ?>'
        })
    });
    
    var store_course = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id2', 'name'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'list_data')); ?>'
        })
    });
    //store_course.load();
    
    var store_teacher = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id2', 'name'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'list_data')); ?>'
        })
    });
    
    var popUpWin_print=0;
	
    function popUpWindow(URLStr, left, top, width, height) {
        if(popUpWin_print){
            if(!popUpWin_print.closed) popUpWin_print.close();
        }
        popUpWin_print = open(URLStr, 'popUpWin',
            'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,'+
            'resizable=yes,copyhistory=yes,width='+width+',height='+height+',left='+left+
            ', top='+top+',screenX='+left+',screenY='+top+'');
    }
	
    function printSchedule() {
        url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_schedules', 'action' => 'print_schedule', 'plugin' => 'edu')); ?>/"+selsection;
        popUpWindow(url, 200, 200, 700, 1000);
    }
	
	function isUniTeacher(class_id) {
		<?php
			$uni_classes = '';
			$non_uni_classes = '';
			foreach ($all_classes as $c) {
				if ($c['EduClass']['uni_teacher'] == 1) {
					$uni_classes .= $c['EduClass']['id'] . ',';
				} else {
					$non_uni_classes .= $c['EduClass']['id'] . ',';
				}
			}
		?>
		var uni_classes = '<?php echo $uni_classes; ?>';
		var non_uni_classes = '<?php echo $non_uni_classes; ?>';
		
		if(uni_classes.indexOf(class_id + ',') >= 0) {
			return true;
		}
		return false;
	}
    
    var ManualSchedulerForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        autoHeight: true,
        width: 500,
        resizable: false,
        plain:true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',
        url:'<?php echo $this->Html->url(array(
            'controller' => 'edu_schedules', 'action' => 'manual_schedule')); ?>',
        defaultType: 'textfield',
        items: [
            <?php
                $options = array('fieldLabel' => 'Class', 'anchor' => '45%');
                $options['items'] = $classes;
                $options['listeners'] = "{
                    scope: this,
                    'select': function(combo, record, index){
                        ManualSchedulerForm.el.mask('Please wait', 'x-mask-loading');
                        var section = Ext.getCmp('section_id');
                        section.setValue('');
                        section.store.removeAll();
                        section.store.reload({
                            params: {
                                edu_class_id : combo.getValue()
                            }
                        });
                        selsection='';
                        
                        store_course.reload({
                            params: {
                                edu_class_id: combo.getValue()
                            }
                        });
                        
                        store_teacher.reload({
                            params: {
                                edu_class_id: combo.getValue()
                            }
                        });
						
						if(isUniTeacher(combo.getValue()))
							Ext.getCmp('teacher_id').enable();
                        else
							Ext.getCmp('teacher_id').disable();
						
                        periodsGrid.getStore().removeAll();
                        ManualSchedulerForm.el.unmask();
                    }
                }";
                $this->ExtForm->input('class_id', $options);
            ?>, {
                xtype: 'combo',
                name: 'section_id',
                id:'section_id',
                typeAhead: true,
                store : store_sections,
                displayField : 'name',
                valueField : 'id',
                anchor:'45%',
                fieldLabel: '<span style="color:red;">*</span> Section',
                mode: 'local',
                allowBlank: false,
                emptyText: 'Select Section',
                editable: false,
                triggerAction: 'all',
                listeners:{
                    scope: this,
                    'select': function (combo, record, index) {
                        selsection=combo.getValue();
                        RefreshPeriodsData();
                        Ext.getCmp('btnPrintSchedule').enable();
                    }
                }
            }, {
                xtype: 'combo',
                name: 'teacher_id',
                id:'teacher_id',
                store : store_teacher,
                displayField : 'name',
                valueField : 'id',
                anchor:'45%',
                fieldLabel: '<span style="color:red;">*</span> Uni-Teacher',
                mode: 'local',
                allowBlank: false,
                emptyText: 'Select Teacher',
                editable: false,
                triggerAction: 'all',
                listeners:{
                    scope: this,
                    'select': function (combo, record, index) {
                        selteacher = combo.getValue();
                        RefreshPeriodsData();
                        Ext.getCmp('btnPrintSchedule').enable();
                    }
                }
            }
        ]
    });
   
    var fm = Ext.form;
    var cm = new Ext.grid.ColumnModel({
        defaults: {
            sortable: false // columns are not sortable by default
        },
        columns: [{
                header: 'Day',
                dataIndex: 'day',
                width: 150,
                sortable: false,
				editor: false
            }<?php for($i = 1; $i <= $num_periods; $i++) { ?>, {
                header: 'Period <?php echo $i; ?>',
                dataIndex: 'period<?php echo $i; ?>',
                width: 100,
                sortable: false,
                editor: new fm.ComboBox({
                    id: 'combo_courses',
                    name : 'combo_courses',
                    hideLabel:false,
                    xtype: 'combo',
                    valueField : 'id2',
                    displayField : 'name',
                    hiddenName : 'id2',
                    store : store_course,
                    triggerAction : 'all',
                    selectOnFocus:true,
                    forceSelection : true,
                    mode : 'local'
                })
            }<?php } ?>
        ]
    });
    
    var periodsGrid = new Ext.grid.EditorGridPanel({
        title: '<?php __('Periods'); ?>',
        id: 'periodsGrid',
        cm: cm,
        store: store_periods,
        frame: true,
        clicksToEdit: 1,
        loadMask: true,
		enableColumnHide: false,
		enableColumnMove: false,
		enableHdMenu: false,
        stripeRows: true,
        height: 329,
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        listeners: {
            cellclick: function(grid, rowIndex, columnIndex, e) {
				if(columnIndex > 0) {
					var record = grid.getStore().getAt(rowIndex);
					var record_id = record.get('id');
					var colm = grid.getColumnModel();
					var the_combo = new Ext.form.ComboBox({
						id: 'combo_courses' + columnIndex,
						name : 'combo_courses' + columnIndex,
						hideLabel:false,
						xtype: 'combo',
						valueField : 'id2',
						displayField : 'name',
						hiddenName : 'id2',
						store : store_course,
						triggerAction : 'all',
						selectOnFocus:true,
						forceSelection : true,
						mode : 'local'
					});
					
					if(record_id > 5){
						the_combo = new Ext.form.ComboBox({
							id: 'combo_courses' + columnIndex,
							name : 'combo_courses' + columnIndex,
							hideLabel:false,
							xtype: 'combo',
							valueField : 'id2',
							displayField : 'name',
							hiddenName : 'id2',
							store : store_teacher,
							triggerAction : 'all',
							selectOnFocus:true,
							forceSelection : true,
							mode : 'local'
						});
					}
					colm.setEditor(columnIndex, the_combo);
				}
            }
        }
    });
    
    periodsGrid.on('afteredit', afterEdit, this );
    
    function afterEdit(e) {
        if(!(e.originalValue === e.value)){
            Ext.getCmp('btnSaveAll').enable();
        }
    }
    
    var ManualScheduleWindow = new Ext.Window({
        title: 'Manual Schedule Manager',
        width: 950,
        height: 485,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [
            ManualSchedulerForm, periodsGrid
        ],
        buttons: [{
            text: 'Save All',
            id: 'btnSaveAll',
            disabled: true,
            handler : function(){
                ManualSchedulerForm.el.mask('Please wait', 'x-mask-loading');
                var records = store_periods.getRange();
                var param = {};
				param['data[Teacher]'] = selteacher
                for(var i = 0; i < records.length; i++) {
                    param['data['+i+'][id]'] = Ext.encode(records[i].get('id'));
                    param['data['+i+'][section_id]'] = selsection;
<?php for ($i = 1; $i <= $num_periods; $i++) { ?>
                    param['data['+i+'][periods][<?php echo $i; ?>]'] =
                        Ext.encode(records[i].get('period<?php echo $i; ?>'));
<?php } ?>
                }
                
                Ext.Ajax.request({
                    url: '<?php echo $this->Html->url(array(
                        'controller' => 'edu_schedules', 'action' => 'save_manual_schedules')); ?>',
                    params: param,
                    method: 'POST',
                    success: function(){
                        Ext.Msg.alert("<?php __('Success'); ?>",
                            "<?php __('Manual Sechedules created successfully!'); ?>");
                        ManualSchedulerForm.el.unmask();
                        RefreshPeriodsData();
                    },
                    failure: function(){
                        alert('Error Saving Schedules, Please Try Again!');
                        ManualSchedulerForm.el.unmask();
                    }
                });
            }
        }, {
            text: 'Print',
            id: 'btnPrintSchedule',
            disabled: true,
            handler: function(btn){
                printSchedule();
            }
        }, {
            text: 'Close',
            handler: function(btn){
                ManualScheduleWindow.close();
            }
        }]
    });
    ManualScheduleWindow.show();
    