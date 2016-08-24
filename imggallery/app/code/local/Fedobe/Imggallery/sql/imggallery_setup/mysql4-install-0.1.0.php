<?php
$installer = $this;
$installer->startSetup();
$table = $installer->getConnection()
    ->newTable($installer->getTable('imgdetails'))
    ->addColumn('imagedetails_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Image ID')
    ->addColumn('image_title', Varien_Db_Ddl_Table::TYPE_TEXT, 256, array(
		'nullable'  => false,
        ), 'Image Title')
	
	->addColumn('gallery_img', Varien_Db_Ddl_Table::TYPE_TEXT, 256, array(
		'nullable'  => false,
        ), 'Gallery Image')
        ->addColumn('image_description', Varien_Db_Ddl_Table::TYPE_TEXT, 256, array(
		), 'Image Description')
	->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
        ), 'Category Id')
        ->addColumn('position', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
        ), 'Position')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
        //'nullable'  => false,
       // 'default'   => '1',
        ), 'Is Enabled');
		
$installer->getConnection()->createTable($table);

$table = $installer->getConnection()
    ->newTable($installer->getTable('imgcategory'))
    ->addColumn('imagecategory_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Category ID')
    ->addColumn('category_title', Varien_Db_Ddl_Table::TYPE_TEXT, 256, array(
		'nullable'  => false,
        ), 'Category Title')
	->addColumn('category_img', Varien_Db_Ddl_Table::TYPE_TEXT, 256, array(
		'nullable'  => false,
        ), 'Category Image')
	->addColumn('category_description', Varien_Db_Ddl_Table::TYPE_TEXT, 256, array(
		), 'Category Description');
		
$installer->getConnection()->createTable($table);

$installer->endSetup();