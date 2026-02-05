<?php

/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       cake
 * @subpackage    cake.cake.libs.model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Application model for Cake.
 *
 * This is a placeholder class.
 * Create the same file in app/app_model.php
 * Add your application-wide methods to the class, your models will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.model
 */
include MODELS . 'audit_trail.php';

class AppModel extends Model {

    function beforeValidate($options = array()) {
        parent::beforeValidate($options);
        foreach ($this->validate as $distinctFieldKey => $distinctFieldValue) {
            foreach ($distinctFieldValue as $customRuleKey => $customRuleValue) {
                foreach ($customRuleValue as $ruleKey => $ruleValue) {
                    if ($ruleKey == 'rule') {
                        if (strtolower($ruleValue[0]) == 'boolean') {
                            $this->data[$this->name][$distinctFieldKey] = (isset($this->data[$this->name][$distinctFieldKey]) && in_array($this->data[$this->name][$distinctFieldKey], array('on', '1'))) ? 1 : 0;
                        }
                    }
                }
            }
        }
        return true;
    }

    function beforeSave() {
        parent::beforeSave();
        /*if ($this->name != 'AuditTrail') {
            $audit_trail_obj = new AuditTrail();
            $data['AuditTrail'] = array();
            $old = '';
            $dada = $this->data;
            if(isset($this->data[$this->name]['id'])) {
                $old_data = $this->read(null, $this->data[$this->name]['id']);
                foreach ($old_data[$this->name] as $f => $v) {
                    $old .= $f . '|' . $v . ';';
                }
            }
            $this->data = $dada;
            
            $data['AuditTrail']['user_id'] = Configure::read('AuthSession.User.id');
            $data['AuditTrail']['session_name'] = session_id();
            $data['AuditTrail']['is_successful'] = false;
            $data['AuditTrail']['action_made'] = 'C'; // C-Created, D-Deleted, U-Updated
            $data['AuditTrail']['table_name'] = $this->name;
            $data['AuditTrail']['old_value'] = $old;
            $data['AuditTrail']['new_value'] = '';
            $data['AuditTrail']['record_id'] = 0;
            $data['AuditTrail']['audit_desc'] = '';
            
            $audit_trail_obj->create();
            $audit_trail_obj->save($data);
            Configure::write('audit_trail_id', $audit_trail_obj->getLastInsertId());
        }*/
        return true;
    }

    function afterSave($created) {
        parent::afterSave($created);
        /*$rid = 0;
        if ($this->name != 'AuditTrail' && $this->name != 'Checksum') {
            $audit_trail_obj = new AuditTrail();
            
            $data['AuditTrail'] = array('id' => Configure::read('audit_trail_id'));
            if (!$created) {
                //$this->log('The model: ' . $this->name, 'debug');
                $data['AuditTrail']['action_made'] = 'U';
                
                $data['AuditTrail']['record_id'] = $this->data[$this->name]['id'];
            } else {
               $data['AuditTrail']['record_id'] = $this->getLastInsertId(); 
            }
            $rid = $data['AuditTrail']['record_id'];
            $new = '';
            $new_data = $this->read(null, $data['AuditTrail']['record_id']);
            foreach ($new_data[$this->name] as $f => $v) {
                $new .= $f . '|' . $v . ';';
            }
            $data['AuditTrail']['audit_desc'] = Configure::read('audit_desc') == null? '': Configure::read('audit_desc');
            $data['AuditTrail']['new_value'] = $new;
            
            $data['AuditTrail']['is_successful'] = true;
            
            $audit_trail_obj->save($data);
        }
        if(in_array($this->name, array('EduAssessmentRecord', 'EduClass'))){
            $this->loadModel('Checksum');
            $checksum = array();
            if (!$created) {
                $checksum = $this->Checksum->find('first', array(
                    'conditions' => array(
                        'rid' => $this->data[$this->name]['id'], 
                        'name' => $this->name
                    )));
                
            }
            $checksum['Checksum']['rid'] = $rid; 
            
            $record_data = $this->read(null, $rid);
            $csdata = '';
            foreach ($record_data[$this->name] as $f => $v) {
                $csdata .= $v;
            }
            $checksum['Checksum']['cvalue'] = md5($csdata);
            $checksum['Checksum']['name'] = $this->name;
            
            $this->Checksum->save($checksum);
        }*/
        return true;
    }
    
    function beforeDelete($cascade = true) {
        parent::beforeDelete($cascade);
        /*
        if ($this->name != 'AuditTrail') {
            $audit_trail_obj = new AuditTrail();
            $data['AuditTrail'] = array();
            $old = '';
            
            $old_data = $this->read(null, $this->id);
            foreach ($old_data[$this->name] as $f => $v) {
                $old .= $f . '|' . $v . ';';
            }
            
            $data['AuditTrail']['user_id'] = Configure::read('AuthSession.User.id');
            $data['AuditTrail']['session_name'] = session_id();
            $data['AuditTrail']['is_successful'] = false;
            $data['AuditTrail']['action_made'] = 'D'; // C-Created, D-Deleted, U-Updated
            $data['AuditTrail']['table_name'] = $this->name; // C-Created, D-Deleted, U-Updated
            $data['AuditTrail']['old_value'] = $old; // C-Created, D-Deleted, U-Updated
            $data['AuditTrail']['new_value'] = ''; // C-Created, D-Deleted, U-Updated
            $data['AuditTrail']['record_id'] = $this->id; // C-Created, D-Deleted, U-Updated
            
            $audit_trail_obj->create();
            $audit_trail_obj->save($data);
            Configure::write('audit_trail_id', $audit_trail_obj->getLastInsertId());
            
        }*/
        return true;
    }
    
    function afterDelete() {
        /*
        if ($this->name != 'AuditTrail') {
            $audit_trail_obj = new AuditTrail();
            
            $data['AuditTrail'] = array('id' => Configure::read('audit_trail_id'));
            
            $data['AuditTrail']['is_successful'] = true;
            
            $audit_trail_obj->save($data);
        }*/
        return true;
    }

    /**
     * Loads and instantiates models.
     * If the model is non existent, it will throw a missing database table error, as Cake generates
     * dynamic models for the time being.
     *
     * Will clear the model's internal state using Model::create()
     *
     * @param string $modelName Name of model class to load
     * @param mixed $options array|string
     *              id      Initial ID the instanced model class should have
     *              alias   Variable alias to write the model to
     * @return mixed true when single model found and instance created, error returned if model not found.
     * @access public
     */
    function loadModel($modelName, $options = array()) {
        if (is_string($options)) {
            $options = array('alias' => $options);
        }
        $options = array_merge(array(
            'datasource'  => 'default',
            'alias'       => false,
            'id'          => false,
        ), $options);
        list($plugin, $className) = pluginSplit($modelName, true, null);
        if (empty($options['alias'])) {
            $options['alias'] = $className;
        }
        if (!isset($this->{$options['alias']}) || $this->{$options['alias']}->name !== $className) {
            if (!class_exists($className)) {
                if ($plugin) {
                    $plugin = "{$plugin}.";
                }
                App::import('Model', "{$plugin}{$className}");
            }
            $table = Inflector::tableize($className);
            if (PHP5) {
                $this->{$options['alias']} = new $className($options['id'], $table, $options['datasource']);
            } else {
                $this->{$options['alias']} =& new $className($options['id'], $table, $options['datasource']);
            }
            if (!$this->{$options['alias']}) {
                return $this->cakeError('missingModel', array(array(
                    'className' => $className, 'code' => 500
                )));
            }
            $this->{$options['alias']}->alias = $options['alias'];
        }
        $this->{$options['alias']}->create();
        return true;
    }
}
