<?php

class HelpItemsController extends AppController {

    var $name = 'HelpItems';

    function index() {
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }
    
    function help_system() {
        
    }

    function list_data() {
        $help_items = $this->HelpItem->find('all', array('order' => 'HelpItem.list_order ASC'));
        $tree_data = array();
        if (count($help_items) > 0) {
            $tree_data = array($this->__getTreeArray($help_items[0], $help_items));
        }
        $this->set('help_items', $tree_data);
    }
    
    function getContentTags($content) {
        $content_tags = array();
        $start = strpos($content, '[[', 0);
        while($start !== FALSE){
            $end = strpos($content, ']]', $start);
            if($end !== FALSE){
                $content_tags[] = substr($content, $start + 2, ($end - $start) - 2);
                $start = strpos($content, '[[', $end);
            } else {
                $start = FALSE;
            }
        }
        return $content_tags;
    }

    function __getTreeArray($node, $adata) {
        $this->loadModel('HelpContent');
		$content = $node['HelpItem']['content'];
		$content = str_replace('[[BASE_URL]]', Configure::read(), $content);
        $content_tags = $this->getContentTags($content);
		$count = 0;
		while(count($content_tags) > 0 && $count < 4) {
			$count++;
			$help_contents = $this->HelpContent->find('all', array('conditions' => array('HelpContent.code' => $content_tags)));
			
			foreach ($help_contents as $help_content) {
				$err = strpos($help_content['HelpContent']['code'], 'ERR-');
				$tip = strpos($help_content['HelpContent']['code'], 'TIP-');
				$img = strpos($help_content['HelpContent']['code'], 'IMG-');
				
				$rcont = $help_content['HelpContent']['content'];
				if($err !== FALSE && $err == 0) {
					$rcont = '<fieldset class=fieldset_err><legend>CAUTION</legend>' . $rcont . '</fieldset>';
				} elseif($tip !== FALSE && $tip == 0) {
					$rcont = '<fieldset class=fieldset_tip><legend>TIP</legend>' . $rcont . '</fieldset>';
				} elseif($img !== FALSE && $img == 0) {
					$rcont = '<img src=http://' . Configure::read('rf_base_url') . 'img/help_images/' . $rcont . ' />';
				}
				$content = str_replace('[[' . $help_content['HelpContent']['code'] . ']]', $rcont, $content);
			}
			$content_tags = $this->getContentTags($content);
		}
        
        $mynode = array(
            'id' => $node['HelpItem']['id'],
            'title' => $node['HelpItem']['title'],
            'content' => $content,
            'version' => $node['HelpItem']['version'],
            'list_order' => $node['HelpItem']['list_order'],
            'children' => array()
        );
        $children = $this->__getChildNodes($node['HelpItem']['id'], $adata);
        foreach ($children as $child) {
            $mynode['children'][] = $this->__getTreeArray($child, $adata);
        }
        return $mynode;
    }

    function __getChildNodes($p_id, $adata) {
        $ret = array();
        foreach ($adata as $ad) {
            if ($ad['HelpItem']['parent_id'] == $p_id) {
                $ret[] = $ad;
            }
        }
        return $ret;
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid help item', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->HelpItem->recursive = 2;
        $this->set('help_item', $this->HelpItem->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->HelpItem->create();
            $this->autoRender = false;
            if ($this->HelpItem->save($this->data)) {
                $this->Session->setFlash(__('The help item has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The help item could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id) {
            $this->set('parent_id', $id);
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid help item', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->HelpItem->save($this->data)) {
                $this->Session->setFlash(__('The help item has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The help item could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $help_item = $this->HelpItem->read(null, $id);
        $this->set('help_item', $help_item);
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for help item', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->HelpItem->delete($i);
                }
                $this->Session->setFlash(__('Help item deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Help item was not deleted', true) . ' ' . $e->getMessage(), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->HelpItem->delete($id)) {
                $this->Session->setFlash(__('Help item deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Help item was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }
    
    function help_menu() {
        $help_items = $this->HelpItem->find('all', array('order' => 'HelpItem.list_order ASC'));
        $tree_data = array();
        if (count($help_items) > 0) {
            $tree_data = array($this->__getTreeArray($help_items[0], $help_items));
        }
        // TODO: Clearout the records which have no valid activity.
        
        $this->set('help_items', $tree_data);
    }

}
