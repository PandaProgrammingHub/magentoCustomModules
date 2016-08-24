<?php
class Fedobe_Popularitycounter_Block_Counterblock extends Mage_Core_Block_Template{
    
     public function __construct()
    {
        $this->setTemplate('fedobe/popularitycounter/counterblock.phtml');
    }
    
    public function getProductId(){
    	
        return $this->getProduct()->getId();
    }
}
