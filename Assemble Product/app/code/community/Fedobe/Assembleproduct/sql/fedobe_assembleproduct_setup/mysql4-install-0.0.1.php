<?php
  
$installer = $this;
  
$installer->startSetup();
  
$installer->run("
DROP TABLE IF EXISTS `{$installer->getTable('fedobe_assembleproduct_primaryoption')}`;

DROP TABLE IF EXISTS `{$installer->getTable('fedobe_assembleproduct_dependencyoption')}`;

CREATE TABLE {$this->getTable('fedobe_assembleproduct_primaryoption')} (
  `primaryoption_id` int(11) unsigned NOT NULL auto_increment,
  `product_id` varchar(100) NOT NULL default '',
  `primaryoption` varchar(100) NOT NULL default '',
  PRIMARY KEY (`primaryoption_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
  CREATE TABLE {$this->getTable('fedobe_assembleproduct_dependencyoption')} (
  `dependencyoption_id` int(11) unsigned NOT NULL auto_increment,
  `primaryoption_id` varchar(100) NOT NULL default '',
  `primaryoption_product_id` varchar(100) NOT NULL default '',
  `dependentoption_id` varchar(100) NOT NULL default '',
  `dependentoption_product_id` varchar(100) NOT NULL default '',
  PRIMARY KEY (`dependencyoption_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
  
$installer->endSetup();
