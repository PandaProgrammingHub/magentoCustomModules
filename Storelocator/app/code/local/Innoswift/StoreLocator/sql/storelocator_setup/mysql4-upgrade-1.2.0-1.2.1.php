<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($this->getTable('storelocator/location'), 'website_address', 'varchar(200) NOT NULL');

$installer->endSetup();