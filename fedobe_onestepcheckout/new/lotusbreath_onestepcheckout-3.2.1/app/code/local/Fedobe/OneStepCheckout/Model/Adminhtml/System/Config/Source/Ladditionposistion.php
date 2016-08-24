<?php

class Fedobe_OneStepCheckout_Model_Adminhtml_System_Config_Source_Ladditionposistion {
    public function toOptionArray()
    {
        return array(
            array('value' => 'below_review', 'label' => Mage::helper('fedobe_onestepcheckout')->__('Below review ') ),
            array('value' => 'below_payment', 'label' => Mage::helper('fedobe_onestepcheckout')->__('Below payment ') ),
        );
    }
}