<?php
/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */

class Fedobe_Landingpage_Block_Adminhtml_Page_Edit_Tab_Design extends Fedobe_Landingpage_Block_Adminhtml_Page_Edit_Tab_Abstract
{
	/**
	 * Retrieve Additional Element Types
	 *
	 * @return array
	*/
	protected function _getAdditionalElementTypes()
	{
		return array(
			'image' => Mage::getConfig()->getBlockClassName('landingpage/adminhtml_page_helper_image')
		);
	}

	/**
	 * Add the design elements to the form
	 *
	 * @return $this
	 */
	protected function _prepareForm()
	{
		parent::_prepareForm();

		$fieldset = $this->getForm()->addFieldset('splash_image', array(
			'legend'=> $this->__('Images')
		));

		$this->_addElementTypes($fieldset);
		
		$fieldset->addField('image', 'image', array(
			'name' 	=> 'image',
			'label' => $this->__('Banner'),
			'title' => $this->__('Banner'),
		))->setRenderer(
			$this->getLayout()->createBlock('landingpage/adminhtml_form_field_storechecker')
		);
		$fieldset->addField('thumbnail', 'image', array(
			'name' 	=> 'thumbnail',
			'label' => $this->__('Logo'),
			'title' => $this->__('Logo'),
		))->setRenderer(
			$this->getLayout()->createBlock('landingpage/adminhtml_form_field_storechecker')
		);
		$fieldset->addField('image_option_label', 'text', array(
			'name' 	=> 'image_option_label',
			'label' => $this->__('Sticker Label'),
			'title' => $this->__('Sticker Label'),
		))->setRenderer(
			$this->getLayout()->createBlock('landingpage/adminhtml_form_field_storechecker')
		);


		$fieldset = $this->getForm()->addFieldset('splash_design_page_layout', array(
			'legend'=> $this->helper('adminhtml')->__('Page Layout'),
			'class' => 'fieldset-wide',
		));

		$fieldset->addField('page_layout', 'select', array(
			'name' => 'page_layout',
			'label' => $this->__('Page Layout'),
			'title' => $this->__('Page Layout'),
			'values' => Mage::getSingleton('landingpage/system_config_source_layout')->toOptionArray(),
		))->setRenderer(
			$this->getLayout()->createBlock('landingpage/adminhtml_form_field_storechecker')
		);
		$fieldset->addField('layout_update_xml', 'editor', array(
			'name' => 'layout_update_xml',
			'label' => $this->__('Layout Update XML'),
			'title' => $this->__('Layout Update XML'),
			'style' => 'width:600px;',
		))->setRenderer(
			$this->getLayout()->createBlock('landingpage/adminhtml_form_field_storechecker')
		);
		$fieldset = $this->getForm()->addFieldset('splash_design_display_settings', array(
			'legend'=> $this->helper('adminhtml')->__('Display Settings'),
			'class' => 'fieldset-wide',
		));
		
		$fieldset->addField('display_mode', 'select', array(
			'name' => 'display_mode',
			'label' => $this->__('Display Mode'),
			'title' => $this->__('Display Mode'),
			'values' => Mage::getModel('catalog/category_attribute_source_mode')->getAllOptions(),
		))->setRenderer(
			$this->getLayout()->createBlock('landingpage/adminhtml_form_field_storechecker')
		);
		$fieldset->addField('cms_block', 'select', array(
			'name' => 'cms_block',
			'label' => $this->__('CMS Block'),
			'title' => $this->__('CMS Block'),
			'values' => Mage::getModel('catalog/category_attribute_source_page')->getAllOptions(),
		))->setRenderer(
			$this->getLayout()->createBlock('landingpage/adminhtml_form_field_storechecker')
		);
		$this->getForm()->setValues($this->_getFormData());
		
		return $this;
	}
}

