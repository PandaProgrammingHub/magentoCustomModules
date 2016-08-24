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
               if( $disable[$i] == 'Newest')
               {
                  
                   $flag1=false;
               }
               else if( $disable[$i] == 'Biggest_Saving')
               {
                  
                   $flag2=false;
               }
               else if( $disable[$i] == 'Discount_Percentage')
               {
                  
                   $flag3=false;
               }
              else if( $disable[$i] == 'Best_Sells')
               {
                  
                   $flag4=false;
               }
               else if( $disable[$i] == 'Most_Viewed')
               {
                  
                   $flag5=false;
               }
               else if( $disable[$i] == 'Top_Rated')
               {
                  
                   $flag6=false;
               }
               
            } 
           
			
		
                         if($flag1)
                            {  
                             $arr1=array('created_at' => Mage::helper('catalog')->__('Newest'));
                             
                            }
                            if($flag2)
                            {
                               $arr2=array('biggest_saving' => Mage::helper('catalog')->__('Biggest Saving'));
                              
                            }
                            if($flag3)
                            {
                               $arr3=array('discount_percentage' => Mage::helper('catalog')->__('Discount Percentage'));
                            }
                            if($flag4)
                            {
                               $arr4=array('qty_ordered' => Mage::helper('catalog')->__('Best Sells'));
                            }
                            if($flag5)
                            {
                               $arr5=array('most_viewed' => Mage::helper('catalog')->__('Most Viewed'));
                            }
                            if($flag6)
                            {
                               $arr6=array('rating_summary' => Mage::helper('catalog')->__('Top Rated'));
                            }
             return array_merge(
                    parent::getAttributeUsedForSortByArray(),$arr1,$arr2,$arr3,$arr4,$arr5,$arr6);
            
            }
            else
            {
                 return array_merge(
                    parent::getAttributeUsedForSortByArray(),
                         array('created_at' => Mage::helper('catalog')->__('Newest')),
                         array('biggest_saving' => Mage::helper('catalog')->__('Biggest Saving')),
                         array('discount_percentage' => Mage::helper('catalog')->__('Discount Percentage')),
                         array('qty_ordered' => Mage::helper('catalog')->__('Best Sells')),
                         array('most_viewed' => Mage::helper('catalog')->__('Most Viewed')),
                         array('rating_summary' => Mage::helper('catalog')->__('Top Rated'))
                         );
            }
      
      
      }
             else{
                 return array_merge(
                    parent::getAttributeUsedForSortByArray());
             }
}
}