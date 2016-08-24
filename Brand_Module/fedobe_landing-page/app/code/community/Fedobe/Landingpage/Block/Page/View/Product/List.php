<?php
/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */

class Fedobe_Landingpage_Block_Page_View_Product_List extends Mage_Catalog_Block_Product_List
{
	/**
	 * Retrieves the current layer product collection
	 *
	 * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
	 */
	protected function _getProductCollection()
	{
		if (is_null($this->_productCollection)) {
			$this->_productCollection = Mage::getSingleton('landingpage/layer')->getProductCollection();

			if ($orders = Mage::getSingleton('catalog/config')->getAttributeUsedForSortByArray()) {
				if (isset($orders['position'])) {
					unset($orders['position']);
				}
				
				$this->setAvailableOrders($orders);

				if (!$this->getSortBy()) {
					$category = Mage::getModel('catalog/category')->setStoreId(
						Mage::app()->getStore()->getId()
					);

					$this->setSortBy($category->getDefaultSortBy());
				}
			}
		}
		
		return $this->_productCollection;
	}
}
