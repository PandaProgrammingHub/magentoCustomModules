<?php

/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */
class Fedobe_Landingpage_Adminhtml_Landingpage_PageController extends Fedobe_Landingpage_Controller_Adminhtml_Abstract {

    /**
     * Forward the request to the dashboard
     *
     * @return $this
     */
    public function indexAction() {
        return $this->_redirect('*/landingpage');
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
        $this->_setActiveMenu('landingpage');

        $this->_title('Fedobe');
        $this->_title('Landing Page');
        $this->_title($this->__('Page'));

        if ($splash) {
            $this->_title($splash->getName());
        }
        //Here to remove the store switcher while adding new landing page
        if (!Mage::registry('splash_page'))
            $this->getLayout()->getBlock('left')->unsetChild('store_switcher');

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
                            if (is_array($v)) {
                                $v = implode(',', $v);
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
                if (is_array($v['value'])) {
                    $v['value'] = implode(',', $v['value']);
                }
                $filerarr[] = (isset($v['aggregator'])) ? "{$v['aggregator']}--{$v['value']}" : "{$v['attribute']}--{$v['operator']}--{$v['value']}";
            }
            sort($filerarr);
            $data['filter_rules'] = implode("--", $filerarr);
            /* End of unique filters */
            $page_id = $this->getRequest()->getParam('id');
            $page = Mage::getModel('landingpage/page')
                    ->setData($data)
                    ->setId($this->getRequest()->getParam('id'));
            try {
                $this->_handleImageUpload($page, 'image');
                $this->_handleImageUpload($page, 'thumbnail');
                $page->save();
                $this->_getSession()->addSuccess($this->__('Landing page was saved'));
                $this->_initValidAttributeSessionMessage();
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__($e->getMessage()));
            }
	    if($this->getRequest()->getParam('id')) {
                $page_id = $this->getRequest()->getParam('id');
            }else{
                $page_id = Mage::registry('page_id');
            }
            $storeId = $this->getRequest()->getParam('store');
            if ($page_id && $this->getRequest()->getParam('back', false)) {
                return $this->_redirect('*/*/edit', array('id' => $page_id, 'store' => $storeId));
            }

            return $this->_redirect('*/landingpage');
        }
        $this->_getSession()->addError($this->__('There was no data to save.'));
        return $this->_redirect('*/landingpage');
    }

    /**
     * Delete a splash page
     *
     */
    public function deleteAction() {
        if ($pageId = $this->getRequest()->getParam('id')) {
            $splashPage = Mage::getModel('landingpage/page')->load($pageId);

            if ($splashPage->getId()) {
                try {
                    $splashPage->delete();
                    $this->_getSession()->addSuccess($this->__('The Splash Page was deleted.'));
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/landingpage');
    }

    public function massDeleteAction() {
        $pageIds = $this->getRequest()->getParam('page');

        if (!is_array($pageIds)) {
            $this->_getSession()->addError($this->__('Please select page(s).'));
        } else {
            if (!empty($pageIds)) {
                try {
                    foreach ($pageIds as $pageId) {
                        $page = Mage::getSingleton('landingpage/page')->load($pageId);

                        if ($page->getId()) {
                            Mage::dispatchEvent('landingpage_controller_page_delete', array('splash_page' => $page, 'page' => $page));

                            $page->delete();
                        }
                    }

                    $this->_getSession()->addSuccess($this->__('Total of %d record(s) have been deleted.', count($pageIds)));
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/landingpage');
    }

    /**
     * Initialise the splash page model
     *
     * @return false|Fedobe_Landingpage_Model_Page
     */
    protected function _initSplashPage() {
        if (($page = Mage::registry('splash_page')) !== null) {
            return $page;
        }
        if ($id = $this->getRequest()->getParam('id')) {
            $page = Mage::getModel('landingpage/page')->load($id);

            //Here let's fetch details from landing page store
            $store_select = $this->_getReadAdapter()->select()
                    ->from(array('page_store' => Mage::getSingleton('core/resource')->getTableName('landingpage/page_store')))
                    ->where("`page_store`.`page_id` = ?", $id);
            $page_attrdata = $this->_getReadAdapter()->fetchAll($store_select);
            $store_id = (Mage::app()->getRequest()->getParam('store')) ? Mage::app()->getRequest()->getParam('store') : Mage::app()->getStore()->getId();
            $defafult_store_id = Mage::app()->getStore()->getId();
            $formatattrdata = array();
            foreach ($page_attrdata as $k => $v) {
                $formatattrdata[$v['page_attribute_name']][$v['store_id']] = $v['page_attribute_value'];
		$formatattrdata[$v['page_attribute_name']]['is_default'][$v['store_id']] = $v['is_default'];
            }
            //Here let's set data to currant page object
            foreach ($formatattrdata as $k => $v) {
                $page->setData($k, ((array_key_exists($store_id, $v)) ? $formatattrdata[$k][$store_id] : $formatattrdata[$k][$defafult_store_id]));
            }
            //Here let's set the custom formatted data into page object for future use
            $page->setData('helperdata', $formatattrdata);
            if ($page->getId()) {
                Mage::register('splash_page', $page);
                return $page;
            }
        }

        return false;
    }

    protected function _handleImageUpload(Fedobe_Landingpage_Model_Page $page, $field) {
        $data = $page->getData($field);
        if (isset($data['value'])) {
            $page->setData($field, $data['value']);
        }

        if (isset($data['delete']) && $data['delete'] == '1') {
            $page->setData($field, '');
        }

        if ($filename = Mage::helper('landingpage/image')->uploadImage($field)) {
            $page->setData($field, $filename);
        }
    }

    protected function _getReadAdapter() {
        return Mage::getSingleton('core/resource')->getConnection('core_read');
    }

    protected function _getWriteAdapter() {
        return Mage::getSingleton('core/resource')->getConnection('core_write');
    }

    public function checkuniqueurlkeyAction() {
        $url_key = Mage::app()->getRequest()->getParam('url_key');
        Mage::getResourceModel('landingpage/page')->duplicateurl($url_key,1);
    }

    public function checkRuleUniquenessAction() {
        $data = array();
        $pageId = $this->getRequest()->getPost('pageid');
	$url = $this->getRequest()->getPost('url');
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
                        if (is_array($v)) {
                            $v = implode(',', $v);
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
            if (is_array($v['value'])) {
                $v['value'] = implode(',', $v['value']);
            }
            $filerarr[] = (isset($v['aggregator'])) ? "{$v['aggregator']}--{$v['value']}" : "{$v['attribute']}--{$v['operator']}--{$v['value']}";
        }
        sort($filerarr);
        $filter_rules = implode("--", $filerarr);
        Mage::getResourceModel('landingpage/page')->checkUniqueRule($filter_rules,$pageId,$url);        
    }

    public function getFilterRulesAction() {
        $condition_rules = $this->getRequest()->getPost('rule');
        $arr = array();
        $data = array();
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
                        if (is_array($v)) {
                            $v = implode(',', $v);
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
            if (is_array($v['value'])) {
                $v['value'] = implode(',', $v['value']);
            }
            $filerarr[] = (isset($v['aggregator'])) ? "{$v['aggregator']}--{$v['value']}" : "{$v['attribute']}--{$v['operator']}--{$v['value']}";
        }
        sort($filerarr);
        $filter_rules = implode("--", $filerarr);
        print_r($filter_rules);
    }
}
