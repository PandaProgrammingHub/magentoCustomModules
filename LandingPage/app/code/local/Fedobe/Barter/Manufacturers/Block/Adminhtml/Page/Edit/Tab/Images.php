<?php
$fedobe_attrspal = Mage::helper('core')->isModuleEnabled('Fedobe_AttributeSplash');
$fedobe_attrspaloutput = Mage::helper('core')->isModuleOutputEnabled('Fedobe_AttributeSplash');
$fish_attrspal = Mage::helper('core')->isModuleEnabled('Fishpig_AttributeSplash');
$fish_attrspaloutput = Mage::helper('core')->isModuleOutputEnabled('Fishpig_AttributeSplash');

if($fedobe_attrspal && $fedobe_attrspal){
    class Fedobe_Barter_Manufacturers_Block_Adminhtml_Page_Edit_Tab_Images extends Fedobe_AttributeSplash_Block_Adminhtml_Page_Edit_Tab_Images {

        protected function _prepareForm() {
            parent::_prepareForm();
            $form = $this->getForm();
            $fieldset = $form->getElement('splash_image');
            $fieldset->addField('option_label', 'text', array(
                'name' => 'option_label',
                'label' => $this->__('Sticker Label'),
                'title' => $this->__('Sticker Label'),
            ));
            $this->getForm()->setValues($this->_getFormData());

            return $this;
        }

    }
}else if($fish_attrspal && $fish_attrspaloutput){
        class Fedobe_Barter_Manufacturers_Block_Adminhtml_Page_Edit_Tab_Images extends Fishpig_AttributeSplash_Block_Adminhtml_Page_Edit_Tab_Images {

        protected function _prepareForm() {
            parent::_prepareForm();
            $form = $this->getForm();
            $fieldset = $form->getElement('splash_image');
            $fieldset->addField('option_label', 'text', array(
                'name' => 'option_label',
                'label' => $this->__('Sticker Label'),
                'title' => $this->__('Sticker Label'),
            ));
            $this->getForm()->setValues($this->_getFormData());

            return $this;
        }

    }
}
