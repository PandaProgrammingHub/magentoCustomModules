<?php

/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */
class Fedobe_Landingpage_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Retrieve a splash page for the product / attribute code combination
     *
     * @param Mage_Catalog_Model_Product $product
     * @param $landingpage
     * @return Fedobe_Landingpage_Model_Splash|null
     */
    public function getProductSplashPage(Mage_Catalog_Model_Product $product, $attributeCode) {
        $key = $attributeCode . '_splash_page';

        if (!$product->hasData($key)) {
            $product->setData($key, false);

            $collection = Mage::getResourceModel('landingpage/page_collection')
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
     * @return Fedobe_Landingpage_Helper_Data
     */
    public function log($msg) {
        Mage::log($msg, false, 'landingpage.log', true);

        return $this;
    }

    /**
     * Get the URL suffix
     *
     * @return string
     */
    public function getUrlSuffix() {
        return Mage::getStoreConfig('landingpage/seo/url_suffix');
    }

    /**
     * Determine whether the group URL key is used in the page URL
     *
     * @return bool
     */
    public function includeGroupUrlKeyInPageUrl() {
        return Mage::getStoreConfigFlag('landingpage/page/include_group_url_key');
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

    public function storecheckbox($_element) {
        $store_attr = array('splash_display_name', 
                            'splash_url_key',
                            'splash_is_enabled',
                            'splash_short_description',
                            'splash_description',
                            'splash_image',
                            'splash_thumbnail',
                            'splash_image_option_label',
                            'splash_page_title',
                            'splash_meta_description',
                            'splash_meta_keywords',
                            'splash_page_layout',
                            'splash_layout_update_xml',
                            'splash_display_mode',
                            'splash_cms_block');
        if (in_array($_element->getHtmlId(), $store_attr)){
            $store_lable = "[STORE VIEW]";
            $flag = 1;
        }else{
            $store_lable = "[GLOBAL]";
            $flag = 0;
        }
        $string = '<td class="scope-label"><span class="nobr">' . $store_lable . '</span></td>';
        $store_id = Mage::app()->getRequest()->getParam('store');
        $default_store_id = Mage::app()->getStore()->getId();
        $field_name = trim($_element->getData('name'));
        $required = trim($_element->getData('required'));
        $label = trim($_element->getData('label'));
        $chkdstring = $disabledstring = $jstring = "";
        if($required){
            $jstring .='<script type="text/javascript">'
                    . '$$(\'label[for="'.$_element->getHtmlId().'"]\').first().update(\''.$label.' <span class="required">*</span>\')'
                    . '</script>';
        }
        if(!$flag && $store_id){
            $jstring .='<script type="text/javascript">'
                    . '$('.$_element->getHtmlId().').disable();'
                    . '</script>';
        }
        if (Mage::registry('splash_page') && ($store_id && $flag)) {
            $pagedata = Mage::registry('splash_page')->getData('helperdata');
            $string .= '<td class="value use-default"><input  ';
            if($pagedata[$field_name]['is_default'][$store_id]){
                $chkdstring = 'checked="checked"';
                $jstring .='<script type="text/javascript">'
                . '$('.$_element->getHtmlId().').disable();'
                . 'var data = '.json_encode($pagedata).';'
                . '</script>';
            }
            if ($_element->getReadonly())
                $disabledstring .= 'disabled="disabled"';
            $string .= ' type="checkbox" name="use_default[]" id="' . $_element->getHtmlId() . '_default" '.$disabledstring .$chkdstring.' onclick="Storechecker.doaction(\''.$_element->getHtmlId().'\',this,\''.$default_store_id.'\',\''.$store_id.'\',Object.toJSON(data),\''.$field_name.'\')" value="' . $field_name . '" style="cursor:pointer;"/>';
            $string .= '<label for="' . $_element->getHtmlId() . '_default" class="normal" style="cursor:pointer;">' . $this->__('Use Default Value') . '</label></td>';
        }
        $icon_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'adminhtml/default/default/images/fedobe_landing_page.png';
        $jstring .= '<style type="text/css">
                        .head-adminhtml-page {background-image: url("'.$icon_url.'");}
                    </style>';
        $string .= $jstring ;
        return $string;
    }

}
