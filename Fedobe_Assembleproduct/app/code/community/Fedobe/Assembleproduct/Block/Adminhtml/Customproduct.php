<?php
class Fedobe_Assembleproduct_Block_Adminhtml_Customproduct extends Mage_Adminhtml_Block_Widget_Grid_Container{
    public function __construct() {
        $this->_controller = 'adminhtml_customproduct';
        $this->_blockGroup = 'assembleproduct';
        $this->_headerText = $this->__('Manage Assemble Product Dependency');
        parent::__construct();
        $this->_removeButton('add');
    }

}

