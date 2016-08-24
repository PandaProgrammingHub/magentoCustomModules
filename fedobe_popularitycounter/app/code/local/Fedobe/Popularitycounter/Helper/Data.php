<?php
  
class Fedobe_Popularitycounter_Helper_Data extends Mage_Core_Helper_Abstract
{  

    public function getCounterBlock(Mage_Catalog_Model_Product $product){
       
     return Mage::app()->getLayout()->createBlock('popularitycounter/counterblock')->setProduct($product)->setTemplate('fedobe/popularitycounter/counterblock.phtml')->toHtml();
    }






    
} 