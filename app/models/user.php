<?php

class User extends AppModel {

    var $name = 'User';
    var $displayField = 'username';
    var $validate = array(
        'username' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'password' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'is_active' => array(
            'boolean' => array(
                'rule' => array('boolean'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'security_question' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'security_answer' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'person_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'change_campus' => array(
            'boolean' => array(
                'rule' => array('boolean'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $belongsTo = array(
        'Person' => array(
            'className' => 'Person',
            'foreignKey' => 'person_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
	   'EduCampus' => array(
            'className' => 'Edu.EduCampus',
            'foreignKey' => 'edu_campus_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
	
    var $hasAndBelongsToMany = array(
        'Group' => array(
            'className' => 'Group',
            'joinTable' => 'groups_users',
            'foreignKey' => 'user_id',
            'associationForeignKey' => 'group_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );

    /**
     * User::isUserBelongsToGroup()
     * 
     * checks whether the user identified by the $id param is a member of
     * group specifed in the current $group_name parameter
     * 
     * @param mixed $id
     * @param string $group_name
     * @return boolean
     */
    function isUserBelongsToGroup($id, $group_name = '') {
        $usr = $this->read(null, $id);
        $found = false;
        foreach ($usr['Group'] as $grp) {
            if ($grp['name'] == $group_name) {
                $found = true;
                break;
            }
        }
        return $found;
    }

    function GetPersonId($userId = '') {
        return $this->field('person_id', array('User.id' => $userId));
    }
    
    
    function afterSave($created) {
        parent::afterSave($created);
        $audit_trail_obj = new AuditTrail();

        //$this->log($this->data['Group'], 'debug');
        
        foreach($this->data['Group'] as $g) {
            $data['AuditTrail'] = array();
            
            include_once MODELS . 'groups_user.php';
            $groups_user = new GroupsUser();
            $gu = $groups_user->find('first', array('conditions' => array('GroupsUser.user_id' => $this->id, 
                'GroupsUser.group_id' => $g['id'])));
            
            $new = "id|" . $gu['GroupsUser']['id'] . ";user_id|" . $this->id . ";group_id|" . $g['id'] . ';';
            
            $data['AuditTrail']['user_id'] = Configure::read('AuthSession.User.id');
            $data['AuditTrail']['session_name'] = session_id();
            $data['AuditTrail']['is_successful'] = true;
            $data['AuditTrail']['action_made'] = 'C'; // C-Created, D-Deleted, U-Updated
            $data['AuditTrail']['table_name'] = 'GroupsUser'; // C-Created, D-Deleted, U-Updated
            $data['AuditTrail']['old_value'] = ''; // C-Created, D-Deleted, U-Updated
            $data['AuditTrail']['new_value'] = $new; // C-Created, D-Deleted, U-Updated
            $data['AuditTrail']['record_id'] = $gu['GroupsUser']['id']; // C-Created, D-Deleted, U-Updated
            
            $audit_trail_obj->create();
            $audit_trail_obj->save($data);
        }
        return true;
    }

	public function getUser($id) {
		$t = $this->find('first', array(
			'conditions' => array(
				'User.id' => $id
			)
		));
        
        if (!empty($t)) {
            return $t;
        }
        return FALSE;
    }
}
