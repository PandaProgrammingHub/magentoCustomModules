<?php
  
class Fedobe_Popularitycounter_Model_Resource_Popularitycountervotes extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {  
        $this->_init('popularitycounter/popularitycountervotes', 'popularitycountervotes_id');
    }
} 