<?php

class Fedobe_Customlogin_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction(){
		$email = $this->getRequest()->getParam('email');
		if($this->isEmailExist($email))
		{
			echo "1";
		}else{
			echo "0";
		}
		
	}
    public function loginAction(){
      $email = $this->getRequest()->getParam('email');
      $password = $this->getRequest()->getParam('password');
      if($this->loginUser($email,$password)){
      	echo "1";
      }else{
      	echo "0";
      }

    }

    public function registerAction(){
      $email = $this->getRequest()->getParam('email');
      $fname = $this->getRequest()->getParam('fname');
      $lname = $this->getRequest()->getParam('lname');
      $pass = $this->getRequest()->getParam('pass');
      $cpass = $this->getRequest()->getParam('cpass');
      //echo $pass.$cpass;
      if($this->setPassword($pass, $cpass))
      {
      	if($this->_result == '')
      	{
      		$pass = $this->setPassword($pass, $cpass);

      		if($this->registerUser($email,$fname,$lname,$pass)){
      	    echo "1";
          	}else{
      	    echo "0";
          }
      	}
      	else{
      		echo "2";
      	}
      }
     
      

    }
    
	protected function isEmailExist($email)
    {
        $customer = Mage::getModel('customer/customer');
        
        $customer->setWebsiteId(Mage::app()->getWebsite()->getId());
        $customer->loadByEmail($email);
        
        if($customer->getId()) {
            return true;
        }

        return false;
    }

    protected function loginUser($email,$password) {
        $session = Mage::getSingleton('customer/session');

        try {
            $session->login($email, $password);
            $customer = $session->getCustomer();
            
            $session->setCustomerAsLoggedIn($customer);
            
            return true;
        } catch(Exception $ex) {
            return false;
        }
    }


    protected function registerUser($email,$fname,$lname,$pass)
    {
        // Empty customer object
        $customer = Mage::getModel('customer/customer');

        $customer->setWebsiteId(Mage::app()->getWebsite()->getId());

        // Set customer
        $customer->setEmail($email);
        $customer->setPassword($pass);
        $customer->setFirstname($fname);
        $customer->setLastname($lname);

        // Try create customer
        try {
            $customer->save();
            $customer->setConfirmation(null);
            $customer->save();
            
            $storeId = $customer->getSendemailStoreId();
            $customer->sendNewAccountEmail('registered', '', $storeId);
            
            Mage::getSingleton('customer/session')->loginById($customer->getId());
            
            //$this->_userId = $customer->getId();
            
            return true;
        // Error by injected HTML/JS
        } catch (Exception $ex) {
            return false;
        }
    }

    protected function setPassword($password = '', $confirmation = '')
    {
        // Sanitize password
        $sanitizedPassword = str_replace(array('\'', '%', '\\', '/', ' '), '', $password);

        // Special characters
        if ($password != $sanitizedPassword) {
            $this->_result .= 'dirtypassword,';
            return true;
        }

        // Too short
        if (strlen($sanitizedPassword) < 6) {
            $this->_result .= 'shortpassword,';
            return true;
        }

        // Too long
        if (strlen($sanitizedPassword) > 16) {
            $this->_result .= 'longpassword,';
            return true;
        }

        // Two passwords does not match
        if ($sanitizedPassword != $confirmation) {
            $this->_result .= 'notsamepasswords,';
            return true;
        }
        
        return $sanitizedPassword;
    }
    
}
