<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Fedobe_Popularitycounter_Model_Popularitycounteroptions
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => null, 'label' => Mage::helper('popularitycounter')->__('')),
            array('value' => 'love', 'label' => Mage::helper('popularitycounter')->__('Love')),
            array('value' => 'viewed', 'label' => Mage::helper('popularitycounter')->__('Viewed')),
            array('value' => 'like', 'label' => Mage::helper('popularitycounter')->__('Like')),
            array('value' => 'recommended', 'label' => Mage::helper('popularitycounter')->__('Recommended')),
            
            
        );
    }
}
