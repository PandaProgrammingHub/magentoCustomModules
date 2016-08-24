<?php

class Fedobe_Avatar_GlobalSetting_Model_System_Config_Source_Globalsettings {

   public function toOptionArray() {

        return array(
            array('value' => "classic", 'label' => "Classic"),
            array('value' => "urban", 'label' => "Urban"),
            array('value' => "trendy", 'label' => "Trendy"),
        );
    }
    
}
