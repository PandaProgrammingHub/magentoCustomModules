<?php

class Fedobe_Imggallery_Block_Adminhtml_Image_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs{
    public function __construct() {
        parent::__construct();
        $this->setId('image_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('imggallery')->__('Image Category'));
    }
    protected function _beforeToHtml() {
		$this->addTab('form_section', array(
			'label' => Mage::helper('imggallery')->__('Image Detail'),
			'title' => Mage::helper('imggallery')->__('Image Detail'),
			'content' => $this->getLayout()->createBlock('imggallery/adminhtml_image_edit_tabs_form')->toHtml(),
                    ));
		return parent::_beforeToHtml();
	} 
}
