<?php

?>
<?php
class Fedobe_OneStepCheckout_Model_Observer {

    const CONFIG_ENABLE_MODULE = 'fedobe_onestepcheckout/general/enabled';

    public function addHistoryComment($data)
    {
        if(Mage::getStoreConfig(self::CONFIG_ENABLE_MODULE)){
            if(Mage::getStoreConfig('fedobe_onestepcheckout/general/allowcomment')){
                $comment	= Mage::getSingleton('customer/session')->getOrderCustomerComment();
                $comment	= trim($comment);
                if (!empty($comment))
                    if(!empty($data['order'])){
                        $order = $data['order'];
                        $order->addStatusHistoryComment($comment)->setIsVisibleOnFront(true)->setIsCustomerNotified(false);
                        $order->setCustomerComment($comment);
                        $order->setCustomerNoteNotify(true);
                        $order->setCustomerNote($comment);
                    }
            }
        }
        return $this;
    }
    public function redirectToOnestepcheckout($observer){
        $isRedirectAfterAddToCart = Mage::getStoreConfig('fedobe_onestepcheckout/general/redirect_to_afteraddtocart');
        if ($isRedirectAfterAddToCart){
            $url = Mage::getUrl('fedobe_onestepcheckout');
            $observer->getEvent()->getResponse()->setRedirect($url);
            Mage::getSingleton('checkout/session')->setNoCartRedirect(true);
        }

    }
}