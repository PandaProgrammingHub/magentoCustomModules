<?php
/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */

class Fedobe_Landingpage_Block_Adminhtml_Page_Edit_Tab_General extends Fedobe_Landingpage_Block_Adminhtml_Page_Edit_Tab_Abstract
{
	/**
	 * Setup the form fields
	 *
	 * @return $this
	 */
	protected function _prepareForm()
	{
		parent::_prepareForm();

		$fieldset = $this->getForm()
			->addFieldset('splash_page_information', array(
				'legend'=> $this->__('Page Information')
			));
		
		$fieldset->addField('display_name', 'text', array(
			'name' 		=> 'display_name',
			'label' 	=> $this->__('Name'),
			'title' 	=> $this->__('Name'),
			'required'	=> true,
			'class'		=> 'required-entry',
		))->setRenderer(
			$this->getLayout()->createBlock('landingpage/adminhtml_form_field_storechecker')
		);
                
		$field = $fieldset->addField('url_key', 'text', array(
			'name' => 'url_key',
			'label' => $this->__('URL Key'),
			'title' => $this->__('URL Key'),
			'required'	=> true,
			'class'		=> 'required-entry',
		))->setRenderer(
			$this->getLayout()->createBlock('landingpage/adminhtml_form_field_urlkey')
				->setSplashType('page')
		);
                if ($page = Mage::registry('splash_page')) {
                $fieldset->addField('filter_rules', 'hidden', array(
				'name' 		=> 'filter_rules',
				'value' => $page->getFilterRules(),
			));
                }
		$fieldset->addField('is_enabled', 'select', array(
			'name' => 'is_enabled',
			'title' => $this->__('Enabled'),
			'label' => $this->__('Enabled'),
			'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
		))->setRenderer(
			$this->getLayout()->createBlock('landingpage/adminhtml_form_field_storechecker')
		);
                
		$fieldset->addField('is_featured', 'select', array(
			'name' => 'is_featured',
			'title' => $this->__('Brand Page'),
			'label' => $this->__('Brand Page'),
			'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
		))->setRenderer(
			$this->getLayout()->createBlock('landingpage/adminhtml_form_field_storechecker')
		);
		$this->getForm()->setValues($this->_getFormData());
                if ($page = Mage::registry('splash_page')) {
                    $fieldset->addField('store_id', 'hidden', array(
                                    'name' 		=> 'store_id',
                                    'value' => ((Mage::app()->getRequest()->getParam('store'))? Mage::app()->getRequest()->getParam('store') : Mage::app()->getStore(true)->getId()),
                            ));
                }
		return $this;
	}
}
