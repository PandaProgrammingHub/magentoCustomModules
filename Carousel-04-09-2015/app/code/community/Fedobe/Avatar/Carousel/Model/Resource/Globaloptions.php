<?php

class Fedobe_Avatar_Carousel_Model_Resource_Globaloptions extends Mage_Core_Model_Resource_Db_Abstract{
    protected function _construct()
    {
        $this->_init('carousel/globaloptions', 'id');
    }
}