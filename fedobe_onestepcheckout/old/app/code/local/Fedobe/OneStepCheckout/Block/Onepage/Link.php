<?php
class Fedobe_OneStepCheckout_Block_Onepage_Link extends Mage_Checkout_Block_Onepage_Link
{
    public function getCheckoutUrl()
    {
        if (Mage::getStoreConfig('fedobe_onestepcheckout/general/enabled')) {
            return $this->getUrl('onestepcheckout', array('_secure' => true));
        }
        return parent::getCheckoutUrl();

    }
}