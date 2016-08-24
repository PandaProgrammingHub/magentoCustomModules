<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($this->getTable('storelocator/location'), 'store_status', 'int(50) NOT NULL');
$installer->run("
 ALTER TABLE {$this->getTable('storelocator/location')}
     ADD INDEX `IDX_ENTITY_ADDRESS` (`address_display`),
     ADD INDEX `IDX_ENTITY_WEBSITE` (`website`),
     ADD INDEX `IDX_ENTITY_STATUS` (`store_status`)    
");
$installer->endSetup();