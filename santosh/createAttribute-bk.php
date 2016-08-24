<?php
/*===================Excuting Code out side of magento package=================*/ 

				error_reporting(E_ALL);
				ini_set('display_errors', 1);
				set_time_limit(0);
				ini_set('memory_limit', '890M');
				require_once '../app/Mage.php';
				Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);

/*================================== End ======================================*/




/*---- Pass attributeLable and attribute_code like "attributeLable" => "attribute_code"----- */

$addNewAttribute =  array(
                        
                        "Earpiece style" => "earpiece_style",
                        "Headband style" => "headband_style",
                        "Features: Microphone" => "features_microphone",
                        "Features: Wireless" => "features_wireless",
                        "Usage" => "usage",
                        "Power Included"=>"power_included",
                        "Power Type"=>"power_type",
                        "Connectivity: wireless"=>"connectivity_wireless",
                        "Connectivity: network/RJ45"=>"connectivity_network/RJ45",
                        "Connectivity: USB"=>"connectivity_usb",
                        "Connectivity: HDMI"=>"connectivity_hdmi",
                        "Connectivity: VGA"=>"connectivity_vga",
                        "Connectivity: dvi"=>"connectivity_dvi",
                        "Function: print"=>"function_print",
                        "Function: scan"=>"function_scan",
                        "Function: copy"=>"function_copy",
                        "Function: fax"=>"function_fax",
                        "Console"=>"console",
                        "Material"=>"material",
                        ); 
/*----- Pass AttributeOptions to AttributeGroup -------- */ 
$addNewAttributeOptions =  array(
                 
		 "earpiece_style" => array("On-ear", "in-ear", "over-ear"),
                 "headband_style" => array("Over-the-hear", "behin-the-neck","none"),
                 "features_microphone" => array("No", "Yes"),
                 "features_wireless" => array("No", "Yes"),
                 "usage" => array("Case", "Processor"),   
                 "power_included" => array("No", "Yes"),   
                 "power_type" => array("No", "Yes"),   
                 "power_type" => array("ATX", "ITX"),   
                 "power_type" => array("ATX", "ITX"),
                 "connectivity_wireless" => array("No", "Yes"),   
                 "connectivity_network/RJ45" => array("No", "Yes"),   
                 "connectivity_usb" => array("No", "Yes"),   
                 "connectivity_hdmi" => array("No", "Yes"),   
                 "connectivity_vga" => array("No", "Yes"),   
                 "connectivity_dvi" => array("No", "Yes"),   
                 "function_print" => array("No", "Yes"),   
                 "function_scan" => array("No", "Yes"),   
                 "function_copy" => array("No", "Yes"),   
                 "function_fax" => array("No", "Yes"),   
                 "console" => array("Sony PS3", "Sony PS4", "Xbox 360"),   
                 "material" => array("Leather", "etc"),   
								  );


/*------------------Create Attribute -------------------------- */
foreach ($addNewAttribute as $key => $value) {

	if(!attributeSearch($value)){
	createAttribute($key,$value,$addNewAttributeOptions[$value]);
}else{
	echo "<br>Attribute '".$value. "' Available";
}
	
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


function createAttribute($key,$value,$addNewAttributeOptions){
	$model=Mage::getModel('eav/entity_setup','core_setup');
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
    
      getAttributeOptions($addNewAttributeOptions)
  
  ),
);
   
   $model->addAttribute('catalog_product',$value,$attrdata);
}

function entityTypeFilter(){
	$entityTypeId = Mage::getModel('catalog/product')
                ->getResource()
                ->getEntityType()
                ->getId();
     return $entityTypeId;
}
function getAttributeOptions($addNewAttributeOptions){
   $result = array();
    foreach ($addNewAttributeOptions as $anotherkey => $val) {
         $result[] = $val;
         
    }
return $result;
}

/*============================End All Function  ===============================*/