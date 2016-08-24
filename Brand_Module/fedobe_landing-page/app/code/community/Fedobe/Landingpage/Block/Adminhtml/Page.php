<?php
/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */

class Fedobe_Landingpage_Block_Adminhtml_Page extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{	
		parent::__construct();
		$this->_controller = 'adminhtml_page';
		$this->_blockGroup = 'landingpage';
		$this->_headerText = 'Landing ' . $this->__('Pages');
		$this->_removeButton('add');
                $this->setTemplate('landing-page/grid/container.phtml');
	}
        /**
     * Prepare button and grid
     *
     * @return Mage_Adminhtml_Block_Catalog_Product
     */
    protected function _prepareLayout()
    {
        $this->_addButton('add_new', array(
            'label'   => Mage::helper('catalog')->__('Add Landidng Page'),
            'onclick' => "setLocation('{$this->getUrl('*/landingpage_page/new')}')",
            'class'   => 'add'
        ));
        return parent::_prepareLayout();
    }
}