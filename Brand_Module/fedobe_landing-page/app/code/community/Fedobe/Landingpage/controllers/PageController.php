<?php

/**
 * @category    Fedobe
 * @package     Fedobe__Landingpage
 */
class Fedobe_Landingpage_PageController extends Mage_Core_Controller_Front_Action {

    /**
     * Display the splash page
     *
     * @return void
     */
    public function viewAction() {
        if (($splashPage = $this->_initSplashPage()) === false) {
            return $this->_forward('noRoute');
        }

        // Register the splash layer model
        Mage::register('current_layer', Mage::getSingleton('landingpage/layer'));
        $this->_applyCustomViewLayout($splashPage);
        if ($rootBlock = $this->getLayout()->getBlock('root')) {
            $rootBlock->addBodyClass('splash-page-' . $splashPage->getId());
            $rootBlock->addBodyClass('splash-page-' . $splashPage->getAttributeCode());
        }
        if ($headBlock = $this->getLayout()->getBlock('head')) {
            if ($title = $splashPage->getPageTitle()) {
                $headBlock->setTitle($title);
            } else {
                $this->_title($splashPage->getName());
            }
            if ($description = $splashPage->getMetaDescription()) {
                $headBlock->setDescription($description);
            }
            if ($index = $splashPage->getIsIndexd()) {
                $headBlock->setRobots($index);
            }
            if ($keywords = $splashPage->getMetaKeywords()) {
                $headBlock->setKeywords($keywords);
            }
            $headBlock->addItem('link_rel', $splashPage->getUrl(), 'rel="canonical"');
        }
        if ($breadBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            if (!$breadBlock->getSkipSplashPageHomeCrumb()) {
                $breadBlock->addCrumb('home', array('label' => $this->__('Home'), 'title' => $this->__('Home'), 'link' => Mage::getUrl()));
            }
            if (!$breadBlock->getSkipSplashPageCrumb()) {
                $breadBlock->addCrumb('splash_page', array('label' => $splashPage->getName(), 'title' => $splashPage->getName()));
            }
        }

        $this->renderLayout();
    }

    /**
     * Apply custom layout handles to the splash page
     *
     * @param Fedobe_AttribtueSplash_Model_Page $splashPage
     * @return Fedobe_AttribtueSplash_PageController
     */
    protected function _applyCustomViewLayout(Fedobe_Landingpage_Model_Page $splashPage) {
        $update = $this->getLayout()->getUpdate();

        $update->addHandle('default');

        $this->addActionLayoutHandles();

        $update->addHandle('landingpage_page_view_' . $splashPage->getId());
        $this->loadLayoutUpdates();
        $update->addUpdate($splashPage->getLayoutUpdateXml());
        $this->generateLayoutXml()->generateLayoutBlocks();
        if ($splashPage->getPageLayout()) {
            $this->getLayout()->helper('page/layout')->applyTemplate($splashPage->getPageLayout());
        } else if ($pageLayout = Mage::getStoreConfig('landingpage/page/template')) {
            $this->getLayout()->helper('page/layout')->applyTemplate($pageLayout);
        }
        $this->_isLayoutLoaded = true;
        return $this;
    }

    /**
     * Initialise the Splash Page model
     *
     * @return false|Fedobe__Landingpage_Model_Page
     */
    protected function _initSplashPage() {
        if (($page = Mage::registry('splash_page')) !== null) {
            return $page;
        }

        $splashPage = Mage::getModel('landingpage/page')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load((int) $this->getRequest()->getParam('id', false));

        if (!$splashPage->getIsEnabled()) {
            return false;
        }
        Mage::register('splash_page', $splashPage);

        return $splashPage;
    }

}