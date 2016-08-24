<?php

class Fedobe_Manufacturers_Block_List extends Mage_Catalog_Block_Product_Abstract {

    public function __construct() {
        parent::__construct();
        if (!$this->getTemplate()) {
            $this->setTemplate('fedobe/manufacturers/default.phtml');
        }
    }

    public function getManufacturers($show_products_count_param, $showonlystickers) {
        if (is_null($this->_allManufacturers)) {
            $englishnames = array();
            $manu_code = Mage::getStoreConfig('manufacturers/general/attr_code');
            $manu_code = ($manu_code) ? $manu_code : "manufacturer";
            $img_width = Mage::getStoreConfig('manufacturers/brand_view/brand_page_image_width');
            $img_hgt = Mage::getStoreConfig('manufacturers/brand_view/brand_page_image_height');
            $img_aspratio = Mage::getStoreConfig('manufacturers/brand_view/brand_page_image_aspect_ratio');
            $storeId = (int) Mage::app()->getStore()->getStoreId();
            if (empty($manu_code))
                return array();
            $attribute = Mage::getModel('catalog/product')->getResource()->getAttribute($manu_code);
            $attri_id = $attribute->getAttributeId();
            if (!$attri_id)
                return array();
            $storeCode = Mage::app()->getStore()->getCode();
            if ($storeCode == 'ar') {
                $englishnames = $this->getEnglishDisplayName($attri_id);
            }
            $collection = Mage::getResourceModel('landingpage/page_collection');
            $brandselect = $collection->getSelect()->where("main_table.is_featured = 1");
            $brand_pagedata = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($brandselect);
            $store_id = (int) Mage::app()->getStore()->getId();
            if (empty($brand_pagedata)) {
                return array();
            } else {
                $pageids = array();
                foreach ($brand_pagedata as $k => $v) {
                    $pageids[] = $v['page_id'];
                }
            }
            //Here let's fetch details from landing page store
            $store_select = Mage::getSingleton('core/resource')->getConnection('core_read')->select()
                    ->from(array('page_store' => Mage::getSingleton('core/resource')->getTableName('landingpage/page_store')), 'page_id')
                    ->where("`page_store`.`page_id` IN (?) ", $pageids)
                    ->where("`page_store`.`page_attribute_name` = ? ", 'is_enabled')
                    ->where("`page_store`.`store_id` IN (?)", array($storeId, 0))
                    ->where("`page_store`.`page_attribute_value` = ? ", '1');
            $brand_page_enabled_data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($store_select);
            if (empty($brand_page_enabled_data)) {
                return array();
            } else {
                $finalpageids = array();
                foreach ($brand_page_enabled_data as $k => $v) {
                    $finalpageids[$v['page_id']] = $v['page_id'];
                }
                $collection = Mage::getResourceModel('landingpage/page_collection');
                $collection->getSelect()->where("main_table.page_id  IN (?)", $finalpageids);
            }
            //echo $collection->getSelect()->__toString();exit;
            //$collection->getSelect()->where("store_id = $storeId OR store_id = 0");
            if (!count($collection))
                return array();
            $preparedarr = $onlytoshow = $stickerfilter = array();
            $onlytoshow = explode(",", $showonlystickers);
            $i = 0;
            foreach ($collection as $brandpagemodel) {

                $store_select = Mage::getSingleton('core/resource')->getConnection('core_read')->select()
                        ->from(array('page_store' => Mage::getSingleton('core/resource')->getTableName('landingpage/page_store')))
                        ->where("`page_store`.`page_id` = ? ", $brandpagemodel->getId());
                $page_attrdata = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($store_select);
                $store_id = (int) Mage::app()->getStore()->getId();
                $formatattrdata = array();
                foreach ($page_attrdata as $k => $v) {
                    $formatattrdata[$v['page_attribute_name']][$v['store_id']] = $v['page_attribute_value'];
                }
                //Here let's set data to currant page object
                foreach ($formatattrdata as $k => $v) {
                    $brandpagemodel->setData($k, ((array_key_exists($store_id, $v)) ? $formatattrdata[$k][$store_id] : $formatattrdata[$k][0]));
                }
                $tmdata = $brandpagemodel->getData();
                $url = Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_SECURE_BASE_URL, $store_id) . $brandpagemodel->getUrlKey();// . Mage::helper('landingpage')->getUrlSuffix();
                $preparedarr['org'][$i]['value'] = $tmdata['option_id'];
                $preparedarr['org'][$i]['label'] = $tmdata['display_name'];
                if ($img_aspratio) {
                    $preparedarr['org'][$i]['image'] = Mage::helper('landingpage/image')->init($brandpagemodel, 'thumbnail')->constrainOnly(false)->keepAspectRatio(true)->keepFrame(false)->resize($img_width, $img_hgt);
                } else {
                    $preparedarr['org'][$i]['image'] = Mage::helper('landingpage/image')->init($brandpagemodel, 'thumbnail')->constrainOnly(false)->keepAspectRatio(false)->keepFrame(false)->resize($img_width, $img_hgt);
                }
                $preparedarr['org'][$i]['url'] = $url;
                $sticker = (in_array($tmdata['image_option_label'], $onlytoshow)) ? $tmdata['image_option_label'] : '';
                $preparedarr['org'][$i]['sticker'] = $sticker;
                //Here let's prepare the data for filter
                $atozarray = range('A', 'Z');

                $dispaly_name = (isset($englishnames[$tmdata['option_id']])) ? $englishnames[$tmdata['option_id']] : $tmdata['display_name'];
                $firstLetter = strtoupper(substr(trim($dispaly_name), 0, 1));

                $flgone = in_array($firstLetter, $atozarray);
                $flagtow = is_numeric($firstLetter);
                if ($flgone) {
                    $preparedarr['org'][$i]['class'] = $firstLetter;
                    $preparedarr['formated'][$firstLetter][] = trim($tmdata['display_name']);
                    $filterclass = $firstLetter;
                }
                if ($flagtow) {
                    $preparedarr['org'][$i]['class'] = "0-9";
                    $preparedarr['formated']["0-9"][] = trim($tmdata['display_name']);
                    $filterclass = "0-9";
                }
                $preparedarr['org'][$i]['class'] = $filterclass;
                if ($sticker)
                    $preparedarr['labels'][] = $sticker;
                $atosearchkey = $this->doclean(trim(strtolower($tmdata['display_name'])));
                $preparedarr['autosearch'][$atosearchkey] = trim($tmdata['display_name']);
                // product count
                if ($show_products_count_param) {
                    $pcollection = Mage::getModel('catalog/product')->getCollection()
                            ->addAttributeToFilter($manu_code, array('eq' => (int) $tmdata['option_id']))
                            ->load();
                    // count as same as Magento.
                    $allBundles = array();
                    foreach ($pcollection as $pr) {
                        $bundles = Mage::getResourceModel('bundle/selection')->getParentIdsByChild($pr->getId());
                        $allBundles = array_unique(array_merge($allBundles, $bundles));
                    }
                    $preparedarr['org'][$i]['product_count'] = $pcollection->count() + count($allBundles);
                }
                $i++;
            }
            sort($preparedarr['labels']);
            ksort($preparedarr['formated']);
            $this->_allManufacturers = $preparedarr;
        }
        return $this->_allManufacturers;
    }

    protected function _beforeToHtml() {
        return parent::_beforeToHtml();
    }

    private function _storeFilterData() {
        $storeId = Mage::app()->getStore()->getStoreId();
        $manuids = Mage::getModel('manufacturers/fedobemanufacturer')->getStoreFilterManufacturerIds($storeId);
        return $manuids;
    }

    public function getEnglishDisplayName($attri_id) {
        $storeId = 1;
        $engnames = array();
        $collection = Mage::getResourceModel('landingpage/page_collection');
        $collection->getSelect()->group('main_table.page_id');
        $collection->getSelect()->where("main_table.attribute_id = $attri_id");
        $collection->getSelect()->where("main_table.is_enabled = 1");
        $collection->getSelect()->where("store_id = $storeId OR store_id = 0");
        if (!count($collection))
            return array();
        foreach ($collection as $brandpagemodel) {
            $tmdata = $brandpagemodel->getData();
            $engnames[$tmdata['option_id']] = trim($tmdata['display_name']);
        }
        return $engnames;
    }

    public function doclean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

}

?>