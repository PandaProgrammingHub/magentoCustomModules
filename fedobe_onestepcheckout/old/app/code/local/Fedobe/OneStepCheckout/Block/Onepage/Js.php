<?php
class Fedobe_OneStepCheckout_Block_Onepage_Js extends Mage_Checkout_Block_Onepage_Abstract {

    protected $_billingAddress = null;
    protected $_shippingAddress = null;
    public function getBillingAddress()
    {
        if (is_null($this->_billingAddress)) {
            if ($this->isCustomerLoggedIn()) {
                $this->_billingAddress = $this->getQuote()->getBillingAddress();
                if(!$this->_billingAddress->getFirstname()) {
                    $this->_billingAddress->setFirstname($this->getQuote()->getCustomer()->getFirstname());
                }
                if(!$this->_billingAddress->getLastname()) {
                    $this->_billingAddress->setLastname($this->getQuote()->getCustomer()->getLastname());
                }
            } else {
                $this->_billingAddress = Mage::getModel('sales/quote_address');
            }
        }

        return $this->_billingAddress;
    }
    public function getShippingAddress()
    {
        if (is_null($this->_shippingAddress)) {
            if ($this->isCustomerLoggedIn()) {
                $this->_shippingAddress = $this->getQuote()->getBillingAddress();
                if(!$this->_shippingAddress->getFirstname()) {
                    $this->_shippingAddress->setFirstname($this->getQuote()->getCustomer()->getFirstname());
                }
                if(!$this->_shippingAddress->getLastname()) {
                    $this->_shippingAddress->setLastname($this->getQuote()->getCustomer()->getLastname());
                }
            } else {
                $this->_shippingAddress = Mage::getModel('sales/quote_address');
            }
        }

        return $this->_shippingAddress;
    }

}