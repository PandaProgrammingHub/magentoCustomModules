<?php

class Fedobe_OneStepCheckout_Model_Session extends Mage_Checkout_Model_Session{

    public function compareOldData($key, $data){

        $oldData = $this->getData($key);
        switch ($key){
            case 'data_shipping_method':
                if($this->getQuote()->getShippingAddress()->getShippingMethod() == $data)
                    return true;
                break;
        }
        if (!$oldData)
            return false;
        //Mage::log($key . "|" . print_r($oldData, true) . "|" . print_r($data, true) , null, 'onestepcheckout.log', true);
        if (is_array($data) && count($data) > 0){
            foreach($data as $keyV => $value){
                if (isset($oldData[$keyV]) && $oldData[$keyV] == $value ){
                    continue;
                }else{
                    return false;
                }
            }
            //Mage::log("$key not require saved again", null, 'onestepcheckout.log', true);
            return true;
        }else{
            if($oldData == $data){
                //Mage::log("$key not require saved again", null, 'onestepcheckout.log', true);
                return true;
            }
        }

        return false;

    }
}
