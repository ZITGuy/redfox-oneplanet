var store_parent_eduPeriods = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_section','edu_schedule','period',<?php for($i=1; $i<=$schedule['EduSchedule']['days']; $i++){ echo "'".$day_names[$i]."',";};?>	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPeriods', 'action' => 'list_data', $parent_id)); ?>'	})
});

		var element = document.createElement('div');
		element.id = "someID";
		document.body.appendChild(element);
		
		var cont='';
		<?php  for($i=1; $i<=$schedule['EduSchedule']['days']; $i++){ ?>
		cont=cont+'<select name="teacher<?php echo $i; ?>" id="teacher<?php echo $i; ?>" style="display:none;"><option value="-">-</option><?php foreach($subjects as $subject){ ?><option value="<?php echo $subject['EduSubject']['name']; ?>"><?php echo $subject['EduSubject']['name']; ?></option><?php } ?></select>';
		<?php } ?>
		document.getElementById("someID").innerHTML=cont;
		var fm = Ext.form;


		var cm = new Ext.grid.ColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: true // columns are not sortable by default           
        },
        columns: [{
            header: 'Period',
            dataIndex: 'period',
            width: 60,
			sortable: false
        }, <?php  for($i=1; $i<=$schedule['EduSchedule']['days']; $i++){ ?>
		{
            header: '<?php echo $day_names[$i]; ?>',
            dataIndex: '<?php echo $day_names[$i]; ?>',
            width: 100,
			align:'right',
			sortable: false,
            editor: new fm.ComboBox({
                triggerAction: 'all',
				forceSelection: true,
                transform: 'teacher<?php echo $i; ?>',
                lazyRender: true,
                listClass: 'x-combo-list-small'
            })
        },
		<?php } ?>
        ]
    });


 var xgrid = new Ext.grid.EditorGridPanel({
        store: store_parent_eduPeriods,
		cm:cm,
        height: 300,
        frame: true,
        clicksToEdit: 1,
		tbar:["<?php __('Class'); ?> : ",{
					xtype : 'combo',
					emptyText: 'Select Class',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							<?php $st = false;foreach ($sections as $item){if($st) echo ",
							";?>['<?php echo $item['EduSection']['id']; ?>' ,'<?php echo $item['EduClass']['name'].' '.$item['EduSection']['name']; ?>']<?php $st = true;}?>]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_parent_eduPeriods.reload({
								params: {
									section_id : combo.getValue(),
									schedule_id: <?php echo $parent_id; ?>
								}
							});
						}
					}
				}],
		bbar: [{
            text: 'Save Changes',
            handler : function(){
              var records = store_parent_eduPeriods.getRange(), fields = store_parent_eduPeriods.fields;
                var param = {};        
                for(var i = 0; i < records.length; i++) {
                    for(var j = 0; j < fields.length; j++){
                        param[ 'data['+i + '][' + fields['items'][j]['name'] +']'] = Ext.encode(records[i].get(fields['items'][j]['name']));
                    }
                }
                Ext.Ajax.request({
                    url: '<?php echo $this->Html->url(array('controller' => 'eduPeriods', 'action' => 'add')); ?>',
                    params: param,
                    method: 'POST',
                    success: function(){
                        store_parent_eduPeriods.reload();
                    },
                    failure: function(){
                        alert('Error Saving Changes, Please Try Again!');
                    }
                });
            }
        }]

    });



var parentEduPeriodsViewWindow = new Ext.Window({
	title: 'Class Preferred Period Settings',
	width: 700,
	height:375,
	minWidth: 700,
	minHeight: 400,
	resizable: false,
	plain:true,
	bodyStyle:'padding:5px;',
	buttonAlign:'center',
        modal: true,
	items: [
		xgrid
	]
});

store_parent_eduPeriods.load();