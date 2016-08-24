<?php
/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */

abstract class Fedobe_Landingpage_Controller_Adminhtml_Abstract extends Mage_Adminhtml_Controller_Action
{
	/**
	 * Determine ACL permissions
	 *
	 * @return bool
	 */
	protected function _isAllowed()
	{
		return Mage::getSingleton('admin/session')->isAllowed(
			'landingpage/dashboard'
		)
		|| Mage::getSingleton('admin/session')->isAllowed(
			'landingpage/dashboard'
		);
	}
}
