<?php

class Inchoo_CustomerCollection_Adminhtml_CustomerCollection_IndexController extends Mage_Adminhtml_Controller_Action 
{        
   
    public function latest5Action()
    {
        $this->_title($this->__('Customers'))->_title($this->__('Latest 5 Customers'));
        $this->loadLayout();
        $this->_setActiveMenu('customer');
        
        $this->_addContent($this->getLayout()->
                createBlock('customercollection/customercollection_latest5customer', 'latest5customer'));
        $this->renderLayout();
    } 
    
    public function notConfirmedAction()
    {
        $this->_title($this->__('Customers'))->_title($this->__('Not confirmed Customers'));
        $this->loadLayout();
        $this->_setActiveMenu('customer');
        
        $this->_addContent($this->getLayout()->
                createBlock('customercollection/customercollection_notconfirmedcustomer', 'notconfirmedcustomer'));
        $this->renderLayout();
    } 
    
    public function gmailAccountAction()
    {
        $this->_title($this->__('Customers'))->_title($this->__('Customers with Gmail accounts'));
        $this->loadLayout();
        $this->_setActiveMenu('customer');
        
        $this->_addContent($this->getLayout()->
                createBlock('customercollection/customercollection_gmailaccountcustomer', 'gmailaccountcustomer'));
        $this->renderLayout();
    }  
    
    public function largestAmountAction()
    {
        $this->_title($this->__('Customers'))->_title($this->__('Customer with largest amount'));
        $this->loadLayout();
        $this->_setActiveMenu('customer');
        
        $this->_addContent($this->getLayout()->
                createBlock('customercollection/customercollection_largestamountcustomer', 'largestamountcustomer'));
        $this->renderLayout();
    } 
    
    public function latest5OrderAction()
    {
        $this->_title($this->__('Customers'))->_title($this->__('Latest 5 Customers who purchased something'));
        $this->loadLayout();
        $this->_setActiveMenu('customer');
        
        $this->_addContent($this->getLayout()->
                createBlock('customercollection/customercollection_latest5customerorder', 'latest5customerorder'));
        $this->renderLayout();
    } 
} 