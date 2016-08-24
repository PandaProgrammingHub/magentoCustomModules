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
$addNewAttributeSet = "Test";
$addNewAttributeGroup = array("D1","L1");
$attributeToAttributeGroup = array(
                              "D1"=>array("operating_system"),
                              "L1"=>array("processor_type"),
                            );
/*---- Pass attributeLable and attribute_code like "attributeLable" => "attribute_code"----- */

$addNewAttribute =  array(
                        "Operating System" => "operating_system",
                        "Processor Type" => "processor_type"
                        ); 
/*----- Pass AttributeOptions to AttributeGroup -------- */ 
$addNewAttributeOptions =  array(
								 "operating_system" => array("Windows 7","Windows 7.0","Windows 8.0","Windows 8.1","Windows 10.0","Ubuntu"),
								 "processor_type" => array("Intel Core i3"," Intel Core i5", "Intel Core i7","AMD Athlon","AMD Sempron")
								  
								  );
/*-------------------------Create AttributeSet & AttributeGroup ----------------------*/
if(!attributeSetSearch($addNewAttributeSet)){
	
	createAttributeSet($addNewAttributeSet);
	//createAttributeGroup($addNewAttributeGroup,$addNewAttributeSet);
}else{
	echo "<br>Attribute Set Available";
	
}
/*------------------------Create AttributeSet & AttributeGroup ---------------*/
/*------------------Create Attribute -------------------------- */
/*foreach ($addNewAttribute as $key => $value) {

	if(!attributeSearch($value)){
	createAttribute($addNewAttribute,$addNewAttributeOptions);
}else{
	echo "<br>Attribute  Available";
}
	
} 
/*------------------------End Create Attribute ----------------------*/

      //assignmentAttributeToAttributeSet($addNewAttributeSet,$attributeToAttributeGroup);           
 
 /*=============End Start Computer AttributeSet ,AttributeGroup, Attribute=============*/
 
 //$s = createAttributeBasedON();
 //echo "<pre>";
//print_r($s);
/*===============================All Function =========================*/

function assignmentAttributeToAttributeSet($attributeSet,$attributeToAttributeGroup){
    $model=Mage::getModel('eav/entity_setup','core_setup');
    $attributeSetId = attributeSetSearch($attributeSet);
    echo "<pre>";
    print_r($attributeToAttributeGroup);
    foreach ($attributeToAttributeGroup as $key => $value) {
    $attributeGroupId = attributeGroupSearch($key);
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
function attributeGroupSearch($addNewAttributeGroup){
  $attributeGroupCollectionData = getAttributeGroupCollection();
  $attributeGroupResult = array_search($addNewAttributeGroup,array_column($attributeGroupCollectionData, 'name', 'id'));
  return $attributeGroupResult;
}
function attributeSearch($addNewAttribute){
	$attributeCollectionData = getAttributeCollection();
	$attributeResult = array_search($addNewAttribute,array_column($attributeCollectionData, 'name', 'id'));
	return $attributeResult;
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



function getAttributeGroupCollection(){

	$attributeGroupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
                              ->load();

foreach ($attributeGroupCollection as $id=>$attributeGroup) {
    
     $attributeGroupId  = $attributeGroup->getAttributeGroupId();
     $attributeGroupName  = $attributeGroup->getAttributeGroupName();
     $attributeSetId = $attributeGroup->getAttributeSetId();
     $attributeGroupData[] = array("id"=>$attributeGroupId, "name"  => $attributeGroupName); 
}
	
 // $attributeGroupData = array_column($attributeGroupData, 'name', 'id'); 
   
  return $attributeGroupData ;
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

function createAttribute($addNewAttribute,$addNewAttributeOptions){
	$model=Mage::getModel('eav/entity_setup','core_setup');
	foreach ($addNewAttribute as $key => $value) {
		
	
   $attrdata = array (
  'attribute_model' => NULL,
  'backend' => '',
  'type' => 'int',
  'table' => '',
  'frontend' => '',
  'input' => 'select',
  'label' => $key,
  'frontend_class' => '',
  'source' => '',
  'required' => '0',
  'user_defined' => '1',
  'default' => '',
  'unique' => '0',
  'note' => '',
  'input_renderer' => NULL,
  'global' => '1',
  'visible' => '1',
  'searchable' => '1',
  'filterable' => '1',
  'comparable' => '1',
  'visible_on_front' => '0',
  'is_html_allowed_on_front' => '0',
  'is_used_for_price_rules' => '1',
  'filterable_in_search' => '1',
  'used_in_product_listing' => '0',
  'used_for_sort_by' => '0',
  'is_configurable' => '1',
  'apply_to' => 'simple',
  'visible_in_advanced_search' => '1',
  'position' => '1',
  'wysiwyg_enabled' => '0',
  'used_for_promo_rules' => '1',
  'option' => 
  array (
    'values' => 
    
      getAttributeOptions($addNewAttributeOptions,$value)
  
  ),
);
   $model->addAttribute('catalog_product',$value,$attrdata);
 }
}

function entityTypeFilter(){
	$entityTypeId = Mage::getModel('catalog/product')
                ->getResource()
                ->getEntityType()
                ->getId();
     return $entityTypeId;
}
function getAttributeOptions($addNewAttributeOptions,$attribute_code){
  foreach ($addNewAttributeOptions as $key => $values) {
 if($key == $attribute_code){
    foreach ($values as $anotherkey => $val) {
         $result[] = array( "id"=>$anotherkey,"name" => $val );
         
    }
  }
}

$result= array_column($result, 'name', 'id');
return $result;
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
/*============================End All Function  ===============================*/