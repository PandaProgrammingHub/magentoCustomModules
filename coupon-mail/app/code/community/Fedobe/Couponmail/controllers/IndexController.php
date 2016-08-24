<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Fedobe_Couponmail_IndexController extends Mage_Core_Controller_Front_Action {        
    public function indexAction() 
	{
             echo "<script>alert('hello')</script>";
		$emailTemplate = Mage::getModel('core/email_template')
            ->loadDefault('coupon_mail_template');

    $emailTemplateVariables = array();
    $emailTemplateVariables['myvar1'] = 'var1 value';
    $emailTemplateVariables['myvar2'] = 'var 2 value';
    $emailTemplateVariables['myvar3'] = 'var 3 value';

   $emailTemplate->getProcessedTemplate($emailTemplateVariables);

   $emailTemplate->setSenderName('santosh panda');
   $emailTemplate->setSenderEmail('santosh@fedobe.com');
    try {
   $emailTemplate->send($recipientEmail, $senderName, $emailTemplateVariables);
    } catch (Exception $e) {
        echo $e->getMessage();
    } 
	}
}