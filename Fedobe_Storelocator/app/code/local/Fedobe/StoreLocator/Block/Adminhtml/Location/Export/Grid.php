<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Grid
 *
 * @author prasad
 */
class Fedobe_StoreLocator_Block_Adminhtml_Location_Export_Grid extends Fedobe_StoreLocator_Block_Adminhtml_Location_Grid {

    protected function _prepareColumns() {

        $this->addColumn('store_title', array(
            'header' => Mage::helper('storelocator')->__('Name'),
            'index' => 'store_title',
            'type' => 'text',
        ));
        $this->addColumn('address_display', array(
            'header' => Mage::helper('storelocator')->__('Address'),
            'index' => 'address_display',
            'type' => 'text',
        ));
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('website', array(
                'header' => Mage::helper('storelocator')->__('Websites'),
                'width' => '100px',
                'sortable' => false,
                'index' => 'website',
                'type' => 'options',
                'options' => Mage::getModel('core/website')->getCollection()->toOptionHash(),
            ));
        }
        $this->addColumn('store_phone', array(
            'header' => Mage::helper('storelocator')->__('Phone'),
            'index' => 'store_phone',
            'type' => 'text',
        ));
        $this->addColumn('store_email', array(
            'header' => Mage::helper('storelocator')->__('Email'),
            'index' => 'store_email',
            'type' => 'text',
        ));

        $this->addColumn('address_geo', array(
            'header' => Mage::helper('storelocator')->__('Address Geo'),
            'index' => 'address_geo',
            'type' => 'hidden',
        ));
        $this->addColumn('store_longitude', array(
            'header' => Mage::helper('storelocator')->__('Longitude'),
            'index' => 'store_longitude',
            'type' => 'hidden',
        ));
        $this->addColumn('store_latitude', array(
            'header' => Mage::helper('storelocator')->__('Latitude'),
            'index' => 'store_latitude',
            'type' => 'hidden',
        ));
        $this->addColumn('store_status', array(
            'header' => Mage::helper('storelocator')->__('Status'),
            'index' => 'store_status',
            'type' => 'options',
            'options' => array('1' => Mage::helper('adminhtml')->__('Active'), '0' => Mage::helper('adminhtml')->__('Inactive'))
        ));
        return parent::_prepareColumns();
    }

}

