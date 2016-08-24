<?php

class Fedobe_OneStepCheckout_Block_OnePage extends Mage_Checkout_Block_Onepage_Abstract {
    protected $_template = 'fedobe/onestepcheckout/onepage.phtml';

    /**
     * Retrieve is allow and show block
     *
     * @return bool
     */
    public function isShow()
    {
        return true;
    }


}