<?php
class Fedobe_Imggallery_Block_Adminhtml_Imggallery_Renderer_Catimage extends
Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
	public function render(Varien_Object $row){
		$value = $row->getData($this->getColumn()->getIndex());
		if($value == NULL){
			return "No category image defined";
		}else{
			return '<img width="200" height="100" src="'.Mage::getBaseUrl('media').DS.$value . '" />';
		}
	}
}