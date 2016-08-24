<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Edit
 *
 * @author Annavarapu prasad
 */
class Fedobe_StoreLocator_Block_Adminhtml_Location_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'storelocator';
        $this->_controller = 'adminhtml_location';
        $this->_mode = 'edit';

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);
        $this->_updateButton('delete', 'label', Mage::helper('storelocator')->__('Delete Location'));
        $this->_updateButton('save', 'label', Mage::helper('storelocator')->__('Save Location'));

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('form_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'edit_form');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'edit_form');
                }
            }
 
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getLocation() {
        return Mage::registry('current_location');
    }

    public function getHeaderText() {
        if (Mage::registry('current_location') && Mage::registry('current_location')->getId()) {
            return Mage::helper('storelocator')->__('Edit Location "%s"', $this->htmlEscape(Mage::registry('current_location')->getStoreTitle()));
        } else {
            return Mage::helper('storelocator')->__('New Location');
        }
    }

//    protected function _prepareLayout() {
//        if ($this->_blockGroup && $this->_controller && $this->_mode) {
//            $this->setChild('form', $this->getLayout()->createBlock($this->_blockGroup . '/' . $this->_controller . '_' . $this->_mode . '_form'));
//        }
//        return parent::_prepareLayout();
//    }
}

