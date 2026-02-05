//<script>
    <?php
        $this->ExtForm->create('EduSchedule');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var PrintSchedulerTeacherForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array(
            'controller' => 'edu_schedules', 'action' => 'print_schedule_teacher')); ?>',
        defaultType: 'textfield',
        items: [
            <?php
                $options = array('fieldLabel' => 'Teacher', 'anchor' => '98%', 'id' => 'cboTeacherCombo');
                $options['items'] = $edu_teachers;
                $this->ExtForm->input('edu_teacher_id', $options);
            ?>,
            <?php
                $options = array('id' => 'txtWaterMark');
                $this->ExtForm->input('watermark', $options);
            ?>
        ]
    });
    
    var PrintScheduleTeacherWindow = new Ext.Window({
        title: 'Print Schedules per Teacher',
        width: 450,
        autoHeight: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [
            PrintSchedulerTeacherForm
        ],
        buttons: [{
            text: 'Show',
            id: 'btnShow',
            handler : function(){
                PrintScheduleTeacher();
            }
        }, {
            text: 'Close',
            handler: function(btn){
                PrintScheduleTeacherWindow.close();
            }
        }]
    });
    PrintScheduleTeacherWindow.show();
    
    
    function PrintScheduleTeacher() {
        var teachers_combo = Ext.getCmp('cboTeacherCombo') ;
        var sel_teacher_id = teachers_combo.getValue();
        var txtWaterMark = Ext.getCmp('txtWaterMark') ;
        var waterMark = txtWaterMark.getValue();
        
        var url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_schedules', 'action' => 'print_schedule_teacher_pdf')); ?>/" +
            sel_teacher_id + "/" + waterMark;
        popUpWindowTeacher(url, 0, 0, 1200, 1200);
    }
    
    var popUpWinTeacher_1=0;
    
    function popUpWindowTeacher(URLStr, left, top, width, height) {
        if(popUpWinTeacher_1){
            if(!popUpWinTeacher_1.closed) popUpWinTeacher_1.close();
        }
        popUpWinTeacher_1 = open(URLStr, 'popUpWinTeacher',
            'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,'+
            'resizable=yes,copyhistory=yes,width='+width+',height='+height+',left='+left+
            ', top='+top+',screenX='+left+',screenY='+top+'');
    }