<?php
class Fedobe_Barter_Manufacturers_Model_Borderoption
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'line', 'label'=>Mage::helper('manufacturers')->__('Line')),
            array('value'=>'dotted', 'label'=>Mage::helper('manufacturers')->__('Dotted')),
            array('value'=>'dashed', 'label'=>Mage::helper('manufacturers')->__('Dashed')),
        );
    }
}