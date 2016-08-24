<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of View
 *
 * @author prasad
 */

/**
 * Used in creating options for Standard|Classic config value selection
 *
 */
class Fedobe_StoreLocator_Model_Adminhtml_System_Config_Source_Location_View {
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => '', 'label'=>Mage::helper('adminhtml')->__('Standard')),
            array('value' => 'classic', 'label'=>Mage::helper('adminhtml')->__('Classic')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            '' => Mage::helper('adminhtml')->__('Standard'),
            'classic' => Mage::helper('adminhtml')->__('Classic'),
        );
    }
}


