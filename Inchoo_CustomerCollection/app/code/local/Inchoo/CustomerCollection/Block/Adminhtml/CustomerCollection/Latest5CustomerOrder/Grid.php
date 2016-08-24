<?php

class Inchoo_CustomerCollection_Block_Adminhtml_CustomerCollection_Latest5CustomerOrder_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('customerGrid');
        $this->setPagerVisibility(false);
        $this->setFilterVisibility(false);
        $this->setDefaultLimit(5);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('customercollection/customer_totals_collection');
        $collection->joinCustomerName()
               ->addBillingData()
               ->addCustomerData()
               ->orderByCreatedAt();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    protected function _prepareColumns()
    {
        $this->addColumn('customer_id', array(
            'header'    => Mage::helper('customer')->__('ID'),
            'width'     => '50px',
            'index'     => 'customer_id',
            'type'  => 'number',
            'sortable'  => false
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('customer')->__('Name'),
            'index'     => 'name',
            'sortable'  => false
        ));
        
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('customer')->__('Order created at'),
            'type'      => 'datetime',
            'align'     => 'center',
            'index'     => 'created_at',
            'gmtoffset' => true,
            'sortable'  => false
        ));
        
        $baseCurrencyCode = Mage::app()->getStore()->getBaseCurrencyCode();

        $this->addColumn('subtotal', array(
            'header'    => $this->__('Order Amount'),
            'align'     => 'right',
            'sortable'  => false,
            'type'      => 'currency',
            'currency_code'  => $baseCurrencyCode,
            'index'     => 'subtotal',
        ));
        
        $this->addColumn('email', array(
            'header'    => Mage::helper('customer')->__('Email'),
            'width'     => '150',
            'index'     => 'customer_email',
            'sortable'  => false
        ));

        $groups = Mage::getResourceModel('customer/group_collection')
            ->addFieldToFilter('customer_group_id', array('gt'=> 0))
            ->load()
            ->toOptionHash();

        $this->addColumn('group', array(
            'header'    =>  Mage::helper('customer')->__('Group'),
            'width'     =>  '100',
            'index'     =>  'customer_group_id',
            'type'      =>  'options',
            'options'   =>  $groups,
            'sortable'  => false
        ));

        $this->addColumn('Telephone', array(
            'header'    => Mage::helper('customer')->__('Telephone'),
            'width'     => '100',
            'index'     => 'telephone',
            'sortable'  => false
        ));

        $this->addColumn('postcode', array(
            'header'    => Mage::helper('customer')->__('ZIP'),
            'width'     => '90',
            'index'     => 'postcode',
            'sortable'  => false
        ));

        $this->addColumn('country_id', array(
            'header'    => Mage::helper('customer')->__('Country'),
            'width'     => '100',
            'type'      => 'country',
            'index'     => 'country_id',
            'sortable'  => false
        ));

        $this->addColumn('region', array(
            'header'    => Mage::helper('customer')->__('State/Province'),
            'width'     => '100',
            'index'     => 'region',
            'sortable'  => false
        ));

        $this->addColumn('customer_since', array(
            'header'    => Mage::helper('customer')->__('Customer Since'),
            'type'      => 'datetime',
            'align'     => 'center',
            'index'     => 'customer_since',
            'gmtoffset' => true,
            'sortable'  => false
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('website_id', array(
                'header'    => Mage::helper('customer')->__('Website'),
                'align'     => 'center',
                'width'     => '80px',
                'type'      => 'options',
                'options'   => Mage::getSingleton('adminhtml/system_store')->getWebsiteOptionHash(true),
                'index'     => 'website_id',
                'sortable'  => false
            ));
        }

        return parent::_prepareColumns();
    }

}
