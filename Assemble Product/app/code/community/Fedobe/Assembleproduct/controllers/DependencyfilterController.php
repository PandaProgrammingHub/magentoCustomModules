<?php
class Fedobe_Assembleproduct_DependencyfilterController extends Mage_Core_Controller_Front_Action {        
    
    public function indexAction() {
   
        $productId = $this->getRequest()->getParam('productId');
    	$parentId  = $this->getRequest()->getParam('parent_Id');
    	$optionId  = $this->getRequest()->getParam('option_id');
 
        
        $optionId  = str_replace("bundle-option-","",$optionId );
 
        $productId = trim($productId);
        $parentId = trim($parentId);
        $optionId = trim($optionId);
        
       
       
       
        $result = $this->getDependencyCollectionForCurrentProductId($productId,$parentId);
        //$jsonData = json_encode($result->getData());
       // $this->getResponse()->setHeader('Content-type', 'application/json');
       // $this->getResponse()->setBody($jsonData);
       //  print_r($result->getData()); 
       echo json_encode($result->getData());
            
       
   }

   public function productnameAction() {
    $productId = $this->getRequest()->getParam('productId');
    $parentId  = $this->getRequest()->getParam('parent_Id');
   
   $_product = Mage::getModel('catalog/product')->load($parentId);

    $selectionCollection = $_product->getTypeInstance(true)->getSelectionsCollection(
                            $_product->getTypeInstance(true)->getOptionsIds($_product), $_product
                    );
                foreach($selectionCollection as $option){
                if($productId == $option->entity_id){
                  $value = $option->selection_id;
                }
              }
    if($value){echo $value;}
    else{ echo "0";}
   }
  public function optionsAction(){
         $productId = $this->getRequest()->getParam('productId');
         
         $_product = Mage::getModel('catalog/product')->load($productId);
    	 $optionCollection = $_product->getTypeInstance()->getOptionsCollection();
	echo json_encode($optionCollection->getData());
       
    }
   


   public function getPrimaryOptionCollection($parentId){
    	$primaryOptionCollection = $this->getPrimaryoptionModel()
                          ->getCollection()
                          ->addFieldToFilter('product_id',array('eq'=>$parentId));
         return $primaryOptionCollection;
    }
    
   public function getDependencyCollectionForCurrentProductId($productId,$parentId){
       $primaryOptionCollection = $this->getPrimaryOptionCollection($parentId);
            foreach ($primaryOptionCollection as $val) {
                $primaryoptionId = $val->getPrimaryoption();
            }
       $collection = $this->getDependencyoptionModel()->getCollection()
                   ->addFieldToFilter('primaryoption_id',array('eq'=>$primaryoptionId ))
                   ->addFieldToFilter('primaryoption_product_id',array('eq'=>$productId));
     return $collection;   
   }
      

   public function getPrimaryoptionModel(){

        return Mage::getModel('fedobe_assembleproduct/primaryoption');
   }


  public function getDependencyoptionModel(){

        return Mage::getModel('fedobe_assembleproduct/dependencyoption');
   }



}