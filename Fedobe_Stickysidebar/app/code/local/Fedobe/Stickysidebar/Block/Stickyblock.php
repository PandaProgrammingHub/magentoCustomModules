<?php
class Fedobe_Stickysidebar_Block_Stickyblock extends Mage_Catalog_Block_Product_Abstract{
    
     public function __construct()
    {
        $this->setTemplate('fedobe/stickysidebar/stickysidebar.phtml');
    }
    
    public function getProductId(){
    	
        return $this->getProduct();
    }
}
