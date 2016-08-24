<?php

/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */
class Fedobe_Landingpage_Block_Adminhtml_Page_Edit_Tab_Conditions extends Mage_Adminhtml_Block_Promo_Catalog_Edit_Tab_Conditions {

    /**
     * Add the design elements to the form
     *
     * @return $this
     */
    protected function _prepareForm() {
        $model = Mage::getModel('landingpage/rule');
        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('rule_');
        $form->setFieldNameSuffix('splash');

        $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate('promo/fieldset.phtml')
            ->setNewChildUrl($this->getUrl('*/promo_catalog/newConditionHtml/form/rule_conditions_fieldset'));

        $fieldset = $form->addFieldset('conditions_fieldset', array(
            'legend'=>Mage::helper('catalogrule')->__('Conditions (At least one condition to filter products)'))
        )->setRenderer($renderer);

        $fieldset->addField('conditions', 'text', array(
            'name' => 'conditions',
            'label' => Mage::helper('catalogrule')->__('Conditions'),
            'title' => Mage::helper('catalogrule')->__('Conditions'),
            'required' => true,
        ))->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/conditions'));
        
	$idPrefix = $form->getHtmlIdPrefix();
        $pageId = Mage::registry('splash_page') ? Mage::registry('splash_page')->getId() : 0;
	$url = Mage::registry('splash_page') ? Mage::registry('splash_page')->getUrl() : Mage::getBaseUrl();
        $generateUrl = $this->getUrl('*/*/checkRuleUniqueness');
        $fieldset->addField('generate_button', 'note', array(
            'text' => $this->getButtonHtml(
                Mage::helper('landingpage')->__('Apply'),
                "checkConditions('{$idPrefix}' ,'{$generateUrl}', '{$pageId}', '{$url}')",
                'generate'
            )
        ));

        $form->setValues($this->_getFormData());

        $this->setForm($form);

	$fieldset->addField('check_note', 'note');

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
        return Mage::helper('landingpage')->__('Conditions');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('landingpage')->__('Conditions');
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
