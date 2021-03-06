<?php 
class Fedobe_Sortby_Block_Product_List extends Mage_Catalog_Block_Product_List
{
    protected $_mycollection ;
    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
             
            
            $showWithoutImagesProduct=Mage::getStoreConfig('sortby_options/sortby/showWithoutImages-options');
            $showOutOfStockProduct=Mage::getStoreConfig('sortby_options/sortby/showOutOfStock-options');
                
            $layer = $this->getLayer();
            /* @var $layer Mage_Catalog_Model_Layer */
            if ($this->getShowRootCategory()) {
                $this->setCategoryId(Mage::app()->getStore()->getRootCategoryId());
            }

            // if this is a product view page
            if (Mage::registry('product')) {
                // get collection of categories this product is associated with
                $categories = Mage::registry('product')->getCategoryCollection()
                    ->setPage(1, 1)
                    ->load();
                // if the product is associated with any category
                if ($categories->count()) {
                    // show products from this category
                    $this->setCategoryId(current($categories->getIterator()));
                }
            }

            $origCategory = null;
            if ($this->getCategoryId()) {
                $category = Mage::getModel('catalog/category')->load($this->getCategoryId());
                if ($category->getId()) {
                    $origCategory = $layer->getCurrentCategory();
                    $layer->setCurrentCategory($category);
                    $this->addModelTags($category);
                }
            }
            $this->_productCollection = $layer->getProductCollection();
            $this->_productCollection->joinField('rating_summary','review_entity_summary','rating_summary','entity_pk_value=entity_id', 
                    array('entity_type'=>1,'store_id'=>Mage::app()->getStore()->getId()),'left');
            
           if($showWithoutImagesProduct == 2)
           {
                $this->_productCollection->addAttributeToFilter('small_image', array('neq' => "no_selection"));
           }
           if($showOutOfStockProduct == 1)
           { 
              // echo "hello";
              //$this->_productCollection = Mage::getModel('cataloginventory/stock_item')
                                          //  ->getCollection()
                                            //->addFieldToFilter('is_in_stock', 0);
               //Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($this->_productCollection);
               // $this->_productCollection->addFieldToFilter('is_in_stock', 0);
           }
            //echo "<pre>";
            //print_r( $this->_productCollection->getData());exit();
            $this->prepareSortableFieldsByCategory($layer->getCurrentCategory());

            if ($origCategory) {
                $layer->setCurrentCategory($origCategory);
            }
        /*========================== BiggestSavings Sorting Collection function Start Here ======================== */
            
            function biggestSavings()
                {
                $showWithoutImagesProduct=Mage::getStoreConfig('sortby_options/sortby/showWithoutImages-options');
                $showOutOfStockProduct=Mage::getStoreConfig('sortby_options/sortby/showOutOfStock-options');
                //echo $showWithoutImagesProduct.'<br>'.$showOutOfStockProduct; 
                    $cat_id = Mage::getModel('catalog/layer')->getCurrentCategory()->getId();
                    $category = Mage::getModel('catalog/category')->load($cat_id);
                    $products = $category->getProductCollection()
                                ->addCategoryFilter($category)
                                ->addAttributeToSelect('*')
                                ->addAttributeToFilter('status', 1)
                                ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
                    if($showWithoutImagesProduct == 2)
                    { 
                        $products->addAttributeToFilter('small_image', array('neq' => "no_selection"));
                    }
                    if($showOutOfStockProduct == 2)
                    {
                         Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
                    }
                    
                    $saving="";
                foreach ( $products as $_product ): 
                    $_productId= $_product->getId();
                    $product = Mage::getModel('catalog/product')->load($_productId);
                    $_actualPrice = $product->getPrice();
                    $_specialPrice = $product->getFinalPrice();
                    if($_specialPrice !=$_actualPrice)
                    {
                    $saving=$_actualPrice - $_specialPrice;
                    $saving=round($saving);
                    $array1[]=array('Product Id'=>$_productId,'Saving'=>$saving);
                    }
                  else{
                        $array2[]=$_productId;
                    }
                     
                 endforeach;
                $len=count($array1);
                $len1=count($array2);
                $dir=Mage::getBlockSingleton('catalog/product_list_toolbar')->getCurrentDirection();
                if($dir == 'asc')
                {
                    $sort = array();
                    foreach($array1 as $k=>$v)
                    {
                        $sort['Saving'][$k] = $v['Saving'];
                    }

                    array_multisort($sort['Saving'], SORT_ASC, $array1);

                    for($i=0;$i<$len;$i++)
                      {
                        $newArray = array_values($array1[$i]);
                        $productIds1[] = $newArray[0];
                      }
                      
                      for($x=0;$x<$len1;$x++)
                         {
                             $productIds2[] =$array2[$x];
                         }   
                         if($len > 0 && $len1 > 0)
                      {
                        $productIds = array_merge($productIds1,$productIds2 );
                      }
                      else if($len == 0)
                      {
                          $productIds = $productIds2;
                      }
                      else if($len1 == 0)
                      {
                          $productIds = $productIds1 ;
                      }
                         /* echo "<pre>";
                            print_r($productIds1);
                              print_r($productIds2);
                        print_r($productIds);*/
                        
                        $collection = Mage::getModel('catalog/product')
                                      ->getCollection()
                                      ->addAttributeToSelect('*')
                                      ->addAttributeToFilter('status', 1)
                                      ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
                       
		        $collection->addAttributeToFilter('entity_id', array('in' => $productIds));
                        $collection->getSelect()->order(new Zend_Db_Expr('FIELD(e.entity_id, ' . "'" . implode("','", $productIds) . "'".')')); 

                                  
                /*echo "<pre>";
                       print_r($collection->getData());
                        exit(); */

                }  
                if($dir == 'desc')
                {
                    $sort = array();
                    foreach($array1 as $k=>$v)
                    {
                        $sort['Saving'][$k] = $v['Saving'];
                    }

                    array_multisort($sort['Saving'], SORT_DESC, $array1);

                    for($i=0;$i<$len;$i++)
                      {
                        $newArray = array_values($array1[$i]);
                        $productIds1[] = $newArray[0];
                      }
                      
                      for($x=0;$x<$len1;$x++)
                         {
                             $productIds2[] =$array2[$x];
                         }   
                         if($len > 0 && $len1 > 0)
                      {
                        $productIds = array_merge($productIds1,$productIds2 );
                      }
                      else if($len == 0)
                      {
                          $productIds = $productIds2;
                      }
                      else if($len1 == 0)
                      {
                          $productIds = $productIds1 ;
                      } 
                          /* echo "<pre>";
                            print_r($productIds1);
                              print_r($productIds2);
                        print_r($productIds);*/
                        
                        $collection = Mage::getModel('catalog/product')
                                      ->getCollection()
                                      ->addAttributeToSelect('*')
                                      ->addAttributeToFilter('status', 1)
                                      ->addCategoryFilter(Mage::registry('current_category'))
                                      ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
                       
		        $collection->addAttributeToFilter('entity_id', array('in' => $productIds));
                        $collection->getSelect()->order(new Zend_Db_Expr('FIELD(e.entity_id, ' . "'" . implode("','", $productIds) . "'".')')); 

                                  
                /*echo "<pre>";
                       print_r($collection->getData());
                        exit(); */

                }  
               
    
        
      
   return $collection;
                 
    
} /* End biggestSavings() Here */
/*========================== DiscountPercentage Sorting Collection function Start Here ======================== */
      function discountPercentage()
                {
                $showWithoutImagesProduct=Mage::getStoreConfig('sortby_options/sortby/showWithoutImages-options');
                $showOutOfStockProduct=Mage::getStoreConfig('sortby_options/sortby/showOutOfStock-options');
                    $cat_id = Mage::getModel('catalog/layer')->getCurrentCategory()->getId();
                    $category = Mage::getModel('catalog/category')->load($cat_id);
                    $products = $category->getProductCollection()
                                ->addCategoryFilter($category)
                                ->addAttributeToSelect('*')
                                ->addAttributeToFilter('status', 1)
                                ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
                    if($showWithoutImagesProduct == 2)
                    { 
                        $products->addAttributeToFilter('small_image', array('neq' => "no_selection"));
                    }
                    if($showOutOfStockProduct == 2)
                    {
                         Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
                    }
                    $precentage="";
                foreach ( $products as $_product ): 
                    $_productId= $_product->getId();
                    $product = Mage::getModel('catalog/product')->load($_productId);
                    $_actualPrice = $product->getPrice();
                    $_specialPrice = $product->getFinalPrice();
                    if($_specialPrice !=$_actualPrice)
                    {
                    $precentage=($_specialPrice/$_actualPrice)*100;
                    $precentage=round($precentage);
                    $array1[]=array('Product Id'=>$_productId,'Precentage'=>$precentage);
                    }
                  else{
                        $array2[]=$_productId;
                    }
                     
                 endforeach;
                $len=count($array1);
                $len1=count($array2);
                $dir=Mage::getBlockSingleton('catalog/product_list_toolbar')->getCurrentDirection();
                if($dir == 'asc')
                {
                    $sort = array();
                    foreach($array1 as $k=>$v)
                    {
                        $sort['Precentage'][$k] = $v['Precentage'];
                    }

                    array_multisort($sort['Precentage'], SORT_ASC, $array1);

                    for($i=0;$i<$len;$i++)
                      {
                        $newArray = array_values($array1[$i]);
                        $productIds1[] = $newArray[0];
                      }
                      
                      for($x=0;$x<$len1;$x++)
                         {
                             $productIds2[] =$array2[$x];
                         }   
                         if($len > 0 && $len1 > 0)
                      {
                        $productIds = array_merge($productIds1,$productIds2 );
                      }
                      else if($len == 0)
                      {
                          $productIds = $productIds2;
                      }
                      else if($len1 == 0)
                      {
                          $productIds = $productIds1 ;
                      } 
                         /* echo "<pre>";
                            print_r($productIds1);
                              print_r($productIds2);
                        print_r($productIds);*/
                        
                        $collection = Mage::getModel('catalog/product')
                                      ->getCollection()
                                      ->addAttributeToSelect('*')
                                      ->addAttributeToFilter('status', 1)
                                      ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
                       
		        $collection->addAttributeToFilter('entity_id', array('in' => $productIds));
                        $collection->getSelect()->order(new Zend_Db_Expr('FIELD(e.entity_id, ' . "'" . implode("','", $productIds) . "'".')')); 

                                  
               /*echo "<pre>";
                       print_r($collection->getData());
                        exit(); */

                }  
                if($dir == 'desc')
                {
                    $sort = array();
                    foreach($array1 as $k=>$v)
                    {
                        $sort['Precentage'][$k] = $v['Precentage'];
                    }

                    array_multisort($sort['Precentage'], SORT_DESC, $array1);

                    for($i=0;$i<$len;$i++)
                      {
                        $newArray = array_values($array1[$i]);
                        $productIds1[] = $newArray[0];
                      }
                      
                      for($x=0;$x<$len1;$x++)
                         {
                             $productIds2[] =$array2[$x];
                         }   
                        if($len > 0 && $len1 > 0)
                      {
                        $productIds = array_merge($productIds1,$productIds2 );
                      }
                      else if($len == 0)
                      {
                          $productIds = $productIds2;
                      }
                      else if($len1 == 0)
                      {
                          $productIds = $productIds1 ;
                      }
                         /* echo "<pre>";
                            print_r($productIds1);
                              print_r($productIds2);
                        print_r($productIds);*/
                        
                        $collection = Mage::getModel('catalog/product')
                                      ->getCollection()
                                      ->addAttributeToSelect('*')
                                      ->addAttributeToFilter('status', 1)
                                      ->addCategoryFilter(Mage::registry('current_category'))
                                      ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
                       
		        $collection->addAttributeToFilter('entity_id', array('in' => $productIds));
                        $collection->getSelect()->order(new Zend_Db_Expr('FIELD(e.entity_id, ' . "'" . implode("','", $productIds) . "'".')')); 

                                  
                /*echo "<pre>";
                       print_r($collection->getData());
                        exit(); */

                }  
               
    
        
      
   return $collection;
                } /* End discountPercentage() Here */
/*========================== BestSells Sorting Collection function Start Here ======================== */
                function bestSells()
                {
                    $showWithoutImagesProduct=Mage::getStoreConfig('sortby_options/sortby/showWithoutImages-options');
                    $showOutOfStockProduct=Mage::getStoreConfig('sortby_options/sortby/showOutOfStock-options');
                    $dir=Mage::getBlockSingleton('catalog/product_list_toolbar')->getCurrentDirection();
                    $time_periods=Mage::getStoreConfig('sortby_options/sortby/best-sellers-period');
                    
                    $storeId    = Mage::app()->getStore()->getId();
                    $today = time();
                    $last = $today - (60*60*24*$time_periods);
                    //echo "<br>Today=".$today."Last=".$last;
                    
                    $from = date("Y-m-d", $last);
                    $to = date("Y-m-d", $today);
                    //echo "<br>Today=".$to."Last=".$from;
         /*=======================Collecting bestSellers Product Id======================== */
                    $products = Mage::getResourceModel('reports/product_collection')
					->addAttributeToSelect('*')
                                        ->addOrderedQty($from, $to)
					->setStoreId($storeId)
					->addStoreFilter($storeId)
                                        ->addCategoryFilter(Mage::registry('current_category'))
					->setOrder('ordered_qty', $dir)
                                        ->addAttributeToFilter('status', 1)
                                        ->addAttributeToFilter('small_image',array('neq'=>'no_selection'))
                                        ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
                  if($showWithoutImagesProduct == 2)
                    { 
                        $products->addAttributeToFilter('small_image', array('neq' => "no_selection"));
                    }
                    if($showOutOfStockProduct == 2)
                    {
                         Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
                    }
                    
                    foreach ( $products as $_product )
                     { 
                        $_productId= $_product->getId();
                        $productIds1[] =$_productId;
                      }
                      $len1=count($productIds1);
                      if($len1<=0)
                      {
                          $products = Mage::getResourceModel('reports/product_collection')
					->addAttributeToSelect('*')
                                        ->addOrderedQty()
					->setStoreId($storeId)
					->addStoreFilter($storeId)
                                        ->addCategoryFilter(Mage::registry('current_category'))
					->setOrder('ordered_qty', $dir)
                                        ->addAttributeToFilter('status', 1)
                                        ->addAttributeToFilter('small_image',array('neq'=>'no_selection'))
                                        ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
                 if($showWithoutImagesProduct == 2)
                    { 
                        $products->addAttributeToFilter('small_image', array('neq' => "no_selection"));
                    }
                    if($showOutOfStockProduct == 2)
                    {
                         Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
                    }                        
                    
                    foreach ( $products as $_product )
                     { 
                        $_productId= $_product->getId();
                        $productIds1[] =$_productId;
                      }
                      }
                      $len1=count($productIds1);
         /*=======================Collecting All Product Id except bestSellers Product Id in Current Category ======================== */
                      $products = Mage::getModel('catalog/product')
                                      ->getCollection()
                                      ->addAttributeToSelect('*')
                                      ->addAttributeToFilter('status', 1)
                                      ->addCategoryFilter(Mage::registry('current_category'))
                                      ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                                      ->addAttributeToFilter('entity_id', array('nin' => $productIds1));
                   if($showWithoutImagesProduct == 2)
                    { 
                        $products->addAttributeToFilter('small_image', array('neq' => "no_selection"));
                    }
                    if($showOutOfStockProduct == 2)
                    {
                         Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
                    }
                      
                      foreach ( $products as $_product )
                     { 
                        $_productId= $_product->getId();
                        $productIds2[] =$_productId;
                      }
                      
                      $len2=count($productIds2);
                      if($len1 > 0 && $len2 > 0)
                      {
                        $productIds = array_merge($productIds1,$productIds2 );
                      }
                      else if($len1 == 0)
                      {
                          $productIds = $productIds2;
                      }
                      else if($len2 == 0)
                      {
                          $productIds = $productIds1 ;
                      }
                      
                      /*echo "<pre>";
                      print_r($productIds);
                      exit();*/
                      
                      $collection = Mage::getModel('catalog/product')
                                      ->getCollection()
                                      ->addAttributeToSelect('*')
                                      ->addAttributeToFilter('status', 1)
                                      ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
                       
		        $collection->addAttributeToFilter('entity_id', array('in' => $productIds));
                        $collection->getSelect()->order(new Zend_Db_Expr('FIELD(e.entity_id, ' . "'" . implode("','", $productIds) . "'".')'));
                      
                      
                    
                   
	
                    /*echo "<pre>";
                       print_r($collection->getData());
                        exit(); */
	
	return $collection; 
                    
                    
                } /* End bestSells() Here */
/*========================== Most Viewed Sorting Collection function Start Here ======================== */
                function mostViewed()
                {
                    $showWithoutImagesProduct=Mage::getStoreConfig('sortby_options/sortby/showWithoutImages-options');
                    $showOutOfStockProduct=Mage::getStoreConfig('sortby_options/sortby/showOutOfStock-options');
                    
                    $dir=Mage::getBlockSingleton('catalog/product_list_toolbar')->getCurrentDirection();
                    $time_periods=Mage::getStoreConfig('sortby_options/sortby/most-views-period');
                    
                    
                    $storeId    = Mage::app()->getStore()->getId();
                    $today = time();
                    $last = $today - (60*60*24*$time_periods);
                    //echo "<br>Today=".$today."Last=".$last;
                    
                    $from = date("Y-m-d", $last);
                    $to = date("Y-m-d", $today);
                   // echo "<br>Today=".$to."Last=".$from;
                  /*=======================Collecting most Viewd Product Id======================== */
                    $products = Mage::getResourceModel('reports/product_collection')
					->addAttributeToSelect('*')
                                        ->addViewsCount($from, $to)
					->setStoreId($storeId)
					->addStoreFilter($storeId)
                                        ->addCategoryFilter(Mage::registry('current_category'))
					->setOrder('views', $dir)
                                        ->addAttributeToFilter('status', 1)
                                        ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
                 if($showWithoutImagesProduct == 2)
                    { 
                        $products->addAttributeToFilter('small_image', array('neq' => "no_selection"));
                    }
                    if($showOutOfStockProduct == 2)
                    {
                         Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
                    }    
                    foreach ( $products as $_product )
                        { 
                                $_productId= $_product->getId();
                                $productIds1[] =$_productId;
                        }
                        $len1=count($productIds1);
                        if($len1<=0)
                        {
                         $products = Mage::getResourceModel('reports/product_collection')
					->addAttributeToSelect('*')
                                        ->addViewsCount()
					->setStoreId($storeId)
					->addStoreFilter($storeId)
                                        ->addCategoryFilter(Mage::registry('current_category'))
					->setOrder('views', $dir)
                                        ->addAttributeToFilter('status', 1)
                                        ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
             if($showWithoutImagesProduct == 2)
                    { 
                        $products->addAttributeToFilter('small_image', array('neq' => "no_selection"));
                    }
                    if($showOutOfStockProduct == 2)
                    {
                         Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
                    }   
                    foreach ( $products as $_product )
                        { 
                                $_productId= $_product->getId();
                                $productIds1[] =$_productId;
                        }   
                        }
                        $len1=count($productIds1);
 /*=======================Collecting All Product Id except most Viewed Product Id in Current Category ======================== */
                      $products = Mage::getModel('catalog/product')
                                      ->getCollection()
                                      ->addAttributeToSelect('*')
                                      ->addAttributeToFilter('status', 1)
                                      ->addCategoryFilter(Mage::registry('current_category'))
                                      ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                                      ->addAttributeToFilter('entity_id', array('nin' => $productIds1));
                 if($showWithoutImagesProduct == 2)
                    { 
                        $products->addAttributeToFilter('small_image', array('neq' => "no_selection"));
                    }
                    if($showOutOfStockProduct == 2)
                    {
                         Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
                    }
                      foreach ( $products as $_product )
                     { 
                        $_productId= $_product->getId();
                        $productIds2[] =$_productId;
                      }
                      
                      $len2=count($productIds2);
                      if($len1 > 0 && $len2 > 0)
                      {
                        $productIds = array_merge($productIds1,$productIds2 );
                      }
                      else if($len1 == 0)
                      {
                          $productIds = $productIds2;
                      }
                      else if($len2 == 0)
                      {
                          $productIds = $productIds1 ;
                      }
                      
                     
                     /* echo "<pre>";
                     print_r($productIds1);
                     print_r($productIds2);
                      print_r($productIds);
                      exit();*/
                      $collection = Mage::getModel('catalog/product')
                                      ->getCollection()
                                      ->addAttributeToSelect('*')
                                      ->addAttributeToFilter('status', 1)
                                      ->addCategoryFilter(Mage::registry('current_category'))
                                      ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
                       
		        $collection->addAttributeToFilter('entity_id', array('in' => $productIds));
                        $collection->getSelect()->order(new Zend_Db_Expr('FIELD(e.entity_id, ' . "'" . implode("','", $productIds) . "'".')'));
                    
                   
                    
                    //echo $dir;
                    /*echo "<pre>";
                       print_r($collection->getData());
                        exit(); */
	
	return $collection; 
                    
                    
                } /* End mostViewed() Here */
                
                
                
               $order=Mage::getBlockSingleton('catalog/product_list_toolbar')->getCurrentOrder();
                if($order == 'biggest_saving')
                {
                    $this->_productCollection=biggestSavings(); 
                }
                if($order == 'discount_percentage')
                {
                    $this->_productCollection=discountPercentage(); 
                }
                if($order == 'best_sells')
                {
                    //echo "<script>alert('Wait im comming')</script>";
                    $this->_productCollection=bestSells(); 
                }
                 if($order == 'most_viewed')
                {
                    //echo "<script>alert('Wait im comming')</script>";
                    $this->_productCollection=mostViewed(); 
                }
              
                 
       
        }
   
        return $this->_productCollection;
    }

}
