<?php

class Fedobe_OneStepCheckout_Helper_Data extends Mage_Checkout_Helper_Data
{

    public function getExtensionVersion()
    {
        return (string) @Mage::getConfig()->getNode()->modules->Fedobe_OneStepCheckout->version;
    }


    public function getSubmitUrl()
    {
        return $this->_getUrl('fedobe_onestepcheckout/index/savePost', array('_secure' => true));
    }

    public function getSaveStepUrl()
    {
        return $this->_getUrl('fedobe_onestepcheckout/index/saveStep', array('_secure' => true));
    }

    /**
     * For compatible with 1.4
     * @param $field
     * @return string
     */
    public function getAttributeValidationClass($attributeCode){
        $customerHelper = Mage::helper('customer/address');

        if(method_exists($customerHelper, 'getAttributeValidationClass')){
            return $customerHelper->getAttributeValidationClass($attributeCode);
        }else{
            $attribute = isset($this->_attributes[$attributeCode]) ? $this->_attributes[$attributeCode]
                : Mage::getSingleton('eav/config')->getAttribute('customer_address', $attributeCode);
            $class = $attribute ? $attribute->getFrontend()->getClass() : '';

            if (in_array($attributeCode, array('firstname', 'middlename', 'lastname', 'prefix', 'suffix', 'taxvat'))) {
                if ($class && !$attribute->getIsVisible()) {
                    $class = ''; // address attribute is not visible thus its validation rules are not applied
                }


                /** @var $customerAttribute Mage_Customer_Model_Attribute */
                $customerAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', $attributeCode);
                $class .= $customerAttribute && $customerAttribute->getIsVisible()
                    ? $customerAttribute->getFrontend()->getClass() : '';
                $class = implode(' ', array_unique(array_filter(explode(' ', $class))));
            }


            return $class;
        }

    }
    const XML_PATH_VAT_FRONTEND_VISIBILITY = 'customer/create_account/vat_frontend_visibility';
    public function isVatAttributeVisible()
    {
        $customerHelper = Mage::helper('customer/address');
        if(method_exists($customerHelper, 'isVatAttributeVisible')){
            return $customerHelper->isVatAttributeVisible();
        }else{
            return (bool)Mage::getStoreConfig(self::XML_PATH_VAT_FRONTEND_VISIBILITY);
        }

    }

    public function getGeoIpUrl(){
        return $this->_getUrl('fedobe_onestepcheckout/service/getGeoIp', array('_secure' => true));
    }
}