<?php


/**
 * Description of Location
 *
 * @author Annavarapu prasad
 */
class Fedobe_StoreLocator_Model_Location extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('storelocator/location');
    }

    /**
     * Geeting resut from maps.googleapis.com based geo address as json fromat.
     * 
     * from the resut taking location lat ang long 
     * @return \Innoswift_StoreLocator_Model_Location
     */
    public function saveLatAndLang() {

        $_geoUrl = "http://maps.googleapis.com/maps/api/geocode/json";

        $_geoUrl .= strpos($_geoUrl, '?') !== false ? '&' : '?';
        $_geoUrl .= 'address=' . urlencode(preg_replace('#\r|\n#', ' ', $this->getAddressGeo())) . "&sensor=true";

        $_curlInit = curl_init();
        curl_setopt($_curlInit, CURLOPT_URL, $_geoUrl);
        curl_setopt($_curlInit, CURLOPT_HEADER, 0);
        curl_setopt($_curlInit, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($_curlInit, CURLOPT_RETURNTRANSFER, 1);
        $_response = curl_exec($_curlInit);
        if (!is_string($_response) || empty($_response)) {
            return $this;
        }
        $result = json_decode($_response);
        $locations = $result->results[0]->geometry->location;
        $_latitude = $locations->lat;
        $_longitude = $locations->lng;

        $this->setStoreLatitude($_latitude)->setStoreLongitude($_longitude);
        return $this;
    }

    /**
     * model_save_before
     */
    public function _beforeSave() {

        $this->saveLatAndLang();
        $this->setAddressDisplay(str_replace(array("\n", "\r"), " ", $this->getAddressDisplay()));
        $this->setAddressGeo(str_replace(array("\n", "\r"), " ", $this->getAddressGeo()));
        parent::_beforeSave();
    }

}

