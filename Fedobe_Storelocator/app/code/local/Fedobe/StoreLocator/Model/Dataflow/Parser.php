<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Parsar
 *
 * @author prasad
 */
class Fedobe_Storelocator_Model_Dataflow_Parser extends Mage_Dataflow_Model_Convert_Adapter_Abstract {

    public function load() {
// you have to create this method, enforced by Mage_Dataflow_Model_Convert_Adapter_Interface 
    }

    public function save() {
// you have to create this method, enforced by Mage_Dataflow_Model_Convert_Adapter_Interface       
    }

    public function saveRow(array $_importData) {

        // loading location model
        $_locationModel = Mage::getModel('storelocator/location');

        $_id = false;



        // set location for default store
        if (!$_importData['website']) {
            $_importData['website'] = 0;
        }
//        $_importData['store_status'] = (int)$_importData['status'];
        // set location by default Active
        if (!$_importData['store_status']) {
            $_importData['store_status'] = 1;
        }
       

        if (!$_id) {
            // checking already existing store with store title
            $_locationModel->load($_importData['store_title'], 'store_title');

            $_id = $_locationModel->getId();
        }

 // set import data into model
        $_locationModel->setData($_importData);

        if ($_id) {
            $_locationModel->setId($_id);
        }
        try {
            $_locationModel->save();
        } catch (Exception $exc) {
            Mage::throwException($exc->getMessage());
        }

        return true;
    }

}

