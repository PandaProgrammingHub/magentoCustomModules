<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($this->getTable('storelocator/location'), 'website', 'int(50) NOT NULL');

$installer->endSetup();


/// Multi store(1.1.0)
/// added for website column for multi store

// added storelocator link in top links in front end