<?php

class Fedobe_Avatar_Carousel_Model_System_Config_Source_Productsettings {

   public function toOptionArray() {
        return array(
            array('value' => 'default', 'label' => 'Default'),
            array('value' => "classic", 'label' => "Classic"),
            array('value' => "urban", 'label' => "Urban"),
            array('value' => "trendy", 'label' => "Trendy"),
            );
    }

}
