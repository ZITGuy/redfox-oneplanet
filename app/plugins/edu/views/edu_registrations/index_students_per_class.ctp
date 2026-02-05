//<script>// Students Per Section
    var store_students = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'student_name', 'identity_number', 'edu_class', 'edu_section', 'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_registrations', 'action' => 'list_data_students_per_class')); ?>"
        }),
        sortInfo: {field: 'edu_class', direction: "ASC"},
        groupField: 'edu_class'
    });

    function RefreshEduStudentData() {
        store_students.reload();
    }
    
    if (center_panel.find('id', 'eduStudentsPerClass-tab') != "") {
        var p = center_panel.findById('eduStudentsPerClass-tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Students Per Class'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduStudentsPerClass-tab',
            xtype: 'grid',
            store: store_students,
            columns: [
                {header: "<?php __('Student'); ?>", dataIndex: 'student_name', sortable: true},
		{header: "<?php __('Student ID'); ?>", dataIndex: 'identity_number', sortable: true},
		{header: "<?php __('Class'); ?>", dataIndex: 'edu_class', sortable: true},
                {header: "<?php __('Section'); ?>", dataIndex: 'edu_section', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
            ],
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Students" : "Student"]})'
            }),
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
            tbar: new Ext.Toolbar({
                items: ["<?php __('Classes'); ?>: ", {
                	xtype: 'combo',
                	emptyText: 'All',
                	store: new Ext.data.ArrayStore({
                    		fields: ['id', 'name'],
                    		data: [
                        		['0', 'Not Selected'],
                        	<?php 
                            		$st = false;
					foreach ($edu_classes as $edu_class){
                                		if($st) echo ",";
                        	?>['<?php echo $edu_class['EduClass']['id']; ?>', '<?php echo $edu_class['EduClass']['name']; ?>']
                        	<?php $st = true;}?>
                    	        ]
                	}),
		        displayField: 'name',
		        valueField: 'id',
		        mode: 'local',
		        value: '0',
		        disableKeyFilter: true,
		        triggerAction: 'all',
		        listeners: {
		            select: function(combo, record, index) {
                        	if(combo.getValue() != -1){
                            		gclass_id = combo.getValue();
                        	} else {
                            		gclass_id = combo.getValue();
                        	}
                        	store_students.reload({
                            	    params: {
                                	start: 0,
                                	limit: list_size,
                                	edu_class_id: combo.getValue()
                            	    }
                               });
                            }
                    	}
                }
                ]
            }),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_students,
                displayInfo: true,
                displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
                beforePageText: "<?php __('Page'); ?>",
                afterPageText: "<?php __('of {0}'); ?>",
                emptyMsg: "<?php __('No data to display'); ?>"
            })
        });
        center_panel.setActiveTab(p);

    }
