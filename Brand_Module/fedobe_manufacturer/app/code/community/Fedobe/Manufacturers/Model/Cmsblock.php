<?php
class Fedobe_Manufacturers_Model_Cmsblock
{
    public function toOptionArray()
    {
        return Mage::getModel('catalog/category_attribute_source_page')->getAllOptions();
    }
}