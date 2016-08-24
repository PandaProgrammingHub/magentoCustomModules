<?php
/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */

class Fedobe_Landingpage_Block_Adminhtml_Page_Edit_Tab_Meta extends Fedobe_Landingpage_Block_Adminhtml_Page_Edit_Tab_Abstract
{
	/**
	 * Add the meta fields to the form
	 *
	 * @return $this
	 */
	protected function _prepareForm()
	{
		parent::_prepareForm();
		
		$fieldset = $this->getForm()->addFieldset('splash_page_meta', array(
			'legend'=> $this->helper('adminhtml')->__('Meta Data'),
			'class' => 'fieldset-wide',
		));


		$fieldset->addField('page_title', 'text', array(
			'name' => 'page_title',
			'label' => $this->__('Page Title'),
			'title' => $this->__('Page Title'),
		))->setRenderer(
			$this->getLayout()->createBlock('landingpage/adminhtml_form_field_storechecker')
		);
		$fieldset->addField('meta_description', 'editor', array(
			'name' => 'meta_description',
			'label' => $this->__('Description'),
			'title' => $this->__('Description'),
			'style' => 'width:98%; height:110px;',
		))->setRenderer(
			$this->getLayout()->createBlock('landingpage/adminhtml_form_field_storechecker')
		);
		$fieldset->addField('meta_keywords', 'editor', array(
			'name' => 'meta_keywords',
			'label' => $this->__('Keywords'),
			'title' => $this->__('Keywords'),
			'style' => 'width:98%; height:110px;',
		))->setRenderer(
			$this->getLayout()->createBlock('landingpage/adminhtml_form_field_storechecker')
		);
                $fieldset->addField('set_index', 'select', array(
			'name' => 'set_index',
			'label' => $this->__('Set Index'),
			'title' => $this->__('Set Index'),
                        'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
		))->setRenderer(
			$this->getLayout()->createBlock('landingpage/adminhtml_form_field_storechecker')
		);
		$this->getForm()->setValues($this->_getFormData());
		
		return $this;
	}
}
