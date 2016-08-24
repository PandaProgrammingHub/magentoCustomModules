<?php
class Fedobe_Manufacturers_Model_Selectoption
{
    public function toOptionArray()
    {
        $brandlabels = trim(Mage::getStoreConfig('manufacturers/brandpage_design_settings/brand_sticker_labels'));
        if($brandlabels){
            $brandlabelsarr = explode(",",$brandlabels);
            $optionsarr = array();
            $optionsarr[] = array('value'=>'', 'label'=>Mage::helper('manufacturers')->__(''));
            foreach($brandlabelsarr as $k => $stickerlabels){
                $stickerlabels = strtoupper($stickerlabels);
                $optionsarr[] = array('value'=>$stickerlabels, 'label'=>Mage::helper('manufacturers')->__($stickerlabels));
            }
            return $optionsarr;
        }else{
            return array(
                array('value'=>'', 'label'=>Mage::helper('manufacturers')->__('')),
                array('value'=>'FEATURE', 'label'=>Mage::helper('manufacturers')->__('Feature')),
                array('value'=>'POPULAR', 'label'=>Mage::helper('manufacturers')->__('Popular')),
                array('value'=>'NEW', 'label'=>Mage::helper('manufacturers')->__('New')),
                array('value'=>'SALE', 'label'=>Mage::helper('manufacturers')->__('Hot')),
                array('value'=>'HOT', 'label'=>Mage::helper('manufacturers')->__('Sale')),
            );
        }
    }
}