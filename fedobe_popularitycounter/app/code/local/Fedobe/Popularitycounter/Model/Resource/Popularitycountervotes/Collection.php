<?php
  
class Fedobe_Popularitycounter_Model_Resource_Popularitycountervotes_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        //parent::__construct();
        $this->_init('popularitycounter/popularitycountervotes');
    }
} 