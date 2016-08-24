<?php

class Fedobe_Barter_Manufacturers_Block_List extends Mage_Catalog_Block_Product_Abstract {

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
            $storeId = Mage::app()->getStore()->getStoreId();
            if (empty($manu_code))
                return array();
            $attribute = Mage::getModel('catalog/product')->getResource()->getAttribute($manu_code);
            $attri_id = $attribute->getAttributeId();
            if (!$attri_id)
                return array();
            $storeCode = Mage::app()->getStore()->getCode();
            if($storeCode=='ar'){
                $englishnames = $this->getEnglishDisplayName($attri_id);
            }
            $collection = Mage::getResourceModel('attributeSplash/page_collection');
            $collection->getSelect()->group('main_table.page_id');
            //$collection->getSelect()->where("main_table.attribute_id = $attri_id");
            $collection->getSelect()->where("main_table.is_enabled = 1");
	    $collection->getSelect()->where("main_table.is_featured = 1");


            $collection->getSelect()->join( array('attrpage_store'=> 'attributesplash_page_store'), 'attrpage_store.page_id = main_table.page_id');
   $collection->getSelect()->where("attrpage_store.store_id = $storeId OR attrpage_store.store_id = 0");

            //$collection->getSelect()->where("store_id = $storeId OR store_id = 0");
            if (!count($collection))
                return array();
            $preparedarr = $onlytoshow = $stickerfilter = array();
            $onlytoshow = explode(",", $showonlystickers);
            $i = 0;
            foreach ($collection as $brandpagemodel) {
                $tmdata = $brandpagemodel->getData();
                $url = $brandpagemodel->getUrl();
                $preparedarr['org'][$i]['value'] = $tmdata['option_id'];
                $preparedarr['org'][$i]['label'] = $tmdata['display_name'];
                if ($img_aspratio) {
                    $preparedarr['org'][$i]['image'] = Mage::helper('attributeSplash/image')->init($brandpagemodel, 'thumbnail')->constrainOnly(false)->keepAspectRatio(true)->keepFrame(false)->resize($img_width, $img_hgt);
                } else {
                    $preparedarr['org'][$i]['image'] = Mage::helper('attributeSplash/image')->init($brandpagemodel, 'thumbnail')->constrainOnly(false)->keepAspectRatio(false)->keepFrame(false)->resize($img_width, $img_hgt);
                }
                $preparedarr['org'][$i]['url'] = $url;
                $sticker = (in_array($tmdata['option_label'], $onlytoshow)) ? $tmdata['option_label'] : '';
                $preparedarr['org'][$i]['sticker'] = $sticker;
                //Here let's prepare the data for filter
                $atozarray = range('A', 'Z');
                
                $dispaly_name = (isset($englishnames[$tmdata['option_id']])) ? $englishnames[$tmdata['option_id']] : $tmdata['display_name'] ;
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
                $preparedarr['autosearch'][] = trim($tmdata['display_name']);
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
        $collection = Mage::getResourceModel('attributeSplash/page_collection');
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

}

?>
