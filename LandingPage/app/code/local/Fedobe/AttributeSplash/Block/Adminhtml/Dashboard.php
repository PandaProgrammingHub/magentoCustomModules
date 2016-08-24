<?php

/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */
class Fedobe_AttributeSplash_Block_Adminhtml_Dashboard extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();

        $this->setId('splash_dashboard_tabs');
        $this->setDestElementId('splash_tab_content');
        $this->setTitle($this->__('Attribute Splash Pages'));
        $this->setTemplate('widget/tabshoriz.phtml');
    }

    protected function _prepareLayout() {
        $tabs = array(
            'page' => 'Pages',
        );

        $_layout = $this->getLayout();

        foreach ($tabs as $alias => $label) {
            $this->addTab($alias, array(
                'label' => Mage::helper('catalog')->__($label),
                'content' => $_layout->createBlock('attributeSplash/adminhtml_' . $alias)->toHtml(),
                'active' => $alias === 'page',
            ));
        }

        Mage::dispatchEvent('attributesplash_dashboard_tabs_prepare_layout', array('tabs' => $this));

        return parent::_prepareLayout();
    }

}
