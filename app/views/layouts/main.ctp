<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?php echo $this->Html->charset(); ?>
    <title>
        RedFox v<?php echo Configure::read('redfox_version'); ?> - 
			<?php echo Configure::read('company_name'); ?> - 
			<?php echo $this->Session->read('Auth.EduCampus.name'); ?> - 
			<?php echo date('F d, Y', strtotime($this->Session->read('today'))); ?>
    </title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('extjs/resources/css/ext-all') . "\n";
		echo $this->Html->css('extjs/ux/css/CenterLayout') . "\n";

		echo $this->Html->script('extjs/adapter/ext/ext-base') . "\n";
		echo $this->Html->script('extjs/ext-all') . "\n";
		echo $this->Html->script('extjs/ux/CenterLayout') . "\n";
		echo $this->Html->script('extjs/ux/TabScrollerMenu') . "\n";
		echo $this->Html->script('extjs/ux/TabCloseMenu') . "\n";
		echo $this->Html->script('extjs/ux/RowLayout') . "\n";
		echo $this->Html->script('extjs/ux/Spinner') . "\n";

		echo $this->Html->script('layouts/basic') . "\n";
		echo $this->Html->script('layouts/custom') . "\n";
		echo $this->Html->script('layouts/combination') . "\n";
		
		echo $this->Html->script('jquery.1.9.min') . "\n";
		echo $this->Html->script('jquery-ui.min') . "\n";
	
		echo $this->Html->css('msdropdown/dd') . "\n";
		echo $this->Html->script('msdropdown/jquery.dd.min') . "\n";
            
	?>
	<style type="text/css" media="screen">
			.headerColumn {
				background-color: #93de8b;
			}
			
			.valueColumn {
				background-color: #e4765c;
			}
            body {
                font-family:'lucida grande',tahoma,arial,sans-serif;
                font-size:11px;
            }
            a {
                color:#15428B;
            }
            a:link, a:visited {
                text-decoration: none;
            }
            a:hover {
                text-decoration: underline;
            }
            #header {
                background: #7F99BE url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/layout-browser-hd-bg.gif')); ?>) repeat-x center;
            }
            #header h1 {
                font-size: 16px;
                color: #fff;
                font-weight: normal;
                padding: 5px 10px;
            }
            #start-div h2 {
                font-size: 12px;
                color: #555;
                padding-bottom:5px;
                border-bottom:1px solid #C3D0DF;
            }
            #start-div p {
                margin: 10px 0;
            }
            #favorite-panel h2 {
                padding:10px 10px 0;
                font-size:12px;
                color:#15428B;
            }
            #favorite-panel p {
                padding:10px 10px 0;
            }
            #favorite-panel pre {
                border-top:1px dotted #ddd;
                border-bottom:1px dotted #ddd;
                margin-top:10px;
                padding:0 5px;
                background:#f5f5f5;
            }
            #favorite-panel .favorite-info {
                margin:15px;
                padding:15px;
                border:1px dotted #999;
                color:#555;
                background: #f9f9f9;
            }
            .x-tab-panel-header-plain .x-tab-strip-top {
                background: #DFE8F6 url(<?php echo $this->Html->url(array('controller' => 'img')); ?>/extjs_images/default/tabs/tab-strip-bg.gif) repeat-x scroll center bottom !important;
            }
            .custom-accordion .x-panel-body{
                background:#ffe;
                text-align:center;
            }
            .custom-accordion .x-panel-body p {
                font-family:georgia,serif;
                padding:20px 80px !important;
                font-size:18px;
                color:#15428B;
            }
            .custom-accordion .x-panel-header-text {
                font-weight:bold;
                font-style:italic;
                color:#555;
            }
            #form-panel .x-panel-footer {
                background:#DFE8F6;
                border-color:#99BBE8;
                border-style:none solid solid;
                border-width:0pt 1px 1px;
            }
            #table-panel .x-table-layout {
                padding:5px;
            }
            #table-panel .x-table-layout td {
                vertical-align:top;
                padding:5px;
                font-size: 11px;
            }
            .icon-send {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/email_go.png')); ?>) !important;
            }
            .icon-save {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/disk.png')); ?>) !important;
            }
            .icon-print {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/printer.png')); ?>) !important;
            }
            .icon-spell {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/spellcheck.png')); ?>) !important;
            }
            .icon-attach {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/page_attach.png')); ?>) !important;
            }
            .icon-users {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/users.png')); ?>) !important;
            }
            .icon-task {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/task.png')); ?>) !important;
            }
            .icon-maintenance {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/maintenance.png')); ?>) !important;
            }
            .icon-audit {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/audit.png')); ?>) !important;
            }
            .icon-education {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/education.png')); ?>) !important;
            }
            .icon-settings {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/settings.png')); ?>) !important;
            }
            .icon-operation {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/operation.png')); ?>) !important;
            }
            .icon-students {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/graduation.png')); ?>) !important;
            }
            .icon-system {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/computer.png')); ?>) !important;
            }
            .icon-modules {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/modules.png')); ?>) !important;
            }
            .icon-parameters {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/parameters.png')); ?>) !important;
            }
            .icon-backup {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/backup.jpg')); ?>) !important;
            }
            .icon-view {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/view.png')); ?>) !important;
            }
            .icon-activity {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/arrow_right.png')); ?>) !important;
            }
            .icon-root-menu {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/root-menu.png')); ?>) !important;
            }
            .icon-accounting {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/accounting.png')); ?>) !important;
            }
            .icon-document {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/document.png')); ?>) !important;
            }
            .icon-report {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/report.png')); ?>) !important;
            }
            .icon-roster {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/roster.png')); ?>) !important;
            }
            .icon-teachers {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/teachers.png')); ?>) !important;
            }
            .icon-help {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/help.png')); ?>) !important;
            }
            .icon-communication {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/message-information.png')); ?>) !important;
            }
            .icon-process {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/process.png')); ?>) !important;
            }
			.icon-uss {
                background-image:url(<?php echo $this->Html->url(array('controller' => 'img', 'action' => 'layout_images/users_maintenance.png')); ?>) !important;
            }
            .email-form .x-panel-mc .x-panel-tbar .x-toolbar {
                border-top:1px solid #C2D6EF;
                border-left:1px solid #C2D6EF;
                border-bottom:1px solid #99BBE8;
                margin:-5px -4px 0;
            }
            .inner-tab-custom .x-border-layout-ct {
                background: #fff;
            }

            #personalBar {
                font-size: 16px;
                color: #fff;
                font-weight: normal;
                padding: 5px 10px;
            }

            .viewtable {
                font-size: 12px;
                color: #000;
                font-weight: normal;
                padding: 5px 10px;
            }

            .viewtable th {
                text-align: right !important;
            }
			
			.fieldset_err {
				border:2px solid red;
				-moz-border-radius:8px;
				-webkit-border-radius:8px;	
				border-radius:8px;
				padding: 5px 10px;
				color: #700;
			}
			
			.fieldset_tip {
				border:2px solid green;
				-moz-border-radius:8px;
				-webkit-border-radius:8px;	
				border-radius:8px;	
				padding: 5px 10px;
				color: #070;
			}
	</style>
	<script>
            /*!
             * Ext JS Library 3.3.0
             * Copyright(c) 2006-2010 Ext JS, Inc.
             * licensing@extjs.com
             * http://www.extjs.com/license
             */
            // Add the additional 'advanced' VTypes
            Ext.apply(Ext.form.VTypes, {
                Currency:  function(v) {
                    return /^\d+\.\d{2}$/.test(v);
                },
                CurrencyText: 'Must be an amount of money.',
                CurrencyMask: /[\d\.]/i,

                Decimal:  function(v) {
                    return /^\d+\.?\d*$/.test(v);
                },
                DecimalText: 'Must be a decimal.',
                DecimalMask: /[\d\.]/i,

                mphone:  function(v) {
                    return /^(2519)\d{8}$/.test(v);
                },
                mphoneText: 'Must be a phone number.',
                mphoneMask: /[\d]/i,

                daterange : function(val, field) {
                    var date = field.parseDate(val);

                    if(!date){
                        return false;
                    }
                    if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime()))) {
                        var start = Ext.getCmp(field.startDateField);
                        start.setMaxValue(date);
                        start.validate();
                        this.dateRangeMax = date;
                    }
                    else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
                        var end = Ext.getCmp(field.endDateField);
                        end.setMinValue(date);
                        end.validate();
                        this.dateRangeMin = date;
                    }
                    /*
                    * Always return true since we're only using this vtype to set the
                    * min/max allowed values (these are tested for after the vtype test)
                    */
                    return true;
                },

                password : function(val, field) {
                    if (field.initialPassField) {
                        var pwd = Ext.getCmp(field.initialPassField);
                        return (val == pwd.getValue());
                    }
                    return true;
                },

                passwordText : 'Passwords do not match'

            });
			
			// Example: show a spinner during all Ajax requests
			var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Please wait..."});
			Ext.Ajax.on('beforerequest', myMask.show, myMask);
			Ext.Ajax.on('requestcomplete', myMask.hide, myMask);
			Ext.Ajax.on('requestexception', myMask.hide, myMask);
			
            function LogoutUser() {
                
                Ext.Ajax.request({
                    url: '<?php echo $this->Html->url(array("controller" => "users", "action" => "logout")); ?>',
                    success: function(response, opts) {
                        location = "<?php echo $this->Html->url(array('controller' => 'back_office', 'action' => 'index')); ?>";
                        exit();
                    },
                    failure: function(response, opts) {
                        Ext.Msg.alert('Cannot be logged out. Error code: ' + response.status);
                    }
                });
            }
			
            function EditUserProfile() {
                Ext.Ajax.request({
                    url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'edit_profile')); ?>',
                    success: function(response, opts) {
                        var user_profile_data = response.responseText;

                        eval(user_profile_data);

                        UserEditProfileWindow.show();
                    },
                    failure: function(response, opts) {
                        Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the user edit form. Error code'); ?>: ' + response.status);
                    }
                });
            }
			
			function ChangeCampus() {
                Ext.Ajax.request({
                    url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'change_campus')); ?>',
                    success: function(response, opts) {
                        var ChangeCampus_data = response.responseText;

                        eval(ChangeCampus_data);

                        UserChangeCampusWindow.show();
                    },
                    failure: function(response, opts) {
                        Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the user change campus form. Error code'); ?>: ' + response.status);
                    }
                });
            }
			
            function ViewAuditTrailDetail(id, name) {
                Ext.Ajax.request({
                    url: '<?php echo $this->Html->url(array('controller' => 'audit_trails', 'action' => 'index2', 'plugin' => '')); ?>/'+id+'/'+name,
                    success: function(response, opts) {
                        var audit_trail_data = response.responseText;

                        eval(audit_trail_data);

                        parentAuditTrailsViewWindow.show();
                    },
                    failure: function(response, opts) {
                        Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Audit Trail view form. Error code'); ?>: ' + response.status);
                    }
                });
            }
		
            function ShowErrorBox(content, helpcode) {
				
                var error_box_panel = {
                    html : '<table><tr><td><?php echo $this->Html->image('symbol_stop.png', array('style' => 'margin-right: 20px;')); ?></td><td><div id="tip1">' + content + '</div></td></tr></table>',
                    frame : true,
                    autoHeight: true
                };
                var er = (!(helpcode.substr(6, 2) == '03') || (<?php echo $has_error_reporting; ?> != 1));
                var ErrorBoxWindow = new Ext.Window({
                    title: '<?php __('Oooops!'); ?>',
                    width: 500,
                    autoHeight: true,
                    resizable: false,
                    plain: true,
                    bodyStyle:'padding:5px;',
                    buttonAlign:'center',
                    modal: true,
                    items: [ 
                        error_box_panel
                    ],
                    buttons: [{
                        text: '<?php __('Close'); ?>',
                        handler: function(btn){
                            ErrorBoxWindow.close();
                        }
                    }]
                });
                ErrorBoxWindow.show();
				
				new Ext.ToolTip({
					target: 'tip1',
					title: helpcode,
					width: 300,
					autoHide: false,
					closable: true,
					draggable: true,
					autoLoad: {url: '<?php echo $this->Html->url(array('controller' => 'help_contents', 'action' => 'get_help')); ?>/' + helpcode}
				});
            }
            
            function openEnrollment() {
                on_enrollment = false;
                var url = 'edu/edu_students/enrollment';
                Ext.Ajax.request({
                    url: "<?php echo Configure::read('localhost_string'); ?>/" + url,
                    success: function(response, opts) {
                        var my_data = response.responseText;
                        
                        eval(my_data);
                    },
                    failure: function(response, opts) {
                        if(response.status == 403) {
                            Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Your Session has been expired. You should relog again.'); ?>");
                            Ext.Msg.show({
                                title:'Oooops!',
                                msg: "<?php __('Your Session has been expired. You should relog again.'); ?>",
                                buttons: Ext.Msg.OK,
                                fn: logout_user,
                                icon: Ext.Msg.ERROR
                            });
                        } else {
                            var obj = Ext.decode(Ext.decode(JSON.stringify(response)).responseText);
                            ShowErrorBox(obj.errormsg + ' (ERR-0003)', 'ERR-0003');
                        }
                    }
                });     
            }
			
			function OpenHelpSystem() {
				/*alert('Hi');*/
				Ext.Ajax.request({
					url: '<?php echo $this->Html->url(array('controller' => 'help_items', 'action' => 'help_system')); ?>',
					success: function(response, opts) {
						var help_system_data = response.responseText;
						var center_panel = Ext.getCmp('mainViewPort').findById('center_panel');
						eval(help_system_data);
					},
					failure: function(response, opts) {
						Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the user edit form. Error code'); ?>: ' + response.status);
					}
				});
			}
		
            Ext.onReady(function(){
                Ext.QuickTips.init();
                var list_size = 40;
                var view_list_size = 20;
                var on_enrollment = false;
				Ext.Ajax.timeout = 600000;
                // This is the main content center region that will contain each example layout panel.
                // It will be implemented as a CardLayout since it will contain multiple panels with
                // only one being visible at any given time.
                var contentPanel = {
                    id: 'center_panel',
                    xtype: 'tabpanel',
                    region: 'center', // this is what makes this panel into a region within the containing layout
                    resizeTabs: true,
                    minTabWidth: 150,
                    tabWidth: 150,
                    enableTabScroll: true,
                    margins: '2 5 5 0',
                    plugins: new Ext.ux.TabCloseMenu(),
                    activeItem: 0,
                    border: false,
                    items: [
                        <?php echo $content_for_layout; ?>
                    ]
                };
			
                // Go ahead and create the TreePanel now so that we can use it below
                var treePanel = new Ext.tree.TreePanel({
                    id: 'tree-panel',
                    title: 'Menu',
                    region:'north',
                    split: true,
                    height: 400,
                    minSize: 150,
                    autoScroll: true,
					
                    // tree-specific configs:
                    rootVisible: false,
                    lines: false,
                    singleExpand: true,
                    useArrows: true,
					enableDD: true,
					
                    loader: new Ext.tree.TreeLoader({
                        dataUrl: '<?php echo $this->Html->url(array('controller' => 'tasks', 'action' => 'active_tasks')); ?>'
                    }),
                    root: new Ext.tree.AsyncTreeNode()
                });
			
                // Assign the changeLayout function to be called on tree node click.
                treePanel.on('dblclick', function(n){
                    //alert(n.text);
                    //var sn = this.selModel.selNode || {}; // selNode is null on initial selection
                    if(n.leaf/* && n.id != sn.id*/){  // ignore clicks on folders and currently selected node 
                        //var the_function_name = getFunctionName(n.controller, n.action, n.parameter);
						if(n.text != 'Help System')
							treePanel.disable();
						
                        var url = n.id.split("|")[1];
                        Ext.Ajax.request({
                            url: "<?php echo Configure::read('localhost_string'); ?>/" + url,
                            success: function(response, opts) {
								
                                var my_data = response.responseText;
                                var center_panel = Ext.getCmp('mainViewPort').findById('center_panel');

                                eval(my_data);
								treePanel.enable();
                            },
                            failure: function(response, opts) {
                                if(response.status == 403) {
                                    Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Your Session has been expired. You should relog again.'); ?>");
                                    Ext.Msg.show({
                                        title:'Oooops!',
                                        msg: "<?php __('Your Session has been expired. You should relog again.'); ?>",
                                        buttons: Ext.Msg.OK,
                                        fn: logout_user,
                                        icon: Ext.Msg.ERROR
                                    });
                                } else {
                                    //alert(JSON.stringify(response));
                                    var obj = Ext.decode(Ext.decode(JSON.stringify(response)).responseText);
                                    ShowErrorBox(obj.errormsg + ' (ERR-0003)', 'ERR-0003');
                                }
								treePanel.enable();
                            }
                        });
                    } else if(n.text == "Home") {
                        var center_panel = Ext.getCmp('mainViewPort').findById('center_panel');
                        var p = center_panel.findById('dashboard_tab');
                        center_panel.setActiveTab(p);
                    } else {
                        n.expand();
                    }
                });
			
                // This is the Details panel that contains the description for each example layout.
                var favoritePanel = {
                    id: 'favorite-panel',
                    title: 'Favorite Menu',
                    region: 'center',
                    bodyStyle: 'padding-bottom:15px;background:#eee;',
                    autoScroll: true,
                    html: '<p class="favorite-info">Your favorite menus will be listed here.</p>'
                };
			
                // Finally, build the main layout once all the pieces are ready.  This is also a good
                // example of putting together a full-screen BorderLayout within a Viewport.
                var viewPort = new Ext.Viewport({
                    id: 'mainViewPort',
                    layout: 'border',
                    title: 'Ext Layout Browser',
                    items: [{
                            xtype: 'box',
                            region: 'north',
                            applyTo: 'header',
                            height: 30
                        }, {
                            layout: 'border',
                            id: 'layout-browser',
                            region:'west',
                            border: false,
                            split:true,
                            margins: '2 0 5 5',
                            width: 275,
                            minSize: 100,
                            maxSize: 500,
                            items: [treePanel, favoritePanel]
                        },
                        contentPanel, {
							layout: 'border',
                            xtype: 'box',
                            region: 'south',
                            html: '<p align=right style="margin: 7px; font-size: 1.2em;"><?php echo $this->Html->link('Reports', array('controller' => 'reports', 'action' => 'report_index'), array('target' => '_blank')); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|<?php echo ($this->Session->read('Auth.User.change_campus')? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="ChangeCampus()">Change Campus</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|': ''); ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link('About', array('controller' => 'about'), array('target' => '_blank')); ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;  <a href="javascript: OpenHelpSystem();">Help</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>',
							border: false,
                            height: 30
                        }
                    ],
                    renderTo: Ext.getBody()
                });
				
				
            });
            $(document).ready(function(e) {
                $("#profile").msDropdown({visibleRows:4});
            });
	</script>
</head>
<body>
    <div id="header">
        <div id="productName" style="float: left; width: 80%;">
			<h1 class="header_bar">
				<font color=red><strong>RedFox</strong></font> v<?php echo Configure::read('redfox_version'); ?> - 
				<?php echo Configure::read('company_name'); ?> - <?php echo $this->Session->read('Auth.EduCampus.name'); ?>
                                <?php echo ($this->Session->check('current_term')? ' - <i><b><font color="pink"> ' . $this->Session->read('current_term') . '</font></b></i>': ''); ?>
				 - Today: 
				<?php echo date('F d, Y', strtotime($this->Session->read('today'))); ?>
				<?php
					$current_date = date("U"); /* to have it in microseconds */
					$redfox_date = date("U", strtotime($this->Session->read('today')));
					$difference = floor (($current_date - $redfox_date)/(3600*24));
					
					echo $difference > 0? ' <font color=yellow>(' . $difference . ' days back)</font>': '';
				?>
			</h1> 
		</div>
        <div id="personalBar" style="float: right; margin-top: -4px">
            <?php
                if ($this->Session->check('Auth')) {
            ?>
                Welcome 
				<?php echo ($this->Session->read('Auth.User.photo_file') != '' || $this->Session->read('Auth.User.photo_file') != 'No file')? $this->Html->image($this->Session->read('Auth.User.photo_file'), array('width' => '22px')): ' '; ?>&nbsp;&nbsp;
                <select onchange="whatToDo(this.value);" id="profile" name="profile" style="width:250px;" style="background-color: #7F99BE; border-style: none;" onselect="">
                    <option value=1 selected data-image="<?php echo $this->Html->url(array('controller' => 'img')) . '/user16.png'; ?>"><?php echo strtoupper($this->Session->read('Auth.User.username')); ?></option>
                    <option value=2 data-image="<?php echo $this->Html->url(array('controller' => 'img')) . '/profile.png'; ?>">My Profile</option>
                    <option value=3 data-image="<?php echo $this->Html->url(array('controller' => 'img')) . '/logout.png'; ?>">Logout</option>
                </select>
				<script>
					function whatToDo(button) {
						if(button == 2) { 
							EditUserProfile(); 
						} else if(button == 3){ 
							LogoutUser();
						}
					}
				</script>
            <?php
                } else {
            ?>
                <button onClick="EditUserProfile()">Login</button>
            <?php
                }
            ?>
        </div>
    </div>
    <div style="display:none;">

        <!-- Start page content -->
        <div id="start-div">
            <div style="float:left;" ></div>
            <div style="margin-left:100px;">
                <h2>Welcome!</h2>
            </div>
        </div>
    </div>
	<?php
        echo $this->Html->script('extjs/ux/ux-all') . "\n";
        echo $this->Html->script('ext_validators') . "\n";
        echo $this->Html->script('calendar-all') . "\n";
        echo $this->Html->script('calendar-list') . "\n";
        
        echo $this->Html->script('handleamharic') . "\n";
        
        echo $this->Html->script('Chart') . "\n";
        
        echo $scripts_for_layout . "\n";
    ?>
</body>
</html>
