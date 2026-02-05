//<script>
    <?php
        $this->ExtForm->create('EduSchedule');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var PrintSchedulerForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'edu_schedules', 'action' => 'print_schedule')); ?>',
        defaultType: 'textfield',
        items: [
            <?php
                $options = array('fieldLabel' => 'Schedule', 'anchor' => '98%', 'id' => 'cboScheduleCombo');
                $options['items'] = $edu_schedules;
                $this->ExtForm->input('edu_schedule_id', $options);
            ?>,
            <?php
                $options = array('id' => 'txtWaterMark');
                $this->ExtForm->input('watermark', $options);
            ?>
        ]
    });
    
    var PrintScheduleWindow = new Ext.Window({
        title: 'Print Schedules',
        width: 450,
        autoHeight: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [
            PrintSchedulerForm
        ],
        buttons: [{
            text: 'Show',
            id: 'btnShow',
            handler : function(){
                PrintSchedule();
            }
        }, {
            text: 'Close',
            handler: function(btn){
                PrintScheduleWindow.close();
            }
        }]
    });
    PrintScheduleWindow.show();
    
    
    function PrintSchedule() {
        var schedules_combo = Ext.getCmp('cboScheduleCombo') ;
        var sel_schedule_id = schedules_combo.getValue();
        var txtWaterMark = Ext.getCmp('txtWaterMark') ;
        var waterMark = txtWaterMark.getValue();
        
        var url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_schedules', 'action' => 'print_schedule_pdf')); ?>/" +
            sel_schedule_id + "/" + waterMark;
        popUpWindow(url, 0, 0, 1200, 1200);
    }
    
    var popUpWin_1=0;
    
    function popUpWindow(URLStr, left, top, width, height) {
        if(popUpWin_1){
            if(!popUpWin_1.closed) popUpWin_1.close();
        }
        popUpWin_1 = open(URLStr, 'popUpWin',
            'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,'+
            'resizable=yes,copyhistory=yes,width='+width+',height='+height+',left='+left+
            ', top='+top+',screenX='+left+',screenY='+top+'');
    }