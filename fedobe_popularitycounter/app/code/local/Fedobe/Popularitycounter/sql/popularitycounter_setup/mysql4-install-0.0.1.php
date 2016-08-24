<?php
  
$installer = $this;
  
$installer->startSetup();
  
$installer->run("
DROP TABLE IF EXISTS `{$installer->getTable('fedobe_popularitycounter_votes')}`;
DROP TABLE IF EXISTS `{$installer->getTable('fedobe_popularitycounter_users')}`;
CREATE TABLE {$this->getTable('fedobe_popularitycounter_votes')} (
  `popularitycountervotes_id` int(11) unsigned NOT NULL auto_increment,
  `product_id` varchar(100) NOT NULL default '',
  `love` varchar(100) NOT NULL default '',
  `viewed` varchar(100) NOT NULL default '',
  `like` varchar(100) NOT NULL default '',
  `recommended` varchar(100) NOT NULL default '',
  PRIMARY KEY (`popularitycountervotes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
  CREATE TABLE {$this->getTable('fedobe_popularitycounter_users')} (
  `popularitycounterusers_id` int(11) unsigned NOT NULL auto_increment,
  `product_id` varchar(100) NOT NULL default '',
  `customer_id` varchar(100) NOT NULL default '',
  `session_id` varchar(100) NOT NULL default '',
  `love` varchar(100) NOT NULL default '',
  `viewed` varchar(100) NOT NULL default '',
  `like` varchar(100) NOT NULL default '',
  `recommended` varchar(100) NOT NULL default '',
  PRIMARY KEY (`popularitycounterusers_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
  
$installer->endSetup();
