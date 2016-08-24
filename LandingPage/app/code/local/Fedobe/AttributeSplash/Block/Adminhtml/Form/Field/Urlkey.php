<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fedobe_AttributeSplash_Block_Adminhtml_Form_Field_Urlkey extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	/**
	 * Retrieve the HTML for the element
	 *
	 * @param Varien_Data_Form_Element_Abstract $element
	 * @return string
	 */
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
	{
		if ($type = $this->getSplashType()) {
			if (($object = Mage::registry('splash_' . $type)) !== null) {
				$this->setSplashBaseUrl($object->getUrlBase());
				$this->setUrlSuffix($object->getUrlSuffix());
				
				return parent::_getElementHtml($element)
				. $this->_getRouteJs($element);
			}
		}
		
		return parent::_getElementHtml($element).$this->_getRequiredJs($element);
	}
	
	/**
	 * Retrieve the JS to display the route
	 *
	 * @param Varien_Data_Form_Element_Abstract $element
	 * @return string
	 */
	protected function _getRouteJs($element)
	{
		return sprintf("
			<script type=\"text/javascript\">
				(function() {
					var inp = $('%s');
					var SPLASH_BASE_URL = '%s';
					var URL_SUFFIX = '%s';
					
					inp.insert({'after': new Element('p', {'class': 'note', 'id': inp.id + '-note'})});
					
					var nt = $(inp.id + '-note');

					inp.observe('blur', function(event) {
						inp.setValue(inp.getValue().toLowerCase().replace(/([^a-z0-9\-\/]{1,})/, ''));
						nt.innerHTML = SPLASH_BASE_URL + inp.getValue() + URL_SUFFIX;
					});
					
					setTimeout(function() {
						inp.focus();
						inp.blur();
					}.bind(this), 1000);
				})();
			</script>
		", $element->getHtmlId(), $this->getSplashBaseUrl(), $this->getUrlSuffix());
	}
        protected function _getRequiredJs($element){
            return sprintf("
			<script type=\"text/javascript\">
				(function() {
                                    var inp = $('%s');
                                    $$('label[for=\"'+inp.id+'\"]').first().update('%s <span class=\"required\">*</span>')
                                })();
			</script>
		", $element->getHtmlId(),$this->__('URL Key'));
        }
}
