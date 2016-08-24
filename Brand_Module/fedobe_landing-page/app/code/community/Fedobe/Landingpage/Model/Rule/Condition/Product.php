<?php

class Fedobe_Landingpage_Model_Rule_Condition_Product extends Mage_CatalogRule_Model_Rule_Condition_Product {

    public function getAttributeName() {
        $predefinedoptions = $this->getAttributeOption();
        $customoptions = Mage::helper('landingpage')->getAllCustomRuleOptions();
        $alloptions = array_merge_recursive($predefinedoptions, $customoptions);
        return $alloptions[$this->getAttribute()];
    }

    public function getValueElementType() {
        $customeletypeattr = Mage::helper('landingpage')->dropdowntype();
        if (in_array($this->getAttribute(), $customeletypeattr)) {
            switch ($this->getAttribute()) {
                case 'custom_stock':
                    return 'select';
                    break;
                case 'custom_state':
                    return 'select';
                    break;
                default :
                    return parent::getValueElementType();
                    break;
            }
        } else {
            return parent::getValueElementType();
        }
    }

    public function getValueSelectOptions() {
        $customeletypeattr = Mage::helper('landingpage')->dropdowntype();
        if (in_array($this->getAttribute(), $customeletypeattr)) {
            switch ($this->getAttribute()) {
                case 'custom_stock':
                    return Mage::helper('landingpage')->getStockOptions();
                    break;
                case 'custom_state':
                    return Mage::helper('landingpage')->getStateOptions();
                    break;
                default :
                    return parent::getValueSelectOptions();
                    break;
            }
        } else {
            return parent::getValueSelectOptions();
        }
    }

    public function getOperatorSelectOptions() {
        $customeletypeattr = Mage::helper('landingpage')->dropdowntype();
        if (in_array($this->getAttribute(), $customeletypeattr)) {
            switch ($this->getAttribute()) {
                case 'custom_stock':
                    foreach ($this->getCustomOperatorOptions() as $k => $v) {
                        $opt[] = array('value' => $k, 'label' => $v);
                    }
                    return $opt;
                    break;
                case 'custom_state':
                    foreach ($this->getCustomStateOperatorOptions() as $k => $v) {
                        $opt[] = array('value' => $k, 'label' => $v);
                    }
                    return $opt;
                    break;
                default :
                    return parent::getOperatorSelectOptions();
                    break;
            }
        } else {
            return parent::getOperatorSelectOptions();
        }
    }

    public function getCustomOperatorOptions() {
        $customoperatoroptions = array(
            '==' => Mage::helper('rule')->__('is'),
            '!=' => Mage::helper('rule')->__('is not')
        );
        return $customoperatoroptions;
    }
    public function getCustomStateOperatorOptions() {
        $customoperatoroptions = array(
            '==' => Mage::helper('rule')->__('is'),
        );
        return $customoperatoroptions;
    }

}