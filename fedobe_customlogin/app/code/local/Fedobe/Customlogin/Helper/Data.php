<?php
  
class Fedobe_Customlogin_Helper_Data extends Mage_Core_Helper_Abstract
{  

    public function getCustomloginBlock(){
       
     return Mage::app()->getLayout()->createBlock('customlogin/customlogin')->setTemplate('fedobe/customlogin/customlogin.phtml')->toHtml();
    }
} 