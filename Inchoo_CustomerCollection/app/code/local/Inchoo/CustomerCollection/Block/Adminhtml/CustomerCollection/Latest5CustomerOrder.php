<?php

class Inchoo_CustomerCollection_Block_Adminhtml_CustomerCollection_Latest5CustomerOrder extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'customercollection';
        $this->_controller = 'customercollection_latest5customerorder';
        $this->_headerText = Mage::helper('customer')->__('Latest 5 Customers who purchased something');
        
        parent::__construct();
        $this->_removeButton('add');
    }
}
