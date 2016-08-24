<?php
/*===================Excuting Code out side of magento package=================*/ 

        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        set_time_limit(0);
        ini_set('memory_limit', '890M');
        require_once '../app/Mage.php';
        Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);

/*================================== End ======================================*/
 
/*===============================All Function =========================*/


function attributeSetSearch($addNewAttributeSet){
  $attributeSetCollectionData = getAttributeSetCollection();
  $attributeSetResult = array_search($addNewAttributeSet,array_column($attributeSetCollectionData, 'name', 'id'));
  return $attributeSetResult;
}

 function getAttributeSetCollection(){
  $attributeSetCollection = Mage::getResourceModel('eav/entity_attribute_set_collection') ->load();
    $attributeSetData = array();
    foreach ($attributeSetCollection as $id=>$attributeSet) {
     $attributeSetId  = $id;
     $attributeSetName  = $attributeSet->getAttributeSetName();
     $attributeSetData[] = array( "id"=>$attributeSetId, "name"  => $attributeSetName ); 
   }
  //$attributeSetData = array_column($attributeSetData, 'name', 'id');
   
  return $attributeSetData ;
}


function createAttributeSet($attributeSet){
  $basedOnAttributeSet = createAttributeBasedON();

    $attributeSet = Mage::getModel('eav/entity_attribute_set')
                ->setEntityTypeId(entityTypeFilter())
                ->setAttributeSetName($attributeSet);
                
    $attributeSet->validate();
    $attributeSet->save();
   $attributeSet->initFromSkeleton($basedOnAttributeSet)->save();

}

function entityTypeFilter(){
  $entityTypeId = Mage::getModel('catalog/product')
                ->getResource()
                ->getEntityType()
                ->getId();
     return $entityTypeId;
}

function createAttributeBasedON(){

$attrSet = Mage::getModel('eav/entity_attribute_set');
$attrSetCollection = $attrSet->getCollection();
$attrSetCollection
    ->addFieldToFilter('entity_type_id', array('eq' => entityTypeFilter()))
    ->addFieldToFilter('attribute_set_name', array('eq' => 'Default')); // This can be any attribute set you might want to clone

$defaultAttrSet = $attrSetCollection->getFirstItem();
$defaultAttrSetId = $defaultAttrSet->getAttributeSetId();
return $defaultAttrSetId;
}
/*============================ End All Function  ===============================*/



/*=========== Start create Test AttributeSet  ==============*/
$addNewAttributeSet = "Test1";



if(!attributeSetSearch($addNewAttributeSet)){
  
  createAttributeSet($addNewAttributeSet);
  
}else{
   echo "<br>Attribute Set".$addNewAttributeSet."Available";
  
}

/*=============End create Test AttributeSet=============*/



