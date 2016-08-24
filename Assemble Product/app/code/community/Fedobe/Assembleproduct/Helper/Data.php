<?php
class Fedobe_Assembleproduct_Helper_Data extends Mage_Core_Helper_Abstract
{
         public function getOptionBlock(Mage_Catalog_Model_Product $product){
       
     return Mage::app()->getLayout()->createBlock('fedobe_assembleproduct/optionblock')->setProduct($product)->setTemplate('fedobe/assembleproduct/optionblock.phtml')->toHtml();
    }
}    