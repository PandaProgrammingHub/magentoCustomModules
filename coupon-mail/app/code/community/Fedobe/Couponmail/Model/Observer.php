<?php
class Fedobe_Couponmail_Model_Observer
{
	
        
    public function sendOrderEmails(Varien_Event_Observer $observer){
        $enable=Mage::getStoreConfig('coupon_mail/couponmail/enable-options');
        $promotion_coupon=Mage::getStoreConfig('coupon_mail/couponmail/block-coupon-code');
        $promotion_coupon=trim($promotion_coupon);
        $len1=strlen($promotion_coupon);
        
          if($enable == 1)  {  
          
	$order = new Mage_Sales_Model_Order();
   	$incrementId = trim(Mage::getSingleton('checkout/session')->getLastRealOrderId());
   	$order->loadByIncrementId($incrementId);
   	$applied_coupon=$order->getCouponCode();
        $applied_coupon=trim($applied_coupon);
        $len2=strlen($applied_coupon);
         
        if( $applied_coupon == $promotion_coupon)
       { 
               
       

               $this->_sendCouponMail($order);
       }
       
       }
	

    }

   public  function _sendCouponMail($order)
    {
          $coupon_mail=Mage::getStoreConfig('coupon_mail/couponmail/email-coupon-code');
        $exparies_on=Mage::getStoreConfig('coupon_mail/couponmail/coupon-code-exparies-on');
          $store_id =$order->getStoreId();
      $logo_src =   Mage::getStoreConfig('design/header/logo_src', $store_id);
      $logo_alt =   Mage::getStoreConfig('design/header/logo_alt', $store_id);
      $coupon_img = Mage::getBaseUrl('media');
      $coupon_img  .= "card.jpg";             

          $emailTemplate  = Mage::getModel('core/email_template');
 
        $emailTemplate->loadDefault('coupon_mail');
        $emailTemplate->setTemplateSubject('Your order Coupon Code');
 
        // Get General email address (Admin->Configuration->General->Store Email Addresses)
        $salesData['email'] = Mage::getStoreConfig('trans_email/ident_general/email');
        $salesData['name'] = Mage::getStoreConfig('trans_email/ident_general/name');
 
        $emailTemplate->setSenderName($salesData['name']);
        $emailTemplate->setSenderEmail($salesData['email']);
        
        $emailTemplateVariables['exparies_on'] = $exparies_on;
        $emailTemplateVariables['logo_alt'] = $logo_alt;
        $emailTemplateVariables['coupon_img'] = $coupon_img;
        $emailTemplateVariables['username']  = $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname();
        $emailTemplateVariables['order_id'] = $order->getIncrementId();
        $emailTemplateVariables['coupon_code'] = $coupon_mail;
        $emailTemplateVariables['store_name'] = $order->getStoreName();
        $emailTemplateVariables['store_url'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        $emailTemplate->send($order->getCustomerEmail(), $order->getStoreName(), $emailTemplateVariables);
       
    return $this;
    }
}