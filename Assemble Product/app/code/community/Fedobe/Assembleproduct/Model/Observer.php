<?php
class Fedobe_Assembleproduct_Model_Observer
{
     public function bundleProductOptionPostionUpdate(Varien_Event_Observer $observer){
        $productId = $observer['productId'];
        $optId = $observer['primary_option_select'];
        $resource = Mage::getSingleton('core/resource');
	$readConnection = $resource->getConnection('core_read');
        $writeConnection = $resource->getConnection('core_write');

	$readQuery = "SELECT * FROM  `catalog_product_bundle_option` WHERE  `parent_id` =  '$productId'";
        $readResults = $readConnection->fetchAll($readQuery);
	
        //echo "<pre>";
        //print_r($readResults);

       foreach ($readResults as $val) {
          $opId = $val['option_id'] ;
          if($opId == $optId){
          $currentPosition = $val['position'] ;
         }
          
      }
       foreach ($readResults as $val) {
         if( $val['position'] == 0){
          $opId = $val['option_id'] ;
          $writeQuery = "UPDATE `catalog_product_bundle_option` SET position = '{$currentPosition}' WHERE `option_id` = '$opId'";
          $writeConnection->query($writeQuery);
        }
      }
     foreach ($readResults as $val) {
          $opId = $val['option_id'] ;
          $writeQuery = "UPDATE `catalog_product_bundle_option` SET position = '0' WHERE `option_id` = '$optId'";
          $writeConnection->query($writeQuery);
      
      }
      // exit();        
    
     }
}