<?php
  
class Fedobe_Assembleproduct_Model_Resource_Dependencyoption extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {  
        $this->_init('assembleproduct/dependencyoption', 'dependencyoption_id');
    }
} 