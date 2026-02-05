<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       redfox
 * @subpackage    redfox.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo Configure::read('app_name'); ?>
    </title>
    
	<?php
            echo $this->Html->meta('icon');

            echo $this->Html->css('default_v2') . "\n";
            echo $this->Html->css('extjs/resources/css/ext-all') . "\n";
            echo $this->Html->css('extjs/resources/css/xtheme-gray') . "\n";
            //echo $this->Html->css('extjs/ux/css/ux-all') . "\n";
            
            echo $this->Html->script('extjs/adapter/ext/ext-base') . "\n";
            echo $this->Html->script('extjs/ext-all') . "\n";
            echo $this->Html->script('extjs/ux/statusbar/StatusBar') . "\n";
            echo $this->Html->script('extjs/ux/statusbar/ValidationStatus') . "\n";
			echo $this->Html->script('extjs/ux/XmlTreeLoader') . "\n";
            
            echo $this->Html->css('extjs/SuperBoxSelect/superboxselect') . "\n";
            
            echo $this->Html->script('extjs/SuperBoxSelect/SuperBoxSelect') . "\n";
	?>
        <style>
            /* style rows on mouseover */
            .x-grid3-row-over .x-grid3-cell-inner {
                font-weight: bold;
                color:#07a;
            }
            .x-grid3-cell-inner:hover {
                background-color: #ffdd99;
                color:#049;
            }
            .x-tree-node {
                color:#000;
            }
            .x-tree-node-leaf {
                background-color: #fafafa;
            }
            .x-tree-node-leaf .x-tree-node-icon {
                background-image: url("<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'leaf.png')); ?>");
            }
            .x-tree-node .x-tree-selected a span, .x-dd-drag-ghost a span{
                font-weight: bold;
            }
            .list {
                list-style-image:none;
                list-style-position:outside;
                list-style-type:square;
                padding-left:16px;
            }
            .list li {
                font-size:11px;
                padding:3px;
            }
            
            /*
             * FileUploadField component styles
             */
            .x-form-file-wrap {
                position: relative;
                height: 22px;
            }
            .x-form-file-wrap .x-form-file {
                position: absolute;
                right: 0;
                -moz-opacity: 0;
                filter:alpha(opacity: 0);
                opacity: 0;
                z-index: 2;
                height: 22px;
            }
            .x-form-file-wrap .x-form-file-btn {
                position: absolute;
                right: 0;
                z-index: 1;
            }
            .x-form-file-wrap .x-form-file-text {
                position: absolute;
                left: 0;
                z-index: 3;
                color: #777;
            }
			
            /* style for the "allow" ActionColumn icon */
            .x-action-col-cell img.allow-col {
                height: 16px;
                width: 16px;
                background-image: url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'symbol_check.png')); ?>);
            }

            /* style for the "disallow" ActionColumn icon */
            .x-action-col-cell img.disallow-col {
                height: 16px;
                width: 16px;
                background-image: url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'symbol_restricted.png')); ?>);
            }
			
			.menu-node {
				background-image: url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'fam/user.gif')); ?>) !important;
			}
			.menu-leaf {
				background-image: url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'fam/book.png')); ?>) !important;
			}
	</style>
	<script>
		Ext.app.MenuLoader = Ext.extend(Ext.ux.tree.XmlTreeLoader, {
			processAttributes : function(attr){
				//alert(attr);
				
				if(attr.leafnode == "0"){ // is it an author node?

					// Set the node text that will show in the tree since our raw data does not include a text attribute:
					attr.text = attr.name;

					// Author icon, using the gender flag to choose a specific icon:
					attr.iconCls = 'menu-node';

					// Override these values for our folder nodes because we are loading all data at once.  If we were
					// loading each node asynchronously (the default) we would not want to do this:
					attr.loaded = true;
					attr.expanded = true;
				}
				else if(attr.leafnode == "1"){ // is it a book node?

					// Set the node text that will show in the tree since our raw data does not include a text attribute:
					attr.text = attr.name;

					// Book icon:
					attr.iconCls = 'menu-leaf';

					// Tell the tree this is a leaf node.  This could also be passed as an attribute in the original XML,
					// but this example demonstrates that you can control this even when you cannot dictate the format of
					// the incoming source XML:
					attr.leaf = true;
				}
			}
		});
		
		Ext.apply(Ext.form.VTypes, {
			Currency:  function(v) {
				return /^\d+\.\d{2}$/.test(v);
			},
			CurrencyText: 'Must be an amount of money.',
			CurrencyMask: /[\d\.]/i
		});
		
		Ext.apply(Ext.form.VTypes, {
			Decimal:  function(v) {
				return /^\d+\.?\d*$/.test(v);
			},
			DecimalText: 'Must be a decimal.',
			DecimalMask: /[\d\.]/i
		});
		
		Ext.onReady(function() {
            Ext.QuickTips.init();
            Ext.History.init();

            var list_size = 40;
            var view_list_size = 10;
            var editWin = null;
            Ext.Ajax.timeout = 60000;
			
            function RefreshTopToolbar() {
                Ext.Ajax.request({
                    url: "<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'toptoolbar')); ?>",
                    success: function(response, opts) {
                        var toolbar_data = response.responseText;
                        var mytoolbar = Ext.getCmp('mainViewPort').findById('north-panel').getBottomToolbar();

                        eval(toolbar_data);

                        Ext.getCmp('mainViewPort').findById('north-panel').getBottomToolbar().doLayout();
                        Ext.getCmp('mainViewPort').doLayout();
                    },
                    failure: function(response, opts) {
                        Ext.Msg.alert('Error', 'Cannot get the toolbar data. Error code: ' + response.status);
                    }
                });
            }

			
            function BuildContainer() {
                var westPanel = Ext.getCmp('mainViewPort').findById('west-panel');
                
                EducationMaintenance();
                EducationView();
                AccountingMaintenance();
                AccountingView();
                SystemMaintenance();
                SystemView();
                
                westPanel.setActiveTab(0);
                edu_menu_tab = westPanel.getItem('edu_menu_tab');
                edu_menu_tab.setActiveTab(0);
 
            }
            
            function EducationMaintenance() {
                Ext.Ajax.request({
                    url: "<?php echo $this->Html->url(array('controller' => 'containers', 'action' => 'active_containers')); ?>/education_maintenance",
                    success: function(response, opts) {
                        var container_data = response.responseText;
                        var mycontainer_panel = Ext.getCmp('mainViewPort').findById('edu_mtab_panel');
                        var westPanel = Ext.getCmp('mainViewPort').findById('west-panel');
                        eval(container_data);

                        if(Ext.getCmp('edu_mtab_panel').getRootNode().hasChildNodes())
                            Ext.getCmp('edu_mtab_panel').getRootNode().item(0).expand();

                        //westPanel.setActiveTab(mycontainer_panel);

                        Ext.getCmp('mainViewPort').findById('edu_mtab_panel').doLayout();
                        Ext.getCmp('mainViewPort').doLayout();
                    },
                    failure: function(response, opts) {
                        Ext.Msg.alert('Error', 'Cannot get the menu data. Error code: ' + response.status);
                    }
                });
            }
            
            function EducationView(){
                
            }
            
            function AccountingMaintenance(){
                Ext.Ajax.request({
                    url: "<?php echo $this->Html->url(array('controller' => 'containers', 'action' => 'active_containers')); ?>/accounting_maintenance",
                    success: function(response, opts) {
                        var container_data = response.responseText;
                        var mycontainer_panel = Ext.getCmp('mainViewPort').findById('acct_mtab_panel');
                        var westPanel = Ext.getCmp('mainViewPort').findById('west-panel');
                        eval(container_data);

                        if(Ext.getCmp('acct_mtab_panel').getRootNode().hasChildNodes())
                            Ext.getCmp('acct_mtab_panel').getRootNode().item(0).expand();

                        Ext.getCmp('mainViewPort').findById('acct_mtab_panel').doLayout();
                        Ext.getCmp('mainViewPort').doLayout();
                    },
                    failure: function(response, opts) {
                        Ext.Msg.alert('Error', 'Cannot get the menu data. Error code: ' + response.status);
                    }
                });
            }
            
            function AccountingView(){
                
            }
            
            function SystemMaintenance(){
                Ext.Ajax.request({
                    url: "<?php echo $this->Html->url(array('controller' => 'containers', 'action' => 'active_containers')); ?>/system_maintenance",
                    success: function(response, opts) {
                        var container_data = response.responseText;
                        var mycontainer_panel = Ext.getCmp('mainViewPort').findById('sys_mtab_panel');
                        var westPanel = Ext.getCmp('mainViewPort').findById('west-panel');
                        eval(container_data);

                        if(Ext.getCmp('sys_mtab_panel').getRootNode().hasChildNodes())
                            Ext.getCmp('sys_mtab_panel').getRootNode().item(0).expand();

                        //westPanel.setActiveTab(mycontainer_panel);

                        Ext.getCmp('mainViewPort').findById('sys_mtab_panel').doLayout();
                        Ext.getCmp('mainViewPort').doLayout();
                    },
                    failure: function(response, opts) {
                        Ext.Msg.alert('Error', 'Cannot get the menu data. Error code: ' + response.status);
                    }
                });
            }
            
            function SystemView(){
                
            }
            
            function getUrl(function_name) {
                switch (function_name) {
<?php foreach($permittedContainers as $permittedContainer) { ?>
<?php 		foreach($permittedContainer['links'] as $clink) { ?>
                    case "<?php echo $clink['function_name']; ?>":
                        return "<?php echo $this->Html->url(array('controller' => $clink['controller'], 'action' => $clink['action'], $clink['parameter'])); ?>";
                        break;
<?php 		} ?>
<?php } ?>
                    default : return function_name;
                }
            }

            function getFunctionName(url) {
                switch (url) {
<?php foreach($permittedContainers as $permittedContainer) { ?>
<?php 		foreach($permittedContainer['links'] as $clink) { ?>
                    case "<?php echo $this->Html->url(array('controller' => $clink['controller'], 'action' => $clink['action'], $clink['parameter'])); ?>":
                        return "<?php echo $clink['function_name']; ?>";
                        break;
<?php 		} ?>
<?php } ?>
                    default : return url;
                }
            }		
		
            var viewport = new Ext.Viewport({
                    layout: "border",
                    id: 'mainViewPort',
                    renderTo: Ext.getBody(),
                    items: [{
                        region: "north",
                        xtype: 'panel',
                        id: 'north-panel',
                        html: '<div id="header">&nbsp;<div>',
                        height: 28,
                        bbar: new Ext.Toolbar({
                            id: 'top-toolbar',
                            items: [
                            ]
                        })
                    }, {
                        xtype: 'treepanel',
                        id:'west-panel',
                        region:'west',
                        split:true,
                        width: 250,
                        minSize: 200,
                        maxSize: 300,
                        collapsible: true,
                        margins:'0 0 5 5',
                        cmargins:'0 5 5 5',
						autoScroll: true,
						rootVisible: true,
						root: new Ext.tree.AsyncTreeNode(),
						
						dataUrl: '<?php echo $this->Html->url(array('controller' => 'containers', 'action' => 'active_containers_2/a.xml')); ?>',
						// Our custom TreeLoader:
						/*loader: new Ext.app.MenuLoader({
							dataUrl: '<?php echo $this->Html->url(array('controller' => 'containers', 'action' => 'active_containers_2/a.xml')); ?>'
						}),*/
						
						listeners: {
							/*'render': function(tp){
								tp.getSelectionModel().on('selectionchange', function(tree, node){
									var el = Ext.getCmp('details-panel').body;
									if(node && node.leaf){
										tpl.overwrite(el, node.attributes);
									}else{
										el.update(detailsText);
									}
								})
							}*/
						}
                    }, {
                        region: 'center',
                        id: 'centerPanel',
                        xtype: 'tabpanel',
                        resizeTabs: true,
                        minTabWidth: 150,
                        tabWidth:150,
                        enableTabScroll:true,
                        margins: '0 0 0 0',
                        plugins: new Ext.ux.TabCloseMenu(),
                        activeTab: 0,
                        items: [<?php echo $content_for_layout; ?>]
                    }, {
                        region: 'south',
                        xtype: 'panel',
                        html: '<center><?php echo Configure::read('app_name'); ?> &copy; 2015 - A B D D Information Technology P.L.C</center>'
                    }]
		}); 
		
		RefreshTopToolbar();
		//BuildContainerNew();
	});
	</script>
</head>
<body>
<form id="history-form" class="x-hidden">
    <input type="hidden" id="x-history-field" />
    <iframe id="x-history-frame"></iframe>
</form>
<span id="app-msg" class="x-hidden"></span>

    <?php
        echo $this->Html->script('extjs/ux/ux-all') . "\n";
        echo $this->Html->script('ext_validators') . "\n";
        echo $this->Html->script('calendar-all') . "\n";
        echo $this->Html->script('calendar-list') . "\n";
        
        echo $this->Html->script('handleamharic') . "\n";
        
        echo $scripts_for_layout . "\n";
    ?>
</body>
</html>