<?php
class Fedobe_Imggallery_Adminhtml_ImageController extends Mage_Adminhtml_Controller_Action{
    protected function _initAction(){
        $this->loadLayout()->_setActiveMenu('imggallery/imgdetails')->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Manage Image'),
                Mage::helper('adminhtml')->__('Image Manager')
                
        );
        return $this;
    }
    
    public function indexAction(){
        $this->_initAction()->renderLayout();
    }


    public function newAction(){
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('imggallery/adminhtml_image_edit'))->_addLeft($this->getLayout()->createBlock('imggallery/adminhtml_image_edit_tabs'));
        $this->renderLayout();
    }
    
    public function saveAction(){
        if($data = $this->getRequest()->getPost()){
         //  echo '<pre>'; print_r($data);
            $model = Mage::getModel('imggallery/imgdetails');
            $id = $this->getRequest()->getParam('id'); 
            foreach ($data as $key => $value){
                echo is_array($value);
                        
                if(is_array($value)){
                    $data[$key] = implode(',', $this->getRequest()->getParam($key));
                }
            }
            
            if($id){
                $model->load($id);
            }
            
            if(isset($_FILES['gallery_img']['name']) and (  file_exists($_FILES['gallery_img']['tmp_name']))){
                try {
                    $uploader = new Varien_File_Uploader('gallery_img');
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $path = Mage::getBaseDir('media'). DS .'imggallery';
                    $imgName = explode('.', $_FILES['gallery_img']['name']);
                    $imgName[0] = $imgName[0]. '-'.'gallery_img'.'-'.date('Y-m-d H-i-s');
                    $imgName = implode('.',$imgName);
                    $imgName = preg_replace('/\s+/', '-', $imgName);
                    $uploader->save($path, $imgName);
                    $data['gallery_img'] = 'imggallery'.DS.$imgName;
                } catch (Exception $ex) {
                    
                }
            }else{
                if(isset($data['gallery_img']) && $data['gallery_img']['delete'] == 1){
                    // delete image file
                    $image = explode(',',$data['gallery_img']);
                    unlink(Mage::getBaseDir('media').DS.$image[1]);
                    // set db blank entry
                    $data['gallery_img'] = ''; 
        }else{
                    unset($data['gallery_img']);
        }
            }
            
            
            
            
            $model->setData($data);
            Mage::getSingleton('adminhtml/session')->setFormData($data);
            try{
                if ($id){
                    $model->setId($id);
                }

                $model->save();
                if (!$model->getId()){
                    Mage::throwException(Mage::helper('imggallery')->__('Error saving slide details'));
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('imggallery')->__('Details was successfully saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                // The following line decides if it is a "save" or "save and continue"
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                }else{
                    $this->_redirect('*/*/');
                }
            }catch(Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                if ($model && $model->getId()){
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                }else {
                    $this->_redirect('*/*/');
                } 
            }
            return;
         }
         Mage::getSingleton('adminhtml/session')->addError(Mage::helper('imggallery')->__('No data found to save'));
         $this->_redirect('*/*/'); 
    }
    
    public function editAction(){
        $id = $this->getRequest()->getParam('id', null);
        $model = Mage::getModel('imggallery/imgdetails');
        if($id){
            $model->load((int)$id);
            if($model->getId()){
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if($data){
                $model->setData($data)->setId($id);
        }
            }else{
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('imagegallery')->__('Category does not exist'));
                    $this->_redirect('*/*/');
            }
        }
        Mage::register('image_data', $model);
    $this->_title($this->__('imggallery'))->_title($this->__('Edit Category'));
    $this->loadLayout();
    $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
    $this->_addContent($this->getLayout()->createBlock('imggallery/adminhtml_image_edit'))
    ->_addLeft($this->getLayout()->createBlock('imggallery/adminhtml_image_edit_tabs'));
    $this->renderLayout();
        
    }
    
    public function deleteAction(){
        if($this->getRequest()->getParam('id') > 0){
            try {
                $model = Mage::getModel('imggallery/imgdetails');
                $id = $this->getRequest()->getParam('id');
                $objModel = $model->load($id);
                $path = Mage::getBaseDir('media');
                unlink($path.DS.$objModel->categoryImg);
                $objModel->setId($id)->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
        $this->_redirect('*/*/');
                
            } catch (Exception $ex) {
                Mage::getSingleton('adminhtml/session')->addError($ex->getMessage());
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/'); 
    }
    
    public function massDeleteAction(){
        // Here the id is got from the function _prepareMassAction in Grid.php. ($this->getMassactionBlock()->setFormFieldName('id');)
        $ids = $this->getRequest()->getParam('id');
        if(!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('imggallery')->__('Please select slide(s).'));
        }else{
            try{
                $imageModel = Mage::getModel('imggallery/imgdetails');
                foreach($ids as $id){
                    $objModel = $imageModel->load($id);
                    $path = Mage::getBaseDir('media');
                    unlink($path.DS.$objModel->categoryImg);
                    $objModel->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('imggallery')->__('Total of %d record(s) were deleted.', count($ids)));
            }catch(Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
}
?>

