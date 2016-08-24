<?php

/**
 * Description of IndexController
 *
 * @author Annavarapu prasad
 */
class Fedobe_StoreLocator_Adminhtml_LocationController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('inno/storelocatons');
        return $this;
    }

    public function indexAction() {

        $this->_title($this->__('Fedobe'))
                ->_title($this->__('Store Locations'));
        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }
        $this->_initAction()
                ->renderLayout();
    }

    public function gridAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newAction() {
        $this->_title($this->__('New Location'));
        $this->_forward('edit');
    }

    public function editAction() {
        $_locationId = (int) $this->getRequest()->getParam('id', null);
        $_model = Mage::getModel('storelocator/location');
        if ($_locationId) {
            $_model->load($_locationId);
            if ($_model->getId()) {
                $_data = mage::getSingleton('adminhtml/session')->getFormData(true);
                if ($_data) {
                    $_model->setData($_data)->setId($_locationId);
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('storelocator')->__('Location does not exist'));
                $this->_redirect('*/*/');
            }
        }
        Mage::register('current_location', $_model);
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    public function saveAction() {

        $_data = $this->getRequest()->getPost();
        $_locationId = $this->getRequest()->getParam('id');
        $_redirectBack = $this->getRequest()->getParam('back', false);
        $_model = Mage::getModel('storelocator/location');
        if ($_data) {
            $_model->setData($_data);

            if ($_locationId) {
                $_model->setId($_locationId);
            }

            try {
                $_model->save();
                $_locationId = $_model->getId();

                $this->_getSession()->addSuccess($this->__('The location has been saved.'));
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
                $_redirectBack = true;
            }
        }
        if ($_redirectBack) {
            $this->_redirect('*/*/edit', array(
                'id' => $_locationId,
                '_current' => true
            ));
        } else {
            $this->_redirect('*/*/');
        }
    }

    public function deleteAction() {
        if ($_id = $this->getRequest()->getParam('id')) {
            $_location = Mage::getModel('storelocator/location')
                    ->load($_id);
            try {
                $_location->delete();
                $this->_getSession()->addSuccess($this->__('The location has been deleted.'));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()
                ->setRedirect($this->getUrl('*/*/'));
    }
      /**
     * Export customer grid to XML format
     */
    public function exportXmlAction() {
        $fileName = 'locations.xml';
        $content = $this->getLayout()->createBlock('storelocator/adminhtml_location_export_grid')
                ->getExcelFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export customer grid to CSV format
     */
    public function exportCsvAction() {
        $fileName = 'locations.csv';
        $content = $this->getLayout()->createBlock('storelocator/adminhtml_location_export_grid')
                ->getCsvFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

}

