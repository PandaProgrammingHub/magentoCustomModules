<?php
class Fedobe_Landingpage_Model_Resource_Page extends Fedobe_Landingpage_Model_Resource_Abstract {
    protected $_adapter, $_operator, $_subcond, $_alias;

    public function _construct() {
        $this->_init('landingpage/page', 'page_id');
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
        return $this->getTable('landingpage/page_store');
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
     * Retrieve a collection of products associated with the landingpage page
     *
     * @return Mage_Catalog_Model_Resource_Eav_Resource_Product_Collection
     */
    public function getProductCollection(Fedobe_Landingpage_Model_Page $page) {
        $storeId = ((int) $page->getStoreId() === 0) ? Mage::app()->getStore()->getId() : $page->getStoreId();
        $ids = array();
        $collection = Mage::getModel('catalog/product')->getCollection()->setStoreId($storeId)
                ->addAttributeToFilter('status', 1);
        $collection = $this->prepareFormattedConditions($collection, $storeId);
//echo $collection->getSelect();exit;
        //Here join for configurable products
//        $collection->getSelect()->joinLeft(array('link_table' => Mage::getSingleton('core/resource')->getTableName('catalog/product_super_link')), 'link_table.product_id = e.entity_id', array('product_id', 'parent_id'));
        $collection->getSelect()->group('e.entity_id');
        //Here code introduced for configurable and simple product.
        //Here let's collect all those product ids and it's parent ids to filter products.
        foreach ($collection as $product) {
            //Here to fetch grouped parent ids
            $groupedid = Mage::getModel('catalog/product_type_grouped')->getParentIdsByChild($product->getId());
            if ($groupedid) {
                $ids[] = $groupedid;
            } else {
                $configid = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product->getId());
                if ($configid)
                    $ids[] = $configid;
            }
            $ids[] = $product->getEntityId();
        }
        $finalcollection = Mage::getModel('catalog/product')->getCollection()
                ->setStoreId($storeId)
                ->addAttributeToFilter('status', 1)
                ->addStoreFilter()
                ->addAttributeToFilter('visibility', array('in' => array(4)))
                ->addFieldToFilter('entity_id', array('in' => $ids))
                ->addAttributeToFilter('is_saleable', TRUE);
        return $finalcollection;
    }

    /**
     * Set required fields before saving model
     *
     * @param Mage_Core_Model_Abstract $object
     * @return $this
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object) {
        //Here let's check for any rules set or not
        if ($conditon_rules = $object->getData('filter_rules')) {
            if ($conditon_rules == 'all--1') {
                throw new Exception('A page cannot be created without any condition rules.');
            }
        }
        $this->_pageIsUniqueToStores($object);
        return parent::_beforeSave($object);
    }

    /**
     * Determine whether [ages scope if unique to store
     *
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     */
    protected function _pageIsUniqueToStores(Mage_Core_Model_Abstract $object) {
        //Here let's check for the filter condition rule suppose to be unique
        $select = $this->_getReadAdapter()->select()
                ->from(array('main_table' => $this->getMainTable()), 'page_id')
                ->where('main_table.filter_rules = ?', $object->getData('filter_rules'))
                ->limit(1);
        if ($object->getId()) {
            $select->where('main_table.page_id <> ?', $object->getId());
        }
        if ($this->_getWriteAdapter()->fetchOne($select)) {
            throw new Exception('A page already exists with a Rule Set.');
        }
        $this->duplicateurl($object->getData('url_key'), 0, $object->getId());
    }

    public function duplicateurl($url_key, $falg = 0, $id = 0) {
        //Here let's check for URL KEY accross tables
        //Here let's check in core rewrite table
        $url_suffix = $urlSuffix = rtrim(Mage::getStoreConfig('landingpage/seo/url_suffix'), '/');
        $urlcoreselect = $this->_getReadAdapter()->select()
                ->from(array('core_url_table' => Mage::getSingleton('core/resource')->getTableName('core/url_rewrite')), 'url_rewrite_id')
                ->where('core_url_table.request_path = ?', $url_key . $url_suffix)
                ->limit(1);

        if ($this->_getWriteAdapter()->fetchOne($urlcoreselect)) {
            if ($falg) {
                echo Mage::helper('landingpage')->__('A page already exists with URL Key associated with category OR product or cms page.');
            } else {
                throw new Exception('A page already exists with URL Key associated with category OR product or cms page.');
            }
        }
        //Here let's check with own store table
        $urlownselect = $this->_getReadAdapter()->select()
                ->from(array('page_store_table' => Mage::getSingleton('core/resource')->getTableName('landingpage/page_store')), 'value_id')
                ->where('page_store_table.page_attribute_value = ?', $url_key)
                ->where('page_store_table.page_attribute_name = ? ', 'url_key')
                ->limit(1);
        if ($id) {
            $urlownselect->where('page_store_table.page_id <> ?', $id);
        }
        if ($this->_getWriteAdapter()->fetchOne($urlownselect)) {
            if ($falg) {
                echo Mage::helper('landingpage')->__('A page already exists with URL Key.');
            } else {
                throw new Exception('A page already exists with URL Key.');
            }
        }
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

    public function getOperatorCondition($field, $operator, $value, $storeId) {
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
                $is_multiselect = $this->getAttributeInputType($field) == 'multiselect' ? TRUE : FALSE;
                $value = (is_array($value)) ? $value : explode(",", $value);
                if ((preg_match('/^.*(category_ids)$/', $field) || preg_match('/^.*(sku)$/', $field) || $is_multiselect) && is_array($value)) {
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
                $categoryIds = explode(",", $icv['value']);
                if (count($categoryIds) > 1) {
                    $filter = NULL;
                    foreach ($categoryIds as $cat) {
                        $filter[]['finset'] = trim($cat);
                    }
                    $collection->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
                            ->addAttributeToFilter('category_id', array($filter));
                } else {
                    $collection->addCategoryFilter(Mage::getModel('catalog/category')->load($icv['value']));
                }
            } else {

                $operator = $icv['operator'];
                $opvalue = $icv['value'];
                if ($operator == 'like') {
                    foreach ($opvalue as $k => $v) {
                        $opvalue[$k] = "%$v%";
                    }
                }
                $collection->addAttributeToFilter($icv['attribute'], array($operator => $opvalue));
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
            if ($icv['attribute'] == 'category_ids') {
                $categoryIds = explode(",", $icv['value']);
                if (count($categoryIds) > 1) {
                    $filter = NULL;
                    foreach ($categoryIds as $cat) {
                        $filter[]['finset'] = trim($cat);
                    }
                    $collection->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
                            ->addAttributeToFilter('category_id', array($filter));
                } else {
                    $collection->addCategoryFilter(Mage::getModel('catalog/category')->load($icv['value']));
                }
            }
            if ($icv['attribute'] != 'category_ids') {
                $operator = $icv['operator'];
                $opvalue = $icv['value'];
                if ($operator == 'like') {
                    foreach ($opvalue as $k => $v) {
                        $opvalue[$k] = "%$v%";
                    }
                }
                $condarr[] = array('attribute' => $icv['attribute'], $operator => $opvalue);
            }
        }
        if (!empty($condarr))
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

    public function getAttributeInputType($attribute_code = '') {
        $type = "";
        if ($attribute_code) {
            $attribute_details = Mage::getSingleton("eav/config")->getAttribute('catalog_product', $attribute_code);
            $type = $attribute_details->getData('frontend_input');
        }
        return $type;
    }

    public function checkUniqueRule($filter_rules,$id,$baseurl) {
        $select = $this->_getReadAdapter()->select()
                ->from(array('main_table' => $this->getMainTable()), 'page_id')
                ->where('main_table.filter_rules = ?', $filter_rules)
                ->limit(1);
        if ($id) {
            $select->where('main_table.page_id <> ?', $id);
        }
        if($filter_rules == 'all--1') {
            echo '<span class=\'error-msg\' style=\'color:red\'>&nbsp;&nbsp;&nbsp;Can\'t apply Blank Rule Set.</span>';
        }else if ($pagid = $this->_getWriteAdapter()->fetchOne($select)) {
            $query = $this->_getReadAdapter()->select()
                ->from(array('store_table' => 'landingpage_page_store'), 'page_attribute_value')
                ->where('store_table.page_id = ?', $pagid)
                ->where('store_table.page_attribute_name = ?', 'url_key')
                ->limit(1);
            $url_suffix = Mage::getStoreConfig('landingpage/seo/url_suffix');
            $url_key = $this->_getWriteAdapter()->fetchOne($query);
            $pageurl = $baseurl.$url_key.$url_suffix;
            echo '<span class=\'error-msg\' style=\'color:red\'>&nbsp;&nbsp;&nbsp;A page already exists with this Rule Set. <a target=\'_blank\' href=\''.$pageurl.'\'>View Page</a></span>';
        }else{
            echo '<span class=\'success-msg\' style=\'color:#02650E\'>&nbsp;&nbsp;&nbsp;Success.</span>';
        }
    }
}
