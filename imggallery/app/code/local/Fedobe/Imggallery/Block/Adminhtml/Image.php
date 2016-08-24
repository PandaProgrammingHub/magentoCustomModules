<?php
class Fedobe_Imggallery_Block_Adminhtml_Image extends Mage_Adminhtml_Block_Widget_Grid_Container{
    public function __construct() {
        $this->_controller = 'adminhtml_image';
        $this->_blockGroup = 'imggallery';
        $this->_headerText = Mage::helper('imggallery')->__('Image Manager');
        $this->_addButtonLabel = Mage::helper('imggallery')->__('Add image');
        parent::__construct();
    }
}

