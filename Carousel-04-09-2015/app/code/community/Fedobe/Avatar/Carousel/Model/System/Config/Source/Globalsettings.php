<?php

class Fedobe_Avatar_Carousel_Model_System_Config_Source_Globalsettings {

   public function toOptionArray() {
        //$options = Mage::helper('carousel')->getGlobalSettings();
        
//        return array(
//            array('value' => strtolower($options[0]), 'label' => $options[0]),
//            array('value' => strtolower($options[1]), 'label' => $options[1]),
//            array('value' => strtolower($options[2]), 'label' => $options[2]),
//            );
        return array(
            array('value' => "classic", 'label' => "Classic"),
            array('value' => "urban", 'label' => "Urban"),
            array('value' => "trendy", 'label' => "Trendy"),
        );
    }
    
}
