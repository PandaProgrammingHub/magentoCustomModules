<?php
class Fedobe_OneStepCheckout_ServiceController extends Mage_Core_Controller_Front_Action
{
    public function getGeoIpAction(){
        $data = array();

        /*
         * $region = Mage::getModel('directory/region')->load($regionId);
            if ($region->getId()) {
                return $region->getCode();
            }
         */
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $json = file_get_contents("http://ip-api.com/json/$ip");
        try{
            $data = (array)json_decode($json);
            $data = array(
                'country_code' => !empty($data['countryCode']) ? $data['countryCode'] : '',
                'region_code' => !empty($data['region']) ? $data['region'] : '' ,
                'region_name' => !empty($data['regionName']) ? $data['regionName'] : '' ,
                'city' => !empty($data['city']) ? $data['city'] : '' ,
                'zip' => !empty($data['zip']) ? $data['zip'] : '' ,
            );
        }catch (Exception $ex){

        }

        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'application/json')
            ->setBody(Mage::helper('core')->jsonEncode($data));
    }
}