<?php
class Fedobe_Assembleproduct_Block_Adminhtml_Customproduct_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs{
    
    public function __construct() {
        parent::__construct();
        $this->setId('assembleproduct_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('fedobe_assembleproduct')->__('Manage Dependency'));
    }
   protected function _beforeToHtml() {
        $this->addTab('dependency_section', array(
            'label' => Mage::helper('fedobe_assembleproduct')->__('create Dependency'),
            'title' => Mage::helper('fedobe_assembleproduct')->__('create Dependency'),
            'content' => $this->getLayout()->createBlock('fedobe_assembleproduct/adminhtml_customproduct_edit_tabs_action')->toHtml(),
                    ));
        return parent::_beforeToHtml();
    } 
    
} 