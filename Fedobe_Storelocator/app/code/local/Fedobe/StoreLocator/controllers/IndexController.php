<?php

/**
 * Description of IndexController
 * 
 * This is the controller for displaying page to search store loactors,
 * getting location of perticuler address of store
 *
 * @author Annavarapu prasad
 */
class Fedobe_StoreLocator_IndexController extends Mage_Core_Controller_Front_Action {

    /**
     * action for render layout
     */
    public function indexAction() {
        $this->loadLayout();      
        $this->renderLayout();
    }

}

