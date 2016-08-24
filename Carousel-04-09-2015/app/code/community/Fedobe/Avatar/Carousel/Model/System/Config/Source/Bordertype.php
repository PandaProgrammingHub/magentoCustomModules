<?php

class Fedobe_Avatar_Carousel_Model_System_Config_Source_Bordertype
{
    public function toOptionArray()
    {
        return array(
            array("value"=>"dashed","label"=>"Dashed"),
            array("value"=>"dotted","label"=>"Dotted"),
            array("value"=>"double","label"=>"Double"),
            array("value"=>"groove","label"=>"Groove"),
            array("value"=>"hidden","label"=>"Hidden"),
            array("value"=>"inset","label"=>"Inset"),
            array("value"=>"outset","label"=>"Outset"),
            array("value"=>"solid","label"=>"Solid")
        );
    }
}
