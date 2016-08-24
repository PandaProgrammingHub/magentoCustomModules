<?php
/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */

class Fedobe_Landingpage_Block_Layer_View extends Mage_Catalog_Block_Layer_View
{
	/**
	 * Returns the layer object for the landingpage model
	 *
	 * @return Fedobe_Landingpage_Model_Layer
	 */
	public function getLayer()
	{
		return Mage::getSingleton('landingpage/layer');
	}
	
	/**
	 * Ensure the default Magento blocks are used
	 *
	 * @return $this
	 */
    protected function _initBlocks()
    {
    	parent::_initBlocks();

		$this->_stateBlockName = 'Mage_Catalog_Block_Layer_State';
		$this->_categoryBlockName = 'Mage_Catalog_Block_Layer_Filter_Category';
		$this->_attributeFilterBlockName = 'Mage_Catalog_Block_Layer_Filter_Attribute';
		$this->_priceFilterBlockName = 'Mage_Catalog_Block_Layer_Filter_Price';
		$this->_decimalFilterBlockName = 'Mage_Catalog_Block_Layer_Filter_Decimal';

                /*$this->_stateBlockName = 'Fedobe_Landingpage_Block_Catalog_Layer_State';*/
		//$this->_attributeFilterBlockName = 'Fedobe_Landingpage_Block_Catalog_Layer_Filter_Attribute';
		//$this->_priceFilterBlockName = 'Fedobe_Landingpage_Block_Catalog_Layer_Filter_Price';

        return $this;
    }
}