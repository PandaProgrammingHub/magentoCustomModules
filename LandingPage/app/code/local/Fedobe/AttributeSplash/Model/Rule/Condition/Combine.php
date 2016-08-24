<?php

class Fedobe_AttributeSplash_Model_Rule_Condition_Combine extends Mage_CatalogRule_Model_Rule_Condition_Combine {

    public function __construct() {
        parent::__construct();
    }

    public function getNewChildSelectOptions() {
        $productCondition = Mage::getModel('catalogrule/rule_condition_product');
        $productAttributes = $productCondition->loadAttributeOptions()->getAttributeOption();

        $attributes = array();
        foreach ($productAttributes as $code => $label) {
            $attributes[] = array('value' => 'catalogrule/rule_condition_product|' . $code, 'label' => $label);
        }

        $conditions = parent::getNewChildSelectOptions();

        $customconditions = $this->getAllCustom();
        $customfilters = $this->getAllCustomFilters();

        $conditions = array_merge_recursive($conditions, array(
            array('label' => Mage::helper('catalogrule')->__('Custom Fields'), 'value' => $customconditions),
            array('label' => Mage::helper('catalogrule')->__('Custom Filters'), 'value' => $customfilters),
        ));
        return $conditions;
    }

    protected function getAllCustom() {
        $customconditions = Mage::helper('attributeSplash')->getAllCustomArr();
        foreach ($customconditions as $code => $label) {
            $customattributes[] = array('value' => 'catalogrule/rule_condition_product|' . $code, 'label' => $label);
        }
        return $customattributes;
    }

    protected function getAllCustomFilters() {
        $customfilters = Mage::helper('attributeSplash')->getAllCustomFiltersArr();
        foreach ($customfilters as $code => $label) {
            $customfiltersattributes[] = array('value' => 'catalogrule/rule_condition_product|' . $code, 'label' => $label);
        }
        return $customfiltersattributes;
    }

}
