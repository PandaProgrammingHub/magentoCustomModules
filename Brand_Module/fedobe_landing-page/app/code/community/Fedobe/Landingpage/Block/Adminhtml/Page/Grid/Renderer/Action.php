<?php
/**
 * @category    Fedobe
 * @package     Fedobe_Collection
 * @license     http://fedobe.com
 * @author      Fedobe Team <support@fedobe.com>
 */

class Fedobe_Landingpage_Block_Adminhtml_Page_Grid_Renderer_Action
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        if (Mage::app()->getRequest()->getParam('store')) {
            $store_id = Mage::app()->getRequest()->getParam('store');
        } else {
            $store_id = 0;
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
        if ($store_id === 0 || Mage::getStoreConfigFlag('web/seo/use_rewrites')) {
           $url = str_replace('/' . basename($_SERVER['SCRIPT_FILENAME']), '', $url);
        }
        if (Mage::getStoreConfigFlag('web/url/use_store') && $store_id === 0) {
           $url = str_replace('/admin/', '/', $url);
        }
        $url_key = $row->getUrl_key();
        $url_suffix = Mage::getStoreConfig('landingpage/seo/url_suffix') ?
                        Mage::getStoreConfig('landingpage/seo/url_suffix') : '';
        $url = $url.$url_key.$url_suffix;
        return '<a href="'.$url.'" target="_blank">'.$this->__('Preview').'</a>';
    }
}
