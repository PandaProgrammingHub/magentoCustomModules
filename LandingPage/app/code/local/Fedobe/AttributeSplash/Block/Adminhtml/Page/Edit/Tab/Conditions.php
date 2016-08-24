<?php

/**
 * @category    Fishpig
 * @package     Fedobe_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */
class Fedobe_AttributeSplash_Block_Adminhtml_Page_Edit_Tab_Conditions extends Mage_Adminhtml_Block_Promo_Catalog_Edit_Tab_Conditions {

    /**
     * Add the design elements to the form
     *
     * @return $this
     */
    protected function _prepareForm() {
        $model = Mage::getModel('attributeSplash/rule');
        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('rule_');
        $form->setFieldNameSuffix('splash');

        $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate('promo/fieldset.phtml')
            ->setNewChildUrl($this->getUrl('*/promo_catalog/newConditionHtml/form/rule_conditions_fieldset'));

        $fieldset = $form->addFieldset('conditions_fieldset', array(
            'legend'=>Mage::helper('catalogrule')->__('Conditions (leave blank for all products)'))
        )->setRenderer($renderer);

        $fieldset->addField('conditions', 'text', array(
            'name' => 'conditions',
            'label' => Mage::helper('catalogrule')->__('Conditions'),
            'title' => Mage::helper('catalogrule')->__('Conditions'),
            'required' => true,
        ))->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/conditions'));
        
        $form->setValues($this->_getFormData());

        $this->setForm($form);

        return $this;
    }

    protected function _getFormData() {
        if ($page = Mage::registry('splash_page')) {
            return $page->getData();
        }
        return array('is_enabled' => 1, 'store_ids' => array(0), 'include_in_menu' => 1);
    }
    /**
     * Prepare content for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('attributeSplash')->__('Conditions');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('attributeSplash')->__('Conditions');
    }

    /**
     * Returns status flag about this tab can be showen or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

}