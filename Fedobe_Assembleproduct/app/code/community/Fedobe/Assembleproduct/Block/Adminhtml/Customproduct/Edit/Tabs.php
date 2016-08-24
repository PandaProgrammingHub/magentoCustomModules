<?php
class Fedobe_Assembleproduct_Block_Adminhtml_Customproduct_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs{
    
    protected $_attributeTabBlock = 'assembleproduct/adminhtml_customproduct_edit_tabs_action';
    
    public function __construct() {
        parent::__construct();
        $this->setId('assembleproduct_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('assembleproduct')->__('Manage Dependency'));
    }
   protected function _beforeToHtml() {
        $this->addTab('dependency_section', array(
            'label' => Mage::helper('assembleproduct')->__('create Dependency'),
            'title' => Mage::helper('assembleproduct')->__('create Dependency'),
            'content' => $this->getLayout()->createBlock('assembleproduct/adminhtml_customproduct_edit_tabs_action')->toHtml(),
                    ));
        return parent::_beforeToHtml();
    } 
    
} 