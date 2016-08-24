<?php
class Fedobe_OneStepCheckout_Helper_Url extends Mage_Checkout_Helper_Url {
    /**
     * Retrieve checkout url
     *
     * @return string
     */
    public function getCheckoutUrl()
    {
        return $this->_getUrl('fedobe_onestepcheckout', array('_secure' => true));
    }

}