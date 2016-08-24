<?php

class Fedobe_OneStepCheckout_Block_Config_Info extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{

    protected $_template = 'fedobe/onestepcheckout/information.phtml';

    /**
     * Retrieve is allow and show block
     *
     * @return bool
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->toHtml();
    }


}