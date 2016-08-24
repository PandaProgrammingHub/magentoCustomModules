<?php
/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */
	
	$this->startSetup();

	$this->run("
		CREATE TABLE IF NOT EXISTS {$this->getTable('landingpage_page_store')} (
                        `value_id` int(11) unsigned NOT NULL auto_increment,
			`page_id` int(11) unsigned NOT NULL,
			`store_id` smallint(5) unsigned NOT NULL default 0,
			`is_default` tinyint(4) NOT NULL default 1,
                        `page_attribute_name` VARCHAR (255) NOT NULL,
                        `page_attribute_value` TEXT NOT NULL,
			PRIMARY KEY (`value_id`),
			KEY `FK_PAGE_ID_LANDING_PAGE_STORE` (`page_id`),
			CONSTRAINT `FK_PAGE_ID_LANDING_PAGE_STORE` FOREIGN KEY (`page_id`) REFERENCES `{$this->getTable('landingpage_page')}` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			KEY `FK_STORE_ID_LANDING_PAGE_STORE` (`store_id`),
			CONSTRAINT `FK_STORE_ID_LANDING_PAGE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Landingpage: Page / Store';
		ALTER TABLE {$this->getTable('landingpage_page_store')} ADD UNIQUE (`page_id`,`store_id`,`page_attribute_name`);
		
	");
	$this->endSetup();
