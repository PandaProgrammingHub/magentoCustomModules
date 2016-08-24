<?php
$fedobe_attrspal = Mage::helper('core')->isModuleEnabled('Fedobe_AttributeSplash');
$fedobe_attrspaloutput = Mage::helper('core')->isModuleOutputEnabled('Fedobe_AttributeSplash');
$fish_attrspal = Mage::helper('core')->isModuleEnabled('Fishpig_AttributeSplash');
$fish_attrspaloutput = Mage::helper('core')->isModuleOutputEnabled('Fishpig_AttributeSplash');

if($fedobe_attrspal && $fedobe_attrspal){
    class Fedobe_Barter_Manufacturers_Block_Adminhtml_Page_Edit_Tab_General extends Fedobe_AttributeSplash_Block_Adminhtml_Page_Edit_Tab_General {

        protected function _prepareForm() {
            parent::_prepareForm();
            $form = $this->getForm();
            $fieldset = $form->getElement('splash_page_information');
            $fieldset->addField('url_slug', 'text', array(
                'name' => 'url_slug',
                'label' => $this->__('URL Slug'),
                'title' => $this->__('URL Slug'),
                'tabindex' => 3,
                'after_element_html' => '<small>Custom URL slug</small>',
            ));
            $this->getForm()->setValues($this->_getFormData());

            return $this;
        }

    }
}else if($fish_attrspal && $fish_attrspaloutput){
        class Fedobe_Barter_Manufacturers_Block_Adminhtml_Page_Edit_Tab_General extends Fedobe_AttributeSplash_Block_Adminhtml_Page_Edit_Tab_General {

        protected function _prepareForm() {
            parent::_prepareForm();
            $form = $this->getForm();
            $fieldset = $form->getElement('splash_page_information');
            $fieldset->addField('url_slug', 'text', array(
                'name' => 'url_slug',
                'label' => $this->__('URL Slug'),
                'title' => $this->__('URL Slug'),
            ));
            $this->getForm()->setValues($this->_getFormData());

            return $this;
        }

    }
}
