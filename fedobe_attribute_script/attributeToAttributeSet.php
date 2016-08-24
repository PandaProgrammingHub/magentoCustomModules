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
/*============================ End All Function  ===============================*/



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
   echo "<br>Attribute Set".$addNewAttributeSet."Available";
  
}
/*------------------------End Create AttributeSet & AttributeGroup--------------------*/

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/
 
  assignmentAttributeToAttributeSet($addNewAttributeSet,$attributeToAttributeGroup);

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/          
 
 /*=============End  Computer AttributeSet ,AttributeGroup, Attribute=============*/

 /*=========== Start Mobiles AttributeSet ,AttributeGroup, Attribute ==============*/
$addNewAttributeSet = "Mobiles";
$addNewAttributeGroup = array("Smartphones","Tablets");
$attributeToAttributeGroup = array(
                              "General"=>
                              array(
                                "marcas",
                                "cor",
                                "sistema_operacional",
                                "tamanho_da_tela",
                                
                                ),
                              
                            );

/*-------------------------Create AttributeSet & AttributeGroup ----------------------*/
if(!attributeSetSearch($addNewAttributeSet)){
  
  createAttributeSet($addNewAttributeSet);
  createAttributeGroup($addNewAttributeGroup,$addNewAttributeSet);
}else{
  echo "<br>Attribute Set".$addNewAttributeSet."Available";
  
}
/*------------------------End Create AttributeSet & AttributeGroup--------------------*/

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/
 
  assignmentAttributeToAttributeSet($addNewAttributeSet,$attributeToAttributeGroup);

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/          
 
 /*=============End  Mobiles AttributeSet ,AttributeGroup, Attribute=============*/

/*=========== Start Audio AttributeSet ,AttributeGroup, Attribute ==============*/
$addNewAttributeSet = "Audio";
$addNewAttributeGroup = array("Sound player","Speakers","Headphones & Earphones");
$attributeToAttributeGroup = array(
                              "General"=>
                              array(
                                "marcas",
                                "cor",
                                ),
                              "Headphones & Earphones"=>
                              array(
                                "earpiece_style",
                                "headband_style",
                                "features_microphone",
                                "features_wireless"
                                ),
                            );

/*-------------------------Create AttributeSet & AttributeGroup ----------------------*/
if(!attributeSetSearch($addNewAttributeSet)){
  
  createAttributeSet($addNewAttributeSet);
  createAttributeGroup($addNewAttributeGroup,$addNewAttributeSet);
}else{
  echo "<br>Attribute Set".$addNewAttributeSet."Available";
  
}
/*------------------------End Create AttributeSet & AttributeGroup--------------------*/

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/
 
  assignmentAttributeToAttributeSet($addNewAttributeSet,$attributeToAttributeGroup);

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/          
 
 /*=============End  Audio AttributeSet ,AttributeGroup, Attribute=============*/

 /*=========== Start Computer Parts & Components AttributeSet ,AttributeGroup, Attribute ==============*/
$addNewAttributeSet = "Computer Parts & Components";
$addNewAttributeGroup = array("Coolers and casemod's","Cases","Sources","Memories","Video Cards","Motherboards","Processors","Cables & Adaptors");
$attributeToAttributeGroup = array(
                              "General"=>
                              array(
                                "marcas",
                                "cor",
                                 "power_type",
                                 "watts",
                                 "memoria",
                                ),
                              "Coolers and casemod's"=>
                              array(
                                "usage",
                                "cooler"
                                ),
                              "Cases"=>
                              array(
                                "power_included",
                                "front_audio",
                                "front_microphone",
                                "front_usb_1_1",
                                "front_usb_2_0",
                                "front_usb_3_0",
                                ),
                              "Memories"=>
                              array(
                                "capacidade_memoria",
                                
                                ),
                              "Video Cards"=>
                              array(
                                "interface_de_memoria",
                                ),
                              "Motherboards"=>
                              array(
                                "slots_de_memoria",
                                ),
                              "Processors"=>
                              array(
                                "processador",
                                ),
                              "Cables & Adaptors"=>
                               array(
                                "connectivity_wireless",
                                "connectivity_network/RJ45",
                                "connectivity_usb",
                                "connectivity_hdmi",
                                ),
                            );

/*-------------------------Create AttributeSet & AttributeGroup ----------------------*/
if(!attributeSetSearch($addNewAttributeSet)){
  
  createAttributeSet($addNewAttributeSet);
  createAttributeGroup($addNewAttributeGroup,$addNewAttributeSet);
}else{
  echo "<br>Attribute Set".$addNewAttributeSet."Available";
  
}
/*------------------------End Create AttributeSet & AttributeGroup--------------------*/

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/
 
  assignmentAttributeToAttributeSet($addNewAttributeSet,$attributeToAttributeGroup);

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/          
 
 /*=====End Computer Parts & Components  AttributeSet ,AttributeGroup, Attribute===========*/

 /*=========== Start Software AttributeSet ,AttributeGroup, Attribute ==============*/
$addNewAttributeSet = "Software";
$addNewAttributeGroup = array("");
$attributeToAttributeGroup = array(
                              "General"=>
                              array(
                                "marcas",
                                ),
                              
                            );

/*-------------------------Create AttributeSet & AttributeGroup ----------------------*/
if(!attributeSetSearch($addNewAttributeSet)){
  
  createAttributeSet($addNewAttributeSet);
  createAttributeGroup($addNewAttributeGroup,$addNewAttributeSet);
}else{
  echo "<br>Attribute Set".$addNewAttributeSet."Available";
  
}
/*------------------------End Create AttributeSet & AttributeGroup--------------------*/

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/
 
  assignmentAttributeToAttributeSet($addNewAttributeSet,$attributeToAttributeGroup);

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/          
 
 /*=============End  Software AttributeSet ,AttributeGroup, Attribute=============*/

 /*=========== Start Computer Accessories AttributeSet ,AttributeGroup, Attribute ==============*/
$addNewAttributeSet = "Computer Accessories";
$addNewAttributeGroup = array("Keyboard","Mouse","Protection","Monitors",);
$attributeToAttributeGroup = array(
                             "General"=>
                              array(
                                "marcas",
                                "cor",
                                 "connectivity_wireless",
                                 "connectivity_usb",
                                ),
                              "Monitors"=>
                              array(
                                "connectivity_hdmi",
                                "connectivity_vga",
                                "connectivity_dvi",
                                ),
                              
                            );

/*-------------------------Create AttributeSet & AttributeGroup ----------------------*/
if(!attributeSetSearch($addNewAttributeSet)){
  
  createAttributeSet($addNewAttributeSet);
  createAttributeGroup($addNewAttributeGroup,$addNewAttributeSet);
}else{
  echo "<br>Attribute Set".$addNewAttributeSet."Available";
  
}
/*------------------------End Create AttributeSet & AttributeGroup--------------------*/

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/
 
  assignmentAttributeToAttributeSet($addNewAttributeSet,$attributeToAttributeGroup);

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/          
 
 /*=============End  Computer Accessories AttributeSet ,AttributeGroup, Attribute=============*/


 /*=========== Start Projectors AttributeSet ,AttributeGroup, Attribute ==============*/
$addNewAttributeSet = "Projectors";
$addNewAttributeGroup = array("");
$attributeToAttributeGroup = array(
                              "General"=>
                              array(
                                "marcas",
                                "cor"
                                ),
                              
                            );

/*-------------------------Create AttributeSet & AttributeGroup ----------------------*/
if(!attributeSetSearch($addNewAttributeSet)){
  
  createAttributeSet($addNewAttributeSet);
  createAttributeGroup($addNewAttributeGroup,$addNewAttributeSet);
}else{
  echo "<br>Attribute Set".$addNewAttributeSet."Available";
  
}
/*------------------------End Create AttributeSet & AttributeGroup--------------------*/

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/
 
  assignmentAttributeToAttributeSet($addNewAttributeSet,$attributeToAttributeGroup);

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/          
 
 /*=============End  Projectors AttributeSet ,AttributeGroup, Attribute=============*/

 /*=========== Start Printers AttributeSet ,AttributeGroup, Attribute ==============*/
$addNewAttributeSet = "Printers";
$addNewAttributeGroup = array("Ink jet","Laser","Multifunctional jet","Multifunction Laser");
$attributeToAttributeGroup = array(
                              "General"=>
                              array(
                                "marcas",
                                "cor",
                                "connectivity_wireless",
                                "connectivity_network/RJ45",
                                "connectivity_usb",
                                "function_print",
                                "function_scan",
                                "function_copy",
                                "function_fax"
                                ),
                              
                            );

/*-------------------------Create AttributeSet & AttributeGroup ----------------------*/
if(!attributeSetSearch($addNewAttributeSet)){
  
  createAttributeSet($addNewAttributeSet);
  createAttributeGroup($addNewAttributeGroup,$addNewAttributeSet);
}else{
  echo "<br>Attribute Set".$addNewAttributeSet."Available";
  
}
/*------------------------End Create AttributeSet & AttributeGroup--------------------*/

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/
 
  assignmentAttributeToAttributeSet($addNewAttributeSet,$attributeToAttributeGroup);

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/          
 
 /*=============End  Printers AttributeSet ,AttributeGroup, Attribute=============*/

 /*=========== Start Cameras AttributeSet ,AttributeGroup, Attribute ==============*/
$addNewAttributeSet = "Cameras";
$addNewAttributeGroup = array("Digicam","Security Cameras","Webcam");
$attributeToAttributeGroup = array(
                              "General"=>
                              array(
                                "marcas",
                                "cor"
                                ),
                              
                            );

/*-------------------------Create AttributeSet & AttributeGroup ----------------------*/
if(!attributeSetSearch($addNewAttributeSet)){
  
  createAttributeSet($addNewAttributeSet);
  createAttributeGroup($addNewAttributeGroup,$addNewAttributeSet);
}else{
  echo "<br>Attribute Set".$addNewAttributeSet."Available";
  
}
/*------------------------End Create AttributeSet & AttributeGroup--------------------*/

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/
 
  assignmentAttributeToAttributeSet($addNewAttributeSet,$attributeToAttributeGroup);

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/          
 
 /*=============End  Cameras AttributeSet ,AttributeGroup, Attribute=============*/

 /*=========== Start Networking AttributeSet ,AttributeGroup, Attribute ==============*/
$addNewAttributeSet = "Networking";
$addNewAttributeGroup = array("ADSL Modems/Internet Modems","Routers and Switches","Network Card");
$attributeToAttributeGroup = array(
                              "General"=>
                              array(
                                "marcas",
                                "cor"
                                ),
                              
                            );

/*-------------------------Create AttributeSet & AttributeGroup ----------------------*/
if(!attributeSetSearch($addNewAttributeSet)){
  
  createAttributeSet($addNewAttributeSet);
  createAttributeGroup($addNewAttributeGroup,$addNewAttributeSet);
}else{
  echo "<br>Attribute Set".$addNewAttributeSet."Available";
  
}
/*------------------------End Create AttributeSet & AttributeGroup--------------------*/

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/
 
  assignmentAttributeToAttributeSet($addNewAttributeSet,$attributeToAttributeGroup);

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/          
 
 /*=============End  Networking AttributeSet ,AttributeGroup, Attribute=============*/
/*=========== Start Storage AttributeSet ,AttributeGroup, Attribute ==============*/
$addNewAttributeSet = "Storage";
$addNewAttributeGroup = array("Blu Ray","CD/DVD R-RW","Memory card","Pen drive","External HD","HD Notebook","SATA III","SSD");
$attributeToAttributeGroup = array(
                              "General"=>
                              array(
                                "marcas",
                                "cor",
                                "capacidade_armazenamento"
                                ),
                              
                            );

/*-------------------------Create AttributeSet & AttributeGroup ----------------------*/
if(!attributeSetSearch($addNewAttributeSet)){
  
  createAttributeSet($addNewAttributeSet);
  createAttributeGroup($addNewAttributeGroup,$addNewAttributeSet);
}else{
  echo "<br>Attribute Set".$addNewAttributeSet."Available";
  
}
/*------------------------End Create AttributeSet & AttributeGroup--------------------*/

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/
 
  assignmentAttributeToAttributeSet($addNewAttributeSet,$attributeToAttributeGroup);

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/          
 
 /*=============End  Storage AttributeSet ,AttributeGroup, Attribute=============*/
 /*=========== Start Gaming AttributeSet ,AttributeGroup, Attribute ==============*/
$addNewAttributeSet = "Gaming";
$addNewAttributeGroup = array("Gaming Console","Games software","Game accessories","Gaming Chairs");
$attributeToAttributeGroup = array(
                              "General"=>
                              array(
                                "marcas",
                                "cor",
                                "console"
                                ),
                              
                            );

/*-------------------------Create AttributeSet & AttributeGroup ----------------------*/
if(!attributeSetSearch($addNewAttributeSet)){
  
  createAttributeSet($addNewAttributeSet);
  createAttributeGroup($addNewAttributeGroup,$addNewAttributeSet);
}else{
  echo "<br>Attribute Set".$addNewAttributeSet."Available";
  
}
/*------------------------End Create AttributeSet & AttributeGroup--------------------*/

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/
 
  assignmentAttributeToAttributeSet($addNewAttributeSet,$attributeToAttributeGroup);

 /*------------Assignment Atrribute To AttributeSet and Attributegroup-----------------*/          
 
 /*=============End  Gaming AttributeSet ,AttributeGroup, Attribute=============*/
