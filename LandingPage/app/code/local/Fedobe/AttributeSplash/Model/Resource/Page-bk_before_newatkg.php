<?php

/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */
class Fedobe_AttributeSplash_Model_Resource_Page extends Fedobe_AttributeSplash_Model_Resource_Abstract {

    /**
     * Fields to be serialized before saving
     * This applies to the filter fields
     *
     * @var array
     */
    protected $_serializableFields = array(
        'other' => array('a:0:{}', array()),
    );
    protected $_adapter, $_operator, $_subcond, $_alias;

    public function _construct() {
        $this->_init('attributeSplash/page', 'page_id');
        $this->_adapter = Mage::getSingleton('core/resource')->getConnection(Mage_Core_Model_Resource::DEFAULT_READ_RESOURCE);
        $this->_operator = array();
        $this->_subcond = array();
        $this->_alias = "custom_index";
    }

    /**
     * Retrieve the store table name
     *
     * @return string
     */
    public function getStoreTable() {
        return $this->getTable('attributeSplash/page_store');
    }

    public function getConditions() {
        $page = Mage::registry('splash_page');
        $condition_rules = unserialize($page->getConditionsSerialized());
        return $condition_rules;
    }

    public function prepareConditionSql($conditions) {
        foreach ($conditions['conditions'] as $condition) {
            if (isset($condition['aggregator'])) {
                $this->prepareConditionSql($condition);
            } else {
                $wheres[] = $this->getOperatorCondition($condition['attribute'], $condition['operator'], $condition['value']);
            }
        }
        $delimiter = $conditions['aggregator'] == "all" ? ' AND ' : ' OR ';
        $this->_operator[] = $delimiter;
        $this->_subcond[] = array('cond' => $wheres, 'operator' => $delimiter);
    }

    /**
     * Retrieve a collection of products associated with the splash page
     *
     * @return Mage_Catalog_Model_Resource_Eav_Resource_Product_Collection
     */
    public function getProductCollection(Fedobe_AttributeSplash_Model_Page $page) {
        $storeId = ((int) $page->getStoreId() === 0) ? Mage::app()->getStore()->getId() : $page->getStoreId();

        $collection = Mage::getResourceModel('catalog/product_collection')
                ->setStoreId($storeId)
                ->addAttributeToFilter('status', 1)
                ->addAttributeToFilter('visibility', array('in' => array(
                Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG,
                Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH
        )));
        $collection = $this->prepareFormattedConditions($collection, $storeId);
//        echo $collection->getSelect()->__toString();exit;

        return $collection;
    }

    /**
     * Set required fields before saving model
     *
     * @param Mage_Core_Model_Abstract $object
     * @return $this
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object) {
        if (!$object->getData('store_ids')) {
            $object->setData('store_ids', array(Mage::app()->getStore(true)->getId()));
        }
        //Here let's check for any rules set or not
        if ($conditon_rules = $object->getData('conditions_serialized')) {
            $conditon_rulesarr = unserialize($conditon_rules);
            if (count($conditon_rulesarr) <= 1) {
                throw new Exception('A page cannot be created without any condition rules.');
            }
        }
        if (!$this->_pageIsUniqueToStores($object)) {
            throw new Exception('A page already exists with URL Key or  Rule Set');
        }
        return parent::_beforeSave($object);
    }

    /**
     * Determine whether [ages scope if unique to store
     *
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     */
    protected function _pageIsUniqueToStores(Mage_Core_Model_Abstract $object) {
        $stores = (array) $object->getData('store_ids');
        if (count($stores) === 1 && (int) $stores[0] === Mage_Core_Model_App::ADMIN_STORE_ID) {
            $stores = $this->getAllStoreId();
        } else {
            $stores[] = (String) Mage_Core_Model_App::ADMIN_STORE_ID;
        }
        $select = $this->_getReadAdapter()
                ->select()
                ->from(array('main_table' => $this->getMainTable()), 'page_id')
                ->join(array('_store' => $this->getStoreTable()), 'main_table.page_id = _store.page_id', '')
                ->limit(1);

        //Here to check the exact condition rules 
        $select->where('main_table.filter_rules = ?', $object->getData('filter_rules'));
        //Here to check the exact url key 
        $select->where('main_table.url_key = ?', $object->getData('url_key'));
        if (count($stores) === 1) {
            $select->where('_store.store_id = ?', array_shift($stores));
        } else {
            $select->where('_store.store_id IN (?)', $stores);
        }
        if ($object->getId()) {
            $select->where('main_table.page_id <> ?', $object->getId());
        }
        return $this->_getWriteAdapter()->fetchOne($select) === false;
    }

    public function getAllStoreId() {
        $allstores = array(
            (String) Mage_Core_Model_App::ADMIN_STORE_ID
        );
        foreach (Mage::app()->getWebsites() as $website) {
            foreach ($website->getGroups() as $group) {
                $stores = $group->getStores();
                foreach ($stores as $store) {
                    $allstores[] = $store->getId();
                }
            }
        }
        return $allstores;
    }

    public function getOperatorCondition($field, $operator, $value) {
        switch ($operator) {
            case '!=':
                $selectOperator = 'neq';
                break;
            case '>=':
                $selectOperator = 'gteq';
                break;
            case '<=':
                $selectOperator = 'lteq';
                break;
            case '>':
                $selectOperator = 'gt';
                break;
            case '<':
                $selectOperator = 'lt';
                break;
            case '{}':
            case '!{}':
                $value = explode(",", $value);
                if ((preg_match('/^.*(category_ids)$/', $field) || preg_match('/^.*(sku)$/', $field) || preg_match('/^.*(gender)$/', $field)) && is_array($value)) {
                    $selectOperator = 'in';
                } else {
                    $selectOperator = 'like';
                }
                if (substr($operator, 0, 1) == '!') {
                    $selectOperator = 'n' . $selectOperator;
                }
                break;

            case '[]':
            case '![]':
            case '()':
            case '!()':
                $value = explode(",", $value);
                $selectOperator = 'finset';
                if (substr($operator, 0, 1) == '!') {
                    $selectOperator = 'nin';
                }
                break;
            default:
                $selectOperator = 'eq';
                break;
        }
        if (is_array($value) && in_array($operator, array('==', '!=', '>=', '<=', '>', '<', '{}', '!{}'))) {
            $results = array();
            foreach ($value as $v) {
                $results[] = trim($v);
            }
            $result = array('attribute' => $field, 'operator' => $selectOperator, 'value' => $results);
        } elseif (in_array($operator, array('()', '!()', '[]', '![]'))) {
            if (!is_array($value)) {
                $value = array($value);
            }
            $results = array();
            foreach ($value as $v) {
                $results[] = trim($v);
            }
            $result = array('attribute' => $field, 'operator' => $selectOperator, 'value' => $results);
        } else {
            $result = array('attribute' => $field, 'operator' => $selectOperator, 'value' => $value);
        }
        //Here to overwrite the conditions as follows
        return $result;
    }

    public function prepareFormattedConditions($collection, $storeId) {
        $conditionsrtring = "";
        $conditions = $this->getConditions();
        $this->prepareConditionSql($conditions);
        $opr = array_reverse($this->_operator);
        $cnd = array_reverse($this->_subcond);
        $stock_filter = $custom_qty_filter = $custom_final_price = $custom_state_filter = 0;
        $qtyfilter = $fpricefilter = $csstatefilter = array();
        $qtyfilter = array();
        if (!empty($opr)) {
            foreach ($opr as $k => $v) {
                if (trim($v) == 'AND') {
                    if (trim($cnd[$k]['operator']) == "AND") {
                        $anddata = $this->commonAndOperation($cnd, $k, $collection);
                    } else {
                        $anddata = $this->commonOrOperation($cnd, $k, $collection);
                    }
                } else {
                    if (trim($cnd[$k]['operator']) == "AND") {
                        $anddata = $this->commonAndOperation($cnd, $k, $collection);
                    } else {
                        $anddata = $this->commonOrOperation($cnd, $k, $collection);
                    }
                }
                $stock_filter = $anddata['stock_filter'][0];
                $custom_qty_filter = $anddata['custom_qty_filter'][0];
                $qtyfilter = $anddata['custom_qty_filter'][1];
                $custom_final_price = $anddata['custom_final_price'][0];
                $fpricefilter = $anddata['custom_final_price'][1];
                $custom_state_filter = $anddata['custom_state'][0];
                $csstatefilter = $anddata['custom_state'][1];
            }
            //Here let's add stock filter
            if ($stock_filter) {
                Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
            }
            //Here for custom quantity filter
            if ($custom_qty_filter) {
                $this->addCustomQuantityFilter($collection, $qtyfilter['operator'], $qtyfilter['value']);
            }
            //Here for custom final price filter
            if ($custom_final_price) {
                $this->addCustomFinalPriceFilter($collection, $fpricefilter['operator'], $fpricefilter['value']);
            }
            //Here for custom state filter
            if ($custom_state_filter) {
                $this->addCustomStateFilter($collection, $csstatefilter['operator'], $csstatefilter['value']);
            }
        }
        return $collection;
    }

    public function commonAndOperation($cnd, $k, $collection) {
        $stock_filter = $custom_qty_filter = $custom_final_price = $custom_state_filter = 0;
        $qtyfilter = $fpricefilter = $csstatefilter = array();
        foreach ($cnd[$k]['cond'] as $ick => $icv) {
            if ($icv['attribute'] == 'custom_stock') {
                $stock_filter = 1;
                continue;
            }
            if ($icv['attribute'] == 'custom_qty') {
                $custom_qty_filter = 1;
                $qtyfilter['operator'] = $icv['operator'];
                $qtyfilter['value'] = $icv['value'];
                continue;
            }
            if ($icv['attribute'] == 'custom_final_price') {
                $custom_final_price = 1;
                $fpricefilter['operator'] = $icv['operator'];
                $fpricefilter['value'] = $icv['value'];
                continue;
            }
            if ($icv['attribute'] == 'custom_state') {
                $custom_state_filter = 1;
                $csstatefilter['operator'] = $icv['operator'];
                $csstatefilter['value'] = $icv['value'];
                continue;
            }
	    if ($icv['attribute'] == 'category_ids') {
               if(strpos($icv['value'],",")) {
                  $categoryIds = explode(",", $icv['value']);
                  $filter = NULL;
                  foreach ($categoryIds as $cat) {
                     $filter[]['finset'] = trim($cat);
                  }
                  $collection->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
                             ->addAttributeToFilter('category_id', array($filter));
//echo $collection->getSelect();exit;
               }else {
                  $collection->addCategoryFilter(Mage::getModel('catalog/category')->load($icv['value']));
               }
            }else{
               $collection->addAttributeToFilter($icv['attribute'], array("{$icv['operator']}" => $icv['value']));
            }
        }
        $returarr = array(
            "stock_filter" => array($stock_filter, array()),
            "custom_qty_filter" => array($custom_qty_filter, $qtyfilter),
            "custom_final_price" => array($custom_final_price, $fpricefilter),
            "custom_state" => array($custom_state_filter, $csstatefilter)
        );
        return $returarr;
    }

    public function commonOrOperation($cnd, $k, $collection) {
        $stock_filter = $custom_qty_filter = $custom_final_price = $custom_state_filter = 0;
        $condarr = $qtyfilter = $fpricefilter = $csstatefilter = array();
        foreach ($cnd[$k]['cond'] as $ick => $icv) {
            if ($icv['attribute'] == 'custom_stock') {
                $stock_filter = 1;
                continue;
            }
            if ($icv['attribute'] == 'custom_qty') {
                $custom_qty_filter = 1;
                $qtyfilter['operator'] = $icv['operator'];
                $qtyfilter['value'] = $icv['value'];
                continue;
            }
            if ($icv['attribute'] == 'custom_final_price') {
                $custom_final_price = 1;
                $fpricefilter['operator'] = $icv['operator'];
                $fpricefilter['value'] = $icv['value'];
                continue;
            }
            if ($icv['attribute'] == 'custom_state') {
                $custom_state_filter = 1;
                $csstatefilter['operator'] = $icv['operator'];
                $csstatefilter['value'] = $icv['value'];
                continue;
            }
            $condarr[] = array('attribute' => $icv['attribute'], "{$icv['operator']}" => $icv['value']);
        }
        $collection->addAttributeToFilter($condarr);
        $returarr = array(
            "stock_filter" => array($stock_filter, array()),
            "custom_qty_filter" => array($custom_qty_filter, $qtyfilter),
            "custom_final_price" => array($custom_final_price, $fpricefilter),
            "custom_state" => array($custom_state_filter, $csstatefilter)
        );
        return $returarr;
    }

    public function addCustomQuantityFilter($collection, $opr, $val) {
        $collection->joinField('qty', 'cataloginventory/stock_item', 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left')
                ->addAttributeToFilter('qty', array($opr => $val));
    }

    public function addCustomFinalPriceFilter($collection, $opr, $val) {
        $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        $websiteId = Mage::app()->getWebsite()->getId();
        $collection->joinField(
                'filter_price', // field alias
                'catalog/product_index_price', // table
                'final_price', // real field name
                'entity_id=entity_id', // primary condition
                array(// additional conditions
            'website_id' => $websiteId,
            'customer_group_id' => $customerGroupId,
            'final_price' => array($opr => $val)
                )
        );
    }

    public function addCustomStateFilter($collection, $opr, $val) {
        if ($val == 'new') {
            $this->addNewStateFilter($collection);
        }
        if ($val == 'onsale') {
            $this->addSaleStateFilter($collection);
        }
    }

    public function addNewStateFilter($collection) {
        $todayDate = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        $collection->addAttributeToFilter('news_from_date', array('date' => true, 'to' => $todayDate))
                ->addAttributeToFilter('news_to_date', array('or' => array(
                        0 => array('date' => true, 'from' => $todayDate),
                        1 => array('is' => new Zend_Db_Expr('null')))
                        ), 'left')
                ->addAttributeToSort('news_from_date', 'desc');
    }

    public function addSaleStateFilter($collection) {
        $todayDate = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        $collection->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
                ->addAttributeToFilter('special_to_date', array('or' => array(
                        0 => array('date' => true, 'from' => $todayDate),
                        1 => array('is' => new Zend_Db_Expr('null')))
                        ), 'left')
                ->addAttributeToFilter('special_price', array('neq' => ""))
                ->addAttributeToSort('special_from_date', 'desc');
    }

}