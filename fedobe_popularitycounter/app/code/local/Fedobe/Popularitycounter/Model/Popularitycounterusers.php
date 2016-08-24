<?php
  
class Fedobe_Popularitycounter_Model_Popularitycounterusers extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('popularitycounter/popularitycounterusers');
    }
} 