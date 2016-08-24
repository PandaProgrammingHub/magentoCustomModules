<?php

$this->startSetup();
/**
 * Create new landing page table
 *
 */
$this->run("

		CREATE TABLE IF NOT EXISTS {$this->getTable('landingpage_page')} (
			`page_id` int(11) unsigned NOT NULL auto_increment,
                        `conditions_serialized` TEXT NOT NULL default '',
                        `filter_rules` TEXT NOT NULL default '',
                        `is_featured` INT(1) NULL default 0,
                        `created_at` TIMESTAMP DEFAULT 0,
                        `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (`page_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Landingpage: Page';

	");
/**
 * Delete all of the old URL rewrites
 *
 */
$this->getConnection('core_write')->delete($this->getTable('core_url_rewrite'), $this->getConnection('core_write')->quoteInto('id_path LIKE (?)', 'splash/%'));
/**
 * Migrate images
 *
 */
$old = Mage::getBaseDir('media') . DS . 'splash';
$new = Mage::getBaseDir('media') . DS . 'landingpage';
try {
    if (is_dir($old)) {
        rename($old, $new);
    }
} catch (Exception $e) {
    Mage::log($e->getMessage(), false, 'landingpage.log', true);
}
$this->endSetup();
