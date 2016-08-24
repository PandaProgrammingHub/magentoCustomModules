<?php
class Fedobe_AttributeSplash_Model_Rule extends Mage_Rule_Model_Abstract
{
    /**
     * Init resource model and id field
     */
    protected function _construct()
    {
        if ($page = Mage::registry('splash_page')) {
            $this->addData($page->getData());
            // set entered data if was error when we do save
            $data = Mage::getSingleton('adminhtml/session')->getPageData(true);
            if (!empty($data)) {
                $this->addData($data);
            }
            $this->getConditions()->setJsFormObject('rule_conditions_fieldset');
        }
        parent::_construct();
    }
    /**
     * Getter for rule conditions collection
     *
     * @return Mage_CatalogRule_Model_Rule_Condition_Combine
     */
    public function getConditionsInstance()
    {
        return Mage::getModel('catalogrule/rule_condition_combine');
    }

    /**
     * Getter for rule actions collection
     *
     * @return Mage_CatalogRule_Model_Rule_Action_Collection
     */
    public function getActionsInstance()
    {
        return Mage::getModel('catalogrule/rule_action_collection');
    }
    
}
