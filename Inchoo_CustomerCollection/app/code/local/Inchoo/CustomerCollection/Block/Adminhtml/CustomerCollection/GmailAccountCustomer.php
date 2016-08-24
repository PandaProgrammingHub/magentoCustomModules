<?php

class Inchoo_CustomerCollection_Block_Adminhtml_CustomerCollection_GmailAccountCustomer extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'customercollection';
        $this->_controller = 'customercollection_gmailaccountcustomer';
        $this->_headerText = Mage::helper('customer')->__('Customers with Gmail accounts');
        
        parent::__construct();
        $this->_removeButton('add');
    }
}
