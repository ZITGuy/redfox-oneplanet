{
	title: 'Dashboard',
        scrolls: false,
        tbar: new Ext.Toolbar({
            items: [
                {
                    xtype: 'tbbutton',
                    text: '<?php __('My Recent Activities'); ?>',
                    tooltip: '<?php __('My Recent Activities'); ?>',
                    icon: 'img/table_add.png',
                    cls: 'x-btn-text-icon',
                    handler: function(btn) {
                        //AddContainer();
                    }
                }, ' ', '|', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Another Button'); ?>',
                    tooltip: '<?php __('My Another Activity'); ?>',
                    icon: 'img/table_add.png',
                    cls: 'x-btn-text-icon',
                    handler: function(btn) {
                        //AddContainer();
                    }
                }
            ]
        }),
	bodyCfg: {
		tag: 'iframe',
		src : "<?php echo $this->Html->url(array('controller' => 'back_office', 'action' => 'dashboard')); ?>"
	}
}