<?php

/**
 * @category    Fedobe
 * @package    Fedobe_Landingpage
 */
class Fedobe_Landingpage_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract {

    /**
     * Cache for request object
     *
     * @var Zend_Controller_Request_Http
     */
    protected $_request = null;

    /**
     * Initialize Controller Router
     *
     * @param Varien_Event_Observer $observer
     */
    public function initControllerRouters(Varien_Event_Observer $observer) {
        $observer->getEvent()->getFront()->addRouter('landingpage', $this);
    }

    /**
     * Get the request object
     *
     * @return Zend_Controller_Request_Http
     */
    public function getRequest() {
        return $this->_request;
    }

    /**
     * Validate and Match Cms Page and modify request
     *
     * @param Zend_Controller_Request_Http $request
     * @return bool
     */
    public function match(Zend_Controller_Request_Http $request) {
        $urlKey = $this->_preparePathInfo($request->getPathInfo());
        //Here let's find out the page that to be loaded from url kry
        //Here let's check with own store table
        $urlownselect = Mage::getSingleton('core/resource')->getConnection('core_read')->select()
                ->from(array('page_store_table' => Mage::getSingleton('core/resource')->getTableName('landingpage/page_store')), 'page_id')
                ->where('page_store_table.page_attribute_value = ?', $urlKey)
                ->where('page_store_table.page_attribute_name = ? ', 'url_key')
                ->where('page_store_table.store_id IN (?) ', array(0, (int) Mage::app()->getStore()->getId()))
                ->limit(1);
        if ($page_id = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchOne($urlownselect)) {
            $page = Mage::getModel('landingpage/page')->load($page_id);
            
            
            
            
            //Here let's fetch details from landing page store
            $store_select = Mage::getSingleton('core/resource')->getConnection('core_read')->select()
                    ->from(array('page_store' => Mage::getSingleton('core/resource')->getTableName('landingpage/page_store')))
                    ->where("`page_store`.`page_id` = ? ", $page_id);
            $page_attrdata = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($store_select);
            $store_id = (int) Mage::app()->getStore()->getId();
            $formatattrdata = array();
            foreach ($page_attrdata as $k => $v) {
                $formatattrdata[$v['page_attribute_name']][$v['store_id']] = $v['page_attribute_value'];
            }
            //Here let's set data to currant page object
            foreach ($formatattrdata as $k => $v) {
                $page->setData($k, ((array_key_exists($store_id, $v)) ? $formatattrdata[$k][$store_id] : $formatattrdata[$k][0]));
            }
            if (!$page->getId() || !$page->isEnabled()) {
                return false;
            }
            Mage::register('splash_page', $page);
            $request->setModuleName($this->_getFrontName())
                    ->setControllerName('page')
                    ->setActionName('view')
                    ->setParam('page_id', $page->getId());

            $request->setAlias(
                    Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, $urlKey . Mage::getStoreConfig('landingpage/seo/url_suffix')
            );
            Mage::helper('landingpage')->clearLayerRewrites();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Retrieve the frontName used by the module
     *
     * @return string
     */
    protected function _getFrontName() {
        return (string) Mage::getConfig()->getNode()->frontend->routers->landingpage->args->frontName;
    }

    /**
     * Prepare the path info variable
     *
     * @param string $pathInfo
     * @return false|string
     */
    protected function _preparePathInfo($pathInfo) {
        $requestUri = ltrim($pathInfo, '/');
	$isBrandQuery = Mage::getSingleton('core/resource')->getConnection('core_read')->select()
                    ->from(array('page_store_table' => Mage::getSingleton('core/resource')->getTableName('landingpage/page_store')), 'page_id')
                    ->join(array('landingpage_page_table' => 'landingpage_page'),"page_store_table.page_id=landingpage_page_table.page_id",array())
                    ->where('page_store_table.page_attribute_value = ?', $requestUri)
                    ->where('page_store_table.page_attribute_name = ? ', 'url_key')
                    ->where('landingpage_page_table.is_featured = ? ', 1)
                    ->where('page_store_table.store_id IN (?) ', array(0, (int) Mage::app()->getStore()->getId()))
                    ->limit(1);
        $pageId = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchOne($isBrandQuery);
        if($pageId){
            return $requestUri;
        }
        if (($urlSuffix = rtrim(Mage::getStoreConfig('landingpage/seo/url_suffix'), '/')) !== '') {
            if (substr($requestUri, -strlen($urlSuffix)) !== $urlSuffix) {
                if (substr($pathInfo, -4) === '.xml') {
                    return $pathInfo;
                }

                return false;
            }
            $requestUri = substr($requestUri, 0, -strlen($urlSuffix));
        }
        return $requestUri;
    }

}
