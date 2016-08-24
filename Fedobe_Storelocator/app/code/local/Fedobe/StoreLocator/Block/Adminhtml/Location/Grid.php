<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Grid
 *
 * @author Annavarapu prasad
 */
class Fedobe_StoreLocator_Block_Adminhtml_Location_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('locationsGrid');
        $this->setUseAjax(true);
        $this->setDefaultSort('id');
        $this->setSaveParametersInSession(true);
    }

    protected function _getStore() {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection() {
        $_collection = Mage::getModel('storelocator/location')->getCollection();
        $this->setCollection($_collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $this->addColumn('id', array(
            'header' => Mage::helper('storelocator')->__('ID'),
            'width' => '50px',
            'index' => 'id',
            'type' => 'number',
        ));
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
                'header' => Mage::helper('storelocator')->__('Store'),
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
         $this->addColumn('website_address', array(
            'header' => Mage::helper('storelocator')->__('Website url'),
            'index' => 'website_address',
            'type' => 'text',
        ));
        $this->addColumn('store_status', array(
            'header' => Mage::helper('storelocator')->__('Status'),
            'index' => 'store_status',
            'type' => 'options',
            'options' => array('1' => Mage::helper('adminhtml')->__('Active'), '0' => Mage::helper('adminhtml')->__('Inactive'))
        ));
        $this->addColumn('edit', array(
            'header' => Mage::helper('storelocator')->__('Action'),
            'width' => '50px',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('storelocator')->__('Edit'),
                    'url' => array(
                        'base' => '*/*/edit',
                        'params' => array('store' => $this->getRequest()->getParam('store'))
                    ),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
        ));
         $this->addExportType('*/*/exportCsv', Mage::helper('storelocator')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('storelocator')->__('Excel XML'));
        return parent::_prepareColumns();
    }

}

