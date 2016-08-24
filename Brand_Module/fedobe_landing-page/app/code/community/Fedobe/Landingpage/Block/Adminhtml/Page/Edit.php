<?php

/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */
class Fedobe_Landingpage_Block_Adminhtml_Page_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_controller = 'adminhtml_page';
        $this->_blockGroup = 'landingpage';
        $this->_headerText = $this->_getHeaderText();
        if ($page = Mage::registry('splash_page')) {
            if ($page->getId()) {
                $this->_addButton('review', array(
                    'label' => Mage::helper('catalog')->__('View Landing Page'),
                    'onclick' => 'reviewlandingpage(\''.$page->getUrl().$page->getUrl_key().$page->getUrlSuffix().'\')',
                ));
            }
        }     
        $this->_removeButton('save');
        $this->_addButton('save', array(
            'label' => Mage::helper('catalog')->__('Save'),
            // 'onclick' => 'editForm.submit()',
            'onclick' => 'checkRuleSave(\''.$this->getUrl('*/*/getFilterRules').'\')',
            'class' => 'save'
        ));
        $this->_addButton('save_and_edit_button', array(
            'label' => Mage::helper('catalog')->__('Save and Continue Edit'),
            // 'onclick' => 'editForm.submit(\'' . $this->getSaveAndContinueUrl() . '\')',
            'onclick' => 'checkRuleSaveAndEdit(\''.$this->getSaveAndContinueUrl().'\',\''.$this->getUrl('*/*/getFilterRules').'\')',
            'class' => 'save'
        ));
    }

    /**
     * Retrieve the URL used for the save and continue link
     * This is the same URL with the back parameter added
     *
     * @return string
     */
    public function getSaveAndContinueUrl() {
        return $this->getUrl('*/*/save', array(
                    '_current' => true,
                    'back' => 'edit',
        ));
    }

    public function getReviewUrl() {
        return $this->getUrl('*/*/save', array(
                    '_current' => true,
                    'target' => 'edit',
        ));
    }
    /**
     * Enable WYSIWYG editor
     *
     */
    protected function _prepareLayout() {
        parent::_prepareLayout();

        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }

        return $this;
    }

    /**
     * Retrieve the header text
     * If splash page exists, use name
     *
     * @return string
     */
    protected function _getHeaderText() {
        if ($page = Mage::registry('splash_page')) {
            if ($displayName = $page->getDisplayName()) {
                return $displayName;
            }
        }
        return $this->__('Add Landing Page');
    }

}
