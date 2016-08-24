<?php
class Fedobe_Imggallery_Model_Source_Order{
    public function toOptionArray(){
        return array(
            array('value' => 'Ascending', 'label'=> 'Ascending'),
            array('value' => 'Descending', 'label' => 'Descending'),
            array('value' => 'Custom', 'label' =>'Cusotm')
        );
    }
}