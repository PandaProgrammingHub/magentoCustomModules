<?php
class Fedobe_Notificationbar_Model_Words
{
   public function toOptionArray()
    {
        return array(
            array('value'=>'left', 'label'=>Mage::helper('notificationbar')->__('Left')),
            array('value'=>'right', 'label'=>Mage::helper('notificationbar')->__('Right')),
            array('value'=>'up', 'label'=>Mage::helper('notificationbar')->__('Up')),
            array('value'=>'down', 'label'=>Mage::helper('notificationbar')->__('Down')),
            
        );
    }

 }
?>