<?php
class Fedobe_OneStepCheckout_Block_Paypal_Iframe extends  Mage_Paypal_Block_Iframe {
    protected function _construct()
    {
        parent::_construct();
        $paymentCode = $this->_getCheckout()
            ->getQuote()
            ->getPayment()
            ->getMethod();
        if (in_array($paymentCode, $this->helper('paypal/hss')->getHssMethods())) {
            $this->_paymentMethodCode = $paymentCode;
            $templatePath = str_replace('_', '', $paymentCode);
            $templateFile = "fedobe/onestepcheckout/paypal/{$templatePath}/iframe.phtml";
            if (file_exists(Mage::getDesign()->getTemplateFilename($templateFile))) {
                $this->setTemplate($templateFile);
            } else {
                $this->setTemplate('fedobe/onestepcheckout/paypal/hss/iframe.phtml');
            }
        }
    }
}