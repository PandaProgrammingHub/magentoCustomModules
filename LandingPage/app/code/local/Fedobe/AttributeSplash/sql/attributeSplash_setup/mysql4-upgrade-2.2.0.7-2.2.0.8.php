<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */
	
	$this->startSetup();
	
	$base = 'attributeSplash';
	
	$configs = array(
		'frontend/template' => 'page/template',
		'frontend/grid_column_count' => 'page/column_count',
	);
		
	foreach($configs as $from => $to) {
		try {
			$this->getConnection()->update(
				$this->getTable('core/config_data'),
				array('path' => $base . '/' . $to),
				$this->getConnection()->quoteInto('path=?', $base . '/' . $from)
			);
		}
		catch (Exception $e) {
			Mage::logException($e);
		}
	}

	$this->endSetup();
