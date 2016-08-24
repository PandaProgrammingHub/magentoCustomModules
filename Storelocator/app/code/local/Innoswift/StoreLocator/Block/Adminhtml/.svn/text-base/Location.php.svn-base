<?php

/**
 * Description of Location
 *
 * @author Annavarapu prasad
 */
class Innoswift_StoreLocator_Block_Adminhtml_Location extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('inno/storelocator/location.phtml');
        if (is_null($this->_addButtonLabel)) {
            $this->_addButtonLabel = $this->__('Add New Location');
        }
        $this->_controller = 'adminhtml_location';
        $this->_blockGroup = 'storelocator';
        $this->_headerText = Mage::helper('storelocator')->__('Store Locations');
    }

    public function getGridHtml() {
        return $this->getChildHtml('grid');
    }

    public function isSingleStoreMode() {
        if (!Mage::app()->isSingleStoreMode()) {
            return false;
        }
        return true;
    }

}

