<?php
/*===================Excuting Code out side of magento package=================*/ 

				error_reporting(E_ALL);
				ini_set('display_errors', 1);
				set_time_limit(0);
				ini_set('memory_limit', '890M');
				require_once '../app/Mage.php';
				Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);

/*================================== End ======================================*/

$_categories = Mage::getModel('catalog/category')->getCollection();
    $catIds = $_categories->getAllIds();
   $arg_attribute = "marcas";
   $agr_Lable = "AMD";
   $arg_value = attributeOptionsSearch($agr_Lable);
   //echo $arg_value;
   //echo "<br>";
   $s = getattributeOptionsCollection();
   //echo "<pre>";
   //print_r($s);
foreach ($catIds as $id) {
      $cat = Mage::getModel('catalog/category')->load($id);
         
    if($cat->getName() == "Marcas")
        {
          $_subcategories = $cat->getChildrenCategories();
       foreach ($_subcategories as $_subcat) {
       $subCatName = $_subcat->getName();
         if($subCatName == $agr_Lable){
                   
      	$products = Mage::getSingleton('catalog/category')->load($_subcat->getId())
		            ->getProductCollection()
		            ->addAttributeToSelect('*');
		
	
		foreach ($products as $product) {
			$pId[] = $product->getId();
		}
	$productsCollection = Mage::getModel('catalog/product')
                      ->getCollection()
                      ->addAttributeToSelect('*')
                      ->addAttributeToFilter('entity_id', array('in' =>$pId));
       


     foreach ($productsCollection as $pro) {
       	$pro = Mage::getModel('catalog/product')
                   ->load($pro->getEntityId());
    $pro->setData($arg_attribute, $arg_value)
            ->getResource()
            ->saveAttribute($pro, $arg_attribute,$arg_value);
       }

       } 

     }
  }
 }
       
       


function attributeOptionsSearch($attributeOptionsLable){
	$attributeOptionsCollectionData = getattributeOptionsCollection();
	$attributeOptionsResult = array_search($attributeOptionsLable,array_column($attributeOptionsCollectionData, 'label', 'value'));
	return $attributeOptionsResult;
}

function getattributeOptionsCollection(){
   $attributeCode = "marcas";

$attributeId = Mage::getResourceModel('eav/entity_attribute_collection')->setCodeFilter($attributeCode)->getFirstItem()->getAttributeId();
$attribute = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId);

// Get the Admin Store View (default) attribute options
$attributeOptions = Mage::getResourceModel('eav/entity_attribute_option_collection')
				    ->setAttributeFilter($attributeId)
				   ->setStoreFilter(0)
				    ->setPositionOrder()
				    ->load()
				    ->toOptionArray();

return $attributeOptions;

}


