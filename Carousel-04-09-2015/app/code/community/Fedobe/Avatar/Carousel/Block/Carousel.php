<?php

class Fedobe_Avatar_Carousel_Block_Carousel extends Mage_Core_Block_Template 
{
    public function parameterValue()
    {   
        $global_theme_color = Mage::getStoreConfig('global_options/general/global_color');
        
        //******BASIC SETTINGS******//
        $product_type = $this->getData('product_type');//?$this->getData('product_type'):Mage::getStoreConfig('avatar_modules/general/product_type');
        //$product_category = $this->getData('category_id')?$this->getData('category_id'):Mage::getStoreConfig('avatar_modules/general/product_category');
        $related_product_sku = $this->getData('related_product_sku')?$this->getData('related_product_sku'):Mage::getStoreConfig('avatar_modules/general/related_product_sku');
        $show_product_name = $this->getData('show_product_name');//?$this->getData('show_product_name'):Mage::getStoreConfig('avatar_modules/general/show_product_name');
        $show_product_sku = $this->getData('show_product_sku');//?$this->getData('show_product_sku'):Mage::getStoreConfig('avatar_modules/general/show_product_sku');
        $show_product_price = $this->getData('show_product_price');//?$this->getData('show_product_price'):Mage::getStoreConfig('avatar_modules/general/show_product_price');
        $show_product_addtocart = $this->getData('show_product_addtocart');//?$this->getData('show_product_addtocart'):Mage::getStoreConfig('avatar_modules/general/show_product_addtocart');
        $show_product_label = (($this->getData('show_product_label')=="no")||($this->getData('show_product_label')=="yes"))?$this->getData('show_product_label'):Mage::getStoreConfig('avatar_modules/general/show_product_label');
        $product_top_label = $this->getData('product_label')?$this->getData('product_label'):Mage::getStoreConfig('avatar_modules/general/product_label');
        $show_discount_percentage = (($this->getData('show_discount_percentage')=="no")||($this->getData('show_discount_percentage')=="yes"))?$this->getData('show_discount_percentage'):Mage::getStoreConfig('avatar_modules/general/show_discount_percentage');
        if(!$show_product_label || ($show_product_label=="no")){
            $product_label_display_none = "display: none;";
        }
        if(!$show_discount_percentage || ($show_discount_percentage=="no")){
            $discount_percentage_display_none = "display: none;";
        }

        //******ADVANCE SETTINGS******//
        $carousel_items = $this->getData('items');//?$this->getData('items'):Mage::getStoreConfig('avatar_modules/general/items');
        $slider_speed = $this->getData('slider_speed')?$this->getData('slider_speed'):Mage::getStoreConfig('avatar_modules/general/slider_speed');
        $pagination_speed = $this->getData('pagination_speed')?$this->getData('pagination_speed'):Mage::getStoreConfig('avatar_modules/general/pagination_speed');
        $auto_play = $this->getData('auto_play')?$this->getData('auto_play'):Mage::getStoreConfig('avatar_modules/general/auto_play');
        $stop_on_hover = $this->getData('stop_on_hover')?$this->getData('stop_on_hover'):Mage::getStoreConfig('avatar_modules/general/stop_on_hover');
        $navigation_button = $this->getData('navigation_button')?$this->getData('navigation_button'):Mage::getStoreConfig('avatar_modules/general/navigation_button');
        $navigation_button_position = $this->getData('navigation_button_position')?$this->getData('navigation_button_position'):Mage::getStoreConfig('avatar_modules/general/navigation_button_position');
        if(Mage::getStoreConfig('avatar_modules/general/navigation_button')){
                $navigation_button_position = $this->getData('navigation_button_position')?$this->getData('navigation_button_position'):Mage::getStoreConfig('avatar_modules/general/navigation_button_position');
            }else{
                $navigation_button_position = $this->getData('navigation_button_position')?$this->getData('navigation_button_position'):"topright";
            }
        $show_pagination = $this->getData('show_pagination')?$this->getData('show_pagination'):Mage::getStoreConfig('avatar_modules/general/show_pagination');
        $lazy_load = $this->getData('lazy_load')?$this->getData('lazy_load'):Mage::getStoreConfig('avatar_modules/general/lazy_load');
         if($auto_play == 1){ $auto_play = "true"; }else{ $auto_play = "false"; }
            if($stop_on_hover == 1){ $stop_on_hover = "true"; }else{ $stop_on_hover = "false";}
            if($navigation_button == 1){ $navigation_button = "true"; }else{$navigation_button = "false"; }
            if($show_pagination == 1){$show_pagination = "true"; }else{ $show_pagination = "false"; }
            if($lazy_load == 1){ $lazy_load = "true"; }else{ $lazy_load = "false"; }

        //******CUSTOM STYLE SETTINGS******//
        $border_width = $this->getData('border_width')?$this->getData('border_width'):Mage::helper('carousel')->getAttributeValue("border_width");
        $border_type = $this->getData('border_type')?$this->getData('border_type'):Mage::helper('carousel')->getAttributeValue("border_type");
        $border_color = $this->getData('border_color')?$this->getData('border_color'):Mage::helper('carousel')->getAttributeValue("border_color");
        $text_color = $this->getData('text_color')?$this->getData('text_color'):Mage::helper('carousel')->getAttributeValue("text_color");
        $link_color = $this->getData('link_color')?$this->getData('link_color'):Mage::helper('carousel')->getAttributeValue("link_color");
        $link_hover_color = $this->getData('link_hover_color')?$this->getData('link_hover_color'):Mage::helper('carousel')->getAttributeValue("link_hover_color");
        $price_color = $this->getData('price_color')?$this->getData('price_color'):Mage::helper('carousel')->getAttributeValue("price_color");
        $new_price_color = $this->getData('new_price_color')?$this->getData('new_price_color'):Mage::helper('carousel')->getAttributeValue("new_price_color");
        $button_background_color = $this->getData('button_background_color')?$this->getData('button_background_color'):Mage::helper('carousel')->getAttributeValue("button_background_color");
        $button_text_color = $this->getData('button_text_color')?$this->getData('button_text_color'):Mage::helper('carousel')->getAttributeValue("button_text_color");
        $button_hover_color = $this->getData('button_hover_color')?$this->getData('button_hover_color'):Mage::helper('carousel')->getAttributeValue("button_hover_color");
        $button_hover_text_color = $this->getData('button_hover_text_color')?$this->getData('button_hover_text_color'):Mage::helper('carousel')->getAttributeValue("button_hover_text_color");
        $product_label_color = $this->getData('product_label_color')?$this->getData('product_label_color'):Mage::helper('carousel')->getAttributeValue("product_label_color");
        $product_label_bg_color = $this->getData('product_label_bg_color')?$this->getData('product_label_bg_color'):Mage::helper('carousel')->getAttributeValue("product_label_bg_color");
        $discount_percentage_color = $this->getData('discount_percentage_color')?$this->getData('discount_percentage_color'):Mage::helper('carousel')->getAttributeValue("discount_percentage_color");
        $discount_percentage_bg = $this->getData('discount_percentage_bg')?$this->getData('discount_percentage_bg'):Mage::helper('carousel')->getAttributeValue("discount_percentage_bg");
        $custom_style_css_enable = $this->getData('custom_style_css_enable')?$this->getData('custom_style_css_enable'):Mage::helper('carousel')->getAttributeValue("custom_style_css_enable");
        if($custom_style_css_enable){
            $custom_style_css = Mage::getStoreConfig('avatar_modules/general/custom_style_css');
        }else{
            $custom_style_css = "";
        }

        //**********New Options**********//
        $categories = $this->getData('categories');
        $attributes = $this->getData('attributes');
		$heading = $this->getData('heading');
        
        return array(
            "global_theme_color"=>$global_theme_color,
            "product_type"=>$product_type,
//            "product_category"=>$product_category,
            "related_product_sku"=>$related_product_sku,
            "show_product_name"=>$show_product_name,
            "show_product_sku"=>$show_product_sku,
            "show_product_name"=>$show_product_name,
            "show_product_price"=>$show_product_price,
            "show_product_addtocart"=>$show_product_addtocart,
            "product_top_label"=>$product_top_label,
            "product_label_display_none"=>$product_label_display_none,
            "discount_percentage_display_none"=>$discount_percentage_display_none,
            "carousel_items"=>$carousel_items,
            "slider_speed"=>$slider_speed,
            "pagination_speed"=>$pagination_speed,
            "auto_play"=>$auto_play,
            "stop_on_hover"=>$stop_on_hover,
            "navigation_button"=>$navigation_button,
            "navigation_button_position"=>$navigation_button_position,
            "show_pagination"=>$show_pagination,
            "lazy_load"=>$lazy_load,
            "border_width"=>$border_width,
            "border_type"=>$border_type,
            "border_color"=>$border_color,
            "text_color"=>$text_color,
            "link_color"=>$link_color,
            "link_hover_color"=>$link_hover_color,
            "price_color"=>$price_color,
            "new_price_color"=>$new_price_color,
            "button_background_color"=>$button_background_color,
            "button_text_color"=>$button_text_color,
            "button_hover_color"=>$button_hover_color,
            "button_hover_text_color"=>$button_hover_text_color,
            "product_label_color"=>$product_label_color,
            "product_label_bg_color"=>$product_label_bg_color,
            "discount_percentage_color"=>$discount_percentage_color,
            "discount_percentage_bg"=>$discount_percentage_bg,
            "custom_style_css" => $custom_style_css,
            "categories" => $categories,
            "attributes" => $attributes,
			"heading" => $heading,
                );
    } 
}