<?php

$installer = $this;
$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('storelocator/location')};
  CREATE TABLE  `{$this->getTable('storelocator/location')}` (
 `id` int(20) PRIMARY KEY AUTO_INCREMENT,
`store_title` varchar(100) NOT NULL,
`address_display` varchar(1000) NOT NULL,
`address_geo` varchar(1000) NOT NULL,
`store_phone` varchar(50) NULL,
`store_email` varchar(50) NULL,
`store_longitude` double,
`store_latitude` double
) ENGINE=InnoDB");

$installer->endSetup();