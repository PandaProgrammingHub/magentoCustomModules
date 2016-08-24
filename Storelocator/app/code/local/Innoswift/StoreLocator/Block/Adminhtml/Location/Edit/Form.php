<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Form
 *
 * @author Annavarapu prasad
 */
class Innoswift_StoreLocator_Block_Adminhtml_Location_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        if (Mage::getSingleton('adminhtml/session')->getLocationData()) {
            $data = Mage::getSingleton('adminhtml/session')->getLocationlData();
            Mage::getSingleton('adminhtml/session')->getLocationData(null);
        } elseif (Mage::registry('current_location')) {
            $data = Mage::registry('current_location')->getData();
        } else {
            $data = array();
        }

        $form = new Varien_Data_Form(array(
                    'id' => 'edit_form',
                    'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                ));

        $form->setUseContainer(true);

        $this->setForm($form);
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset = $form->addFieldset('location_website', array(
                'legend' => Mage::helper('storelocator')->__('Store Name')
                    ));

            $fieldset->addField('website', 'select', array(
                'label' => Mage::helper('storelocator')->__('Select Store'),               
                'name' => 'website',
                'options' => Mage::getModel('core/website')->getCollection()->toOptionHash(),
            ));
        }

        $fieldset = $form->addFieldset('location_form', array(
            'legend' => Mage::helper('storelocator')->__('Location Information')
                ));

        $fieldset->addField('store_title', 'text', array(
            'label' => Mage::helper('storelocator')->__('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'store_title',
            'after_element_html' => '<small>Store Name for display</small>',
        ));

        $fieldset->addField('address_display', 'textarea', array(
            'label' => Mage::helper('storelocator')->__('Address Of Display'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'address_display',
            'style' => "width:275px;height:94px;",
            'after_element_html' => '<small>Address to display in frontend</small>',
        ));

        $fieldset->addField('address_geo', 'textarea', array(
            'label' => Mage::helper('storelocator')->__('Address Of Geo'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'address_geo',
            'style' => "width:275px;height:94px;",
            'after_element_html' => '<small>Please give the correct geo address of store</small>',
        ));
        $fieldset->addField('store_phone', 'text', array(
            'label' => Mage::helper('storelocator')->__('Store Phone'),
//             'class'     => 'required-entry',
//             'required'  => true,
            'name' => 'store_phone',
        ));
        $fieldset->addField('website_address', 'text', array(
            'label' => Mage::helper('storelocator')->__('Website Url'),
             'class'     => '',
//             'required'  => true,
            'name' => 'website_address',
        ));
        $fieldset->addField('store_email', 'text', array(
            'label' => Mage::helper('storelocator')->__('Store E-mail'),
            'class' => 'validate-email',
//             'required'  => true,
            'name' => 'store_email',
        ));
        $fieldset->addField('store_status', 'select', array(
            'label' => Mage::helper('storelocator')->__('Status'),           
//             'required'  => true,
            'name' => 'store_status',
            'options' => array('1' => Mage::helper('adminhtml')->__('Active'), '0' => Mage::helper('adminhtml')->__('Inactive'))
        ));

//        $fieldset->addField('store_longitude', 'hidden', array(
//            'name' => 'store_longitude',
//        ));
//
//        $fieldset->addField('store_latitude', 'hidden', array(
//            'name' => 'store_latitude',
//        ));
        $form->setValues($data);

        return parent::_prepareForm();
    }

}

