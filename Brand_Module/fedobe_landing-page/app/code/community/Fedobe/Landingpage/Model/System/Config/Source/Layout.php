<?php
/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */

class Fedobe_Landingpage_Model_System_Config_Source_Layout
{
	public function toOptionArray()
	{
		$options = Mage::getModel('page/source_layout')->toOptionArray(false);
		
		array_unshift($options, array(
			'value'=>'', 
			'label'=>Mage::helper('adminhtml')->__('No layout updates')
		));

		return $options;
	}
}
