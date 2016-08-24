<?php

class Fedobe_Newssubscribers_Model_System_Config_Template
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'default', 'label'=>Mage::helper('adminhtml')->__('Default')),
            array('value' => 'fedobe', 'label'=>Mage::helper('adminhtml')->__('Fedobe')),
        );
    }
}