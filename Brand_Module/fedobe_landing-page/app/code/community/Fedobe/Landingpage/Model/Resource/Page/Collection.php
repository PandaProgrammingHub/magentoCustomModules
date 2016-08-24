<?php
/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */

class Fedobe_Landingpage_Model_Resource_Page_Collection extends Fedobe_Landingpage_Model_Resource_Collection_Abstract
{
	/**
	 * Setup the collection model
	 *
	 * @return $this
	 */
	public function _construct()
	{
		$this->_init('landingpage/page');
		
		$this->_map['fields']['page_id'] = 'main_table.page_id';
		$this->_map['fields']['store'] = 'store_table.store_id';
		
		return parent::_construct();
	}


	/**
	 * Order Splash Pges by the option value sort order field
	 *
	 * @return $this
	 */	
	public function addOrderBySortOrder()
	{
		$this->getSelect()->order('_option_table.sort_order ASC');
		$this->getSelect()->order('display_name ASC');

		return $this;		
	}

	/**
	 * Filter the collection by a product ID
	 *
	 * @param Mage_Catalog_Model_Product $product
	 * @param int $storeId = null
	 * @return $this
	 */
	public function addProductFilter(Mage_Catalog_Model_Product $product, $storeId = null)
	{
		if (is_null($storeId)) {
			$storeId = Mage::app()->getStore()->getId();
		}

		$this->getSelect()
			->join(
				array('_product_filter' => $this->getTable('catalog/product_index_eav')),
				"`_product_filter`.`attribute_id`= `_attribute_table`.`attribute_id` AND `_product_filter`.`value` = `main_table`.`option_id`"
				. $this->getConnection()->quoteInto(" AND `_product_filter`.`entity_id` = ?", $product->getId())
				. $this->getConnection()->quoteInto(" AND `_product_filter`.`store_id`=? ", $storeId),
				''
			);

		return $this;
	}
	
	/**
	 * Order the groups by name
	 *
	 * @return $this
	 */
	public function addOrderByName()
	{
		$this->getSelect()->order('main_table.display_name ASC');
		
		return $this;
	}
	
	/**
	 * After loading the collection, perform the afterLoad resource method on each item
	 *
	 * @return $this
	 */
	protected function _afterLoad()
	{
		$this->walk('afterLoad');
		
		return parent::_afterLoad();
	}
}
