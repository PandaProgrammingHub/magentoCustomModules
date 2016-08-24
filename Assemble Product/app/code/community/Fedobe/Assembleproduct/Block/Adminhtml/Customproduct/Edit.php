<?php
class Fedobe_Assembleproduct_Block_Adminhtml_Customproduct_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct() {
        parent::__construct();
        $this->_objectId = 'assembleproductid';
        $this->_blockGroup = 'fedobe_assembleproduct';
        $this->_controller = 'adminhtml_customproduct';
        $this->_mode = 'edit';
        $this->_removeButton('reset');
        $this->_updateButton('save', 'label', Mage::helper('fedobe_assembleproduct')->__('Save Dependency'));
        $this->_updateButton('delete', 'label', Mage::helper('fedobe_assembleproduct')->__('Delete'));
        $this->_addButton('saveandcontinue', [
           'label' => Mage::helper('fedobe_assembleproduct')->__('Save And Continue Edit'),
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
        if (Mage::registry('fedobe_assembleproduct') && Mage::registry('fedobe_assembleproduct')->getId()) {
            return Mage::helper('fedobe_assembleproduct')->__('Manage Dependency "%s"', $this->htmlEscape(Mage::registry('fedobe_assembleproduct')->getName()));
        } 
    }
    /**
     * Retrieve currently edited product object
     *
     * @return Mage_Catalog_Model_Product
     */
    

}