<?php

class Inchoo_CustomerCollection_Block_Adminhtml_CustomerCollection_NotConfirmedCustomer extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'customercollection';
        $this->_controller = 'customercollection_notconfirmedcustomer';
        $this->_headerText = Mage::helper('customer')->__('Not confirmed Customers');
        
        parent::__construct();
        $this->_removeButton('add');
    }
}
