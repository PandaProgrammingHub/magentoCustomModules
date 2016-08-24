<?php
/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */

class Fedobe_Landingpage_Block_Adminhtml_Page_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	/**
	 * Init the tabs block
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setId('splash_page_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle($this->__('Landing Page Information'));
	}
	
	/**
	 * Add tabs to the block
	 *
	 * @return $this
	 */
	protected function _beforeToHtml()
	{
		$tabs = array(
			'general' 	=> 'Information',
			'conditions' 	=> 'Conditions',
			'content' 	=> 'Content',
                        'meta' 		=> 'SEO',
			'design' 	=> 'Design',
		);
		
		foreach($tabs as $alias => $label) {
			$this->addTab($alias, array(
				'label' => $this->__($label),
				'title' => $this->__($label),
				'content' => $this->getLayout()->createBlock('landingpage/adminhtml_page_edit_tab_' . $alias)->toHtml(),
			));
		}
		
		Mage::dispatchEvent('landingpage_adminhtml_page_edit_tabs', array('tabs' => $this));
		
		return parent::_beforeToHtml();
	}
}
