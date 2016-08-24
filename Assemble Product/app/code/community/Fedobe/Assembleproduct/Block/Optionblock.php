<?php
class Fedobe_Assembleproduct_Block_Optionblock extends Mage_Core_Block_Template{
    
     public function __construct()
    {
        $this->setTemplate('fedobe/assembleproduct/optionblock.phtml');
    }
    
    public function getCurrentProductId(){
    	
        return $this->getProduct()->getId();
    }

    public function getCurrentBundleProductOptionsCollection(){
    	$_product = Mage::getModel('catalog/product')->load($this->getCurrentProductId());
		$optionCollection = $_product->getTypeInstance()->getOptionsCollection();
		return $optionCollection;
    }


    public function getPrimaryOptionCollection(){
    	$primaryOptionCollection = $this->getPrimaryoptionModel()
                          ->getCollection()
                          ->addFieldToFilter('product_id',array('eq'=>$this->getCurrentProductId()));
         return $primaryOptionCollection;
    }


    public function getDependencyOption_PrimaryIdCollection(){
            $primaryOptionCollection = $this->getPrimaryOptionCollection();
            foreach ($primaryOptionCollection as $val) {
                $primaryoptionId = $val->getPrimaryoption();
            }
            $dependencyOption_PrimaryIdCollection = $this->getDependencyoptionModel()
                          ->getCollection()
                          ->addFieldToFilter('primaryoption_id',array('eq'=>$primaryoptionId));
            return $dependencyOption_PrimaryIdCollection;
    }   


    public function getprimaryoptionProductIds(){
             $_product = Mage::getModel('catalog/product')->load($this->getCurrentProductId());
            $primaryOptionCollection = $this->getPrimaryOptionCollection();
            $primaryoptionProductIds = array();
            foreach ($primaryOptionCollection as $val) {
                    $primaryoption = $val->getPrimaryoption();
                }

                $selectionCollection = $_product->getTypeInstance(true)->getSelectionsCollection(
                            $_product->getTypeInstance(true)->getOptionsIds($_product), $_product
                    );
                foreach($selectionCollection as $option){
                if($primaryoption == $option->option_id){
                  $primaryoptionProductIds[] = $option->product_id; 
                }
              }
              return $primaryoptionProductIds;
    }

    public function getPrimaryoptionModel(){
         return Mage::getModel('fedobe_assembleproduct/primaryoption');
   }

   public function getDependencyoptionModel(){
         return Mage::getModel('fedobe_assembleproduct/dependencyoption');
   }

}