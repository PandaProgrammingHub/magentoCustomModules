<?php
$fedobe_attrspal = Mage::helper('core')->isModuleEnabled('Fedobe_Landingpage');
$fedobe_attrspaloutput = Mage::helper('core')->isModuleOutputEnabled('Fedobe_Landingpage');

if($fedobe_attrspal && $fedobe_attrspal){
    class Fedobe_Manufacturers_Block_Adminhtml_Page_Edit_Tab_Images extends Fedobe_Landingpage_Block_Adminhtml_Page_Edit_Tab_Images {

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
