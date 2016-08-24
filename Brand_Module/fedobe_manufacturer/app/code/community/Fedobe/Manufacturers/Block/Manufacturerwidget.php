<?php
class Fedobe_Manufacturers_Block_Manufacturerwidget extends Fedobe_Manufacturers_Block_List implements Mage_Widget_Block_Interface
{
     public function __construct() {
        parent::__construct();
        $this->setTemplate('fedobe/manufacturers/grid.phtml');
    }
}