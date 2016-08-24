<?php
class Fedobe_Manufacturers_Model_Displayoption
{
    public function toOptionArray()
    {
        
        return array(
            array('value'=>'image_text', 'label'=>Mage::helper('manufacturers')->__('Image + Text')),
            array('value'=>'image', 'label'=>Mage::helper('manufacturers')->__('Image')),
            array('value'=>'text', 'label'=>Mage::helper('manufacturers')->__('Text')),
        );
    }
}