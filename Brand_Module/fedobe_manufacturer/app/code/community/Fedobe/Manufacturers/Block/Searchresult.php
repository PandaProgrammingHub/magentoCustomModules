<?php
class Fedobe_Manufacturers_Block_Searchresult extends Fedobe_Manufacturers_Block_Advanced_Result {
    public function getBrandid(){
        if(Mage::registry('current_product')->getId()){
            $attribute_to_select = trim(Mage::getStoreConfig('manufacturers/general/attr_code'));
            $attribute_to_select = ($attribute_to_select) ? $attribute_to_select : 'manufacturer';
            $obj = Mage::getModel('catalog/product');
            $_product = $obj->load(Mage::registry('current_product')->getId());
            $attr_id = $_product->getData($attribute_to_select);
            if($attr_id)
                return $attr_id;
            else
                return FALSE;
        }else{
            return FALSE;
        }
    }
    
    public function getSameBrandCollection($page_size){
        $attr_id = $this->getBrandid();
        if($attr_id){
            $attribute_to_select = trim(Mage::getStoreConfig('manufacturers/general/attr_code'));
            $attribute_to_select = ($attribute_to_select) ? $attribute_to_select : 'manufacturer';
            //Here to load all products from same brand
            $collection = Mage::getModel('catalog/product')->getCollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('status', 1)
                        ->addAttributeToFilter('visibility', 4)
                        ->addAttributeToFilter($attribute_to_select,$attr_id)
                        ->addFieldToFilter( 'entity_id', 
                            array( 'nin' => array (Mage::registry('current_product')->getId()) )
                        )
                        ->setOrder('created_at', 'DESC')
                        ->setPageSize($page_size)
                        ->setCurPage(1);
            $collection->getSelect()->order(new Zend_Db_Expr('RAND()'));
            return $collection;
        }else{
            return array();
        }
    }
    
    public function getAttributeData(){
        $attrdata = array();
        $attr_id = $attr_id = $this->getBrandid();
        if($attr_id){
            $brandpagemodel = Mage::getModel('landingpage/page')->load($manufacuterdata[$manufacturers[$i]['value']]['page_id']);
            
            
            if(isset($tmdata['page_id'])){
                    $url = $brandpagemodel->getUrl();
                }else{
                    $url = Mage::helper('cms')->getPageTemplateProcessor()->filter('{{store url="catalogsearch/advanced/result" _query="' . $manu_code . '=' . $manufacturers[$i]['value'] . '"}}');
                }
            
            
            $tmdata = $brandpagemodel->getData();
        }
    }
    
    
}
