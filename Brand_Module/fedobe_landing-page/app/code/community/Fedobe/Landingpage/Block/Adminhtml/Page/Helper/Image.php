<?php
/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */

class Fedobe_Landingpage_Block_Adminhtml_Page_Helper_Image extends Varien_Data_Form_Element_Image
{
    /**
     * Prepend the base image URL to the image filename
     *
     * @return null|string
     */
    protected function _getUrl()
    {
        if ($this->getValue() && !is_array($this->getValue())) {
            return Mage::helper('landingpage/image')->getImageUrl($this->getValue());
        }
        
        return null;
    }
}