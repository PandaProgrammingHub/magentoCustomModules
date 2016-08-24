<?php

class Fedobe_Avatar_Carousel_Helper_Data extends Mage_Core_Helper_Abstract 
{
    public function getAllProductCollection($category = NULL, $attributes = NULL)
    {
        $_productCollection = Mage::getModel('catalog/product')->getCollection();
        $_productCollection->addAttributeToSelect('*');
        
        if($category !=""){
            $categoryIds = explode(",", $category);
            $filter = NULL;
            foreach ($categoryIds as $cat) {
                $filter[]['finset'] = $cat;
            }
            $_productCollection->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
                        ->addAttributeToFilter('category_id', array($filter));
			$_productCollection->getSelect()->group('entity_id'); 
        }
        
        if($attributes !=""){
            $attr = explode(",", $attributes);
            foreach ($attr as $attrkeyval) {
                    $temp = explode(":", $attrkeyval);
                    $attrname = trim($temp[0]);
                    $attrlabel = trim($temp[1]);
                    $_productCollection->addAttributeToFilter(
                                                                            $attrname, 
                                                                            array('in' => 
                                                                                array(
                                                                                    Mage::getResourceModel('catalog/product')
                                                                                            ->getAttribute($attrname)->getSource()
                                                                                            ->getOptionId($attrlabel)
                                                                                    )
                                                                                )
                                                                         );
            }
        }
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($_productCollection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($_productCollection);
        
        return $_productCollection;
    }

    public function getBestSellerProductCollection($category = NULL, $attributes = NULL)
    {
        $storeId = Mage::app()->getStore()->getId();
        $_productCollection = Mage::getResourceModel('reports/product_collection')
            ->addOrderedQty()
            ->addAttributeToSelect('*')
            ->addAttributeToSelect(array('name', 'price', 'small_image'))
            ->setStoreId($storeId)
            ->addStoreFilter($storeId)
            ->addCategoryFilter(Mage::getModel('catalog/category')->load($category))
            ->setOrder('ordered_qty', 'desc');
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($_productCollection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($_productCollection);
//        $_productCollection->setPage(1, $this->getLimit());
        return $_productCollection;
    }
    
    public function getRecentlyAddedProductCollection($category = NULL, $attributes = NULL)
    {
        $_productCollection = Mage::getModel('catalog/product')->getCollection();
        $_productCollection->addAttributeToSelect('*')
                           ->addFieldToFilter('visibility', array(
                                       Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
                                       Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
                           ))
                           ->addAttributeToSort("entity_id","DESC")
                           ->addCategoryFilter(Mage::getModel('catalog/category')->load($category))
                           ->getSelect()
                                ->limit(20)
                           ;
        return $_productCollection;
    }
    
    public function getFeaturedProductCollection()
    {
        $storeId    = Mage::app()->getStore()->getId();
        $_productCollection = Mage::getModel('catalog/product')->getCollection();
        $_productCollection->addAttributeToSelect('*')
                           ->addAttributeToFilter(array(array('attribute' => 'fedobefeatured', 'eq' => '1')))
                           ->setStoreId($storeId)
                           ->addStoreFilter($storeId)
                           ->addFieldToFilter('visibility', array(
                                       Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
                                       Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
                           ))
                           ->getSelect()
                           ->limit(20)
                           ;
        return $_productCollection;
    }
    
    public static function calcPerc($val1, $val2) {
        $total1 = ($val1 - $val2);
        $total2 = $total1 / $val1;
        $total = $total2 * 100;

        return number_format($total, '0') . "% off";
    }
    
    public function getAttributeValue($attribute_name)
    {
        $module_name = "product-carousel";
        $style = (Mage::getStoreConfig('avatar_modules/general/global_setting') != "default")?Mage::getStoreConfig('avatar_modules/general/global_setting'):Mage::getStoreConfig('global_options/general/global_setting');

        $moduleCustomSettingEnable = Mage::getStoreConfig('avatar_modules/general/custom_style_setting_enable');
        //$carousel_custom_setting_enable = Mage::getStoreConfig('avatar_modules/general/carousel_custom_setting_enable');
        $advance_global_setting_enable = Mage::getStoreConfig('global_options/advance/advance_global_setting_enable');
        $collection = Mage::getModel('carousel/designattributesvalue')->getCollection();
        
            $collection->getSelect()
                           ->join( array('attr_name_table'=>'fedobe_design_attribute_name'),'main_table.attribute_name_id = attr_name_table.attribute_name_id')
                           ->join( array('module_name_table'=>'fedobe_module_unique_name'),'attr_name_table.module_id = module_name_table.module_id')
                           ->join( array('global_style'=>'fedobe_global_settings'),'global_style.style_id = attr_name_table.style_id')
                            ->where('module_name_table.module_name = "'.$module_name.'" and attr_name_table.attribute_name = "'.$attribute_name.'" and global_style.style_name = "'.$style.'"')
                          ->reset(Zend_Db_Select::COLUMNS)->columns('attribute_value');
            $sql = $collection->getSelect();
            $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
            $rows       = $connection->fetchAll($sql);
            
            if(!$moduleCustomSettingEnable){
                if(!$advance_global_setting_enable){
                   return $rows[0][attribute_value];
                }else{
                    if($attribute_name == "text_color"){ 
                        if(Mage::getStoreConfig('global_options/advance/text_color') != ""){
                            return Mage::getStoreConfig('global_options/advance/text_color');
                        }else{
                             return $rows[0][attribute_value];
                        }
                    }elseif ($attribute_name == "link_color") {
                        if(Mage::getStoreConfig('global_options/advance/link_color') != ""){
                            return Mage::getStoreConfig('global_options/advance/link_color');
                        }else{
                             return $rows[0][attribute_value];
                        }
                    }elseif ($attribute_name == "link_hover_color") {
                        if(Mage::getStoreConfig('global_options/advance/link_hover_color') != ""){
                            return Mage::getStoreConfig('global_options/advance/link_hover_color');
                        }else{
                             return $rows[0][attribute_value];
                        }
                    }elseif ($attribute_name == "button_background_color") {
                        if(Mage::getStoreConfig('global_options/advance/button_background_color') != ""){
                            return Mage::getStoreConfig('global_options/advance/button_background_color');
                        }else{
                             return $rows[0][attribute_value];
                        }
                    }elseif ($attribute_name == "button_text_color") {
                        if(Mage::getStoreConfig('global_options/advance/button_text_color') != ""){
                            return Mage::getStoreConfig('global_options/advance/button_text_color');
                        }else{
                             return $rows[0][attribute_value];
                        }
                    }elseif ($attribute_name == "button_hover_color") {
                        if(Mage::getStoreConfig('global_options/advance/button_hover_color') != ""){
                            return Mage::getStoreConfig('global_options/advance/button_hover_color');
                        }else{
                             return $rows[0][attribute_value];
                        }
                    }elseif ($attribute_name == "button_hover_text_color") {
                        if(Mage::getStoreConfig('global_options/advance/button_hover_text_color') != ""){
                             return Mage::getStoreConfig('global_options/advance/button_hover_text_color');
                        }else{
                             return $rows[0][attribute_value];
                        }
                    }else{
                        return $rows[0][attribute_value];
                    }
                }
            }
            else{
                if($attribute_name == "border_width"){
                    if(Mage::getStoreConfig('avatar_modules/general/border_width') != ""){
                        return Mage::getStoreConfig('avatar_modules/general/border_width');
                    }else{
                         return $rows[0][attribute_value];
                    }
                }elseif($attribute_name == "border_type"){
                    if(Mage::getStoreConfig('avatar_modules/general/border_type') != ""){
                        return Mage::getStoreConfig('avatar_modules/general/border_type');
                    }else{
                         return $rows[0][attribute_value];
                    }
                }elseif($attribute_name == "border_color"){
                    if(Mage::getStoreConfig('avatar_modules/general/border_color') != ""){
                        return Mage::getStoreConfig('avatar_modules/general/border_color');
                    }else{
                         return $rows[0][attribute_value];
                    }
                }elseif($attribute_name == "text_color"){
                    if(Mage::getStoreConfig('avatar_modules/general/text_color')==""){
                         if($advance_global_setting_enable){
                             return Mage::getStoreConfig('global_options/advance/text_color');
                         }else{
                             return $rows[0][attribute_value];
                         }
                     }else{
                         return Mage::getStoreConfig('avatar_modules/general/text_color');
                     }
                }elseif($attribute_name == "link_color"){
                    if(Mage::getStoreConfig('avatar_modules/general/link_color')==""){
                         if($advance_global_setting_enable){
                             return Mage::getStoreConfig('global_options/advance/link_color');
                         }else{
                             return $rows[0][attribute_value];
                         }
                     }else{
                         return Mage::getStoreConfig('avatar_modules/general/link_color');
                     }
                }elseif($attribute_name == "link_hover_color"){
                    if(Mage::getStoreConfig('avatar_modules/general/link_hover_color')==""){
                         if($advance_global_setting_enable){
                             return Mage::getStoreConfig('global_options/advance/link_hover_color');
                         }else{
                             return $rows[0][attribute_value];
                         }
                     }else{
                         return Mage::getStoreConfig('avatar_modules/general/link_hover_color');
                     }
                }elseif($attribute_name == "price_color"){
                    if(Mage::getStoreConfig('avatar_modules/general/price_color') != ""){
                        return Mage::getStoreConfig('avatar_modules/general/price_color');
                    }else{
                         return $rows[0][attribute_value];
                    }
                }elseif($attribute_name == "new_price_color"){
                    if(Mage::getStoreConfig('avatar_modules/general/new_price_color') != ""){
                        return Mage::getStoreConfig('avatar_modules/general/new_price_color');
                    }else{
                         return $rows[0][attribute_value];
                    }
                }elseif($attribute_name == "button_background_color"){
                     if(Mage::getStoreConfig('avatar_modules/general/button_background_color')==""){
                         if($advance_global_setting_enable){
                             return Mage::getStoreConfig('global_options/advance/button_background_color');
                         }else{
                             return $rows[0][attribute_value];
                         }
                     }else{
                         return Mage::getStoreConfig('avatar_modules/general/button_background_color');
                     }
                }elseif($attribute_name == "button_text_color"){
                    if(Mage::getStoreConfig('avatar_modules/general/button_text_color')==""){
                         if($advance_global_setting_enable){
                             return Mage::getStoreConfig('global_options/advance/button_text_color');
                         }else{
                             return $rows[0][attribute_value];
                         }
                     }else{
                         return Mage::getStoreConfig('avatar_modules/general/button_text_color');
                     }
                }elseif($attribute_name == "button_hover_color"){
                    if(Mage::getStoreConfig('avatar_modules/general/button_hover_color')==""){
                         if($advance_global_setting_enable){
                             return Mage::getStoreConfig('global_options/advance/button_hover_color');
                         }else{
                             return $rows[0][attribute_value];
                         }
                     }else{
                         return Mage::getStoreConfig('avatar_modules/general/button_hover_color');
                     }
                }elseif($attribute_name == "button_hover_text_color"){
                    if(Mage::getStoreConfig('avatar_modules/general/button_hover_text_color')==""){
                         if($advance_global_setting_enable){
                             return Mage::getStoreConfig('global_options/advance/button_hover_text_color');
                         }else{
                             return $rows[0][attribute_value];
                         }
                     }else{
                         return Mage::getStoreConfig('avatar_modules/general/button_hover_text_color');
                     }
                }elseif($attribute_name == "product_label_color"){
                    if(Mage::getStoreConfig('avatar_modules/general/product_label_color') != ""){
                        return Mage::getStoreConfig('avatar_modules/general/product_label_color');
                    }else{
                         return $rows[0][attribute_value];
                    }
                }elseif($attribute_name == "product_label_bg_color"){
                    if(Mage::getStoreConfig('avatar_modules/general/product_label_bg_color') != ""){
                        return Mage::getStoreConfig('avatar_modules/general/product_label_bg_color');
                    }else{
                         return $rows[0][attribute_value];
                    }
                }elseif($attribute_name == "discount_percentage_color"){
                    if(Mage::getStoreConfig('avatar_modules/general/discount_percentage_color') != ""){
                        return Mage::getStoreConfig('avatar_modules/general/discount_percentage_color');
                    }else{
                         return $rows[0][attribute_value];
                    }
                }elseif($attribute_name == "discount_percentage_bg"){
                    if(Mage::getStoreConfig('avatar_modules/general/discount_percentage_bg') != ""){
                        return Mage::getStoreConfig('avatar_modules/general/discount_percentage_bg');
                    }else{
                         return $rows[0][attribute_value];
                    }
                }
            }
    }
}