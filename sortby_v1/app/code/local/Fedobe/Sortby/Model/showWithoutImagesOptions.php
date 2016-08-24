<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Fedobe_Sortby_Model_showWithoutImagesOptions
{
    
    public function toOptionArray()
    {
        return array(
            array('value'=>1, 'label'=>Mage::helper('sortby')->__('Yes')),
            array('value'=>2, 'label'=>Mage::helper('sortby')->__('No')),
            
        );
    }
}
