<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Fedobe_Sortby_Model_System_Config_Source_View 
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'Newest', 'label' => Mage::helper('sortby')->__('Newest')),
            array('value' => 'Biggest_Saving', 'label' => Mage::helper('sortby')->__('Biggest Saving')),
            array('value' => 'Best_Sells', 'label' => Mage::helper('sortby')->__('Best Sells')),
            array('value' =>'Most_Viewed', 'label' => Mage::helper('sortby')->__('Most Viewed')),
            array('value' => 'Top_Rated', 'label' => Mage::helper('sortby')->__('Top Rated')),
            array('value' => null, 'label' => Mage::helper('sortby')->__('Disable None'))
            
        );
    }
}
