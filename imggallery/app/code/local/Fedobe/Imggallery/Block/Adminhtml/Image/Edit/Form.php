<?php
class Fedobe_Imggallery_Block_Adminhtml_Image_Edit_Form extends Mage_Adminhtml_Block_Widget_Form{
	protected function _prepareForm(){
		if(Mage:: registry('image_data')){
			$data= Mage::registry('image_data')->getData();
		}
		else{
			$data=[];
		}
		$form=new Varien_Data_Form([
			'id'=>'edit_form',
			'action'=>$this->getUrl('*/*/save', array('id'=>$this->getRequest()->getParam('id'))),
			'method'=>'post',
			'enctype'=>'multipart/form-data'
			]);
		$form->setUseContainer(true);
		$this->setForm($form);
		$form->setvalues($data);
		return parent:: _prepareForm();
	}
}
?>