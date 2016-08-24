<?php

/**
 * 
 * @category    CategoryCarousel
 * @package     Avatar_CategoryCarousel
 * @author        Fedobe Solution Pvt Ltd <support@fedobe.com>
 *
 */

class Fedobe_Avatar_Carousel_Model_System_Config_Source_Category
{
    public function toOptionArray()
    {
        $collection = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*');

        $options = array();       
       
        foreach($collection as $category){
            if($category->getName() != 'Root Catalog'){
                $options[] = array(
                   'label' => $category->getName(),
                   'value' => $category->getId()
                );
            }
        }
        return $options;
    }
}
