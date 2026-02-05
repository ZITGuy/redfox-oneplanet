//<script>
    <?php
        $this->ExtForm->create('EduAcademicYear');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var EduAcademicYearCopyForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 160,
        labelAlign: 'right',
        autoHeight: true,
        width: 500,
        resizable: false,
        plain:true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',
        url:'<?php echo $this->Html->url(array('controller' => 'eduAcademicYears', 'action' => 'copy')); ?>',
        defaultType: 'textfield',

        items: [
            <?php $this->ExtForm->input('oldid', array('hidden' => $edu_academic_year['EduAcademicYear']['id'])); ?>,
            <?php
                $options = array('value' => 'Copy of ' . $edu_academic_year['EduAcademicYear']['name']);
                $this->ExtForm->input('name', $options);
            ?>,
            <?php
                $options1 = array('xtype' => 'checkbox', 'value' => true);
                $this->ExtForm->input('copy_quarters', $options1);
            ?>,
            <?php
                $options2 = array('xtype' => 'checkbox', 'value' => true);
                $this->ExtForm->input('copy_quarter_events', $options2);
            ?>
        ]
    });
    
    var labelPanel = {
        html : '<font color=red size=2.5em>You are about to create another academic year based on the ' .
            'selected academic year data. You also can modify any of the values of the academic year ' .
            'and the quarters after copying the academic year. Are you sure to do this?</font>',
        frame : true,
        height: 20
    }
		
    var EduAcademicYearCopyWindow = new Ext.Window({
            title: '<?php __('Copy Academic Year'); ?>',
            width: 400,
            minWidth: 400,
            autoHeight: true,
            layout: 'fit',
            modal: true,
            resizable: true,
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'right',
            items: [labelPanel, EduAcademicYearCopyForm],
            tools: [{
                id: 'refresh',
                qtip: 'Reset',
                handler: function () {
                    EduAcademicYearCopyForm.getForm().reset();
                },
                scope: this
            }, {
                id: 'help',
                qtip: 'Help',
                handler: function () {
                    Ext.Msg.show({
                        title: 'Help',
                        buttons: Ext.MessageBox.OK,
                        msg: 'This form is used to insert a new Edu Academic Year.',
                        icon: Ext.MessageBox.INFO
                    });
                }
            }, {
                id: 'toggle',
                qtip: 'Collapse / Expand',
                handler: function () {
                    if(EduAcademicYearCopyWindow.collapsed)
                        EduAcademicYearCopyWindow.expand(true);
                    else
                        EduAcademicYearCopyWindow.collapse(true);
                }
            }],
            buttons: [{
                text: '<?php __('Create and Open'); ?>',
                handler: function(btn){
                    EduAcademicYearCopyForm.getForm().submit({
                        waitMsg: '<?php __('Submitting your data...'); ?>',
                        waitTitle: '<?php __('Wait Please...'); ?>',
                        success: function(f,a){
                            Ext.Msg.show({
                                title: '<?php __('Success'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
                                
                            });
                            Ext.Msg.confirm('Confirm', a.result.msg + ' Do you want to print the summary?', function(btn) {
                                if(btn == 'yes') {
                                    printAYSummary();
                                }
                            });
                            EduAcademicYearCopyWindow.close();
                            RefreshEduAcademicYearData();
                        },
                        failure: function(f,a){
                            Ext.Msg.show({
                                title: '<?php __('Warning'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.errormsg,
                                icon: Ext.MessageBox.ERROR
                            });
                        }
                    });
                }
            }, {
                text: '<?php __('Cancel'); ?>',
                handler: function(btn){
                    EduAcademicYearCopyWindow.close();
                }
            }]
    });
    
    var popUpWin_copy=0;
	
    function popUpWindowAY(URLStr, left, top, width, height) {
        if(popUpWin_copy){
            if(!popUpWin_copy.closed) popUpWin_copy.close();
        }
        popUpWin_copy = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
    }

    function printAYSummary() {
        url = "<?php echo $this->Html->url(array('controller' => 'edu_academic_years', 'action' => 'print_ay_summary', 'plugin' => 'edu')); ?>";
        popUpWindowAY(url, 200, 200, 700, 1000);
    }