<?php

class Fedobe_Barter_Manufacturers_Model_Fedobemanufacturer extends Mage_Core_Model_Abstract {

    public function getStoreFilterManufacturerIds($store_id,$attr_id = 0){
        $formattedmandetails = array();
        $adapter = $this->_getReadAdapter();
        $table = $this->getTable();
        $maintable = $this->getMainTable();
        $select = $adapter->select()
                ->from($table, 'page_id')
                ->where('store_id = :store_id OR store_id = 0');
        $binds = array(
            ':store_id' => (int) $store_id
        );
        $manids = $adapter->fetchCol($select, $binds);
        $manids = implode(",", $manids);
        $mansselect = $adapter->select()
                ->from($maintable,"*")
                ->where("is_enabled = 1 AND FIND_IN_SET(page_id,'$manids')");
        if($attr_id){
            $attrb_id = "'%--".$attr_id."'";
            $mansselect->where("filter_rules LIKE $attrb_id AND `is_featured`=1 ")
                       ->limit(1);
        }

        $mandetails = $adapter->fetchAll($mansselect);
        foreach ($mandetails as $k => $v){
            $formattedmandetails[$attr_id] = $mandetails[$k];
        }
        //echo "<pre>";print_r($formattedmandetails);exit;
        return $formattedmandetails;
    }

    public function _getReadAdapter(){
        return Mage::getSingleton('core/resource')->getConnection('core_read');
    }
    
    public function _getWriteAdapter(){
        return Mage::getSingleton('core/resource')->getConnection('core_write');
    }
    
    public function getTable(){
        return Mage::getSingleton('core/resource')->getTableName('attributeSplash/page_store');
    }
    public function getMainTable(){
        return Mage::getSingleton('core/resource')->getTableName('attributeSplash/page');
    }
}