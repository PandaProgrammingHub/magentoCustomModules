<?php
/*===================Excuting Code out side of magento package=================*/ 

				error_reporting(E_ALL);
				ini_set('display_errors', 1);
				set_time_limit(0);
				ini_set('memory_limit', '890M');
				require_once '../app/Mage.php';
				Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);

/*================================== End ======================================*/



/*=========== Start Computer AttributeSet ,AttributeGroup, Attribute ==============*/
$addNewAttributeSet = "Computers";
$addNewAttributeGroup = array("Desktops","Laptop");
$attributeToAttributeGroup = array(
                              "General"=>
                              array(
                                "marcas",
                                "cor",
                                "sistema_operacional",
                                "capacidade_armazenamento",
                                "processador",
                                "capacidade_memoria",
                                ),
                              
                            );

/*-------------------------Create AttributeSet & AttributeGroup ----------------------*/
if(!attributeSetSearch($addNewAttributeSet)){
	
	createAttributeSet($addNewAttributeSet);
	createAttributeGroup($addNewAttributeGroup,$addNewAttributeSet);
}else{
	echo "<br>Attribute Set Available";
	
}
/*$attributesetid = attributeSetSearch($addNewAttributeSet);
echo $attributesetid;
$s = attributeGroupSearch("Desktops",$attributesetid);
echo "<pre>";
print_r($s);*/

  assignmentAttributeToAttributeSet($addNewAttributeSet,$attributeToAttributeGroup);           
 
 /*=============End Start Computer AttributeSet ,AttributeGroup, Attribute=============*/
 
 
/*===============================All Function =========================*/

function assignmentAttributeToAttributeSet($attributeSet,$attributeToAttributeGroup){
    $model=Mage::getModel('eav/entity_setup','core_setup');
    $attributeSetId = attributeSetSearch($attributeSet);
    foreach ($attributeToAttributeGroup as $key => $value) {
    $attributeGroupId = attributeGroupSearch($key,$attributeSetId);
    foreach ($value as $ik => $iv) {
      $attributeId = attributeSearch($iv);
      $model->addAttributeToSet('catalog_product',$attributeSetId,$attributeGroupId,$attributeId);
      
    }
    //$model->addAttributeToSet('catalog_product',$attributeSetId,$attributeGroupId,$attributeId);
    }
    
}
function attributeSetSearch($addNewAttributeSet){
	$attributeSetCollectionData = getAttributeSetCollection();
	$attributeSetResult = array_search($addNewAttributeSet,array_column($attributeSetCollectionData, 'name', 'id'));
	return $attributeSetResult;
}
function attributeGroupSearch($addNewAttributeGroup,$attributeSetId){
  $attributeGroupCollectionData = getAttributeGroupCollection($attributeSetId);
  $attributeGroupResult = array_search($addNewAttributeGroup,array_column($attributeGroupCollectionData, 'name', 'id'));
  return $attributeGroupResult;
}
function attributeSearch($addNewAttribute){
  $attributeCollectionData = getAttributeCollection();
  $attributeResult = array_search($addNewAttribute,array_column($attributeCollectionData, 'name', 'id'));
  return $attributeResult;
}

function getAttributeCollection(){
  $attributes = Mage::getSingleton('eav/config')
    ->getEntityType(entityTypeFilter())
    ->getAttributeCollection()
    ->addSetInfo();
   
foreach ($attributes as $attribute){

     $attributeId = $attribute->getAttributeId();
     $attributeName = $attribute->getAttribute_code();
   $attributeData[] = array( "id"=>$attributeId, "name"  => $attributeName);  
}
     //$attributeData = array_column($attributeData, 'name', 'id'); 
   
  return $attributeData ;
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



function getAttributeGroupCollection($attributesetid){

	$attributeGroupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
                              ->setAttributeSetFilter($attributesetid)
                              ->load();

foreach ($attributeGroupCollection as $id=>$attributeGroup) {
    
     $attributeGroupId  = $attributeGroup->getAttributeGroupId();
     $attributeGroupName  = $attributeGroup->getAttributeGroupName();
     $attributeGroupData[] = array("id"=>$attributeGroupId, "name"  => $attributeGroupName); 
}
	
 // $attributeGroupData = array_column($attributeGroupData, 'name', 'id'); 
   
  return $attributeGroupData ;
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


function createAttributeGroup($attributeGroup,$attributeSet){

	$attributeSetId = attributeSetSearch($attributeSet);
	$i = 1; 
	foreach ($attributeGroup as $key => $value) {
		
	
	$modelGroup = Mage::getModel('eav/entity_attribute_group');
    $modelGroup->setAttributeGroupName($value)
                ->setAttributeSetId($attributeSetId)
                ->setSortOrder($i);
    $modelGroup->save();
    $i++;
}
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
    ->addFieldToFilter('attribute_set_name', array('eq' => 'Test')); // This can be any attribute set you might want to clone

$defaultAttrSet = $attrSetCollection->getFirstItem();
$defaultAttrSetId = $defaultAttrSet->getAttributeSetId();
return $defaultAttrSetId;
}
/*============================End All Function  ===============================*/