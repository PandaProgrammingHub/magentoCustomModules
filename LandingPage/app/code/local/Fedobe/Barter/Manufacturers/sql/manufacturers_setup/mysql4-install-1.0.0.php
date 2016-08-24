<?php
$installer = $this;
$installer->startSetup();

$fedobe_attrspal = Mage::helper('core')->isModuleEnabled('Fedobe_AttributeSplash');
$fedobe_attrspaloutput = Mage::helper('core')->isModuleOutputEnabled('Fedobe_AttributeSplash');

$fish_attrspal = Mage::helper('core')->isModuleEnabled('Fishpig_AttributeSplash');
$fish_attrspaloutput = Mage::helper('core')->isModuleOutputEnabled('Fishpig_AttributeSplash');

$content = addslashes('{{block type="manufacturers/list" name="fedobe.brandpage" template="fedobe/manufacturers/grid.phtml" }}');
$installer->run("
            REPLACE INTO {$this->getTable('cms_page')} (`title`, `root_template`, `meta_keywords`, `meta_description`, `identifier`, `content_heading`, `content`,`is_active`, `sort_order`) VALUES ('Brand Page','one_column','Brand Page','Brand Page','fedobebrandpage',NULL,'{$content}',1,0);
        ");
$connection    = Mage::getSingleton('core/resource')->getConnection('core_write');
$page_id = $connection->lastInsertId();          
$installer->run("
            REPLACE INTO {$this->getTable('cms_page_store')} (`page_id`, `store_id`) VALUES ($page_id,0);
        ");
$installer->endSetup();
