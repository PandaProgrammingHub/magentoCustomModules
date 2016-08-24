<?php
class Fedobe_Couponmail_Model_Words
{
   public function toOptionArray()
    {
        return array(
            array('value'=>1, 'label'=>Mage::helper('couponmail')->__('Yes')),
            array('value'=>2, 'label'=>Mage::helper('couponmail')->__('No')),
            
        );
    }

 }
?>