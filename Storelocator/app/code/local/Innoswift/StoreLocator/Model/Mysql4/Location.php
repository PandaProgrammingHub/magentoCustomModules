<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of location
 *
 * @author prasad
 */
class Innoswift_StoreLocator_Model_Mysql4_Location extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {

        $this->_init('storelocator/location', 'id');
    }

}

