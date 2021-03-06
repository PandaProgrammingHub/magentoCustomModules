<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

abstract class Fedobe_AttributeSplash_Model_Resource_Abstract extends Mage_Core_Model_Resource_Db_Abstract
{
	/**
	 * Retrieve select object for load object data
	 * This gets the default select, plus the attribute id and code
	 *
	 * @param   string $field
	 * @param   mixed $value
	 * @return  Zend_Db_Select
	*/
	protected function _getLoadSelect($field, $value, $object)
	{
		$select = $this->_getReadAdapter()->select()
			->from(array('main_table' => $this->getMainTable()))
			->where("`main_table`.`{$field}` = ?", $value)
			->limit(1);
			
		$adminId = Mage_Core_Model_App::ADMIN_STORE_ID;
		
		$storeId = $object->getStoreId();
		
		if ($storeId !== $adminId) {
			$cond = $this->_getReadAdapter()->quoteInto(
				'`store`.`' . $this->getIdFieldName() . '` = `main_table`.`' . $this->getIdFieldName() . '` AND `store`.`store_id` IN (?)', array($adminId, $storeId)
			);
			
			$select->join(array('store' => $this->getStoreTable()), $cond, '')
				->order('store.store_id DESC');
		}

		return $select;
	}

	/**
	 * Get store ids to which specified item is assigned
	 *
	 * @param int $id
	 * @return array
	*/
	public function lookupStoreIds($objectId)
	{
		$select = $this->_getReadAdapter()->select()
			->from($this->getStoreTable(), 'store_id')
			->where($this->getIdFieldName() . ' = ?', (int)$objectId);
	
		return $this->_getReadAdapter()->fetchCol($select);
	}
	
	/**
	 * Determine whether the current store is the Admin store
	 *
	 * @return bool
	 */
	public function isAdmin()
	{
		return (int)Mage::app()->getStore()->getId() === Mage_Core_Model_App::ADMIN_STORE_ID;
	}
		
	/**
	 * Set required fields before saving model
	 *
	 * @param Mage_Core_Model_Abstract $object
	 * @return $this
	 */
	protected function _beforeSave(Mage_Core_Model_Abstract $object)
	{
            
		if (!$object->getDisplayName()) {
			if (!$object->getFrontendLabel()) {
				throw new Exception(Mage::helper('attributeSplash')->__('Splash object must have a name'));
			}
			else {
				$object->setDisplayName($object->getFrontendLabel());
			}
		}
		
		if (!$object->getUrlKey()) {
			$object->setUrlKey($object->getname());
		}
		
		$object->setUrlKey($this->formatUrlKey($object->getUrlKey()));
		
		if ($object->isObjectNew()) {
			$object->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
		}

		$object->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
		
		return parent::_beforeSave($object);
	}

	/**
	 * Set store data after saving model
	 *
	 * @param Mage_Core_Model_Abstract $object
	 * @return $this
	 */	
	protected function _afterSave(Mage_Core_Model_Abstract $object)
	{
		if ($object->getId()) {
			$oldStores = $this->lookupStoreIds($object->getId());
			$newStores = (array)$object->getStoreIds();
	
			if (empty($newStores)) {
				$newStores = (array)$object->getStoreId();
			}
	
			$table  = $this->getStoreTable();
			$insert = array_diff($newStores, $oldStores);
			$delete = array_diff($oldStores, $newStores);
			
			if ($delete) {
				$this->_getWriteAdapter()->delete($table, array($this->getIdFieldName() . ' = ?' => (int) $object->getId(), 'store_id IN (?)' => $delete));
			}
			if ($insert) {
				$data = array();
			
				foreach ($insert as $storeId) {
					$data[] = array(
						$this->getIdFieldName()  => (int) $object->getId(),
						'store_id' => (int) $storeId
					);
				}

				$this->_getWriteAdapter()->insertMultiple($table, $data);
			}
		}
	}

	/**
	 * Load store data after loading model
	 *
	 * @param Mage_Core_Model_Abstract $object
	 * @return $this
	 */
	protected function _afterLoad(Mage_Core_Model_Abstract $object)
	{
		if ($object->getId()) {
			$storeIds = $this->lookupStoreIds($object->getId());
			$object->setData('store_ids', $storeIds);			
			
			if (!$this->isAdmin()) {
				$object->setStoreId(Mage::app()->getStore(true)->getId());
			}
		}
		
		return parent::_afterLoad($object);
	}

	/**
	 * Format a string to a valid URL key
	 * Allow a-zA-Z0-9, hyphen and /
	 *
	 * @param string $str
	 * @return string
	 */
	public function formatUrlKey($str)
	{
		$urlKey = str_replace("'", '', $str);
		$urlKey = preg_replace('#[^0-9a-z\/]+#i', '-', Mage::helper('catalog/product_url')->format($urlKey));
		$urlKey = strtolower($urlKey);
		$urlKey = trim($urlKey, '-');
		
		return $urlKey;
	}
}
