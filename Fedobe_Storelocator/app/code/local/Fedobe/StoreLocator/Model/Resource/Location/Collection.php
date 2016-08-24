<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Collection
 *
 * @author prasad
 */
class Fedobe_StoreLocator_Model_Resource_Location_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('storelocator/location');
    }
/**
 * 
 * @param type $_lat
 * @param type $_long
 * @param type $_radius
 * @param type $_mesurments
 * @return \Innoswift_StoreLocator_Model_Resource_Location_Collection
 */
    function addRadiusToFillter($_lat, $_long, $_radius, $_mesurments = 'mi') {
        $_connection = $this->getConnection();
        $_distance = sprintf(
                "(%s*acos(cos(radians(%s))*cos(radians(`store_latitude`))*cos(radians(`store_longitude`)-radians(%s))+sin(radians(%s))*sin(radians(`store_latitude`))))", $_mesurments == 'mi' ? 3959 : 6371, $_connection->quote($_lat), $_connection->quote($_long), $_connection->quote($_lat)
        );
        $this->_select = $_connection->select()->from(array('main_table' => $this->getResource()->getMainTable()), array('*', 'distance' => $_distance))
                ->where('`store_latitude` is not null and `store_latitude`<>0 and `store_longitude` is not null and `store_longitude`<>0 and ' . $_distance . '<=?', $_radius)
                ->order('distance');
        return $this;
    }

}

