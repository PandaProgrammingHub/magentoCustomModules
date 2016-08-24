<?php
class Fedobe_Sortby_Model_ModuleEnableOptions
{
   public function toOptionArray()
    {
        return array(
            array('value'=>1, 'label'=>Mage::helper('sortby')->__('Yes')),
            array('value'=>2, 'label'=>Mage::helper('sortby')->__('No')),
            
        );
    }

 }
?>