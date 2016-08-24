<?php
  
class Fedobe_Stickysidebar_Helper_Data extends Mage_Core_Helper_Abstract
{  

    public function getStickySideBarBlock(Mage_Catalog_Model_Product $product){
       
     return Mage::app()->getLayout()->createBlock('stickysidebar/stickyblock')->setProduct($product)->setTemplate('fedobe/stickysidebar/stickysidebar.phtml')->toHtml();
    }   
} 