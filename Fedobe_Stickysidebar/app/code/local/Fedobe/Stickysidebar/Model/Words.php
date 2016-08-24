<?php
class Fedobe_Stickysidebar_Model_Words
{
   public function toOptionArray()
    {
        return array(
            array('value'=>"1", 'label'=>Mage::helper('stickysidebar')->__('Yes')),
            array('value'=>"0", 'label'=>Mage::helper('stickysidebar')->__('No')),
            
        );
    }

 }
?>