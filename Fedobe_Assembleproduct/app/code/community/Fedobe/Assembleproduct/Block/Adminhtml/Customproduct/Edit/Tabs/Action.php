<?php
/**
 * Adminhtml customer action tab
 *
 */
class Fedobe_Assembleproduct_Block_Adminhtml_Customproduct_Edit_Tabs_Action
    extends Mage_Adminhtml_Block_Widget
    
{

    public function __construct()
    {   parent::_construct();
        $this->setTemplate('fedobe/assembleproduct/action.phtml');

    }

    public function getCurrentProductId(){
        $currentproductId = Mage::registry('assembleproduct')->getId();
        return $currentproductId;
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

           public function  getDependencyOptionArray(){

                $primaryoptionProductIds = $this->getprimaryoptionProductIds();
                $dependencyOptionCollection = $this->getDependencyOption_PrimaryIdCollection();
                $dependencyOptionCollectionArray = array();
                
                foreach ($primaryoptionProductIds as $key => $value) {
                      foreach ($dependencyOptionCollection as $val) {
                      if($value == $val->getPrimaryoption_product_id()){

                        $dependencyOptionCollectionArray[$value][] = array($val->getDependentoption_id() => $val->getDependentoption_product_id() );
                      }
                  }           
                }
                return $dependencyOptionCollectionArray;
            
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

                    return Mage::getModel('assembleproduct/primaryoption');
               }


              public function getDependencyoptionModel(){

                    return Mage::getModel('assembleproduct/dependencyoption');
               }
            public function getOptionsLable($id){
          $optionCollection = $this->getCurrentBundleProductOptionsCollection();
          $optionsLable;

          foreach ($optionCollection as $option) {
        if($option->getOption_id() == $id){
            $optionsLable = $option->default_title;
          }
        }
        return $optionsLable;
    }
    public function getProductName($id){
    $_product = Mage::getModel('catalog/product')->load($id);
    return $_product->getName();

    }
    public function getProductSku($id){
    $_product = Mage::getModel('catalog/product')->load($id);
    return $_product->getSku();

    }
   
}
