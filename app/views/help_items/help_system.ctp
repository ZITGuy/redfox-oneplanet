//<script>
    // Go ahead and create the TreePanel now so that we can use it below
    var helpTreePanel = new Ext.tree.TreePanel({
        id: 'help-tree-panel',
        title: 'Help',
        split: true,
        autoHeight: true,
        //minSize: 150,
        autoScroll: true,

        // tree-specific configs:
        rootVisible: false,
        lines: false,
        singleExpand: true,
        useArrows: true,

        loader: new Ext.tree.TreeLoader({
            dataUrl: '<?php echo $this->Html->url(array('controller' => 'help_items', 'action' => 'help_menu')); ?>'
        }),

        root: new Ext.tree.AsyncTreeNode()
    });

    // Assign the changeLayout function to be called on tree node click.
    helpTreePanel.on('click', function(n){
        var sn = this.selModel.selNode || {}; // selNode is null on initial selection
        if(n.id != sn.id){  // ignore clicks on folders and currently selected node 
            var content = n.id.split("|||")[1];
            //alert(content);
            var help_content_panel = center_panel.findById('help_content_panel');
            help_content_panel.setTitle(n.text);
			var textStyle = "<style> .help_content { font-size: 14px; }  h1 { font-size:25px; color: green; } .help_content > ul > li { margin-left: 30px; list-style-type: circle; } .help_content > ol > li { margin-left: 30px; list-style-type: lower-alpha; } </style><h1>" + n.text + "</h1><br><br>";
            help_content_panel.update(textStyle + "<div class=help_content>" + content + "</div>");
        } else if(n.leaf) {
            n.expand();
        }
    });
                        
    if (center_panel.find('id', 'help_system_tab') != "") {
        var p = center_panel.findById('help_system_tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Help'); ?>',
            id: 'help_system_tab',
            closable: true,
            loadMask: true,
            stripeRows: true,
            layout:'border',
            defaults: {
                collapsible: true,
                split: true,
                bodyStyle: 'padding:35px'
            },
            items: [{
                title: 'RedFox Help System',
                region:'west',
                margins: '0 0 0 0',
                cmargins: '0 0 0 0',
                width: 250,
                minSize: 150,
                maxSize: 350,
                bodyStyle: 'padding:0px',
                items: [helpTreePanel],
				tbar: new Ext.Toolbar({
                    items: [{
                        xtype: 'textfield',
                        emptyText: '<?php __('[Search]'); ?>',
                        id: 'help_menu_search_field',
						anchor: '100%',
                        listeners: {
                            specialkey: function (field, e) {
                                if (e.getKey() == e.ENTER) {
                                    
                                }
                            }
                        }
                    }]
				})
            }, {
                id: 'help_content_panel',
                title: 'Introduction',
                collapsible: false,
                region:'center',
				autoScroll: true,
                margins: '5 0 0 0',
                html: '<font size=25px color=green>Welcome to RedFox Help</font>'                
            }]
        });
        
        center_panel.setActiveTab(p);
		
		var myRootNode = helpTreePanel.getNodeById(1);
		helpTreePanel.fireEvent('click', myRootNode);
		
    }