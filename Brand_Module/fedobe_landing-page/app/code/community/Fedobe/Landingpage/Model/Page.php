<?php

/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */
class Fedobe_Landingpage_Model_Page extends Fedobe_Landingpage_Model_Abstract {

    /**
     * Setup the model's resource
     *
     * @return void
     */
    const STATUS_ENABLED = 1;
    public function _construct() {
        $this->_init('landingpage/page');
    }

    public function loadByUrlKey($urlKey) {
        return $this->load($urlKey, 'url_key');
    }

    /**
     * Retrieve the URL suffix from the config
     *
     * @return string
     */
    static public function getUrlSuffix() {
        return trim(Mage::getStoreConfig('landingpage/seo/url_suffix'));
    }

    /**
	 * Retrieve the URL suffix
	 *
	 * @return string
	 */
	protected function _getUrlSuffix()
	{
		return self::getUrlSuffix();
		return trim(Mage::getStoreConfig('landingpage/seo/url_suffix'));
	}
    /**
     * Retrieve the store ID of the splash page
     * This isn't always the only store it's associated with
     * but the current store ID
     *
     * @return int
     */
    public function getStoreId() {
        if (!$this->hasStoreId()) {
            $this->setStoreId((int) Mage::app()->getStore(true)->getId());
        }

        return (int) $this->_getData('store_id');
    }

    /**
     * Retrieve the URL for the splash page
     * If cannot find rewrite, return system URL
     *
     * @return string
     */
    public function getUrl() {
        if ($this->hasUrl()) {
            return $this->_getData('url');
        }
        $uri = $this->getUrlKey() . $this->getUrlSuffix();
        return $this->_getUrl($uri);
    }

    /**
    * Determine whether the page is enabled
    *
    * @return bool
    */
   public function isEnabled()
   {
           return (int)$this->getIsEnabled() === self::STATUS_ENABLED;
   }
   
    /**
     * Determine whether the model is active
     *
     * @return bool
     */
    public function isActive() {
        return (($page = Mage::registry('splash_page')) !== null) && $page->getId() === $this->getId();
    }

    /**
     * Retrieve the full URL of the splash image
     *
     * @return string
     */
    public function getImage() {
        return Mage::helper('landingpage/image')->getImageUrl($this->getData('image'));
    }

    /**
     * Retrieve the URL for the image
     * This converts relative URL's to absolute
     *
     * @return string
     */
    public function getImageUrl() {
        if ($this->_getData('image_url')) {
            if (strpos($this->_getData('image_url'), 'http://') === false) {
                $this->setImageUrl(Mage::getBaseUrl() . ltrim($this->_getData('image_url'), '/ '));
            }
        }

        return $this->_getData('image_url');
    }

    /**
     * Retrieve the full URL of the splash thumbnail
     *
     * @return string
     */
    public function getThumbnail() {
        return Mage::helper('landingpage/image')->getImageUrl($this->getData('thumbnail'));
    }

    /**
     * Retrieve a collection of products associated with the splash page
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    public function getProductCollection() {
        if (!$this->hasProductCollection()) {
            $this->setProductCollection($this->getResource()->getProductCollection($this));
        }

        return $this->getData('product_collection');
    }
    
    public function getBrandPageDetails($id,$store_id){
        $pagecollection = Mage :: getModel('landingpage/page')->getCollection();
        $pagecollection->getSelect()->from($maintable,"*")->where(" main_table.`is_enabled` = 1 AND main_table.`is_featured`=1 AND main_table.`filter_rules` LIKE '%--$id' AND store_table.`store_id` IN(0,$store_id)");
        return $pagecollection;
    }
}