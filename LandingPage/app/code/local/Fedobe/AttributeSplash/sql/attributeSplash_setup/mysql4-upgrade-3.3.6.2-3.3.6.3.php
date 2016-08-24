<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */
	
	$this->startSetup();

	$this->getConnection()->addColumn($this->getTable('attributesplash_page'), 'conditions_serialized', " TEXT NOT NULL default '' AFTER `description`");
	$this->getConnection()->addColumn($this->getTable('attributesplash_page'), 'filter_rules', " TEXT NOT NULL default '' AFTER `conditions_serialized`");
 	$this->getConnection()->addColumn($this->getTable('attributesplash_page'), 'is_featured', " INT(1) NULL default 0 AFTER `page_layout`");
	$this->endSetup();
