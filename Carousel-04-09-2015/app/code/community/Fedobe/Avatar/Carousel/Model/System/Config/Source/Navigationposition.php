<?php

class Fedobe_Avatar_Carousel_Model_System_Config_Source_Navigationposition
{
    public function toOptionArray() {
        return array(
            array('value' => 'bottomcenter', 'label' => 'Bottom + Center'),
            array('value' => 'topright', 'label' => 'Top + Right'),
            array('value' => 'bottomright', 'label' => 'Bottom + Right'),
            array('value' => 'overcarousel', 'label' => 'Over Carousel'),
            );
    }
}
