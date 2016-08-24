<?php
class Fedobe_Manufacturers_Model_Displaymode
{
    public function toOptionArray()
    {
        return Mage::getModel('catalog/category_attribute_source_mode')->getAllOptions();
    }
}