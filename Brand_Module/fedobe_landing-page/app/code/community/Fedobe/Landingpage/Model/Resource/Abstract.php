<?php

/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */
abstract class Fedobe_Landingpage_Model_Resource_Abstract extends Mage_Core_Model_Resource_Db_Abstract {

    /**
     * Determine whether the current store is the Admin store
     *
     * @return bool
     */
    public function isAdmin() {
        return (int) Mage::app()->getStore()->getId() === Mage_Core_Model_App::ADMIN_STORE_ID;
    }

    /**
     * Set required fields before saving model
     *
     * @param Mage_Core_Model_Abstract $object
     * @return $this
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object) {
        if (!$object->getPageId()) {
            if (!$object->getDisplayName()) {
                if (!$object->getFrontendLabel()) {
                    throw new Exception(Mage::helper('landingpage')->__('Landing page must have a name'));
                } else {
                    $object->setDisplayName($object->getFrontendLabel());
                }
            }
        }
        if (!$object->getUrlKey()) {
            $object->setUrlKey($this->formatUrlKey($object->getname()));
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
    protected function _afterSave(Mage_Core_Model_Abstract $object) {

        if ($object->getId()) {
	    Mage::register('page_id',$object->getId());
            $page_id = $object->getId();
            $image = $object->getData('image');
            $thumbnail = $object->getData('thumbnail');
            $page_data = Mage::app()->getRequest()->getParam('splash');
            $storeId = $page_data['store_id'];
            $object->unsetData();
            $page_data['image'] = $image;
            $page_data['thumbnail'] = $thumbnail;
            unset($page_data['filter_rules']);
            unset($page_data['store_id']);

            //Here let's store the use default things
            $default_values = Mage::app()->getRequest()->getParam('use_default');
            foreach ($default_values as $k => $attr_name) {
                if (array_key_exists($attr_name, $page_data)) {
                    unset($page_data[$attr_name]);
                }
            }
	    $table = $this->getStoreTable();
            $page_store_data = array();
            if (!empty($page_data)) {
                //Here let's prepare data to be inserted
                foreach ($page_data AS $attr_name => $attr_val) {
                    switch ($attr_name) {
                        case 'image':
                            $attr_val = $image;
                            break;
                        case 'thumbnail':
                            $attr_val = $thumbnail;
                            break;
                        default:
                            $attr_val = $attr_val;
                            break;
                    }

		    if(empty($storeId) || ((int) $storeId === 0)){
                        $page_store_data[] = array(
                            'page_id' => (int) $page_id,
                            'store_id' => (int) $storeId,
                            'page_attribute_name' => trim($attr_name),
                            'page_attribute_value' => trim($attr_val)
                        );
                        foreach (Mage::app()->getWebsites() as $website) {
                            foreach ($website->getGroups() as $group) {
                                $stores = $group->getStores();
                                foreach ($stores as $store) {
                                    $page_store_data[] = array(
                                        'page_id' => (int) $page_id,
                                        'store_id' => (int) $store->getId(),
                                        'page_attribute_name' => trim($attr_name),
                                        'page_attribute_value' => trim($attr_val)
                                    );
                                
                                }
                            }
                        }
                    }else{
                        $page_store_data[trim($attr_name)] = array(
                            'page_attribute_name' => trim($attr_name),
                            'page_attribute_value' => trim($attr_val),
                            'is_default' => 0
                        );
                    }

                }
            }else if(empty($page_data) && $storeId){
                $select = $this->_getReadAdapter()->select()
                                ->from(array('page_store_table' => Mage::getSingleton('core/resource')->getTableName('landingpage/page_store')))
                                ->where('page_store_table.page_id = ? ', (int)$page_id)
                                ->where('page_store_table.store_id = ? ', 0);
                $results = $this->_getWriteAdapter()->fetchAll($select);
                foreach ($results as $value) {
                    $page_attribute_name = trim($value['page_attribute_name']);
                    $default_store_data = array(
                                    'page_attribute_name' => trim($value['page_attribute_name']),
                                    'page_attribute_value' => trim($value['page_attribute_value']),
                                    'is_default' => 1
                                );
                    $this->_getWriteAdapter()->update($table, $default_store_data, array($this->getIdFieldName() . ' = ?' => $page_id, 'store_id =? ' => $storeId, 'page_attribute_name =?' => $page_attribute_name));
                }
            }
            
            if (!empty($page_store_data))
                if(!empty($storeId) && ((int) $storeId != 0)){
                    $select = $this->_getReadAdapter()->select()
                                ->from(array('page_store_table' => Mage::getSingleton('core/resource')->getTableName('landingpage/page_store')))
                                ->where('page_store_table.page_id = ? ', (int)$page_id)
                                ->where('page_store_table.store_id = ? ', 0);
                    $results = $this->_getWriteAdapter()->fetchAll($select);
                    foreach ($results as $value) {
                        $default_store_data[trim($value['page_attribute_name'])] = array(
                                        'page_attribute_name' => trim($value['page_attribute_name']),
                                        'page_attribute_value' => trim($value['page_attribute_value']),
                                        'is_default' => 1
                                    );
                    }
                    $update_data = array_merge($default_store_data, $page_store_data);
                    foreach ($update_data as $value) {
                        $page_attribute_name = $value['page_attribute_name'];
                        $data = array(
                                'page_attribute_value' => $value['page_attribute_value'],
                                'is_default' => $value['is_default']
                            );
                        $this->_getWriteAdapter()->update($table, $data, array($this->getIdFieldName() . ' = ?' => $page_id, 'store_id =? ' => $storeId, 'page_attribute_name =?' => $page_attribute_name));
                    }
                }else if(((int) $storeId === 0) && !is_null($storeId)){
                    foreach ($page_store_data as $store_data) {
                        $page_attribute_name = $store_data['page_attribute_name'];
                        $update_data = array(
                            'page_attribute_value' => $store_data['page_attribute_value']
                            );
                        $this->_getWriteAdapter()->update($table, $update_data, array($this->getIdFieldName() . ' = ?' => $page_id, 'is_default =? ' => 1, 'page_attribute_name =?' => $page_attribute_name));
                    }
                }else if(is_null($storeId)){
                    $this->_getWriteAdapter()->insertMultiple($table, $page_store_data);
                }
        }
    }

    public function formatUrlKey($str) {
        $urlKey = str_replace("'", '', $str);
        $urlKey = preg_replace('#[^0-9a-z\/]+#i', '-', Mage::helper('catalog/product_url')->format($urlKey));
        $urlKey = strtolower($urlKey);
        $urlKey = trim($urlKey, '-');

        return $urlKey;
    }

}
