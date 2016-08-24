<?php

/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */
class Fedobe_AttributeSplash_Adminhtml_AttributeSplash_PageController extends Fedobe_AttributeSplash_Controller_Adminhtml_Abstract {

    /**
     * Forward the request to the dashboard
     *
     * @return $this
     */
    public function indexAction() {
        return $this->_redirect('*/attributeSplash');
    }

    /**
     * Add a new splash page
     *
     * @return $this
     */
    public function newAction() {
        return $this->_forward('edit');
    }

    /**
     * Display the add/edit form for the splash page
     *
     */
    public function editAction() {
        $splash = $this->_initSplashPage();

        $this->loadLayout();
        $this->_setActiveMenu('attributeSplash');

        $this->_title('FishPig');
        $this->_title('Attribute Splash');
        $this->_title($this->__('Page'));

        if ($splash) {
            $this->_title($splash->getName());
        }

        $this->renderLayout();
    }

    protected function _initValidAttributeSessionMessage() {
        if (($page = $this->_initSplashPage()) !== false) {
            if ($page->getAttributeModel() && !$page->getAttributeModel()->getData('is_filterable')) {
                $page->getAttributeModel()->setIsFilterable(1)->save();
            }
        }

        return $this;
    }

    /**
     * Save the posted data
     *
     */
    public function saveAction() {
        if ($data = $this->getRequest()->getPost('splash')) {
            //Here let add the rules data
            $condition_rules = $this->getRequest()->getPost('rule');
            $arr = array();
            foreach ($condition_rules as $key => $value) {
                if (($key === 'conditions') && is_array($value)) {
                    foreach ($value as $id => $cdata) {
                        $path = explode('--', $id);
                        $node = & $arr;
                        for ($i = 0, $l = sizeof($path); $i < $l; $i++) {
                            if (!isset($node[$key][$path[$i]])) {
                                $node[$key][$path[$i]] = array();
                            }
                            $node = & $node[$key][$path[$i]];
                        }
                        foreach ($cdata as $k => $v) {
                            //check for gender attribute array issue
                            if(is_array($v)){
                              $v = implode(',',$v);
                            }
                            $node[$k] = $v;
                        }
                    }
                }
            }
            $data['conditions_serialized'] = serialize($arr['conditions'][1]);
            //Here to get one uniqune rule filter coloumn
            $filerarr = array();
            foreach ($condition_rules['conditions'] as $k => $v) {
                //check for gender attribute array issue
                if(is_array($v['value'])){
                  $v['value'] = implode(',',$v['value']);
                }
                $filerarr[] = (isset($v['aggregator'])) ? "{$v['aggregator']}--{$v['value']}": "{$v['attribute']}--{$v['operator']}--{$v['value']}";
            }
            sort($filerarr);
            $data['filter_rules'] = implode("--", $filerarr);
            /*End of unique filters*/
            $page = Mage::getModel('attributeSplash/page')
                    ->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

            try {
                $this->_handleImageUpload($page, 'image');
                $this->_handleImageUpload($page, 'thumbnail');

                $page->save();
                $this->_getSession()->addSuccess($this->__('Splash page was saved'));

                $this->_initValidAttributeSessionMessage();
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__($e->getMessage()));
            }

            if ($page->getId() && $this->getRequest()->getParam('back', false)) {
                return $this->_redirect('*/*/edit', array('id' => $page->getId()));
            }

            return $this->_redirect('*/attributeSplash');
        }

        $this->_getSession()->addError($this->__('There was no data to save.'));

        return $this->_redirect('*/attributeSplash');
    }

    /**
     * Delete a splash page
     *
     */
    public function deleteAction() {
        if ($pageId = $this->getRequest()->getParam('id')) {
            $splashPage = Mage::getModel('attributeSplash/page')->load($pageId);

            if ($splashPage->getId()) {
                try {
                    $splashPage->delete();
                    $this->_getSession()->addSuccess($this->__('The Splash Page was deleted.'));
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/attributeSplash');
    }

    public function massDeleteAction() {
        $pageIds = $this->getRequest()->getParam('page');

        if (!is_array($pageIds)) {
            $this->_getSession()->addError($this->__('Please select page(s).'));
        } else {
            if (!empty($pageIds)) {
                try {
                    foreach ($pageIds as $pageId) {
                        $page = Mage::getSingleton('attributeSplash/page')->load($pageId);

                        if ($page->getId()) {
                            Mage::dispatchEvent('attributeSplash_controller_page_delete', array('splash_page' => $page, 'page' => $page));

                            $page->delete();
                        }
                    }

                    $this->_getSession()->addSuccess($this->__('Total of %d record(s) have been deleted.', count($pageIds)));
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/attributeSplash');
    }

    /**
     * Initialise the splash page model
     *
     * @return false|Fishpig_AttributeSplash_Model_Page
     */
    protected function _initSplashPage() {
        if (($page = Mage::registry('splash_page')) !== null) {
            return $page;
        }

        if ($id = $this->getRequest()->getParam('id')) {
            $page = Mage::getModel('attributeSplash/page')->load($id);

            if ($page->getId()) {
                Mage::register('splash_page', $page);
                return $page;
            }
        }

        return false;
    }

    protected function _handleImageUpload(Fishpig_AttributeSplash_Model_Page $page, $field) {
        $data = $page->getData($field);

        if (isset($data['value'])) {
            $page->setData($field, $data['value']);
        }

        if (isset($data['delete']) && $data['delete'] == '1') {
            $page->setData($field, '');
        }

        if ($filename = Mage::helper('attributeSplash/image')->uploadImage($field)) {
            $page->setData($field, $filename);
        }
    }

}
