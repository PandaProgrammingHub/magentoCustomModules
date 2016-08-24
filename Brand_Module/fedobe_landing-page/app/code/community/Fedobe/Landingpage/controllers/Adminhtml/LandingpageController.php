<?php
class Fedobe_Landingpage_Adminhtml_LandingpageController extends Fedobe_Landingpage_Controller_Adminhtml_Abstract
{
	/**
	 * Display a grid of splash groups
	 *
	 */
	public function indexAction()
	{
		$this->loadLayout();
		$this->_title('Fedobe');
		$this->_title($this->__('Landing Pages by Fedobe'));
		$this->_setActiveMenu('landingpage');
		$this->renderLayout();
	}
	
	/**
	 * Display the grid of splash groups without the container (header, footer etc)
	 * This is used to modify the grid via AJAX
	 *
	 */
	public function groupGridAction()
	{
		$this->loadLayout();
		$this->renderLayout();
	}

	/**
	 * Display the grid of splash pages without the container (header, footer etc)
	 * This is used to modify the grid via AJAX
	 *
	 */
	public function pageGridAction()
	{
		$this->loadLayout();
		$this->renderLayout();
	}	

}
