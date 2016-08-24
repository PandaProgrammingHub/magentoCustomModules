<?php
//delete
class Fedobe_Avatar_Carousel_Model_System_Config_Source_Instructiondetails {

    public function getCommentText($element, $currentValue) {
        $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)."fedobebrandpage";
        $instructions = <<<INSTRUC
<h3>This is the Brand Page pro module by <a href="http://fedobe.com/">Fedobe Solutions Private Limited</a></h3>
<div style="margin-top: 15px;">
    <div>
        <h4><span class='notice'><b><u>Note:</u></b></span></h4>
        <div>
             While being used in default theme please set <b><span class='error'>Load JQuery</span></b> to <b><span class='notice'>yes</span></b> in <b><span class='error'>General</span></b> tab of this page in order to make everything functional.  
        </div>  
        </br>    
    </div>
    
    <div>
        <h4><span class='error'><b><u>Features:</u></b></span></h4>
    </div>
    <ul style="list-style-type: square;">
        <li>
            It creates the brand page automatically for you (<a href="$url">Click Here to see you Brand Page</a>).<br/>
            <span class='notice'>Change the CMS page identifier "fedobebrandpage" to your own page identifier from magento admin (CMS > Pages)</span>   
        </li>
        <li>
            Purely based on block codes that can be used as many places you want.
        </li>
        <li>
            Comes with three label brand filter mechanism.
        </li>
        <li>
            Over more than 30 parameters and settings to configure according to the need,for documentation details (<a href="javascript:void(0);">Visit here</a>).
        </li>
        <li>
            You can able to upload brand images and sticker labels for the Brands (i.e from top menu Fedobe > Brand Images)
        </li>
        <li>
            Brand informations in product and category page
        </li>
        <li>
            This includes a slider based on block codes with flexible parametes and settings.    
        </li>
        <li>
            Purely responsive and tested with RWD,Ultimo theme.    
        </li>  
    </ul>
</div>
<div style="margin-top: 10px;">
    This is the default block code example that is being used,which can be configured with the flexible parameters and settings.<br/>
    <b>{{block type="manufacturers/list" name="fedobe.brandpage" template="fedobe/manufacturers/grid.phtml" }}</b></br>
   This is another example of block code with some sort of paramters to display the brands</br> 
   <b>{{block type="manufacturers/list" name="fedobe.brandpage" template="fedobe/manufacturers/grid.phtml"  enable_filter=0 display_type='image' show_product_count=0 dispaly_brand_item_stickers=1 show_all_brands_link=0 total_to_show=2 stickers="NEW,HOT"  block_title="Our Brands"}}</b>            
</div>
INSTRUC;
        return $instructions;
    }

}