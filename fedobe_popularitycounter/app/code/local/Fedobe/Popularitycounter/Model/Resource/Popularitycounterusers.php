<?php
  
class Fedobe_Popularitycounter_Model_Resource_Popularitycounterusers extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {  
        $this->_init('popularitycounter/popularitycounterusers', 'popularitycounterusers_id');
    }
} 