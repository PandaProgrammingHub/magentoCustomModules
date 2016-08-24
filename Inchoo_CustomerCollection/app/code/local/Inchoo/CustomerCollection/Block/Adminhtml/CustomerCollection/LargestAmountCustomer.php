<?php

class Inchoo_CustomerCollection_Block_Adminhtml_CustomerCollection_LargestAmountCustomer extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'customercollection';
        $this->_controller = 'customercollection_largestamountcustomer';
        $this->_headerText = Mage::helper('customer')->__('Customers with largest amount');
        
        parent::__construct();
        $this->_removeButton('add');
    }
}
