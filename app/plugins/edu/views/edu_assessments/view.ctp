//<script>
    var store_assessment_assessmentRecords = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','student_id','student','assessment','rank'	]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_assessment_records', 'action' => 'list_data', $assessment['EduAssessment']['id'])); ?>'	})
    });
		
    var fm = Ext.form;

    var cm = new Ext.grid.ColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: true // columns are not sortable by default           
        },
        columns: [{
            id: 'name',
            header: 'Student Name',
            dataIndex: 'student',
            width: 220,
			sortable: false
        }, {
            header: 'Rank',
            dataIndex: 'rank',
            width: 130,
			align:'right',
			sortable: false,
            editor: new fm.NumberField({
                allowBlank: false,
                allowNegative: false
            })
        }]
    });


 var xgrid = new Ext.grid.EditorGridPanel({
        store: store_assessment_assessmentRecords,
        cm:cm,
        width: 600,
        height: 300,
        frame: true,
        clicksToEdit: 1,
        bbar: [{
            text: 'Save Changes',
            handler : function(){
                var records = store_assessment_assessmentRecords.getRange(), fields = store_assessment_assessmentRecords.fields;
                var param = {};        
                for(var i = 0; i < records.length; i++) {
                    for(var j = 0; j < fields.length; j++){
                        param[ 'data['+i + '][' + fields['items'][j]['name'] +']'] = Ext.encode(records[i].get(fields['items'][j]['name']));
                    }
                }
                Ext.Ajax.request({
                    url: '<?php echo $this->Html->url(array('controller' => 'edu_assessment_records', 'action' => 'add')); ?>',
                    params: param,
                    method: 'POST',
                    success: function(){
                        AssessmentViewWindow.close();
                    },
                    failure: function(){
                        alert('Error Saving Changes, Please Try Again!');
                    }
                });
            }
        }]

    });

	
		var AssessmentViewWindow = new Ext.Window({
			title: 'Students Rank',
			width: 400,
			autoHeight:true,
			minWidth: 300,
			minHeight: 345,
			resizable: false,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
                        modal: true,
			items: [ 
				xgrid
			]
		});
		
		store_assessment_assessmentRecords.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
