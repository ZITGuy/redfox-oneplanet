//<script>
	var personalBar = document.getElementById('personalBar');
	var personalBarText;
	
    <?php
    if ($this->Session->check('Auth')) {
        ?>
        personalBarText += '<b>Records Per Page:</b>';
		personalBarText += '<select>';
		personalBarText += '	<option value=20>20</option>';
		personalBarText += '	<option value=30>30</option>';
		personalBarText += '	<option value=40 selected>40</option>';
		personalBarText += '	<option value=50>50</option>';
		personalBarText += '	<option value=100>100</option>';
		personalBarText += '	<option value=200>200</option>';
		personalBarText += '	<option value=300>300</option>';
		personalBarText += '</select>';
		
		
        personalBarText += 'Welcome <b><?php echo $this->Session->read('Auth.User.username'); ?></b>';
		personalBarText += '<button onClick="">Logout</button>';
        personalBarText += '<button onClick="">My Profile</button>';
        
        <?php
    } else {
        ?>
        personalBarText += '<button onClick="">Login</button>';
        <?php
    }
    ?>

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
 