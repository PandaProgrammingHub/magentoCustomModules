<?php

/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */
class Fedobe_Landingpage_Block_Adminhtml_Page_Edit_Tab_Content extends Fedobe_Landingpage_Block_Adminhtml_Page_Edit_Tab_Abstract {

    /**
     * Prepare the form
     *
     * @return $this
     */
    protected function _prepareForm() {
        parent::_prepareForm();

        $fieldset = $this->getForm()->addFieldset('splash_page_content', array(
            'legend' => $this->helper('adminhtml')->__('Content'),
            'class' => 'fieldset-wide',
        ));

        $htmlConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(array(
            'add_widgets' => true,
            'add_variables' => true,
            'add_image' => true,
            'files_browser_window_url' => $this->getUrl('adminhtml/cms_wysiwyg_images/index')
        ));

        $fields = array(
            'short_description' => 'Short Description',
            'description' => 'Description',
        );

        foreach ($fields as $field => $label) {
            $fieldset->addField($field, 'editor', array(
                'name' => $field,
                'label' => $this->helper('adminhtml')->__($label),
                'title' => $this->helper('adminhtml')->__($label),
                'style' => 'width:100%; height:400px;',
                'config' => $htmlConfig,
            ))->setRenderer(
			$this->getLayout()->createBlock('landingpage/adminhtml_form_field_storechecker')
            );
        }

        $this->getForm()->setValues($this->_getFormData());

        return $this;
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();

        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

}