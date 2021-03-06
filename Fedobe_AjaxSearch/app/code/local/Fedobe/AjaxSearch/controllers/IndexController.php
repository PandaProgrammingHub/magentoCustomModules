<?php
class Fedobe_AjaxSearch_IndexController  extends Mage_Core_Controller_Front_Action
{
    private function _sendJson(array $data = array())
    {
        @header('Content-type: application/json');
        echo json_encode($data);
        exit();
    }

    private function _trim($text, $len, $delim = '...')
    {
        if (function_exists("mb_strstr")) {
            $strlen = 'mb_strlen';
            $strpos = 'mb_strpos';
            $substr = 'mb_substr';
        } else {
            $strlen = 'strlen';
            $strpos = 'strpos';
            $substr = 'substr';
        }
        
        
        if ($strlen($text) > $len) {
            $whitespaceposition = $strpos($text, " ", $len) - 1;
            if($whitespaceposition > 0) {
                $text = $substr($text, 0, ($whitespaceposition + 1));
            }
            return $text . $delim;
        }
        return $text;
    }

    protected function _getProductCollection($query, $store) 
    {
        if (class_exists('Mage_CatalogSearch_Model_Resource_Search_Collection')) {
            $collection = Mage::getResourceModel('ajaxsearch/product_collection');
            /* @var $collection TM_AjaxSearch_Model_Mysql4_Product_Collection */
            $collection->addSearchFilter($query);
        } else {
            $collection = Mage::getResourceModel('ajaxsearch/collection')
                ->getProductCollection($query);
            /* @var $collection TM_AjaxSearch_Model_Mysql4_Collection */
        }
        $collection->addStoreFilter($store)
            ->addUrlRewrite()
            ->addAttributeToSort(
                Mage::getStoreConfig('ajax_search/general/sortby'), 
                Mage::getStoreConfig('ajax_search/general/sortorder')
            )
            ->setPageSize(Mage::getStoreConfig('ajax_search/general/productstoshow'))
            ;
        Mage::getSingleton('catalog/product_status')
            ->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')
            ->addVisibleInSearchFilterToCollection($collection);
        
        $collection->load();
        return $collection;
    }
    
    protected function _getCategoryCollection($query, $store) 
    {       
        return Mage::getResourceModel('ajaxsearch/collection')
            ->getCategoryCollection($query, $store);
    }
    
    protected function _getCmsCollection($query) 
    {
        return Mage::getResourceModel('ajaxsearch/collection')
                ->getCmsCollection($query);
    }
    
    public function indexAction()
    {
        $query = $this->getRequest()->getParam('query', '');
        $store = Mage::app()->getStore()->getStoreId();
        
        $searchURL = Mage::helper('catalogsearch/data')->getResultUrl($query);
        $suggestions = array();
        
        $suggestions[] = array('html' => 
            '<p class="headerajaxsearchwindow">' . 
                Mage::getStoreConfig('ajax_search/general/headertext') . 
                " <a href='{$searchURL}' style='color:#A9D809;' target='_blank'>{$query}</a>" .
            '</p>'
        );
        
        $isEnabledImage = Mage::getStoreConfig('ajax_search/general/enableimage');
        $imageHeight    = Mage::getStoreConfig('ajax_search/general/imageheight');
        $imageWidth     = Mage::getStoreConfig('ajax_search/general/imagewidth');
        
        $isEnabledDescription = Mage::getStoreConfig('ajax_search/general/enabledescription');
        $lengthDescription = (int) Mage::getStoreConfig('ajax_search/general/descriptionchars');

        $collection = $this->_getProductCollection($query, $store);
        foreach($collection as $_row) {
            
            $_product = Mage::getModel('catalog/product')->load($_row->getId());

            $_image = $_description = '';
            
            if($isEnabledImage) {
                $_image = Mage::helper('catalog/image')->init($_product, 'thumbnail')
                        ->resize($imageWidth, $imageHeight)
                        ->__toString();
            }
            if($isEnabledDescription) {
                $_description = strip_tags($this->_trim(
                    $_product->getShortDescription(), $lengthDescription                        
                ));
            }
            
            $suggestions[] = array(
                'name'        => $_product->getName(),
                'url'         => $_product->getProductUrl(),
                'image'       => $_image,
                'description' => $_description
            );
        }

        /*
         * 	category search
         */
        if (Mage::getStoreConfig('ajax_search/general/enablecatalog')) {
            $collection = $this->_getCategoryCollection($query, $store);
            if (count($collection)) {
                $categoryTranslated = $this->__('Category');
                $suggestions[] = array('html' => '<p class="headercategorysearch"> '.$categoryTranslated.' </p><span class="hr"></span>');
            }
            foreach ($collection as $_row) {
                $category = Mage::getModel("catalog/category")->load($_row['entity_id']);
                $suggestions[] = array(
                    'name' => $_row['name'],
                    'url'  => $category->getUrl()
                ); 
            }
        }
        /*
         * end category search
         */

        /*
         * 	cms search
         */
        if (Mage::getStoreConfig('ajax_search/general/enablecms')) {
            
            $collection = $this->_getCmsCollection($query, $store);
            if (count($collection)) {
                $suggestions[] = array('html' => '<p class="headercategorysearch"> CMS Page </p><span class="hr"></span>');
            }
            foreach ($collection as $_page) {
                $suggestions[] = array(
                    'name' => $_page['title'],
                    'url'  => Mage::getBaseUrl() . $_page['identifier']
                );
            }
        }
        /*
         * end cms search
         */
        
        $suggestions[] = array('html' => 
            '<p class="headerajaxsearchwindow">' . 
                Mage::getStoreConfig('ajax_search/general/footertext') . 
            " <a href='{$searchURL}' style='color:#A9D809;'>{$query}</a>" . 
            '</p>'
        );
       
       $this->_sendJson(array(
            'query'       => $query,
            'suggestions' => $suggestions
        ));

    }

}