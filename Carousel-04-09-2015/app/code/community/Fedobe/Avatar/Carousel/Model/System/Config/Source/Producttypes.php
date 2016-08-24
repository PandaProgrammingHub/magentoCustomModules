<?php

class Fedobe_Avatar_Carousel_Model_System_Config_Source_Producttypes {

   public function toOptionArray() {
        return array(
//            array('value' => 'related', 'label' => 'Related Product'),
//            array('value' => 'recommended', 'label' => 'Recommended'),
//            array('value' => 'same-category', 'label' => 'Items from same category'),
            array('value' => 'allproduct', 'label' => 'All Product'),
            array('value' => 'featured', 'label' => 'Featured Product'),
            array('value' => 'best-seller', 'label' => 'Best Seller'),
            array('value' => 'recently-added', 'label' => 'Recently Added'),
            );
    }

}
