<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Fedobe_Sortby_Model_Config extends Mage_Catalog_Model_Config
{
    public function getAttributeUsedForSortByArray()
    {
        $enableOptions=Mage::getStoreConfig('sortby_options/sortby/enable-options');
        $disableOptions=Mage::getStoreConfig('sortby_options/sortby/disable-options');
           
      if($enableOptions == 1)
      { 
          if($disableOptions)
          { 
              
            $disable=  explode(',', $disableOptions);  
             $str = count($disable);
            $flag1=true;
            $flag2=true;
            $flag3=true;
            $flag4=true;
            $flag5=true;
            $flag6=true;
            $arr1=array();
            $arr2=array();
            $arr3=array();
            $arr4=array();
            $arr5=array();
            $arr6=array();
             for($i=0;$i<$str;$i++)
            {
               if( $disable[$i] == 'more_recent')
               {
                  
                   $flag1=false;
               }
               else if( $disable[$i] == 'best_seller')
               {
                  
                   $flag2=false;
               }
                else if( $disable[$i] == 'rating_summary')
               {
                  
                   $flag3=false;
               }
               else if( $disable[$i] == 'more_viewed')
               {
                  
                   $flag4=false;
               }
               else if( $disable[$i] == 'discount')
               {
                  
                   $flag5=false;
               }
               
              
               
              
               
            } 
           
			
		
                         if($flag1)
                            {  
                             $arr1=array('more_recent' => Mage::helper('catalog')->__('More Recent'));
                             
                            }
                            if($flag2)
                            {
                               $arr2=array('best_seller' => Mage::helper('catalog')->__('Best Seller'));
                              
                            }
                            if($flag3)
                            {
                               $arr3=array('rating_summary' => Mage::helper('catalog')->__('Most Rated'));
                            }
                            if($flag4)
                            {
                               $arr4=array('more_viewed' => Mage::helper('catalog')->__('More Viewed'));
                            }
                            if($flag5)
                            {
                               $arr5=array('discount' => Mage::helper('catalog')->__('Discount'));
                            }
                            
             return array_merge(
                    parent::getAttributeUsedForSortByArray(),$arr1,$arr2,$arr3,$arr4,$arr5);
            
            }
            else
            {
                 return array_merge(
                    parent::getAttributeUsedForSortByArray(),
                         array('more_recent' => Mage::helper('catalog')->__('More Recent')),
                         array('best_seller' => Mage::helper('catalog')->__('Best Seller')),
                         array('rating_summary' => Mage::helper('catalog')->__('Most Rated')),
                         array('more_viewed' => Mage::helper('catalog')->__('More Viewed')),
                         array('discount' => Mage::helper('catalog')->__('Discount'))
                         );
            }
      
      
      }
             else{
                 return array_merge(
                    parent::getAttributeUsedForSortByArray());
             }
}
}