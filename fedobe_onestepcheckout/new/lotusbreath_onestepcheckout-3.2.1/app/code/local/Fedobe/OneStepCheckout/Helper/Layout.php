<?php

class Fedobe_OneStepCheckout_Helper_Layout extends Mage_Checkout_Helper_Data{

    public function switchTemplate(){
        $layout = Mage::getStoreConfig('fedobe_onestepcheckout/layout/layout');
        switch ($layout){
            case '2cols':
                return 'fedobe/onestepcheckout/onepage.phtml';
                break;
            case '3cols':
                return 'fedobe/onestepcheckout/onepage_3columns.phtml';
                break;
            default:
                return 'fedobe/onestepcheckout/onepage.phtml';
                break;
        }
    }
}