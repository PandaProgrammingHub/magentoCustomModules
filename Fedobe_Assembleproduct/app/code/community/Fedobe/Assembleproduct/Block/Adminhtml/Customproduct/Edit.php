<?php
class Fedobe_Assembleproduct_Block_Adminhtml_Customproduct_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct() {
        parent::__construct();
        $this->_objectId = 'assembleproductid';
        $this->_blockGroup = 'assembleproduct';
        $this->_controller = 'adminhtml_customproduct';
        $this->_mode = 'edit';
        $this->_removeButton('reset');
        $this->_updateButton('save', 'label', Mage::helper('assembleproduct')->__('Save Dependency'));
        $this->_updateButton('delete', 'label', Mage::helper('assembleproduct')->__('Delete'));
        $this->_addButton('saveandcontinue', [
           'label' => Mage::helper('assembleproduct')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ], -100);
        
        $this->_formScripts[] ="
            function toggleEditor(){
                if (tinyMCE.getInstanceById('form_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'edit_form');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'edit_form');
                }
            }
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }"; 
        
    }
        
    public function getHeaderText() {
        if (Mage::registry('assembleproduct') && Mage::registry('assembleproduct')->getId()) {
            return Mage::helper('assembleproduct')->__('Manage Dependency "%s"', $this->htmlEscape(Mage::registry('assembleproduct')->getName()));
        } 
    }
    /**
     * Retrieve currently edited product object
     *
     * @return Mage_Catalog_Model_Product
     */
    

}