<?php
class Fedobe_Barter_Manufacturers_Block_Advanced_Result extends Mage_CatalogSearch_Block_Advanced_Result {
    public function getManufacturerDetails($attr_id){
        $storeId = Mage::app()->getStore()->getStoreId();
        return Mage::getModel('manufacturers/fedobemanufacturer')->getStoreFilterManufacturerIds($storeId,$attr_id);
    }
    
    public function isAllowed(){
        $attr_code = trim(Mage::getStoreConfig('manufacturers/general/attr_code'));
        $params = $this->getRequest()->getParams();
        if(array_key_exists ($attr_code, $params)){
            return $params[$attr_code];
        }else{
            return FALSE;
        }
    }
    

}
