<?php

/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */
class Fedobe_AttributeSplash_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Retrieve a splash page for the product / attribute code combination
     *
     * @param Mage_Catalog_Model_Product $product
     * @param $attributeCode
     * @return Fishpig_AttributeSplash_Model_Splash|null
     */
    public function getProductSplashPage(Mage_Catalog_Model_Product $product, $attributeCode) {
        $key = $attributeCode . '_splash_page';

        if (!$product->hasData($key)) {
            $product->setData($key, false);

            $collection = Mage::getResourceModel('attributeSplash/page_collection')
                    ->addStoreFilter(Mage::app()->getStore())
                    ->addAttributeCodeFilter($attributeCode)
                    ->addProductFilter($product)
                    ->setPageSize(1)
                    ->setCurPage(1)
                    ->load();

            if (count($collection) > 0) {
                $page = $collection->getFirstItem();

                if ($page->getId()) {
                    $product->setData($key, $page);
                }
            }
        }

        return $product->getData($key);
    }

    /**
     * Log an error message
     *
     * @param string $msg
     * @return Fishpig_AttributeSplash_Helper_Data
     */
    public function log($msg) {
        Mage::log($msg, false, 'attributeSplash.log', true);

        return $this;
    }

    /**
     * Get the URL suffix
     *
     * @return string
     */
    public function getUrlSuffix() {
        return Mage::getStoreConfig('attributeSplash/seo/url_suffix');
    }

    /**
     * Determine whether the group URL key is used in the page URL
     *
     * @return bool
     */
    public function includeGroupUrlKeyInPageUrl() {
        return Mage::getStoreConfigFlag('attributeSplash/page/include_group_url_key');
    }


    /**
     * Disable the MageWorx_SeoSuite rewrites for the layered navigation
     *
     * @return $this
     */
    public function clearLayerRewrites() {
        Mage::getConfig()->setNode('modules/MageWorx_SeoSuite/active', 'false', true);
        Mage::getConfig()->setNode('global/models/catalog/rewrite/layer_filter_item', null, true);
        Mage::getConfig()->setNode('global/models/catalog/rewrite/layer_filter_attribute', null, true);
        Mage::getConfig()->setNode('global/models/catalog/rewrite/layer_filter_category', null, true);
        Mage::getConfig()->setNode('global/models/catalog_resource/rewrite/layer_filter_item', null, true);
        Mage::getConfig()->setNode('global/models/catalog_resource_eav_mysql4/rewrite/layer_filter_item', null, true);
        Mage::getConfig()->setNode('global/blocks/catalog/rewrite/product_list_toolbar', null, true);
        Mage::getConfig()->setNode('global/blocks/catalog/rewrite/layer_filter_attribute', null, true);

        return $this;
    }

    public function getAllCustomFiltersArr() {
        $customfilters = array(
            'custom_state' => "State"
        );
        return $customfilters;
    }

    public function getAllCustomArr() {
        $customconditions = array(
            "custom_qty" => "Qty",
            "custom_final_price" => "Final Price",
            "custom_stock" => "Stock",
            "product_new_from" => "Product New From Days"
        );
        return $customconditions;
    }

    public function getAllCustomRuleOptions() {
        $alloptions = array();
        $alloptions = array_merge_recursive($this->getAllCustomFiltersArr(), $this->getAllCustomArr());
        return $alloptions;
    }

    public function dropdowntype() {
        return array('custom_stock', 'custom_state');
    }

    public function getStockOptions() {
        $stockopt = array();
        $stockopt[] = array('value' => '0', 'label' => "Does not Matter");
        $stockopt[] = array('value' => '1', 'label' => "In Stock");
        $stockopt[] = array('value' => '2', 'label' => "Out Of Stock");
        return $stockopt;
    }

    public function getStateOptions() {
        $stateopt = array();
        $stateopt[] = array('value' => 'new', 'label' => "New");
        $stateopt[] = array('value' => 'onsale', 'label' => "On Sale");
//        $stateopt[] = array('value' => 'bestseller', 'label' => "Best Sellers");
//        $stateopt[] = array('value' => 'most_viewed', 'label' => "Most Viewed");
//        $stateopt[] = array('value' => 'top_rated', 'label' => "Top Rated");
//        $stateopt[] = array('value' => 'reviews_count', 'label' => "Reviews Count");
//        $stateopt[] = array('value' => 'best_value', 'label' => "Best Value");
        return $stateopt;
    }

}
