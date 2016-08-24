<?php

class Fedobe_Notificationbar_Block_Adminhtml_Config_Form_Field_Colorpicker extends Mage_Adminhtml_Block_System_Config_Form_Field{

	protected function _getElementHtml( Varien_Data_Form_Element_Abstract $element ) {
      $color = new Varien_Data_Form_Element_Text();
		$data = array(
			'name'      => $element->getName(),
			'html_id'   => $element->getId(),
		);
		$color->setData( $data );
		$color->setValue($element->getValue(), $format);
		$color->setForm($element->getForm());
		$color->addClass($element->getClass());

		return $color->getElementHtml();
         

    }
}
 