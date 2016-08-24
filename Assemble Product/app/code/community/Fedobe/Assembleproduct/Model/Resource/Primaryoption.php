<?php
  
class Fedobe_Assembleproduct_Model_Resource_Primaryoption extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {  
        $this->_init('fedobe_assembleproduct/primaryoption', 'primaryoption_id');
    }
} 