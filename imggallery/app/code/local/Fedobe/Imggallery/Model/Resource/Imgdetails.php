<?php

class Fedobe_Imggallery_Model_Resource_Imgdetails extends Mage_Core_Model_Mysql4_Abstract{
    public function _construct() {
        $this->_init('imggallery/imgdetails', 'imagedetails_id');
    }
}

