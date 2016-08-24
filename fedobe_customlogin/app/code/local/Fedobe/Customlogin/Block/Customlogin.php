<?php
class Fedobe_Customlogin_Block_Customlogin extends Mage_Core_Block_Template{
    
     public function __construct()
    {
        $this->setTemplate('fedobe/customlogin/customlogin.phtml');
    }
    
    public function getProductId(){
    	
        return $this->getProduct()->getId();
    }
}
