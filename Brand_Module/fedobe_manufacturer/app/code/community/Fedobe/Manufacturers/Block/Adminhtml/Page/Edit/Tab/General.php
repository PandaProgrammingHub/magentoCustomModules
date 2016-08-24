<?php
$fedobe_attrspal = Mage::helper('core')->isModuleEnabled('Fedobe_Landingpage');
$fedobe_attrspaloutput = Mage::helper('core')->isModuleOutputEnabled('Fedobe_Landingpage');

if($fedobe_attrspal && $fedobe_attrspal){
    class Fedobe_Manufacturers_Block_Adminhtml_Page_Edit_Tab_General extends Fedobe_Landingpage_Block_Adminhtml_Page_Edit_Tab_General {

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
}
