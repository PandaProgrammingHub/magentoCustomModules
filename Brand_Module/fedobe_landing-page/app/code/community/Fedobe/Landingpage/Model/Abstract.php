<?php
/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */

abstract class Fedobe_Landingpage_Model_Abstract extends Mage_Core_Model_Abstract {

    /**
     * Retrieve the name of the splash page
     * If display name isn't set, option value label will be returned
     *
     * @return string
     */
    public function getName() {
        return $this->getDisplayName() ? $this->getDisplayName() : $this->getFrontendLabel();
    }

    /**
     * Retrieve the URL for the splash page
     * If cannot find rewrite, return system URL
     *
     * @return string
     */
    protected function _getUrl($uri) {

           if (Mage::app()->getRequest()->getParam('store')) {
               $store_id = Mage::app()->getRequest()->getParam('store');
           } else {
               $store_id = $this->getStoreId();
           }
           $url = Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_SECURE_BASE_URL, $store_id);

           if(Mage::getStoreConfig('web/url/use_store', $store_id)){
              if($store_id == 0){
                   foreach (Mage::app()->getWebsites() as $website) {
                       $iDefaultStoreId = Mage::app()
                                       ->getWebsite($website->getData('website_id'))
                                       ->getDefaultGroup()
                                       ->getDefaultStoreId();
                   }
                   $storeinfo = Mage::getModel('core/store')->load($iDefaultStoreId);
                   $store_code = $storeinfo->getCode();
               }else{
                   $storeinfo = Mage::getModel('core/store')->load($store_id);
                   $store_code = $storeinfo->getCode();
                   }
                   $url = $url.$store_code."/";
               }

           if (!$url) {
               $url = Mage::getUrl('', array(
                           '_direct' => $uri,
                           '_secure' => false,
                           '_nosid' => true,
                           '_store' => $store_id,
               ));
           }
           if ($store_id === 0 || Mage::getStoreConfigFlag('web/seo/use_rewrites')) {
               $url = str_replace('/' . basename($_SERVER['SCRIPT_FILENAME']), '', $url);
           }

           if (Mage::getStoreConfigFlag('web/url/use_store') && $store_id === 0) {
               $url = str_replace('/admin/', '/', $url);
           }

           return $url;
       }

    /**
     * Retrieve the URL Base
     * This is used for url_key field in the Admin
     *
     * @return string
     */
    public function getUrlBase() {
        return $this->_getUrl();
    }

    /**
     * Retrieve the URL suffix from the config
     *
     * @return string
     */
    static public function getUrlSuffix() {
        return Mage::getStoreConfig('landingpage/seo/url_suffix');
    }

    /**
     * Retrieve the description
     * If $process is true, output will be filtered
     *
     * @param bool $process = true
     * @return string
     */
    public function getDescription($process = true) {
        if ($process) {
            return Mage::helper('cms')->getBlockTemplateProcessor()->filter($this->getData('description'));
        }

        return $this->getData('description');
    }

    /**
     * Retrieve the short_description
     *
     * @return string
     */
    public function getShortDescription() {
        return Mage::helper('cms')->getBlockTemplateProcessor()->filter($this->getData('short_description'));
    }

    /**
     * Retrieve the Meta description.
     * If empty, use the short description
     *
     * @return string
     */
    public function getMetaDescription() {
        return $this->getData('meta_description') ? $this->getData('meta_description') : strip_tags($this->getShortDescription());
    }

     /**
     * Retrieve the No Indexing Information.
     *
     * @return string
     */
    public function getIsIndexd() {
        return $this->getData('set_index') ? "INDEX,FOLLOW" : "NOINDEX,FOLLOW";
    }

    /**
     * Retrieve the date/time the item was updated
     *
     * @param bool $includeTime = true
     * @return string
     */
    public function getUpdatedAt($includeTime = true) {
        if ($str = $this->_getData('updated_at')) {
            return $includeTime ? $str : trim(substr($str, 0, strpos($str, ' ')));
        }

        return '';
    }

    /**
     * Retrieve the date/time the item was created
     *
     * @param bool $includeTime = true
     * @return string
     */
    public function getCreatedAt($includeTime = true) {
        if ($str = $this->_getData('created_at')) {
            return $includeTime ? $str : trim(substr($str, 0, strpos($str, ' ')));
        }

        return '';
    }

    /**
     * Retrieve the menu node ID
     *
     * @return string
     */
    public function getMenuNodeId() {
        return 'splash-' . substr($this->_resourceName, strpos($this->_resourceName, '/') + 1) . $this->getId();
    }

    /**
     * Retrieve the store ID of the splash page
     * This isn't always the only store it's associated with
     * but the current store ID
     *
     * @return int
     */
    public function getStoreId() {
        if (!$this->hasStoreId() || (int) $this->_getData('store_id') === 0) {
            return (int) Mage::app()->getStore(true)->getId();
        }

        return (int) $this->_getData('store_id');
    }

    /**
     * Determine whether object has a store ID of 0
     *
     * @return bool
     */
    public function isGlobal() {
        if ($storeIds = $this->getStoreIds()) {
            foreach ($storeIds as $storeId) {
                if ((int) $storeId === 0) {
                    return true;
                }
            }
        }

        return false;
    }

}
