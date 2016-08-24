<?php

class Fedobe_Manufacturers_Model_System_Config_Source_Manuid {

    public function toOptionArray() {
        $manu_code = Mage::getStoreConfig('manufacturers/general/attr_code');
        $manu_code = ($manu_code) ? $manu_code : "manufacturer";
        $storeId = Mage::app()->getStore()->getStoreId();
        if (empty($manu_code))
            return array();
        $attribute = Mage::getModel('catalog/product')->getResource()->getAttribute($manu_code);
        if (!$attribute)
            return array();
        $manufacturers = $attribute->setStoreId($storeId)->getSource()->getAllOptions(false);
        if (!$manufacturers)
            return array();
        return $manufacturers;
    }

}

?>