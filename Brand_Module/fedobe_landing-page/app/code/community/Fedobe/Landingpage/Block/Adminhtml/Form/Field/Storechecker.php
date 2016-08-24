<?php

class Fedobe_Landingpage_Block_Adminhtml_Form_Field_Storechecker extends Mage_Adminhtml_Block_System_Config_Form_Field {

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        if ($type = $this->getSplashType()) {
            if (($object = Mage::registry('splash_' . $type)) !== null) {
                $this->setSplashBaseUrl($object->getUrlBase());
                $this->setUrlSuffix($object->getUrlSuffix());

                return parent::_getElementHtml($element)
                        . Mage::helper('landingpage')->storecheckbox($element);
            }
        }
        return parent::_getElementHtml($element) . Mage::helper('landingpage')->storecheckbox($element);
    }
}