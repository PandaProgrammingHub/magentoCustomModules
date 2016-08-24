<?php
class Fedobe_Assembleproduct_Adminhtml_DependencyController extends Mage_Adminhtml_Controller_Action{
    
    protected function _initAction(){
        $this->loadLayout()
             ->_setActiveMenu('assembleproduct');
             
        return $this;
    }
    
    public function indexAction(){
        $this->_initAction()
             ->renderLayout();
    }

  public function editAction(){
        $id = $this->getRequest()->getParam('id', null);
        $model = Mage::getModel('catalog/product');
        if($id){
            $model->load((int)$id);
            if($model->getId()){
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if($data){
                $model->setData($data)->setId($id);
        }
            }else{
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('assembleproduct')->__('Category does not exist'));
                    $this->_redirect('*/*/');
            }
        }
     Mage::register('assembleproduct', $model);   
    $this->_title($this->__('assembleproduct'))->_title($this->__((Mage::registry('assembleproduct')->getName())));
    $this->loadLayout();
    $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
    $this->_addContent($this->getLayout()->createBlock('assembleproduct/adminhtml_customproduct_edit'));
    $this->_addLeft($this->getLayout()->createBlock('assembleproduct/adminhtml_customproduct_edit_tabs'));
    $this->renderLayout();
        
    }

   public function saveAction(){
        if($data = $this->getRequest()->getPost()){
            
            $this->savePrimaryoption($data);
            $this->saveDependencyoption($data);

            if($this->getRequest()->getParam('id')) {
                $page_id = $this->getRequest()->getParam('id');
            }else{
                $page_id = Mage::registry('page_id');
            }
            $storeId = $this->getRequest()->getParam('store');
            if ($page_id && $this->getRequest()->getParam('back', false)) {
                return $this->_redirect('*/*/edit', array('id' => $page_id, 'store' => $storeId));
            }

            return $this->_redirect('*/*/');
        }
        $this->_getSession()->addError($this->__('There was no data to save.'));
        return $this->_redirect('*/*/');
            
        
    }
  public function deleteAction(){

        if ($proId = $this->getRequest()->getParam('id')) {
            
            $primaryOptionRows = $this->getPrimaryoptionModel()->getCollection()
                                ->addFieldToFilter('product_id',array('eq'=>$proId ));
          foreach ($primaryOptionRows as $row) {
              $priId = $row->getPrimaryoption_id();
              $primaryOptionId = $row->getPrimaryoption();
             }
            if($priId){
              $primaryOptionModel = $this->getPrimaryoptionModel()->load($priId);
                  if($primaryOptionId){
                     $dependencyOptionRows = $this->getDependencyoptionModel()->getCollection()
                                            ->addFieldToFilter('primaryoption_id',array('eq'=>$primaryOptionId));
                     foreach ($dependencyOptionRows as $row) {
                          $depId[] = $row->getDependencyoption_id();
                       }
                    if($depId){
                        
                      $dependencyOptionModel = $this->getDependencyoptionModel()->getCollection()
                                               ->addFieldToFilter('dependencyoption_id', array('in' => $depId));;
                      }
                      
                  }
                try {
                      
                     $primaryOptionModel->delete();
                     foreach ($dependencyOptionModel as $val) {
                    $objModel = $this->getDependencyoptionModel()->load($val->getId());
                    $objModel->delete();
                    }
                     
                     
                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The Dependencyoption was deleted.'));
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                }
            }

        }
        $this->_redirect('*/*/'); 
  }



   public function getPrimaryoptionModel(){

        return Mage::getModel('assembleproduct/primaryoption');
   }


  public function getDependencyoptionModel(){

        return Mage::getModel('assembleproduct/dependencyoption');
   }


  public function getOptionslable($proId){

    $_product = Mage::getModel('catalog/product')->load($proId);
    $optionCollection = $_product->getTypeInstance()->getOptionsCollection();
    return $optionCollection;
    }


    public function savePrimaryoption($data){
      
        $proId = $data['productId'];
        $primaryoptionValueId = $data['final_primary_option_id'];
        $model = $this->getPrimaryoptionModel();
        $finallData = array(
                            'product_id'=>$proId,
                            'primaryoption'=>$primaryoptionValueId
                         );
        $rows = $this->getPrimaryoptionModel()->getCollection()
                   ->addFieldToFilter('product_id',array('eq'=>$proId ));
          foreach ($rows as $row) {
              $id=$row->getPrimaryoption_id();
             }

            if($id){

                $model = $this->getPrimaryoptionModel()->load($id)->addData($finallData);
            try {
        $model->setPrimaryoption_id($id)->save();
         Mage::getSingleton('adminhtml/session')->addSuccess('Record successfully updated');
        } catch (Exception $e){
        Mage::getSingleton('adminhtml/session')->addError($this->__($e->getMessage()));
            }
          }else{
                 $model->setData($finallData);
            try {
                 $model->save();
               Mage::getSingleton('adminhtml/session')->addSuccess('Record successfully saved');
              } catch (Exception $e){
              Mage::getSingleton('adminhtml/session')->addError($this->__($e->getMessage()));
                }
            }

    }




    public function saveDependencyoption($data){
        $proId = $data['productId'];
        $primaryoptionValueId = $data['final_primary_option_id'];
        $primaryoptionProductId = $data[$data['final_primary_option_id']];
        
        $successSaveCounter = 0; 
        $successUpdateCounter = 0; 
        $errorSaveCounter = 0;
        $errorUpdateCounter = 0;

        $optionCollection = $this->getOptionslable($proId);
        foreach ($optionCollection as $option) {
            $option_ids = $option->option_id;
            $option_labels = $option->default_title; 
             $model = $this->getDependencyoptionModel();
             if( $option_ids != $data['final_primary_option_id'] ){
              $dependentoptionProductId = implode(",", $data[$option->option_id]);
              $finallData = array(
                        'primaryoption_id'=>$primaryoptionValueId,
                        'primaryoption_product_id'=>$primaryoptionProductId ,
                        'dependentoption_id'=>$option->option_id,
                        'dependentoption_product_id'=>$dependentoptionProductId
                        );

            $rows = $this->getDependencyoptionModel()->getCollection()
                   ->addFieldToFilter('primaryoption_product_id',array('eq'=>$primaryoptionProductId ))
                   ->addFieldToFilter('dependentoption_id',array('eq'=>$option->option_id));
             
            
                   
            foreach ($rows as $row) {
              $id=$row->getDependencyoption_id();
             }
             
            if($id){

                $model = $this->getDependencyoptionModel()->load($id)->addData($finallData);
            try {
        $model->setDependencyoption_id($id)->save();
         //Mage::getSingleton('adminhtml/session')->addSuccess('Record successfully updated');
          $successUpdateCounter++; 
           } catch (Exception $e){
        //Mage::getSingleton('adminhtml/session')->addError($this->__($e->getMessage()));
            $errorUpdateCounter++;
      }
          }else{
                 $model->setData($finallData);
            try {
                 $model->save();
               //Mage::getSingleton('adminhtml/session')->addSuccess('Record successfully saved');
      
        $successSaveCounter++;


              } catch (Exception $e){
              //Mage::getSingleton('adminhtml/session')->addError($this->__($e->getMessage()));
        
        $errorSaveCounter++;


                }
            }
        }
      }
      if(!$errorUpdateCounter){
        if(!$successSaveCounter){
        if($successUpdateCounter){
         Mage::getSingleton('adminhtml/session')->addSuccess('Record successfully updated');
        }
      }
    }
      else{
        Mage::getSingleton('adminhtml/session')->addError($this->__($e->getMessage()));
      }

      if(!$errorSaveCounter){
        if($successSaveCounter){
         Mage::getSingleton('adminhtml/session')->addSuccess('Record successfully updated');
        }
      }
      else{
        Mage::getSingleton('adminhtml/session')->addError($this->__($e->getMessage()));
      }
}

   

    
}
?>

