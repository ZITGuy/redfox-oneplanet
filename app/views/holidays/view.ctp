//<script>	
    <?php $holiday_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $holiday['Holiday']['name'] . "</b></td></tr>" . 
            "<tr><th align=right>" . __('Date', true) . ":</th><td><b>" . $holiday['Holiday']['date'] . "</b></td></tr>" . 
            "<tr><th align=right>" . __('Is Recurrent', true) . ":</th><td><b>" . $holiday['Holiday']['is_recurrent'] . "</b></td></tr>" . 
            "<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $holiday['Holiday']['created'] . "</b></td></tr>" . 
            "<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $holiday['Holiday']['modified'] . "</b></td></tr>" . 
    "</table>"; 
    ?>
    var holiday_view_panel_1 = {
        html : '<?php echo $holiday_html; ?>',
        frame : true,
        height: 80
    };
    
    var holiday_view_panel_2 = new Ext.TabPanel({
        activeTab: 0,
        anchor: '100%',
        height:190,
        plain:true,
        defaults:{autoScroll: true},
        items:[
        ]
    });

    var HolidayViewWindow = new Ext.Window({
        title: '<?php __('View Holiday'); ?>: <?php echo $holiday['Holiday']['name']; ?>',
        width: 500,
        height:345,
        minWidth: 500,
        minHeight: 345,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [ 
            holiday_view_panel_1,
            holiday_view_panel_2
        ],
        buttons: [{
            text: '<?php __('Close'); ?>',
            handler: function(btn){
                HolidayViewWindow.close();
            }
        }]
    });
