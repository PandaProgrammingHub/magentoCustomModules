<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Fedobe_Sortby_Model_SortbySpecificFeaturesOptions
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => null, 'label' => Mage::helper('sortby')->__('')),
            array('value' => 'more_recent', 'label' => Mage::helper('sortby')->__('More recent')),
            array('value' => 'best_seller', 'label' => Mage::helper('sortby')->__('Best Seller')),
            array('value' => 'rating_summary', 'label' => Mage::helper('sortby')->__('Most Rated')),
            array('value' => 'more_viewed', 'label' => Mage::helper('sortby')->__('More Viewed')),
            array('value' => 'discount', 'label' => Mage::helper('sortby')->__('Discount'))
            
            
            
            
            
        );
    }
}
