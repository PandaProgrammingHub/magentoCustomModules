<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Location
 *
 * @author prasad
 */
class Innoswift_StoreLocator_Block_Location extends Mage_Core_Block_Template {

    /**
     * getting location search form post data
     * @return type
     */
    function _getPostData() {

        $_postData = $this->getRequest()->getParams();

        return $_postData;
    }

    /**
     * 
     * @return collection of store locations
     */
    function getLocations() {

        $_locationCollection = Mage::getModel('storelocator/location')->getCollection();

        $_data = $this->_getPostData();

        // with out search storelocations not display
        if (!$_data && !$this->isEnableMapBefore()) {
            $_locationCollection = '';
        }

        if ($_data) {
//            if ($_data['storeName']) {
//                $_locationCollection->addFieldToFilter('store_title', array('like' => "%{$_data['storeName']}%"));
//            }
            if ($_address = trim($_data['address'])) {

                $_region = Mage::getModel('directory/region')->load($_address, 'code');

                if ($_region->getId()) {
                    $_address = $_region->getDefaultName();
                }

                $_coordinates = $this->getAddressLatAndLng($_address);

                if ($_coordinates) {
                    //getting locations in between radius based on first item location
                    $_locationCollection->addRadiusToFillter($_coordinates['lat'], $_coordinates['lng'], $_data['radius'], 'mi');
                } else {
                    $_locationCollection->addFieldToFilter('address_display', array('like' => "%{$_data['address']}%"));
                }
            }
        }
        if ($this->isEnableMapBefore()) {
            if (!Mage::app()->isSingleStoreMode()) {
                $_storeId = Mage::app()->getStore()->getId();
                $_locationCollection->addFieldToFilter('website', array('eq' => $_storeId));
            }
            $_locationCollection->addFieldToFilter('store_status', array('eq' => 1));
        }

        return $_locationCollection;
    }

    /**
     * This is store config to display two types of views (standard,classic)
     * @return type
     */
    function getLocationView() {
        return Mage::getStoreConfig('storelocation/settings/location_view');
    }

    /**
     * Config option foe map zoom
     * @return type
     */
    function getMapZoomLevel() {
        return (int) Mage::getStoreConfig('storelocation/settings/zoom_level');
    }

    /**
     * config for enable or disable search template in forntend
     * @return type
     */
    function isEnableCustomeSearch() {
        return Mage::getStoreConfig('storelocation/settings/custom_search');
    }

    /**
     * Config option for map enable or disable befor search
     * @return type
     */
    function isEnableMapBefore() {
        return Mage::getStoreConfig('storelocation/settings/map_before_search');
    }

    /**
     *  radius options getting form store config
     * @return type Array
     */
    function getRadiusOptions() {
        $_radius = Mage::getStoreConfig('storelocation/settings/radius_options');

        if (!$_radius) {
            return;
        }
        $_radiusOptions = explode(',', $_radius);
        return $_radiusOptions;
    }

    /**
     * just for css 
     * @return boolean
     */
    function enableArrows() {
        $_viewType = $this->getLocationView();
        $_checkCount = 4;
        if ($_viewType) {
            $_checkCount = 3;
        }

        if (count($this->getLocations()) > $_checkCount) {
            return true;
        }
        return false;
    }

    /*     * \
     * getting lat and lng for state or zip code
     */

    function getAddressLatAndLng($_address) {
        $url = "https://maps.googleapis.com/maps/api/geocode/json";
        //$result_string = file_get_contents($url);
        //  ?address=
//" . urlencode($_address) . "&sensor=false//
        $_client = new Zend_Http_Client($url);
        $_client->setParameterGet("sensor", "false");
        $_client->setParameterGet("address", urlencode($_address));

        try {
            $_response = $_client->request("GET");
            $result = json_decode($_response->getBody(), true);
            $result1[] = $result['results'][0];
            $result2[] = $result1[0]['geometry'];
            $result3[] = $result2[0]['location'];
            if (!$result3[0]) {
                return false;
            }
            return $result3[0];
        } catch (Exception $e) {
             return false;
        }
    }

}

