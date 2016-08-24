<?php

/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */
class Fedobe_AttributeSplash_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract {

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
        $observer->getEvent()->getFront()->addRouter('attributeSplash', $this);
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
        $page = Mage::getModel('attributeSplash/page')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->loadByUrlKey($urlKey);

        if (!$page->getId() || !$page->isEnabled()) {
            return false;
        }

        Mage::register('splash_page', $page);

        $request->setModuleName($this->_getFrontName())
                ->setControllerName('page')
                ->setActionName('view')
                ->setParam('page_id', $page->getId());

        $request->setAlias(
                Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, $urlKey . Mage::getStoreConfig('attributeSplash/seo/url_suffix')
        );

        Mage::helper('attributeSplash')->clearLayerRewrites();

        return true;
    }

    /**
     * Retrieve the frontName used by the module
     *
     * @return string
     */
    protected function _getFrontName() {
        return (string) Mage::getConfig()->getNode()->frontend->routers->attributeSplash->args->frontName;
    }

    /**
     * Prepare the path info variable
     *
     * @param string $pathInfo
     * @return false|string
     */
    protected function _preparePathInfo($pathInfo) {
        $requestUri = ltrim($pathInfo, '/');
        if (($urlSuffix = rtrim(Mage::getStoreConfig('attributeSplash/seo/url_suffix'), '/')) !== '') {
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
